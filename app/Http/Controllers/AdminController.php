<?php

namespace App\Http\Controllers;

use App\Models\PermasalahanLahan;
use Illuminate\Http\Request;
use App\Models\Shm;
use App\Models\Hpl;
use App\Models\Kecamatan;
use App\Models\Kabupaten;
use App\Models\Desa;
use App\Models\KawasanTransmigrasi;
use App\Models\ShmDokumen;
use App\Models\HplDokumen;
use App\Models\JenisPermasalahan;
use App\Models\PlDokumen;
use App\Models\PlProgress;
use App\Models\Provinsi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class AdminController extends Controller
{
   public function dashboard(Request $request)
    {
        // Ambil semua request dari filter
        $provinsiId = $request->provinsi;
        $kabupaten  = $request->kabupaten;
        $kawasan    = $request->kawasan;
        $lokasi     = $request->lokasi;
        $tahun      = $request->tahun;

        $provinsiList = Provinsi::orderBy('nama_provinsi')->get();

        /*
        |--------------------------------------------------------------------------
        | 1. FUNGSI FILTER GLOBAL
        |--------------------------------------------------------------------------
        | Closure ini akan dipakai untuk menempelkan filter wilayah ke tabel SHM, HPL, dan PL
        */
        $filterWilayah = function ($query) use ($provinsiId, $kabupaten, $kawasan, $lokasi) {
            $query->when($provinsiId, function ($q) use ($provinsiId) {
                $q->whereHas('kawasan.desa.kecamatan.kabupaten.provinsi', function ($q2) use ($provinsiId) {
                    $q2->where('id', $provinsiId);
                });
            })
            ->when($kabupaten, function ($q) use ($kabupaten) {
                $q->whereHas('kawasan.desa.kecamatan.kabupaten', function ($q2) use ($kabupaten) {
                    $q2->where('nama_kabupaten', 'like', '%' . $kabupaten . '%');
                });
            })
            ->when($kawasan, function ($q) use ($kawasan) {
                $q->whereHas('kawasan', function ($q2) use ($kawasan) {
                    $q2->where('nama_kawasan', 'like', '%' . $kawasan . '%');
                });
            })
            ->when($lokasi, function ($q) use ($lokasi) {
                $q->whereHas('kawasan', function ($q2) use ($lokasi) {
                    $q2->where('nama_lokasi', 'like', '%' . $lokasi . '%');
                });
            });
        };

        /*
        |--------------------------------------------------------------------------
        | 2. SUMMARY CARD WILAYAH (Berdasarkan Filter)
        |--------------------------------------------------------------------------
        */
        $wilayahQuery = KawasanTransmigrasi::query()
            ->join('desa', 'desa.id', '=', 'kawasan_transmigrasi.desa_id')
            ->join('kecamatan', 'kecamatan.id', '=', 'desa.kecamatan_id')
            ->join('kabupaten', 'kabupaten.id', '=', 'kecamatan.kabupaten_id')
            ->join('provinsi', 'provinsi.id', '=', 'kabupaten.provinsi_id')
            ->when($provinsiId, fn($q) => $q->where('provinsi.id', $provinsiId))
            ->when($kabupaten, fn($q) => $q->where('kabupaten.nama_kabupaten', 'like', '%' . $kabupaten . '%'))
            ->when($kawasan, fn($q) => $q->where('kawasan_transmigrasi.nama_kawasan', 'like', '%' . $kawasan . '%'))
            ->when($lokasi, fn($q) => $q->where('kawasan_transmigrasi.nama_lokasi', 'like', '%' . $lokasi . '%'));

        $summaryWilayah = (clone $wilayahQuery)->selectRaw('
                COUNT(DISTINCT provinsi.id) as total_provinsi,
                COUNT(DISTINCT kabupaten.id) as total_kabupaten,
                COUNT(DISTINCT kawasan_transmigrasi.id) as total_lokasi
            ')->first();

        $totalProvinsi  = $summaryWilayah->total_provinsi ?? 0;
        $totalKabupaten = $summaryWilayah->total_kabupaten ?? 0;
        $totalLokasi    = $summaryWilayah->total_lokasi ?? 0;

        /*
        |--------------------------------------------------------------------------
        | 3. DATA UNTUK GRAFIK SHM
        |--------------------------------------------------------------------------
        */
        $shmQuery = Shm::query();
        $filterWilayah($shmQuery); // Pasang filter pencarian

        // Filter Tahun khusus untuk SHM Pie
        if ($tahun) {
            $shmQuery->where('target_tahunan', $tahun);
        }

        $rowShm = (clone $shmQuery)->selectRaw('
            SUM(clear_shm) as clear,
            SUM(realisasi_shm) as realisasi,
            SUM(bermasalah_shm) as bermasalah
        ')->first();

        $pie = [
            ['name' => 'Clear', 'y' => (int) ($rowShm->clear ?? 0)],
            ['name' => 'Sudah Terbit', 'y' => (int) ($rowShm->realisasi ?? 0)],
            ['name' => 'Bermasalah', 'y' => (int) ($rowShm->bermasalah ?? 0)],
        ];

        // Dropdown List Tahun SHM
        $listTahunQuery = Shm::query();
        $filterWilayah($listTahunQuery);
        $listTahun = $listTahunQuery->select('target_tahunan')
            ->whereNotNull('target_tahunan')
            ->distinct()
            ->orderBy('target_tahunan', 'desc')
            ->pluck('target_tahunan');

        // Grafik Kolom Tahunan SHM
        $rowsTahunan = (clone $wilayahQuery)
            ->join('shm', 'shm.kawasan_transmigrasi_id', '=', 'kawasan_transmigrasi.id')
            ->selectRaw('shm.target_tahunan as tahun, SUM(shm.bidang) as total_bidang')
            ->whereNotNull('shm.target_tahunan')
            ->groupBy('shm.target_tahunan')
            ->orderBy('shm.target_tahunan')
            ->get();

        $tahunList  = $rowsTahunan->pluck('tahun')->toArray();
        $dataBidang = $rowsTahunan->pluck('total_bidang')->map(fn($v) => (int)$v)->toArray();

        /*
        |--------------------------------------------------------------------------
        | 4. DATA UNTUK GRAFIK HPL
        |--------------------------------------------------------------------------
        */
        $hplQuery = Hpl::query();
        $filterWilayah($hplQuery); // Pasang filter pencarian

        $statusHpl = (clone $hplQuery)->selectRaw('status_hpl, COUNT(*) as total')
            ->groupBy('status_hpl')
            ->pluck('total', 'status_hpl');

        $pieStatusHpl = [
            ['name' => 'SK HPL',     'y' => (int) ($statusHpl['sk'] ?? 0)],
            ['name' => 'Sertifikat', 'y' => (int) ($statusHpl['sertifikat'] ?? 0)],
            ['name' => 'Usulan',     'y' => (int) ($statusHpl['usulan'] ?? 0)],
        ];

        $rekapPeta = (clone $hplQuery)->selectRaw("
                SUM(CASE WHEN peta = 1 THEN 1 ELSE 0 END) as ada_peta,
                SUM(CASE WHEN peta = 0 THEN 1 ELSE 0 END) as tidak_ada_peta
            ")->first();

        $piePetaHpl = [
            ['name' => 'Ada Peta', 'y' => (int) ($rekapPeta->ada_peta ?? 0)],
            ['name' => 'Tidak Ada Peta', 'y' => (int) ($rekapPeta->tidak_ada_peta ?? 0)],
        ];

        /*
        |--------------------------------------------------------------------------
        | 5. DATA UNTUK GRAFIK PERMASALAHAN LAHAN
        |--------------------------------------------------------------------------
        */
        $jenisData = (clone $wilayahQuery)
            ->join('permasalahan_lahan', 'permasalahan_lahan.kawasan_transmigrasi_id', '=', 'kawasan_transmigrasi.id')
            ->join('jenis_permasalahan', 'jenis_permasalahan.jenis_pl_id', '=', 'permasalahan_lahan.jenis_pl_id')
            ->selectRaw('jenis_permasalahan.nama_permasalahan, SUM(permasalahan_lahan.jumlah_bidang) as total')
            ->groupBy('jenis_permasalahan.nama_permasalahan')
            ->pluck('total', 'jenis_permasalahan.nama_permasalahan');

        $pieJenisPermasalahan = [];
        foreach ($jenisData as $nama => $total) {
            $pieJenisPermasalahan[] = [
                'name' => $nama,
                'y'    => (int) $total
            ];
        }

        // Ambil list SHM jika sewaktu-waktu dipakai di tabel
        $shm = (clone $shmQuery)->get();

        return view('admin.dashboard', compact(
            'provinsiList', 'provinsiId',
            'pie', 'pieStatusHpl', 'piePetaHpl',
            'tahunList', 'dataBidang',
            'totalProvinsi', 'totalKabupaten', 'totalLokasi',
            'listTahun', 'pieJenisPermasalahan', 'shm'
        ));
    }

    public function dashboardPdf(Request $request)
    {
        $hpl = Hpl::with('kawasan.desa.kecamatan.kabupaten.provinsi','dokumen')
            ->when($request->provinsi,function($q)use($request){
                $q->whereHas('kawasan.desa.kecamatan.kabupaten.provinsi',function($x)use($request){
                    $x->where('nama_provinsi',$request->provinsi);
                });
            })
            ->when($request->search,function($q)use($request){
                $q->whereHas('kawasan',function($x)use($request){
                    $x->where('nama_kawasan','like','%'.$request->search.'%')
                    ->orWhere('nama_lokasi','like','%'.$request->search.'%');
                });
            })
            ->get();

        $shm = Shm::with('kawasan.desa.kecamatan.kabupaten.provinsi','dokumen')->get();
        $pl  = PermasalahanLahan::with('kawasan.desa.kecamatan.kabupaten.provinsi','dokumen')->get();

        $pdf = Pdf::loadView('admin.dashboard-pdf',compact('hpl','shm','pl'))
            ->setPaper('a4','landscape');

        return $pdf->download('dashboard-pertanahan.pdf');
    }


    public function getKabupaten($provinsi_id)
    {
        return Kabupaten::where('provinsi_id', $provinsi_id)->get();
    }
    public function getShm()
    {
        $provinsi = Provinsi::all();
        $shm = Shm::with([
            'kawasan.desa.kecamatan.kabupaten.provinsi',
            'dokumen'
        ])->get();
        $jenisPermasalahan = JenisPermasalahan::orderBy('nama_permasalahan')->get();

        return view('admin.shm', compact('provinsi', 'shm', 'jenisPermasalahan'));
    }
    public function storeShm(Request $request)
    {
        $request->validate([
            'kabupaten_id'   => 'required|exists:kabupaten,id',
            'nama_kecamatan' => 'required|string|max:255',
            'nama_desa'      => 'required|string|max:255',
            'nama_kawasan'   => 'required|string|max:255',
            'nama_lokasi'    => 'required|string|max:255',

            'pola'           => 'required|string|max:100',
            'tahun_patan'    => 'required|integer',
            'jumlah_kk'      => 'required|integer|min:0',
            'target_shm'     => 'required|integer|min:0',
            'realisasi_shm'  => 'required|integer|min:0|lte:target_shm',
            
            // Kolom baru yang ditambahkan
            'clear_shm'      => 'required|integer|min:0',
            'bermasalah_shm' => 'required|integer|min:0',

            'status_hpl'     => ['required', Rule::in(['Serah', 'Belum'])],
            'status_upt'     => ['required', Rule::in(['Serah', 'Bina'])],

            'luas'           => 'nullable|numeric|min:0',
            'target_tahunan' => 'nullable|digits:4|integer',
            'bidang'         => 'required|integer|min:0',

            'deskripsi'      => 'nullable|string',
            'dokumen.*'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        try {

            DB::transaction(function () use ($request) {

                // Kecamatan
                $kecamatan = Kecamatan::firstOrCreate([
                    'kabupaten_id'  => $request->kabupaten_id,
                    'nama_kecamatan'=> $request->nama_kecamatan,
                ]);

                // Desa
                $desa = Desa::firstOrCreate([
                    'kecamatan_id'  => $kecamatan->id,
                    'nama_desa'     => $request->nama_desa,
                ]);

                // Kawasan
                $kawasan = KawasanTransmigrasi::firstOrCreate([
                    'desa_id'       => $desa->id,
                    'nama_kawasan'  => $request->nama_kawasan,
                    'nama_lokasi'   => $request->nama_lokasi,
                ]);

                // Auto hitung sisa
                $sisaShm = $request->target_shm - $request->realisasi_shm;

                $tipologi = $request->tipologi === 'lainnya'
                    ? $request->tipologi_manual
                    : $request->tipologi;
                    
                // Simpan SHM
                $shm = Shm::create([
                    'kawasan_transmigrasi_id' => $kawasan->id,
                    'pola'           => $request->pola,
                    'tahun_patan'    => $request->tahun_patan,
                    'jumlah_kk'      => $request->jumlah_kk,
                    'target_shm'     => $request->target_shm,
                    'realisasi_shm'  => $request->realisasi_shm,
                    'sisa_shm'       => $sisaShm,
                    
                    // Kolom baru yang ditambahkan
                    'clear_shm'      => $request->clear_shm,
                    'bermasalah_shm' => $request->bermasalah_shm,

                    'status_hpl'     => $request->status_hpl,
                    'status_upt'     => $request->status_upt,
                    'luas'           => $request->luas,
                    'target_tahunan' => $request->target_tahunan,
                    'bidang'         => $request->bidang,
                    'deskripsi'      => $request->deskripsi,
                    'nama_tipologi'   => $tipologi,
                    'tipologi_bidang' => $request->tipologi_bidang,

                ]);

                // Upload Dokumen
                if ($request->hasFile('dokumen')) {
                    foreach ($request->file('dokumen') as $file) {

                        $path = $file->store('shm_dokumen', 'public');

                        ShmDokumen::create([
                            'shm_id'       => $shm->shm_id,
                            'nama_dokumen' => $file->getClientOriginalName(),
                            'path_file'    => $path,
                        ]);
                    }
                }

            });

            return redirect()->back()->with('success', 'Data SHM berhasil disimpan');

        } catch (QueryException $e) {

            // Error duplicate MySQL
            if ($e->errorInfo[1] == 1062) {
                return redirect()->back()
                    ->withErrors(['duplicate' => 'Data SHM untuk kawasan dan tahun tersebut sudah ada.'])
                    ->withInput();
            }

            throw $e;
        }
    }
    public function cekKawasan(Request $request)
    {
        $exists = KawasanTransmigrasi::where('nama_kawasan', $request->nama_kawasan)
            ->exists();

        return response()->json([
            'exists' => $exists
        ]);
    }
    public function storeDokumenShm(Request $request)
    {
        $request->validate([
            'shm_id' => 'required|exists:shm,shm_id',
            'dokumen' => 'required',
            'dokumen.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        foreach ($request->file('dokumen') as $file) {

            $path = $file->store('shm_dokumen', 'public');

            ShmDokumen::create([
                'shm_id' => $request->shm_id,
                'nama_dokumen' => $file->getClientOriginalName(),
                'path_file' => $path,
            ]);
        }

        return back()->with('success','Dokumen berhasil ditambahkan');
    }

    
    public function editShm($id)
    {
        $shm = Shm::with([
            'kawasan.desa.kecamatan.kabupaten.provinsi',
            'dokumen'
        ])->findOrFail($id);

        

        return view('admin.shm', compact('shm'));
    }
    public function updateShm(Request $request, $id)
    {
        $request->validate([
            'nama_kawasan'   => 'required|string',
            'nama_lokasi'    => 'required|string',
            'nama_kecamatan' => 'required|string',
            'nama_desa'      => 'required|string',
            'pola'           => 'required|string',

            'tahun_patan'    => 'required|array',
            'tahun_patan.*'  => 'required|numeric',
            'jumlah_kk'      => 'required|array',
            'jumlah_kk.*'    => 'required|numeric|min:0',

            'status_hpl'     => 'required|in:Serah,Belum',
            'status_upt'     => 'required|in:Serah,Bina',
            'luas'           => 'nullable|numeric',
            'target_tahunan' => 'nullable|integer',
            'bidang'         => 'required|integer',
            'target_shm'     => 'required|integer|min:0',
            'realisasi_shm'  => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $id) {

            $shmUtama = Shm::findOrFail($id);

            $tahunList = $request->tahun_patan;
            $kkList    = $request->jumlah_kk;
            $shmIds    = $request->shm_id ?? [];

            $sisaShm = max($request->target_shm - $request->realisasi_shm, 0);

            // ==============================
            // TRACK ID YANG MASIH ADA
            // ==============================
            $existingIds = [];

            foreach ($tahunList as $index => $tahun) {

                $kk    = $kkList[$index] ?? 0;
                $rowId = $shmIds[$index] ?? null;

                $data = [
                    'pola'            => $request->pola,
                    'tahun_patan'     => $tahun,
                    'jumlah_kk'       => $kk,
                    'status_hpl'      => $request->status_hpl,
                    'status_upt'      => $request->status_upt,
                    'luas'            => $request->luas,
                    'target_tahunan'  => $request->target_tahunan,
                    'bidang'          => $request->bidang,
                    'deskripsi'       => $request->deskripsi,
                    'target_shm'      => $request->target_shm,
                    'realisasi_shm'   => $request->realisasi_shm,
                    'sisa_shm'        => $sisaShm,
                    'clear_shm'   => $request->realisasi_shm,
                    'realisasi_shm'   => $request->realisasi_shm,
                    'nama_tipologi'   => $request->tipologi == 'lainnya'
                                            ? $request->tipologi_manual
                                            : $request->tipologi,
                    'tipologi_bidang' => $request->tipologi_bidang,
                ];

                if ($rowId) {

                    // ✅ UPDATE
                    Shm::where('shm_id', $rowId)->update($data);
                    $existingIds[] = $rowId;

                } else {

                    // ✅ CREATE BARU (INI YANG BIKIN BARIS NAMBah)
                    $new = Shm::create(array_merge($data, [
                        'kawasan_transmigrasi_id' => $shmUtama->kawasan_transmigrasi_id,
                    ]));

                    $existingIds[] = $new->shm_id;
                }
            }

            // ==============================
            // DELETE YANG TIDAK ADA DI FORM
            // ==============================
            Shm::where('kawasan_transmigrasi_id', $shmUtama->kawasan_transmigrasi_id)
                ->whereNotIn('shm_id', $existingIds)
                ->delete();

            // ==============================
            // UPDATE WILAYAH
            // ==============================
            $kawasan = $shmUtama->kawasan;
            $kawasan->update([
                'nama_kawasan' => $request->nama_kawasan,
                'nama_lokasi'  => $request->nama_lokasi,
            ]);

            $desa = $kawasan->desa;
            $desa->update([
                'nama_desa' => $request->nama_desa,
            ]);

            $kecamatan = $desa->kecamatan;
            $kecamatan->update([
                'nama_kecamatan' => $request->nama_kecamatan,
            ]);
        });

        return back()->with('success', 'Data SHM berhasil diperbarui');
    }

    public function deleteShm($shm_id)
    {
        DB::transaction(function () use ($shm_id) {

            $shm = Shm::with('dokumen')->findOrFail($shm_id);

            /** ================= HAPUS FILE DOKUMEN ================= */
            foreach ($shm->dokumen as $dokumen) {
                if (Storage::disk('public')->exists($dokumen->path_file)) {
                    Storage::disk('public')->delete($dokumen->path_file);
                }
            }

            /** ================= HAPUS DATA ================= */
            $shm->dokumen()->delete(); // tabel shm_dokumen
            $shm->delete();            // tabel shm
        });

        return redirect()->back()->with('success', 'Data SHM berhasil dihapus');
    }
    
    public function updateDokumenShm(Request $request, $id)
    {
        $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'dokumen' => 'nullable|mimes:pdf|max:5120',
        ]);

        $dok = ShmDokumen::findOrFail($id);

        if ($request->hasFile('dokumen')) {
            // 1. Hapus file lama jika ada
            if ($dok->path_file) {
                Storage::disk('public')->delete($dok->path_file);
            }

            $file = $request->file('dokumen');
            // Ambil nama asli file tanpa extension untuk disimpan sebagai nama_dokumen
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('shm_dokumen', $filename, 'public');

            // 2. Update path file
            $dok->path_file = $path;
            
            // 3. Update nama_dokumen otomatis dari file (Opsional)
            // Hapus baris ini jika kamu ingin tetap menggunakan input manual dari user
            $dok->nama_dokumen = $originalName; 
        } else {
            // Jika tidak upload file baru, gunakan input manual dari field 'nama_dokumen'
            $dok->nama_dokumen = $request->nama_dokumen;
        }

        $dok->save();

        return back()->with('success', 'Dokumen berhasil diperbarui');
    }

    public function deleteDokumenShm($id)
    {
        $dok = ShmDokumen::findOrFail($id);

        if ($dok->path_file) {
            Storage::disk('public')->delete($dok->path_file);
        }

        $dok->delete();

        return back()->with('success', 'Dokumen berhasil dihapus');
    }

    public function getHpl()
    {
        $provinsi = Provinsi::all();
        $kabupaten = Kabupaten::all();
        
        // Ambil SEMUA data HPL tanpa dikelompokkan agar muncul semua baris di tabel
        $hpl = Hpl::with([
            'kawasan.desa.kecamatan.kabupaten.provinsi',
            'dokumen'
        ])
        ->orderBy('hpl_id', 'desc')
        ->get();

        return view('admin.hpl', compact('provinsi', 'kabupaten', 'hpl'));
    }
    
    public function storeHpl(Request $request)
    {
        $request->validate([
            'kabupaten_id' => 'required|exists:kabupaten,id',
            'nama_kecamatan' => 'required|string|max:255',
            'nama_desa' => 'required|string|max:255',
            'nama_kawasan' => 'required|string|max:255',
            'status_hpl' => 'required|in:sk,sertifikat,usulan',
            'lokasi_kawasan' => 'required|in:didalam,diluar',
            'no_sk_hpl' => 'nullable|string|max:500',
            'tgl_hpl' => 'nullable|date',
            'luas_sk' => 'required|numeric',
            'sisa_luas' => 'nullable|numeric',
            'no_sertifikat.*' => 'nullable|string|max:255', // 🔥 array
            'peta' => 'nullable|in:0,1',
            'file_peta' => 'nullable|file|mimes:pdf,jpg,jpeg,png,zip|max:10240',
            'dokumen.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        DB::transaction(function () use ($request) {

            // 🔥 kode grup HPL (PENTING untuk olah data)
            $kodeHpl = 'HPL-' . now()->format('YmdHis') . '-' . rand(100,999);

            // 1️⃣ Kecamatan
            $kecamatan = Kecamatan::firstOrCreate([
                'kabupaten_id' => $request->kabupaten_id,
                'nama_kecamatan' => $request->nama_kecamatan,
            ]);

            // 2️⃣ Desa
            $desa = Desa::firstOrCreate([
                'kecamatan_id' => $kecamatan->id,
                'nama_desa' => $request->nama_desa,
            ]);

            // 3️⃣ Kawasan
            $kawasan = KawasanTransmigrasi::firstOrCreate([
                'desa_id' => $desa->id,
                'nama_kawasan' => $request->nama_kawasan,
            ], [
                'nama_lokasi' => $request->nama_desa,
            ]);

            // 4️⃣ Upload peta (sekali saja)
            $filePeta = null;
            if ($request->hasFile('file_peta')) {
                $filePeta = $request->file('file_peta')->store('hpl_peta', 'public');
            }

            // 🔥 ambil list sertifikat
            $sertifikatList = array_filter($request->no_sertifikat ?? []);

            // =====================================================
            // 🔥 LOOP INSERT HPL
            // =====================================================

            if (empty($sertifikatList)) {

                // ✅ tanpa sertifikat
                $hpl = Hpl::create([
                    'kode_hpl' => $kodeHpl,
                    'kawasan_transmigrasi_id' => $kawasan->id,
                    'status_hpl' => $request->status_hpl,
                    'lokasi_kawasan' => $request->lokasi_kawasan,
                    'no_sk_hpl' => $request->no_sk_hpl,
                    'tgl_hpl' => $request->tgl_hpl,
                    'luas_sk' => $request->luas_sk,
                    'sisa_luas' => $request->sisa_luas,
                    'no_sertifikat' => null,
                    'peta' => $request->peta ?? 0,
                    'file_peta' => $filePeta,
                ]);

                // upload dokumen
                $this->storeDokumenHpl($request, $hpl->hpl_id);

            } else {

                // ✅ banyak sertifikat → banyak baris
                foreach ($sertifikatList as $sertifikat) {

                    $hpl = Hpl::create([
                        'kode_hpl' => $kodeHpl,
                        'kawasan_transmigrasi_id' => $kawasan->id,
                        'status_hpl' => $request->status_hpl,
                        'lokasi_kawasan' => $request->lokasi_kawasan,
                        'no_sk_hpl' => $request->no_sk_hpl,
                        'tgl_hpl' => $request->tgl_hpl,
                        'luas_sk' => $request->luas_sk,
                        'sisa_luas' => $request->sisa_luas,
                        'no_sertifikat' => $sertifikat,
                        'peta' => $request->peta ?? 0,
                        'file_peta' => $filePeta,
                    ]);

                    // upload dokumen per baris
                    $this->storeDokumenHpl($request, $hpl->hpl_id);
                }
            }
        });

        return redirect()->back()->with('success', 'Data HPL berhasil disimpan');
    }

    public function storeDokumenHpl(Request $request, $hplId = null)
    {
        // Jika $hplId null, berarti dipanggil dari modal "Tambah Dokumen" (menggunakan Request input)
        $targetHplId = $hplId ?? $request->hpl_id;

        // Validasi hanya jika dipanggil langsung dari Route (bukan dari fungsi storeHpl)
        if (!$hplId) {
            $request->validate([
                'hpl_id' => 'required|exists:hpl,hpl_id',
                'dokumen' => 'required',
                'dokumen.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120'
            ]);
        }

        if ($request->hasFile('dokumen')) {
            foreach ($request->file('dokumen') as $file) {
                $path = $file->store('hpl_dokumen', 'public');

                HplDokumen::create([
                    'hpl_id' => $targetHplId,
                    'nama_dokumen' => $file->getClientOriginalName(),
                    'path_file' => $path,
                ]);
            }
        }

        // Jika dipanggil dari Route modal, berikan feedback sukses
        if (!$hplId) {
            return back()->with('success', 'Dokumen berhasil ditambahkan');
        }
    }
    public function checkKawasan(Request $request)
    {
        $exists = Hpl::where('nama_kawasan', $request->nama_kawasan)->exists();

        return response()->json([
            'exists' => $exists
        ]);
    }
    
    public function editHpl($id)
    {
        $hpl = Hpl::with([
            'kawasan.desa.kecamatan.kabupaten.provinsi',
            'dokumen'
        ])->findOrFail($id);

        return view('admin.hpl', compact('hpl'));
    }
    public function updateHpl(Request $request, $id)
    {
        $request->validate([
            'nama_kawasan'     => 'required|string',
            'nama_desa'        => 'required|string',
            'status_hpl'       => 'required|in:sk,sertifikat,usulan',
            'no_sk_hpl'        => 'nullable|string|max:500',
            'tgl_hpl'          => 'nullable|date',
            'luas_sk'          => 'nullable|numeric',
            'sisa_luas'        => 'nullable|numeric',
            'no_sertifikat'    => 'nullable|array',
            'no_sertifikat.*'  => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $id) {

            $hpl = Hpl::findOrFail($id);
            $kawasanId = $hpl->kawasan_transmigrasi_id;

            // ================= UPDATE DATA UMUM KE SEMUA BARIS =================
            Hpl::where('kawasan_transmigrasi_id', $kawasanId)
                ->update([
                    'status_hpl' => $request->status_hpl,
                    'no_sk_hpl'  => $request->status_hpl === 'usulan' ? null : $request->no_sk_hpl,
                    'tgl_hpl'    => $request->status_hpl === 'usulan' ? null : $request->tgl_hpl,
                    'luas_sk'    => $request->status_hpl === 'usulan' ? null : $request->luas_sk,
                    'sisa_luas'  => $request->status_hpl === 'usulan' ? null : $request->sisa_luas,
                ]);

            // ================= SINKRONISASI SERTIFIKAT =================
            $old = Hpl::where('kawasan_transmigrasi_id', $kawasanId)
                        ->pluck('no_sertifikat')
                        ->filter()
                        ->toArray();

            $new = collect($request->no_sertifikat)
                        ->filter()
                        ->values()
                        ->toArray();

            // Hapus yang tidak ada lagi di form
            $deleteSertifikat = array_diff($old, $new);

            if (!empty($deleteSertifikat)) {
                // 1. Ambil ID HPL yang akan dihapus
                $hplIdsToDelete = Hpl::where('kawasan_transmigrasi_id', $kawasanId)
                    ->whereIn('no_sertifikat', $deleteSertifikat)
                    ->pluck('hpl_id');

                // 2. Hapus dokumen fisik dan datanya terlebih dahulu (Anak)
                foreach ($hplIdsToDelete as $idHpl) {
                    $dokumens = HplDokumen::where('hpl_id', $idHpl)->get();
                    foreach ($dokumens as $dok) {
                        if (Storage::disk('public')->exists($dok->path_file)) {
                            Storage::disk('public')->delete($dok->path_file);
                        }
                        $dok->delete();
                    }
                }

                // 3. Baru hapus data HPL-nya (Induk)
                Hpl::whereIn('hpl_id', $hplIdsToDelete)->delete();
            }

            // Tambah yang baru
            $insert = array_diff($new, $old);

            foreach ($insert as $no) {
                Hpl::create([
                    'kawasan_transmigrasi_id' => $kawasanId,
                    'status_hpl' => $request->status_hpl,
                    'lokasi_kawasan' => $hpl->lokasi_kawasan,
                    'no_sk_hpl' => $request->no_sk_hpl,
                    'tgl_hpl' => $request->tgl_hpl,
                    'luas_sk' => $request->luas_sk,
                    'sisa_luas' => $request->sisa_luas,
                    'no_sertifikat' => $no,
                ]);
            }

            // ================= UPDATE DATA KAWASAN =================
            $kawasan = $hpl->kawasan;

            $kawasan->update([
                'nama_kawasan' => $request->nama_kawasan,
            ]);

            $kawasan->desa->update([
                'nama_desa' => $request->nama_desa,
            ]);
        });

        return back()->with('success', 'Data HPL berhasil diperbarui');
    }
    public function deleteHpl($hpl_id)
    {
        DB::transaction(function () use ($hpl_id) {

            $hpl = Hpl::with('dokumen')->findOrFail($hpl_id);

            /** ================= HAPUS FILE DOKUMEN ================= */
            foreach ($hpl->dokumen as $dokumen) {
                if (Storage::disk('public')->exists($dokumen->path_file)) {
                    Storage::disk('public')->delete($dokumen->path_file);
                }
            }

            /** ================= HAPUS DATA ================= */
            $hpl->dokumen()->delete(); 
            $hpl->delete();            
        });

        return redirect()->back()->with('success', 'Data HPL berhasil dihapus');
    }
    
    public function updateDokumenHpl(Request $request, $id)
    {
        $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'dokumen' => 'nullable|mimes:pdf|max:5120',
        ]);

        $dok = HplDokumen::findOrFail($id);

        if ($request->hasFile('dokumen')) {
            // 1. Hapus file lama jika ada
            if ($dok->path_file) {
                Storage::disk('public')->delete($dok->path_file);
            }

            $file = $request->file('dokumen');
            // Ambil nama asli file tanpa extension untuk disimpan sebagai nama_dokumen
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('hpl_dokumen', $filename, 'public');

            // 2. Update path file
            $dok->path_file = $path;
            
            // 3. Update nama_dokumen otomatis dari file (Opsional)
            // Hapus baris ini jika kamu ingin tetap menggunakan input manual dari user
            $dok->nama_dokumen = $originalName; 
        } else {
            // Jika tidak upload file baru, gunakan input manual dari field 'nama_dokumen'
            $dok->nama_dokumen = $request->nama_dokumen;
        }

        $dok->save();

        return back()->with('success', 'Dokumen berhasil diperbarui');
    }

    public function deleteDokumenHpl($id)
    {
        $dok = HplDokumen::findOrFail($id);

        if ($dok->path_file) {
            Storage::disk('public')->delete($dok->path_file);
        }

        $dok->delete();

        return back()->with('success', 'Dokumen berhasil dihapus');
    }
    public function getPermasalahanLahan()
    {
        $provinsi = Provinsi::all();
        $kabupaten = Kabupaten::all();
        $kawasanList = KawasanTransmigrasi::whereHas('permasalahan')
        ->with([
            'desa.kecamatan.kabupaten.provinsi',
            'permasalahan.jenis',
            'permasalahan.dokumen', 'permasalahan.progress'
        ])
        ->get();

         $jenisPermasalahan = JenisPermasalahan::all();

        return view('admin.permasalahanLahan', compact('provinsi','kabupaten',
         'kawasanList', 'jenisPermasalahan'));
    }
    public function storePl(Request $request)
    {
        $request->validate([
            'provinsi_id' => 'required|exists:provinsi,id',
            'kabupaten_id' => 'required|exists:kabupaten,id',

            'nama_kecamatan' => 'required|string|max:255',
            'nama_desa' => 'required|string|max:255',
            'nama_kawasan' => 'required|string|max:255',
            'nama_lokasi' => 'required|string|max:255',

            'status_lahan' => 'required|in:HPL,Pencadangan',
            'pola' => 'required|string|max:255',
            'tahun_patan' => 'required|integer|digits:4|min:1900|max:2100',
            'jumlah_kk' => 'required|integer',
            'deskripsi' => 'required|string',

            // ✅ VALIDASI ARRAY (INI KUNCI)
            'permasalahan' => 'required|array|min:1',
            'permasalahan.*.jenis_pl_id' => 'required|exists:jenis_permasalahan,jenis_pl_id',
            'permasalahan.*.jumlah_bidang' => 'required|integer|min:0',

            // progress
            'tahun' => 'required|integer|digits:4|min:1900|max:2100',
            'jumlah_kasus' => 'required|integer|min:0',
            'status_penanganan' => 'required|in:Aktif,Selesai',

            'dokumen.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        DB::transaction(function () use ($request) {

            $kecamatan = Kecamatan::firstOrCreate(
                [
                    'kabupaten_id' => $request->kabupaten_id,
                    'nama_kecamatan' => $request->nama_kecamatan,
                ]
            );

            $desa = Desa::firstOrCreate(
                [
                    'kecamatan_id' => $kecamatan->id,
                    'nama_desa' => $request->nama_desa,
                ]
            );

            $kawasan = KawasanTransmigrasi::firstOrCreate(
                [
                    'desa_id' => $desa->id,
                    'nama_kawasan' => $request->nama_kawasan,
                ],
                [
                    'nama_lokasi' => $request->nama_lokasi,
                ]
            );

            foreach ($request->permasalahan as $item) {

                $pl = PermasalahanLahan::create([
                    'kawasan_transmigrasi_id' => $kawasan->id,
                    'status_lahan' => $request->status_lahan,
                    'pola' => $request->pola,
                    'tahun_patan' => $request->tahun_patan,
                    'jumlah_kk' => $request->jumlah_kk,
                    'jumlah_bidang' => $item['jumlah_bidang'],
                    'jenis_pl_id' => $item['jenis_pl_id'],
                    'deskripsi' => $request->deskripsi,
                ]);

                // 🔥 Progress dibuat untuk setiap PL
                PlProgress::create([
                    'pl_id' => $pl->pl_id,
                    'tahun' => $request->tahun,
                    'jumlah_kasus' => $request->jumlah_kasus,
                    'status_penanganan' => $request->status_penanganan,
                    'tindak_lanjut' => $request->tindak_lanjut,
                    'rekomendasi' => $request->rekomendasi,
                ]);

                // 🔥 Dokumen juga sebaiknya di dalam loop
                if ($request->hasFile('dokumen')) {
                    foreach ($request->file('dokumen') as $file) {

                        $path = $file->store('pl_dokumen', 'public');

                        PlDokumen::create([
                            'pl_id' => $pl->pl_id,
                            'nama_dokumen' => $file->getClientOriginalName(),
                            'path_file' => $path,
                        ]);
                    }
                }
            }

        });

        return back()->with('success', 'Data Permasalahan Lahan berhasil disimpan');
    }
    public function storeDokumenPl(Request $request)
    {
        $request->validate([
            'pl_id' => 'required|exists:permasalahan_lahan,pl_id',
            'dokumen' => 'required',
            'dokumen.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        foreach ($request->file('dokumen') as $file) {

            $path = $file->store('shm_dokumen', 'public');

            PlDokumen::create([
                'pl_id' => $request->pl_id,
                'nama_dokumen' => $file->getClientOriginalName(),
                'path_file' => $path,
            ]);
        }

        return back()->with('success','Dokumen berhasil ditambahkan');
    }
    public function editPl($id)
    {
        $pl = PermasalahanLahan::with([
            'kawasan.desa.kecamatan.kabupaten.provinsi',
            'dokumen'
        ])->findOrFail($id);

        return view('admin.pl', compact('pl'));
    }
    public function updatePl(Request $request, $id)
    {
        $request->validate([

            'nama_kawasan' => 'required|string|max:255',
            'nama_lokasi' => 'required|string|max:255',

            'status_lahan' => 'required|string',

            'pola' => 'nullable|string|max:255',
            'tahun_patan' => 'nullable|integer',
            'jumlah_kk' => 'nullable|integer',

            'tahun' => 'required|integer',
            'jumlah_kasus' => 'required|integer',
            'status_penanganan' => 'required|string',

            'rekomendasi' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'tindak_lanjut' => 'nullable|string',

            'permasalahan' => 'nullable|array',
            'permasalahan.*.pl_id' => 'nullable|integer',
            'permasalahan.*.jenis_pl_id' => 'nullable|integer',
            'permasalahan.*.jumlah_bidang' => 'nullable|integer',

            'dokumen.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',

        ]);


        DB::transaction(function () use ($request, $id) {

            /** ================= PERMASALAHAN UTAMA ================= */

            $pl = PermasalahanLahan::findOrFail($id);


            $pl->update([
                'status_lahan' => $request->status_lahan,
                'pola' => $request->pola,
                'tahun_patan' => $request->tahun_patan,
                'jumlah_kk' => $request->jumlah_kk,
                'deskripsi' => $request->deskripsi,
            ]);

            /** ================= UPDATE KAWASAN ================= */

            if ($pl->kawasan) {

                $pl->kawasan->update([
                    'nama_kawasan' => $request->nama_kawasan,
                    'nama_lokasi' => $request->nama_lokasi,
                ]);

            }
            /** ================= UPDATE SEMUA PERMASALAHAN ================= */

            if ($request->permasalahan) {

                foreach ($request->permasalahan as $item) {

                    // jika pl_id ada → update
                    if (!empty($item['pl_id'])) {

                        PermasalahanLahan::where('pl_id', $item['pl_id'])

                            ->update([

                                'jenis_pl_id' => $item['jenis_pl_id'],

                                'jumlah_bidang' => $item['jumlah_bidang'],

                            ]);

                    }

                    // jika tidak ada → create baru
                    else {

                        PermasalahanLahan::create([

                            'kawasan_transmigrasi_id' => $pl->kawasan_transmigrasi_id,

                            'status_lahan' => $request->status_lahan,

                            'pola' => $request->pola,

                            'tahun_patan' => $request->tahun_patan,

                            'jumlah_kk' => $request->jumlah_kk,

                            'jenis_pl_id' => $item['jenis_pl_id'],

                            'jumlah_bidang' => $item['jumlah_bidang'],

                            'deskripsi' => $request->deskripsi,

                        ]);

                    }

                }

            }



            /** ================= UPDATE / CREATE PROGRESS ================= */

            PlProgress::updateOrCreate(

                ['pl_id' => $pl->pl_id],

                [

                    'tahun' => $request->tahun,

                    'jumlah_kasus' => $request->jumlah_kasus,

                    'status_penanganan' => $request->status_penanganan,

                    'tindak_lanjut' => $request->tindak_lanjut,

                    'rekomendasi' => $request->rekomendasi,

                ]

            );



            /** ================= UPLOAD DOKUMEN ================= */

            if ($request->hasFile('dokumen')) {

                foreach ($request->file('dokumen') as $file) {

                    $path = $file->store('pl_dokumen', 'public');

                    PlDokumen::create([

                        'pl_id' => $pl->pl_id,

                        'nama_dokumen' => $file->getClientOriginalName(),

                        'path_file' => $path,

                    ]);

                }

            }

        });



        return back()->with('success', 'Data Permasalahan Lahan berhasil diperbarui');
    }
    public function updateDokumenPl(Request $request, $id)
    {
        $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'dokumen' => 'nullable|mimes:pdf|max:5120',
        ]);

        $dok = PlDokumen::findOrFail($id);

        if ($request->hasFile('dokumen')) {
            // 1. Hapus file lama jika ada
            if ($dok->path_file) {
                Storage::disk('public')->delete($dok->path_file);
            }

            $file = $request->file('dokumen');
            // Ambil nama asli file tanpa extension untuk disimpan sebagai nama_dokumen
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('pl_dokumen', $filename, 'public');

            // 2. Update path file
            $dok->path_file = $path;
            
            // 3. Update nama_dokumen otomatis dari file (Opsional)
            // Hapus baris ini jika kamu ingin tetap menggunakan input manual dari user
            $dok->nama_dokumen = $originalName; 
        } else {
            // Jika tidak upload file baru, gunakan input manual dari field 'nama_dokumen'
            $dok->nama_dokumen = $request->nama_dokumen;
        }

        $dok->save();

        return back()->with('success', 'Dokumen berhasil diperbarui');
    }

    public function deleteDokumenPl($id)
    {
        $dok = PlDokumen::findOrFail($id);

        if ($dok->path_file) {
            Storage::disk('public')->delete($dok->path_file);
        }

        $dok->delete();

        return back()->with('success', 'Dokumen berhasil dihapus');
    }

    public function deletePl($kawasanId)
    {
        $permasalahan = PermasalahanLahan::where('kawasan_transmigrasi_id', $kawasanId)->get();

        foreach ($permasalahan as $pl) {
        $pl->dokumen()->delete(); // hapus pl_dokumen dulu
        $pl->delete();            // baru hapus permasalahan
        }


        return redirect()->back()->with('success', 'Data Permasalahan Lahan berhasil dihapus');
    }
}
