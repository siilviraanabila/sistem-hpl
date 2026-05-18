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
        $hplDokumenQuery = HplDokumen::query()

        ->whereHas('hpl.kawasan', function ($q) use (
            $provinsiId,
            $kabupaten,
            $kawasan,
            $lokasi
        ) {

            $q->when($provinsiId, function ($q2) use ($provinsiId) {
                $q2->whereHas(
                    'desa.kecamatan.kabupaten.provinsi',
                    fn($qq) => $qq->where('id', $provinsiId)
                );
            });

            $q->when($kabupaten, function ($q2) use ($kabupaten) {
                $q2->whereHas(
                    'desa.kecamatan.kabupaten',
                    fn($qq) => $qq->where(
                        'nama_kabupaten',
                        'like',
                        '%' . $kabupaten . '%'
                    )
                );
            });

            $q->when($kawasan, function ($q2) use ($kawasan) {
                $q2->where(
                    'nama_kawasan',
                    'like',
                    '%' . $kawasan . '%'
                );
            });

            $q->when($lokasi, function ($q2) use ($lokasi) {
                $q2->where(
                    'nama_lokasi',
                    'like',
                    '%' . $lokasi . '%'
                );
            });
        });

        $statusDokumen = (clone $hplDokumenQuery)
            ->selectRaw('jenis_dokumen, COUNT(*) as total')
            ->groupBy('jenis_dokumen')
            ->pluck('total', 'jenis_dokumen');

        $pieStatusHpl = [
            [
                'name' => 'SK HPL',
                'y' => (int) ($statusDokumen['sk'] ?? 0)
            ],
            [
                'name' => 'Sertifikat',
                'y' => (int) ($statusDokumen['sertifikat'] ?? 0)
            ],
            [
                'name' => 'Peta',
                'y' => (int) ($statusDokumen['peta'] ?? 0)
            ],
        ];
        $totalHpl = Hpl::count();

        $totalAdaPeta = (clone $hplDokumenQuery)
            ->where('jenis_dokumen', 'peta')
            ->distinct('hpl_id')
            ->count('hpl_id');

        $piePetaHpl = [
            [
                'name' => 'Ada Peta',
                'y' => $totalAdaPeta
            ],
            [
                'name' => 'Tidak Ada Peta',
                'y' => max($totalHpl - $totalAdaPeta, 0)
            ],
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
            'pie',
            'tahunList', 'dataBidang',
            'totalProvinsi', 'totalKabupaten', 'totalLokasi',
            'listTahun', 'pieJenisPermasalahan', 'shm', 'hplDokumenQuery', 'pieStatusHpl', 'piePetaHpl'
        ));
    }
}
