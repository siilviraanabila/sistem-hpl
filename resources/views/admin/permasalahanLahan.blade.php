<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>Permasalahan Lahan</title>
    @include('layouts.header')
    @vite('resources/css/app.css') 
</head>
<body class="bg-slate-100 min-h-screen">
    <div class="p-4 pt-20 space-y-10">
        <div class="items-center justify-between lg:flex">
            <div class="p-2 w-full">

                <div class="flex flex-col md:flex-row items-start justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <h2 class="text-2xl font-bold text-gray-700">
                        Permasalahan Lahan
                    </h2>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button
                            data-modal-target="tambah-modal"
                            data-modal-toggle="tambah-modal"
                            class="px-5 py-2.5 text-sm font-medium text-white
                                bg-blue-600 rounded-lg shadow-sm
                                hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300
                                transition">
                            Tambah Permasalahan Lahan
                        </button>
                    </div>
                </div>

                {{-- ================= TABEL UTAMA ================= --}}
                <div class="mt-2 bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-gray-700">
                            <thead class="text-xs uppercase bg-gray-300 border-b">
                                <tr>
                                    <th class="px-4 py-3 text-center">No</th>
                                    <th class="px-4 py-3 text-center">Provinsi</th>
                                    <th class="px-4 py-3 text-center">Kabupaten</th>
                                    <th class="px-4 py-3 text-center">Kawasan</th>
                                    <th class="px-4 py-3 text-center">Lokasi</th>
                                    <th class="px-4 py-3 text-center">Permasalahan</th>
                                    <th class="px-4 py-3 text-center">Dokumen</th>
                                    <th class="px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            
                            <tbody class="divide-y">
                                @forelse ($kawasanList as $index => $kawasan)
                                <tr class="hover:bg-gray-50">

                                    {{-- NO --}}
                                    <td class="px-4 py-2 text-center">{{ $index + 1 }}</td>

                                    {{-- PROVINSI --}}
                                    <td class="px-4 py-2 text-center">{{ optional($kawasan->desa?->kecamatan?->kabupaten?->provinsi)->nama_provinsi ?? '-' }}</td>

                                    {{-- KABUPATEN --}}
                                    <td class="px-4 py-2 text-center">{{ optional($kawasan->desa?->kecamatan?->kabupaten)->nama_kabupaten ?? '-' }}</td>

                                    {{-- KAWASAN --}}
                                    <td class="px-4 py-2 text-center ">{{ $kawasan->nama_kawasan }}</td>

                                    {{-- LOKASI --}}
                                    <td class="px-4 py-2 text-center">{{ $kawasan->nama_lokasi }}</td>

                                    {{-- PERMASALAHAN --}}
                                    <td class="px-4 py-2 text-sm">
                                        @if($kawasan->permasalahan && $kawasan->permasalahan->count())
                                            <ul class="space-y-1">
                                                @foreach($kawasan->permasalahan as $pl)
                                                    <li>
                                                        • {{ $pl->jenis->nama_permasalahan ?? '-' }}
                                                        ({{ $pl->jumlah_bidang ?? 0 }})
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>

                                    {{-- DOKUMEN --}}
                                    <td class="px-4 py-2 text-center">
                                        @php
                                            $totalDok = $kawasan->permasalahan?->sum(fn($p) => $p->dokumen?->count() ?? 0) ?? 0;
                                        @endphp
                                        @if($totalDok > 0)
                                            <button
                                                data-modal-target="dokumen-modal-{{ $kawasan->id }}"
                                                data-modal-toggle="dokumen-modal-{{ $kawasan->id }}"
                                                class="text-blue-600 font-medium hover:underline">
                                                {{ $totalDok }} Dokumen
                                            </button>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>

                                    {{-- AKSI --}}
                                    <td class="px-4 py-2 text-center">
                                        <div class="flex justify-center gap-3">
                                            {{-- DETAIL --}}
                                            <button type="button" onclick="openModal('detail-modal-{{ $kawasan->id }}')"
                                                class="text-blue-600 hover:text-blue-800" title="Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>

                                            {{-- EDIT --}}
                                            <button type="button"
                                                onclick="openModal('edit-modal-{{ $kawasan->id }}')"
                                                class="text-yellow-600 hover:text-yellow-800"
                                                title="Edit">
                                                
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                </svg>
                                            </button>

                                            {{-- DELETE --}}
                                            <form action="{{ route('deletePl', $kawasan->id) }}" method="POST" onsubmit="return confirm('Yakin hapus semua permasalahan di kawasan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus Semua Permasalahan">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v3H9V4a1 1 0 011-1z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                                        Data PL belum tersedia
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>


    {{-- ================= ALL MODALS DI BAWAH SINI (LUAR TABEL) ================= --}}
    @php
        $input = "w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-slate-500 focus:outline-none";
    @endphp

    {{-- MODAL TAMBAH DATA --}}
    <div id="tambah-modal" tabindex="-1" aria-hidden="true" class="fixed inset-0 z-50 hidden bg-black/50 flex items-start justify-center overflow-y-auto">
        <div class="relative mx-auto mt-10 mb-10 w-[95%] max-w-4xl">
            <div class="bg-white rounded-2xl shadow-xl p-6 max-h-[90vh] overflow-y-auto">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Tambah Data Permasalahan Lahan</h3>

                @if ($errors->any())
                    <div class="p-4 mb-6 text-red-700 bg-red-100 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('storePl') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- DATA WILAYAH --}}
                    <div class="border border-gray-200 rounded-xl p-5 mb-6">
                        <h4 class="text-md font-semibold text-gray-700 mb-4">Data Wilayah</h4>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Provinsi</label>
                                <select id="provinsi" name="provinsi_id" class="{{ $input }}" required>
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach ($provinsi as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_provinsi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Kabupaten</label>
                                <select id="kabupaten" name="kabupaten_id" class="{{ $input }}" required>
                                    <option value="">-- Pilih Kabupaten --</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Kecamatan</label>
                                <input type="text" name="nama_kecamatan" class="{{ $input }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Desa</label>
                                <input type="text" name="nama_desa" class="{{ $input }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Nama Kawasan</label>
                                <input type="text" name="nama_kawasan" class="{{ $input }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Nama Lokasi</label>
                                <input type="text" name="nama_lokasi" class="{{ $input }}" required>
                            </div>
                        </div>
                    </div>

                    {{-- DATA PERMASALAHAN --}}
                    <div class="border border-gray-200 rounded-2xl p-6 mb-6 bg-white">
                        <div class="flex items-center justify-between mb-5">
                            <h4 class="text-lg font-semibold text-gray-800">Data Permasalahan Lahan</h4>
                        </div>
                        <div class="grid md:grid-cols-4 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Status Lahan</label>
                                <select name="status_lahan" class="{{ $input }}" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="HPL">HPL</option>
                                    <option value="Pencadangan">Pencadangan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Pola</label>
                                <input type="text" name="pola" class="{{ $input }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Tahun Patan</label>
                                <input type="number" name="tahun_patan" class="{{ $input }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Total KK</label>
                                <input type="number" name="jumlah_kk" class="{{ $input }}" required>
                            </div>
                        </div>

                        {{-- TABLE RINCIAN JENIS --}}
                        <div class="border border-gray-200 rounded-xl overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-700">Rincian Jenis Permasalahan</span>
                                <button 
                                    id="btn-tambah-permasalahan"
                                    type="button"
                                    onclick="tambahPermasalahan()" 
                                    class="text-sm px-3 py-1.5 bg-blue-700 hover:bg-blue-800 text-white rounded-lg transition
                                    {{ $jenisPermasalahan->count() >= 5 ? 'hidden' : '' }}">
                                    + Tambah
                                </button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-white-100 text-gray-700">
                                        <tr>
                                            <th class="p-3 text-left w-[50%]">Jenis Permasalahan</th>
                                            <th class="p-3 text-left w-[30%]">Jumlah Bidang</th>
                                        </tr>
                                    </thead>
                                    <tbody id="body-permasalahan" class="divide-y">

                                        @foreach($jenisPermasalahan as $index => $j)
                                        <tr class="hover:bg-gray-50">

                                            <td class="p-3">
                                                <input type="hidden"
                                                    name="permasalahan[{{ $index }}][jenis_pl_id]"
                                                    value="{{ $j->jenis_pl_id }}">

                                                <input type="text"
                                                    value="{{ $j->nama_permasalahan }}"
                                                    class="w-full border rounded-lg px-3 py-2 bg-white"
                                                    readonly>
                                            </td>

                                            <td class="p-3">
                                                <input type="number"
                                                    name="permasalahan[{{ $index }}][jumlah_bidang]"
                                                    class="{{ $input }}"
                                                    min="0"
                                                    required>
                                            </td>

                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-600 mb-1">Deskripsi Permasalahan</label>
                            <textarea name="deskripsi" rows="4" class="{{ $input }}" placeholder="Tuliskan uraian singkat permasalahan..." required></textarea>
                        </div>
                    </div>

                    {{-- PROGRESS --}}
                    <div class="border border-gray-200 rounded-xl p-5 mb-6">
                        <h4 class="font-semibold text-gray-700 mb-4">Progress Penanganan</h4>
                        <div class="grid md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Tahun Progress</label>
                                <input type="number" name="tahun" class="{{ $input }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Jumlah Kasus</label>
                                <input type="number" name="jumlah_kasus" min="0" class="{{ $input }}" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Status Penanganan</label>
                                <select name="status_penanganan" class="{{ $input }}" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Tindak Lanjut</label>
                                <textarea name="tindak_lanjut" rows="3" class="{{ $input }}"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Rekomendasi</label>
                                <textarea name="rekomendasi" rows="3" class="{{ $input }}"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- DOKUMEN --}}
                    <div class="border border-gray-200 rounded-xl p-5 mb-6">
                        <h4 class="font-semibold text-gray-700 mb-4">Dokumen Pendukung</h4>
                        <input type="file" name="dokumen[]" multiple class="{{ $input }}" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" data-modal-hide="tambah-modal" class="px-5 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg shadow">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- LOOPING MODAL PER KAWASAN (DOKUMEN, DETAIL, EDIT) --}}
    @foreach ($kawasanList as $kawasan)
        
        {{-- MODAL DOKUMEN --}}
        <div id="dokumen-modal-{{ $kawasan->id }}" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center">
            <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] flex flex-col">
                <div class="p-6 border-b flex justify-between items-center">
                    <h3 class="text-lg font-bold">Dokumen – {{ $kawasan->nama_kawasan }}</h3>
                </div>
                <div class="p-6 overflow-y-auto">
                    @php
                        $semuaDokumen = collect();
                        if ($kawasan->permasalahan) {
                            foreach ($kawasan->permasalahan as $pl) {
                                if ($pl->dokumen) {
                                    $semuaDokumen = $semuaDokumen->merge($pl->dokumen);
                                }
                            }
                        }
                    @endphp
                    @if($semuaDokumen->count() > 0)
                        <ul class="space-y-3 mb-6">
                            @foreach ($semuaDokumen as $dok)
                                <li class="border rounded-lg p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-gray-50">
                                    <span class="text-sm font-medium text-gray-700 truncate w-full sm:w-[70%]">{{ $dok->nama_dokumen }}</span>
                                    <div class="flex items-center gap-4 shrink-0">
                                        <a href="{{ Storage::url($dok->path_file) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-semibold transition">Lihat</a>
                                        <span class="text-gray-300">|</span>
                                        <form action="{{ route('deleteDokumenPl', $dok->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-semibold transition">Hapus</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-center text-gray-500 mb-6 mt-2">Belum ada dokumen.</p>
                    @endif

                    <div class="border-t pt-5">
                        <h4 class="text-sm font-bold text-gray-700 mb-3">Tambah Dokumen Baru</h4>
                        <form action="{{ route('storeDokumenPl') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Untuk Permasalahan Lahan:</label>
                                <select name="pl_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-slate-500 focus:outline-none" required>
                                    <option value="">-- Pilih Jenis Permasalahan --</option>
                                    @foreach($kawasan->permasalahan as $pl)
                                        <option value="{{ $pl->pl_id }}">{{ $pl->jenis->nama_permasalahan ?? 'Data ID: '.$pl->pl_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Pilih File</label>
                                <input type="file" name="dokumen[]" multiple accept=".pdf,.jpg,.jpeg,.png" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-slate-500 focus:outline-none" required>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">Upload Dokumen</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="p-4 border-t text-right">
                    <button data-modal-hide="dokumen-modal-{{ $kawasan->id }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded transition text-sm">Tutup</button>
                </div>
            </div>
        </div>

        {{-- MODAL EDIT --}}
        <div id="edit-modal-{{ $kawasan->id }}" class="hidden fixed inset-0 z-50 bg-black/50 flex items-start justify-center overflow-y-auto">
            @php
                $permasalahan = $kawasan->permasalahan ?? collect();
                $firstPer = $permasalahan->first();
                $latestProgress = optional($firstPer) ? $firstPer->progress->sortByDesc('tahun')->first() : null;
            @endphp
            <div class="relative mx-auto mt-10 mb-10 w-[95%] max-w-4xl">
                <div class="bg-white rounded-2xl shadow-xl p-6 max-h-[90vh] overflow-y-auto">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Edit Data Permasalahan Lahan</h3>
                    
                    <form action="{{ route('updatePl', $firstPer?->pl_id ?? 0) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="border border-gray-200 rounded-2xl p-6 mb-6 bg-white">
                            <h3 class="text-md font-semibold text-gray-700 mb-3">Data Wilayah</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-sm font-medium">Kecamatan</label><input type="text" name="nama_kecamatan" value="{{ $kawasan->desa->kecamatan->nama_kecamatan ?? '' }}" class="{{ $input }}"></div>
                                <div><label class="block text-sm font-medium">Desa</label><input type="text" name="nama_desa" value="{{ $kawasan->desa->nama_desa ?? '' }}" class="{{ $input }}"></div>
                                <div><label class="block text-sm font-medium">Nama Kawasan</label><input type="text" name="nama_kawasan" value="{{ $kawasan->nama_kawasan ?? '' }}" class="{{ $input }}"></div>
                                <div><label class="block text-sm font-medium">Nama Lokasi</label><input type="text" name="nama_lokasi" value="{{ $kawasan->nama_lokasi ?? '' }}" class="{{ $input }}"></div>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-2xl p-6 mb-6 bg-white">
                            <div class="flex items-center justify-between mb-5">
                                <h4 class="text-lg font-semibold text-gray-800">Data Permasalahan Lahan</h4>
                            </div>
                            <div class="grid md:grid-cols-4 gap-4 mb-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium mb-4">Status Lahan</label>
                                    <select name="status_lahan" class="{{ $input }}">
                                        <option value="HPL" {{ $firstPer?->status_lahan == 'HPL' ? 'selected' : '' }}>HPL</option>
                                        <option value="Pencadangan" {{ $firstPer?->status_lahan == 'Pencadangan' ? 'selected' : '' }}>Pencadangan</option>
                                    </select>
                                </div>
                                <div><label class="block text-sm font-medium mb-4">Pola</label><input type="text" name="pola" value="{{ $firstPer?->pola }}" class="{{ $input }}"></div>
                                <div><label class="block text-sm font-medium mb-4">Tahun Patan</label><input type="number" name="tahun_patan" value="{{ $firstPer?->tahun_patan }}" class="{{ $input }}"></div>
                                <div><label class="block text-sm font-medium mb-4">Jumlah KK</label><input type="number" name="jumlah_kk" value="{{ $firstPer?->jumlah_kk }}" class="{{ $input }}"></div>
                            </div>

                            <div class="border border-gray-200 rounded-xl overflow-hidden">
                                <div class="bg-gray-50 px-4 py-3 border-b flex justify-between">
                                    <span class="text-sm font-semibold text-gray-700">Rincian Jenis Permasalahan</span>
                                    <button 
                                        id="btn-tambah-edit-{{ $kawasan->id }}"
                                        type="button"
                                        onclick="tambahPermasalahanEdit({{ $kawasan->id }})"
                                        class="text-sm px-3 py-1.5 bg-blue-700 text-white rounded-lg
                                        {{ $permasalahan->count() >= $jenisPermasalahan->count() ? 'hidden' : '' }}">
                                        + Tambah
                                    </button>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <tbody id="body-permasalahan-edit-{{ $kawasan->id }}">
                                            @foreach($jenisPermasalahan as $i => $jenis)

                                            @php
                                                $data = $permasalahan->firstWhere('jenis_pl_id', $jenis->jenis_pl_id);
                                            @endphp

                                            <tr>
                                                <td class="p-3 w-[50%]">

                                                    <input type="hidden"
                                                        name="permasalahan[{{ $i }}][jenis_pl_id]"
                                                        value="{{ $jenis->jenis_pl_id }}">

                                                    <input type="text"
                                                        value="{{ $jenis->nama_permasalahan }}"
                                                        class="{{ $input }} bg-gray-100"
                                                        readonly>

                                                    @if($data)
                                                        <input type="hidden"
                                                            name="permasalahan[{{ $i }}][pl_id]"
                                                            value="{{ $data->pl_id }}">
                                                    @endif

                                                </td>

                                                <td class="p-3 w-[30%]">
                                                    <input
                                                        type="number"
                                                        name="permasalahan[{{ $i }}][jumlah_bidang]"
                                                        value="{{ $data->jumlah_bidang ?? 0 }}"
                                                        class="{{ $input }}"
                                                        min="0">
                                                </td>

                                            </tr>

                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-600 mb-1">Deskripsi Permasalahan</label>
                                <textarea name="deskripsi" rows="3" class="{{ $input }}">{{ old('deskripsi', $firstPer?->deskripsi) }}</textarea>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-xl p-5 mb-6 bg-white">
                            <h4 class="font-semibold text-gray-700 mb-4">Progress Penanganan</h4>
                            <div class="grid md:grid-cols-3 gap-4 mt-4">
                                <div><label class="block text-sm font-medium mb-4">Tahun Progress</label><input type="number" name="tahun" class="{{ $input }}" value="{{ $latestProgress?->tahun }}"></div>
                                <div><label class="block text-sm font-medium mb-4">Jumlah Kasus</label><input type="number" name="jumlah_kasus" min="0" class="{{ $input }}" value="{{ $latestProgress?->jumlah_kasus }}"></div>
                                <div>
                                    <label class="block text-sm font-medium mb-4">Status Penanganan</label>
                                    <select name="status_penanganan" class="{{ $input }}">
                                        <option value="">-- Pilih Status --</option>
                                        <option value="Aktif" {{ $latestProgress?->status_penanganan == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="Selesai" {{ $latestProgress?->status_penanganan == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid md:grid-cols-2 gap-4 mt-4">
                                <div><label class="block text-sm font-medium mb-4">Tindak Lanjut</label><textarea name="tindak_lanjut" rows="3" class="{{ $input }}">{{ $latestProgress?->tindak_lanjut }}</textarea></div>
                                <div><label class="block text-sm font-medium mb-4">Rekomendasi</label><textarea name="rekomendasi" rows="3" class="{{ $input }}">{{ $latestProgress?->rekomendasi }}</textarea></div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" onclick="closeModal('edit-modal-{{ $kawasan->id }}')" class="px-5 py-2 bg-gray-200 rounded-lg">Batal</button>
                            <button type="submit" class="px-5 py-2 bg-slate-800 text-white rounded-lg">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ================= MODAL DETAIL ================= --}}
        <div id="detail-modal-{{ $kawasan->id }}" class="fixed inset-0 z-50 hidden pt-6 bg-black/50 flex items-center justify-center overflow-y-auto">
            
            
            <div class="bg-white rounded-xl shadow-xl w-[90%] max-w-3xl max-h-[85vh] flex flex-col overflow-hidden">
                
                {{-- HEADER --}}
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 shrink-0 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-white">Detail Permasalahan Lahan</h3>
                        <p class="text-sm text-blue-100 mt-0.5">{{ $kawasan->nama_kawasan }}</p>
                    </div>
                    <button type="button" onclick="closeModal('detail-modal-{{ $kawasan->id }}')" class="text-white hover:text-gray-200 p-2 focus:outline-none transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- BODY --}}
                <div class="p-6 overflow-y-auto max-h-[70vh] space-y-6 text-sm text-gray-700 bg-slate-50">
                    
                    {{-- Info Wilayah --}}
                    <div class="bg-white border rounded-xl p-5 shadow-sm">
                        <h4 class="font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Wilayah</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                                <p class="text-xs text-gray-500 mb-1">Provinsi</p>
                                <p class="font-semibold text-gray-900">{{ $kawasan->desa?->kecamatan?->kabupaten?->provinsi?->nama_provinsi ?? '-' }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                                <p class="text-xs text-gray-500 mb-1">Kabupaten</p>
                                <p class="font-semibold text-gray-900">{{ $kawasan->desa?->kecamatan?->kabupaten?->nama_kabupaten ?? '-' }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                                <p class="text-xs text-gray-500 mb-1">Desa</p>
                                <p class="font-semibold text-gray-900">{{ $kawasan->desa?->nama_desa ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    @php
                        $permasalahan = $kawasan->permasalahan;
                        $firstPer = $permasalahan->first();
                        $latestProgress = optional($firstPer)->progress->sortByDesc('tahun')->first();
                    @endphp

                    @if($permasalahan->count())
                        {{-- Info Lahan --}}
                        <div class="bg-white border rounded-xl p-5 shadow-sm">
                            <h4 class="font-semibold text-gray-800 mb-4 border-b pb-2">Data Lahan & Pola</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                                    <p class="text-xs text-gray-500 mb-1">Status Lahan</p>
                                    <p class="font-bold text-blue-700 uppercase">{{ $firstPer->status_lahan ?? '-' }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                                    <p class="text-xs text-gray-500 mb-1">Pola</p>
                                    <p class="font-semibold text-gray-900">{{ $firstPer->pola ?? '-' }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                                    <p class="text-xs text-gray-500 mb-1">Tahun Patan</p>
                                    <p class="font-semibold text-gray-900">{{ $firstPer->tahun_patan ?? '-' }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                                    <p class="text-xs text-gray-500 mb-1">Jumlah KK</p>
                                    <p class="font-semibold text-gray-900">{{ $firstPer->jumlah_kk ?? 0 }} KK</p>
                                </div>
                            </div>
                        </div>

                        {{-- Jenis Permasalahan --}}
                        <div class="bg-white border rounded-xl p-5 shadow-sm">
                            <h4 class="font-semibold text-gray-800 mb-4 border-b pb-2">Rincian Permasalahan</h4>
                            <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5">
                                @foreach($permasalahan as $item)
                                    @php
                                        $itemProg = $item->progress->sortByDesc('tahun')->first();
                                        $status = $itemProg?->status_penanganan ?? 'Belum ada progress';
                                        $badgeColor = match(strtolower($status)) {
                                            'aktif' => 'bg-yellow-100 text-yellow-700 border border-yellow-300',
                                            'selesai' => 'bg-green-100 text-green-700 border border-green-300',
                                            default => 'bg-gray-100 text-gray-600 border border-gray-300'
                                        };
                                    @endphp
                                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 hover:shadow-md hover:border-blue-300 transition duration-300">
                                        <div class="flex justify-between items-start mb-3 gap-2">
                                            <h5 class="font-bold text-gray-800 leading-tight">{{ $item->jenis?->nama_permasalahan ?? '-' }}</h5>
                                            <span class="text-[10px] font-bold px-2.5 py-1 rounded-full whitespace-nowrap {{ $badgeColor }}">{{ $status }}</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-2">Jumlah Bidang: <span class="font-bold text-gray-900">{{ $item->jumlah_bidang ?? 0 }}</span></p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Keterangan / Deskripsi --}}
                        <div class="bg-white border rounded-xl p-5 shadow-sm">
                            <h4 class="font-semibold text-gray-800 mb-4 border-b pb-2">Keterangan & Tindak Lanjut</h4>
                            <div class="grid md:grid-cols-3 gap-4">
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                                    <p class="font-semibold text-sm mb-1.5 text-gray-800">Deskripsi</p>
                                    <p class="text-gray-600 leading-relaxed">{{ $firstPer->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                                    <p class="font-semibold text-sm mb-1.5 text-gray-800">Tindak Lanjut</p>
                                    <p class="text-gray-600 leading-relaxed">{{ $latestProgress?->tindak_lanjut ?? 'Belum ada tindak lanjut.' }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                                    <p class="font-semibold text-sm mb-1.5 text-gray-800">Rekomendasi</p>
                                    <p class="text-gray-600 leading-relaxed">{{ $latestProgress?->rekomendasi ?? 'Belum ada rekomendasi.' }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center text-gray-500 py-10 bg-white rounded-xl border border-dashed">
                            Tidak ada data permasalahan lahan
                        </div>
                    @endif

                </div>

                {{-- FOOTER BUTTON --}}
                <div class="p-4 border-t border-gray-200 bg-white flex justify-end">
                    <button type="button" onclick="closeModal('detail-modal-{{ $kawasan->id }}')" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg transition shadow-sm">
                        Tutup
                    </button>
                </div>

            </div>
            
        </div>
        
    @endforeach

    <script>
        // Dependensi Dropdown Provinsi -> Kabupaten
        document.getElementById('provinsi')?.addEventListener('change', function () {
            const provinsiId = this.value;
            const kabupatenSelect = document.getElementById('kabupaten');
            kabupatenSelect.innerHTML = '<option value="">Loading...</option>';

            if (provinsiId) {
                fetch(`/get-kabupaten/${provinsiId}`)
                    .then(response => response.json())
                    .then(data => {
                        kabupatenSelect.innerHTML = '<option value="">-- Pilih Kabupaten --</option>';
                        data.forEach(item => {
                            kabupatenSelect.innerHTML += `<option value="${item.id}">${item.nama_kabupaten}</option>`;
                        });
                    });
            } else {
                kabupatenSelect.innerHTML = '<option value="">-- Pilih Kabupaten --</option>';
            }
        });

        // ============================
        // FUNGSI UNTUK TAMBAH BARIS TABEL
        // ============================

        const maxTipologi = {{ $jenisPermasalahan->count() }};

        let indexPermasalahan = 1;

        function tambahPermasalahan() {

            let totalRow = document.querySelectorAll('#body-permasalahan tr').length;

            if (totalRow >= maxTipologi) {
                return;
            }

            let html = `
                <tr class="hover:bg-gray-50">
                    <td class="p-3">
                        <select name="permasalahan[${indexPermasalahan}][jenis_pl_id]" class="w-full border rounded-lg px-3 py-2" required>
                            <option value="">-- Pilih Jenis --</option>
                            @foreach($jenisPermasalahan as $j)
                                <option value="{{ $j->jenis_pl_id }}">{{ $j->nama_permasalahan }}</option>
                            @endforeach
                        </select>
                    </td>

                    <td class="p-3">
                        <input type="number" name="permasalahan[${indexPermasalahan}][jumlah_bidang]" class="w-full border rounded-lg px-3 py-2" min="0" required>
                    </td>

                    <td class="p-3 text-center">
                        <button type="button" onclick="hapusRow(this, {{ $kawasan->id }})" class="px-3 py-1.5 text-sm bg-red-100 hover:bg-red-200 text-red-700 rounded-lg">
                            Hapus
                        </button>
                    </td>
                </tr>
            `;

            document.getElementById('body-permasalahan').insertAdjacentHTML('beforeend', html);

            indexPermasalahan++;

            cekTombolTambah();
        }

        function cekTombolTambah() {

            let totalRow = document.querySelectorAll('#body-permasalahan tr').length;
            let tombol = document.getElementById('btn-tambah-permasalahan');

            if (totalRow >= maxTipologi) {
                tombol.style.display = 'none';
            } else {
                tombol.style.display = 'inline-block';
            }
        }

        function tambahPermasalahanEdit(kawasanId){

            let tbody = document.getElementById('body-permasalahan-edit-'+kawasanId);
            let totalRow = tbody.querySelectorAll('tr').length;

            if(totalRow >= maxTipologi){
                return;
            }

            let index = totalRow;

            let html = `
            <tr>
                <td class="p-3 w-[50%]">
                    <select name="permasalahan[${index}][jenis_pl_id]" class="w-full border rounded-lg px-3 py-2">
                        <option value="">-- Pilih Jenis --</option>

                        @foreach($jenisPermasalahan as $j)
                            <option value="{{ $j->jenis_pl_id }}">
                                {{ $j->nama_permasalahan }}
                            </option>
                        @endforeach

                    </select>
                </td>

                <td class="p-3 w-[30%]">
                    <input type="number"
                        name="permasalahan[${index}][jumlah_bidang]"
                        class="w-full border rounded-lg px-3 py-2">
                </td>

                <td class="p-3 text-center w-[20%]">
                    <button type="button"
                        onclick="hapusRow(this, ${kawasanId})"
                        class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg">
                        Hapus
                    </button>
                </td>
            </tr>
            `;

            tbody.insertAdjacentHTML('beforeend', html);

            cekTombolEdit(kawasanId);
        }

        function cekTombolEdit(kawasanId){

            let tbody = document.getElementById('body-permasalahan-edit-'+kawasanId);
            let tombol = document.getElementById('btn-tambah-edit-'+kawasanId);

            let totalRow = tbody.querySelectorAll('tr').length;

            if(totalRow >= maxTipologi){
                tombol.style.display = "none";
            }else{
                tombol.style.display = "inline-block";
            }

        }

        function hapusRow(button, kawasanId){
            button.closest('tr').remove();
            cekTombolEdit(kawasanId);
        }
        // ============================
        // FUNGSI UNTUK MODAL BUKA TUTUP
        // ============================
        function openModal(id){
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id){
            document.getElementById(id).classList.add('hidden');
        }
    </script>
    <script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js"></script>
</body>
</html>