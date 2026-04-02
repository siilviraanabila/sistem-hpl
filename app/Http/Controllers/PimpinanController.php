<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermasalahanLahan;
use App\Models\Shm;
use App\Models\Hpl;
use App\Models\Kecamatan;
use App\Models\Kabupaten;
use App\Models\Desa;
use App\Models\KawasanTransmigrasi;
use App\Models\ShmDokumen;
use App\Models\HplDokumen;
use App\Models\PlDokumen;
use App\Models\Provinsi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class PimpinanController extends Controller
{
    public function dashboard(Request $request)
    {
        
        $provinsiId = $request->provinsi;
        $provinsiList = Provinsi::orderBy('nama_provinsi')->get();

        // Base Query SHM + filter provinsi lewat relasi
        $baseQuery = Shm::with('kawasan.desa.kecamatan.kabupaten.provinsi');

        if ($provinsiId) {
            $baseQuery->whereHas('kawasan.desa.kecamatan.kabupaten.provinsi', function ($q) use ($provinsiId) {
                $q->where('id', $provinsiId);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | PIE SHM (Target vs Realisasi vs Sisa)
        |--------------------------------------------------------------------------
        */

        $tahun = $request->tahun;

        // Base query untuk pie (HARUS pakai filter tahun juga)
        $baseQuery = Shm::query();

        if ($tahun) {
            $baseQuery->where('target_tahunan', $tahun);
        }

        $row = (clone $baseQuery)->selectRaw('
            SUM(clear_shm) as clear,
            SUM(realisasi_shm) as realisasi,
            SUM(bermasalah_shm) as bermasalah
        ')->first();

        $pie = [
            ['name' => 'Clear', 'y' => (int) ($row->clear ?? 0)],
            ['name' => 'Sudah Terbit', 'y' => (int) ($row->realisasi ?? 0)],
            ['name' => 'Bermasalah', 'y' => (int) ($row->bermasalah ?? 0)],
        ];

        // Data tabel / lainnya (kalau masih perlu)
        $query = Shm::query();

        if ($tahun) {
            $query->where('target_tahunan', $tahun);
        }

        $shm = $query->get();

        // List tahun tetap
        $listTahun = Shm::select('target_tahunan')
            ->distinct()
            ->orderBy('target_tahunan', 'desc')
            ->pluck('target_tahunan');


        /*
        |--------------------------------------------------------------------------
        | PIE HPL
        |--------------------------------------------------------------------------
        */
        $hpl = (clone $baseQuery)->selectRaw('status_hpl, COUNT(*) as total')
            ->groupBy('status_hpl')
            ->pluck('total', 'status_hpl');

        $pieHpl = [
            ['name' => 'Serah', 'y' => (int) ($hpl['Serah'] ?? 0)],
            ['name' => 'Belum', 'y' => (int) ($hpl['Belum'] ?? 0)],
        ];

        /*
        |--------------------------------------------------------------------------
        | PIE UPT
        |--------------------------------------------------------------------------
        */
        $upt = (clone $baseQuery)->selectRaw('status_upt, COUNT(*) as total')
            ->groupBy('status_upt')
            ->pluck('total', 'status_upt');

        $pieUpt = [
            ['name' => 'Serah', 'y' => (int) ($upt['Serah'] ?? 0)],
            ['name' => 'Bina',  'y' => (int) ($upt['Bina'] ?? 0)],
        ];

        /*
        |--------------------------------------------------------------------------
        | GRAFIK TAHUNAN (X = target_tahunan, Y = total bidang)
        |--------------------------------------------------------------------------
        */
        $rows = Shm::query()
            ->join('kawasan_transmigrasi', 'kawasan_transmigrasi.id', '=', 'shm.kawasan_transmigrasi_id')
            ->join('desa', 'desa.id', '=', 'kawasan_transmigrasi.desa_id')
            ->join('kecamatan', 'kecamatan.id', '=', 'desa.kecamatan_id')
            ->join('kabupaten', 'kabupaten.id', '=', 'kecamatan.kabupaten_id')
            ->join('provinsi', 'provinsi.id', '=', 'kabupaten.provinsi_id')
            ->when($provinsiId, function ($q) use ($provinsiId) {
                $q->where('provinsi.id', $provinsiId);
            })
            ->selectRaw('target_tahunan as tahun, SUM(bidang) as total_bidang')
            ->groupBy('target_tahunan')
            ->orderBy('target_tahunan')
            ->get();

        $tahunList  = $rows->pluck('tahun')->toArray();
        $dataBidang = $rows->pluck('total_bidang')->map(fn($v) => (int)$v)->toArray();


        /*
        |--------------------------------------------------------------------------
        | REKAP PERMASALAHAN LAHAN
        |--------------------------------------------------------------------------
        */

        $plQuery = PermasalahanLahan::query()
            ->join('kawasan_transmigrasi', 'kawasan_transmigrasi.id', '=', 'permasalahan_lahan.kawasan_transmigrasi_id')
            ->join('desa', 'desa.id', '=', 'kawasan_transmigrasi.desa_id')
            ->join('kecamatan', 'kecamatan.id', '=', 'desa.kecamatan_id')
            ->join('kabupaten', 'kabupaten.id', '=', 'kecamatan.kabupaten_id')
            ->join('provinsi', 'provinsi.id', '=', 'kabupaten.provinsi_id');

        if ($provinsiId) {
            $plQuery->where('provinsi.id', $provinsiId);
        }

        /* =========================
        PIE JENIS PERMASALAHAN
        ========================= */
        $jenisData = (clone $plQuery)
            ->join('jenis_permasalahan', 'jenis_permasalahan.jenis_pl_id', '=', 'permasalahan_lahan.jenis_pl_id')
            ->selectRaw('jenis_permasalahan.nama_permasalahan, COUNT(DISTINCT permasalahan_lahan.pl_id) as total')
            ->groupBy('jenis_permasalahan.nama_permasalahan')
            ->pluck('total', 'jenis_permasalahan.nama_permasalahan');

        $pieJenisPermasalahan = [];

        foreach ($jenisData as $nama => $total) {
            $pieJenisPermasalahan[] = [
                'name' => $nama,
                'y'    => (int) $total
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | REKAP STATUS HPL
        |--------------------------------------------------------------------------
        */
        $hplQuery = Hpl::with('kawasan.desa.kecamatan.kabupaten.provinsi');

        if ($provinsiId) {
            $hplQuery->whereHas('kawasan.desa.kecamatan.kabupaten.provinsi', function ($q) use ($provinsiId) {
                $q->where('id', $provinsiId);
            });
        }

        $statusHpl = (clone $hplQuery)->selectRaw('status_hpl, COUNT(*) as total')
            ->groupBy('status_hpl')
            ->pluck('total', 'status_hpl');

        $pieStatusHpl = [
            ['name' => 'SK HPL',     'y' => (int) ($statusHpl['sk'] ?? 0)],
            ['name' => 'Sertifikat', 'y' => (int) ($statusHpl['sertifikat'] ?? 0)],
            ['name' => 'Usulan',     'y' => (int) ($statusHpl['usulan'] ?? 0)],
        ];

        $petaQuery = Hpl::query()
            ->join('kawasan_transmigrasi', 'kawasan_transmigrasi.id', '=', 'hpl.kawasan_transmigrasi_id')
            ->join('desa', 'desa.id', '=', 'kawasan_transmigrasi.desa_id')
            ->join('kecamatan', 'kecamatan.id', '=', 'desa.kecamatan_id')
            ->join('kabupaten', 'kabupaten.id', '=', 'kecamatan.kabupaten_id')
            ->join('provinsi', 'provinsi.id', '=', 'kabupaten.provinsi_id');

        if ($provinsiId) {
            $petaQuery->where('provinsi.id', $provinsiId);
        }

        $rekapPeta = (clone $petaQuery)->selectRaw("
                SUM(CASE WHEN hpl.peta = 1 THEN 1 ELSE 0 END) as ada_peta,
                SUM(CASE WHEN hpl.peta = 0 THEN 1 ELSE 0 END) as tidak_ada_peta
            ")->first();

        $piePetaHpl = [
            ['name' => 'Ada Peta', 'y' => (int) ($rekapPeta->ada_peta ?? 0)],
            ['name' => 'Tidak Ada Peta', 'y' => (int) ($rekapPeta->tidak_ada_peta ?? 0)],
        ];

        $wilayahQuery = KawasanTransmigrasi::query()
            ->join('desa', 'desa.id', '=', 'kawasan_transmigrasi.desa_id')
            ->join('kecamatan', 'kecamatan.id', '=', 'desa.kecamatan_id')
            ->join('kabupaten', 'kabupaten.id', '=', 'kecamatan.kabupaten_id')
            ->join('provinsi', 'provinsi.id', '=', 'kabupaten.provinsi_id');

        if ($provinsiId) {
            $wilayahQuery->where('provinsi.id', $provinsiId);
        }

        $summaryWilayah = (clone $wilayahQuery)->selectRaw('
                COUNT(DISTINCT provinsi.id) as total_provinsi,
                COUNT(DISTINCT kabupaten.id) as total_kabupaten,
                COUNT(DISTINCT kawasan_transmigrasi.id) as total_lokasi
            ')->first();
        $totalProvinsi  = $summaryWilayah->total_provinsi ?? 0;
        $totalKabupaten = $summaryWilayah->total_kabupaten ?? 0;
        $totalLokasi    = $summaryWilayah->total_lokasi ?? 0;

        $query = KawasanTransmigrasi::query();

        if ($request->provinsi) {
            $query->whereHas('desa.kecamatan.kabupaten.provinsi', function ($q) use ($request) {
                $q->where('id', $request->provinsi);
            });
        }

        if ($request->kabupaten) {
            $query->whereHas('desa.kecamatan.kabupaten', function ($q) use ($request) {
                $q->where('nama_kabupaten', 'like', '%' . $request->kabupaten . '%');
            });
        }

        if ($request->kawasan) {
            $query->where('nama_kawasan', 'like', '%' . $request->kawasan . '%');
        }

        if ($request->lokasi) {
            $query->where('nama_lokasi', 'like', '%' . $request->lokasi . '%');
        }

        $data = $query->get();



        return view('pimpinan.dashboard', compact(
            'provinsiList',
            'provinsiId',
            'pie',
            'pieHpl',
            'pieUpt',
            'tahunList','piePetaHpl',
            'dataBidang', 'pieStatusHpl',
            'totalProvinsi',
            'totalKabupaten',
            'totalLokasi','shm','listTahun', 'pieJenisPermasalahan',
            
        ));
    }
}
