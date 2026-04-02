<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>Sertifikat Hak Milik (SHM)</title>
    @include('layouts.header')
    @vite('resources/css/app.css') 
    
</head>
<body class="bg-slate-100 min-h-screen">
    <div class="p-4 pt-20 space-y-10">
        <div class="items-center justify-between lg:flex">
            <div class="p-2 w-full">

                <div class="flex flex-col md:flex-row items-start justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">

                    <h2 class="text-2xl font-bold text-gray-700 mt-2">
                        Sertifikat Hak Milik
                    </h2>

                    <div class="flex flex-col sm:flex-row gap-3">

                        <button
                            data-modal-target="tambah-modal"
                            data-modal-toggle="tambah-modal"
                            class="px-5 py-2.5 text-sm font-medium text-white
                                bg-blue-600 rounded-lg shadow-sm
                                hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300
                                transition">
                            Tambah SHM
                        </button>
                    </div>
                </div>
                <div class="mt-2 bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-gray-700">
                            <thead class="bg-gray-300 text-slate-700 uppercase text-xs tracking-wide">
                                <tr class="border-b">
                                    <th rowspan="2" class="px-4 py-3 text-center">No</th>
                                    <th rowspan="2" class="px-4 py-3 text-center">Provinsi</th>
                                    <th rowspan="2" class="px-4 py-3 text-center">Kabupaten</th>
                                    <th rowspan="2" class="px-4 py-3 text-center">Kawasan</th>
                                    <th rowspan="2" class="px-4 py-3 text-center">Lokasi</th>
                                    <th rowspan="2" class="px-4 py-3 text-center">Pola</th>
                                    
                                    <th rowspan="2" class="px-4 py-3 text-center">Jumlah KK</th>

                                    <th colspan="3" class="px-4 py-3 text-center">
                                        Status Sertifikat
                                    </th>

                                    <th rowspan="2" class="px-4 py-3 text-center">Dokumen</th>
                                    <th rowspan="2" class="px-4 py-3 text-center">Aksi</th>
                                </tr>

                                <tr class="border-b bg-gray-300">
                                    <th class="px-4 py-3 text-center text-yellow-700">Target</th>
                                    <th class="px-4 py-3 text-center text-green-800">Sudah</th>
                                    <th class="px-4 py-3 text-center text-red-800">Belum</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y">
                                @forelse ($shm as $index => $item)
                                <tr class="hover:bg-gray-50">

                                    <td class="px-4 py-2 text-center">{{ $index + 1 }}</td>

                                    <td class="px-4 py-2 text-center">
                                        {{ $item->kawasan?->desa?->kecamatan?->kabupaten?->provinsi?->nama_provinsi ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        {{ $item->kawasan?->desa?->kecamatan?->kabupaten?->nama_kabupaten ?? '-' }}
                                    </td>
                                    
                                    <td class="px-4 py-2 text-center">
                                        {{ $item->kawasan?->nama_kawasan ?? '-' }} 
                                    </td>

                                    <td class="px-4 py-2 text-center">
                                        {{ $item->kawasan?->nama_lokasi ?? '-' }} 
                                    </td>

                                    <td class="px-4 py-2 text-center">
                                        {{ $item->pola ?? '-' }} 
                                    </td>

                                    
                                    <td class="px-4 py-2 text-center">
                                        {{ $item->jumlah_kk ?? '-' }} 
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700 font-semibold">
                                            {{ $item->target_shm ?? 0 }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700 font-semibold">
                                            {{ $item->realisasi_shm ?? 0 }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700 font-semibold">
                                            {{ $item->sisa_shm ?? 0 }}
                                        </span>
                                    </td>


                                    <td class="px-4 py-2 text-center">
                                        @if($item->dokumen->count() > 0)
                                            <button onclick="openDokumenModal({{ $item->shm_id }})" class="text-blue-600 underline font-medium">
                                                {{ $item->dokumen->count() }} Dokumen
                                            </button>
                                        @else
                                            <button onclick="openTambahDokumen({{ $item->shm_id }})" 
                                                    class="text-sm px-3 py-1 rounded-full bg-blue-50 text-blue-700 hover:bg-blue-100">
                                                + Tambah
                                            </button>
                                        @endif
                                    </td>


                                    <td class="px-4 py-2 text-center">
                                        <div class="flex justify-center gap-3">

                                            {{-- DETAIL --}}
                                            <button data-modal-target="detail-modal-{{ $item->shm_id }}"
                                                data-modal-toggle="detail-modal-{{ $item->shm_id }}"
                                                class="text-blue-600 hover:text-blue-800"
                                                title="Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                                                        -1.274 4.057-5.064 7-9.542 7
                                                        -4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>

                                            {{-- EDIT --}}
                                            <button data-modal-target="edit-modal-{{ $item->shm_id }}"
                                                data-modal-toggle="edit-modal-{{ $item->shm_id }}"
                                            class="text-yellow-600 hover:text-yellow-800"
                                            title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                </svg>
                                            </button>

                                            {{-- DELETE --}}
                                            <button data-modal-target="delete-modal-{{ $item->shm_id }}"
                                                data-modal-toggle="delete-modal-{{ $item->shm_id }}"
                                                method="POST"
                                                    class="text-red-600 hover:text-red-800"
                                                    title="Hapus">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                                            a2 2 0 01-1.995-1.858L5 7m5-4h4
                                                            a1 1 0 011 1v3H9V4a1 1 0 011-1z"/>
                                                    </svg>
                                            </button>

                                        </div>
                                    </td>
                                </tr>
                                    <div id="dokumen-modal-{{ $item->shm_id }}"
                                        class="hidden fixed inset-0 z-70 bg-black/50 flex items-center justify-center">

                                        <div class="bg-white rounded-xl shadow-xl w-[90%] max-w-3xl max-h-[85vh] flex flex-col overflow-hidden">

                                            <div class="p-4 bg-slate-100 border-b flex justify-between items-center">
                                                <h3 class="text-lg font-semibold text-gray-800 truncate">
                                                    Dokumen SHM – {{ $item->kawasan->nama_kawasan }}
                                                </h3>

                                                <button onclick="openTambahDokumen({{ $item->shm_id }})"
                                                    class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                                    + Tambah Baru
                                                </button>
                                            </div>

                                            <div class="p-4 max-h-[60vh] overflow-y-auto">

                                                @if($item->dokumen->count() > 0)
                                                    <ul class="space-y-3">
                                                        @foreach ($item->dokumen as $dok)
                                                            <li class="border rounded-lg p-3 flex justify-between items-center hover:bg-gray-50">

                                                                <span class="text-sm font-medium text-gray-700 truncate max-w-[160px]"
                                                                    title="{{ $dok->nama_dokumen }}">
                                                                    {{ $dok->nama_dokumen }}
                                                                </span>

                                                                <div class="flex gap-2">

                                                                    <a href="{{ Storage::url($dok->path_file) }}" target="_blank"
                                                                        class="text-blue-600 hover:text-blue-800"
                                                                        title="DetailDokumen">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                                                                                -1.274 4.057-5.064 7-9.542 7
                                                                                -4.477 0-8.268-2.943-9.542-7z"/>
                                                                        </svg>             
                                                                    </a>

                                                                    <button onclick="openEditDokumen({{ $dok->id }}, '{{ $dok->nama_dokumen }}')"
                                                                        class="text-yellow-600 hover:text-yellow-800"
                                                                        title="EditDokumen">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/>
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                                        </svg>
                                                                    </button>

                                                                    <form action="{{ route('deleteDokumenShm', $dok->id) }}" method="POST"
                                                                        onsubmit="return confirm('Hapus dokumen ini?')">
                                                                        @csrf
                                                                        @method('DELETE')

                                                                        <button type="submit"
                                                                            class="text-red-600 hover:text-red-800"
                                                                            title="HapusDokumen">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                                                                    a2 2 0 01-1.995-1.858L5 7m5-4h4
                                                                                    a1 1 0 011 1v3H9V4a1 1 0 011-1z"/>
                                                                            </svg>
                                                                        </button>
                                                                    </form>

                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <div class="text-center py-10 text-gray-500">
                                                        Belum ada dokumen yang diunggah.
                                                    </div>
                                                @endif

                                            </div>

                                            <div class="p-4 border-t bg-gray-50 flex justify-end">
                                                <button onclick="closeDokumenModal({{ $item->shm_id }})"
                                                    class="px-6 py-2.5 text-sm font-semibold text-white bg-slate-800 rounded-lg hover:bg-slate-900 transition-all shadow-md hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-slate-200 active:scale-95">
                                                    Tutup
                                                </button>
                                            </div>

                                        </div>
                                    </div>

                                    <div id="tambah-dokumen-modal" class="hidden fixed inset-0 z-80 bg-black/50 flex items-center justify-center">
                                        <div class="bg-white rounded-xl shadow max-w-md w-full p-6">

                                            <h3 class="text-lg font-semibold mb-4">
                                                Tambah Dokumen SHM
                                            </h3>

                                            <form action="{{ route('storeDokumenShm') }}" method="POST" enctype="multipart/form-data">

                                                @csrf

                                                <input type="hidden" name="shm_id" id="shm_id_input">

                                                <div class="mb-4">
                                                    <label class="text-sm">Nama Dokumen</label>
                                                    <input type="text" name="nama_dokumen"
                                                        class="w-full border rounded-lg px-3 py-2 mt-1" required>
                                                </div>

                                                <div class="mb-6">
                                                    <label class="text-sm">File (PDF)</label>
                                                    <input type="file" name="dokumen[]" multiple
                                                        accept="application/pdf"
                                                        class="w-full border rounded-lg px-3 py-2 mt-1" required>
                                                </div>

                                                <div class="flex justify-end gap-3">

                                                    <button type="button" onclick="closeTambahDokumen()"
                                                        class="px-4 py-2 bg-gray-200 rounded-lg">
                                                        Batal
                                                    </button>

                                                    <button type="submit"
                                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                                                        Simpan
                                                    </button>

                                                </div>

                                            </form>

                                        </div>
                                    </div>

                                    <div id="editDokumenModal" class="fixed inset-0 hidden bg-black/80 flex items-center justify-center z-[80]">
                                        <div class="bg-white p-6 rounded-lg shadow-xl w-[400px]">
                                            <h3 class="font-bold mb-4">Edit Dokumen</h3>
                                            
                                            <form id="editDokumenForm" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')

                                                <div class="mb-3">
                                                    <label class="block text-sm mb-1">Nama Dokumen</label>
                                                    <input type="text" id="namaDokumen" name="nama_dokumen" required
                                                        class="border w-full p-2 rounded focus:ring-2 focus:ring-blue-500">
                                                </div>

                                                <div class="mb-4">
                                                    <label class="block text-sm mb-1">Ganti File PDF <span class="text-gray-400 text-xs">(Opsional)</span></label>
                                                    <input type="file" name="dokumen" accept="application/pdf" class="w-full text-sm">
                                                    <p class="text-[10px] text-gray-500 mt-1">Maksimal 5MB, format PDF</p>
                                                </div>

                                                <div class="flex justify-end gap-2">
                                                    <button type="button" onclick="closeEditDokumen()" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                                                        Batal
                                                    </button>
                                                    <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-slate-800 rounded-lg hover:bg-slate-900 transition-all shadow-md hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-slate-200 active:scale-95">
                                                        Simpan Perubahan
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div id="detail-modal-{{ $item->shm_id }}" tabindex="-1"
                                        class="fixed inset-0 z-50 hidden bg-black/50 flex items-start justify-center overflow-y-auto">

                                        <div class="bg-white rounded-xl shadow-xl mb-10 w-[90%] max-w-3xl max-h-[85vh] flex flex-col overflow-hidden">

                                            {{-- HEADER --}}
                                            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                                                <h3 class="text-lg font-semibold text-white">
                                                    Detail Sertifikat Hak Milik (SHM)
                                                </h3>
                                                <p class="text-sm text-white font-bold">
                                                    {{ $item->kawasan->nama_kawasan }}
                                                </p>
                                            </div>

                                            {{-- CONTENT --}}
                                            <div class="p-6 space-y-6 text-sm text-gray-700 overflow-y-auto flex-1">

                                                {{-- ================= WILAYAH ================= --}}
                                                <div>
                                                    <h4 class="font-semibold text-gray-800 mb-3 border-b pb-1">
                                                        Informasi Wilayah
                                                    </h4>

                                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                                        <div class="bg-gray-50 rounded-lg p-3">
                                                            <p class="text-xs text-gray-500">Provinsi</p>
                                                            <p class="font-medium">
                                                                {{ $item->kawasan?->desa?->kecamatan?->kabupaten?->provinsi?->nama_provinsi ?? '-' }}
                                                            </p>
                                                        </div>

                                                        <div class="bg-gray-50 rounded-lg p-3">
                                                            <p class="text-xs text-gray-500">Kabupaten</p>
                                                            <p class="font-medium">
                                                                {{ $item->kawasan?->desa?->kecamatan?->kabupaten?->nama_kabupaten ?? '-' }}
                                                            </p>
                                                        </div>

                                                        <div class="bg-gray-50 rounded-lg p-3">
                                                            <p class="text-xs text-gray-500">Kecamatan</p>
                                                            <p class="font-medium">
                                                                {{ $item->kawasan?->desa?->kecamatan?->nama_kecamatan ?? '-' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="grid grid-cols-2 md:grid-cols-2 gap-4 mt-4">
                                                        <div class="bg-gray-50 rounded-lg p-3">
                                                            <p class="text-xs text-gray-500">Desa</p>
                                                            <p class="font-medium">
                                                                {{ $item->kawasan?->desa?->nama_desa ?? '-' }}
                                                            </p>
                                                        </div>
                                                        <div class="bg-gray-50 rounded-lg p-3">
                                                            <p class="text-xs text-gray-500">Lokasi</p>
                                                            <p class="font-medium">
                                                                {{ $item->kawasan?->nama_lokasi ?? '-' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- ================= DATA SHM ================= --}}
                                                <div>
                                                    <h4 class="font-semibold text-gray-800 mb-3 border-b pb-1">
                                                        Data SHM
                                                    </h4>

                                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                                        <div class="bg-gray-50 rounded-lg p-3">
                                                            <p class="text-xs text-gray-500">Pola</p>
                                                            <p class="font-semibold">{{ $item->pola }}</p>
                                                        </div>

                                                        <div class="bg-gray-50 rounded-lg p-3">
                                                            <p class="text-xs text-gray-500">Tahun Patan</p>
                                                            <p class="font-semibold">{{ $item->tahun_patan }}</p>
                                                        </div>

                                                        <div class="bg-gray-50 rounded-lg p-3">
                                                            <p class="text-xs text-gray-500">Jumlah KK</p>
                                                            <p class="font-semibold">{{ $item->jumlah_kk }}</p>
                                                        </div>
                                                        <div class="bg-gray-50 rounded-lg p-3">
                                                            <p class="text-xs text-gray-500">Status HPL</p>
                                                            <p class="font-semibold">{{ $item->status_hpl }}</p>
                                                        </div>
                                                        <div class="bg-gray-50 rounded-lg p-3">
                                                            <p class="text-xs text-gray-500">Status UPT</p>
                                                            <p class="font-semibold">{{ $item->status_upt }}</p>
                                                        </div>
                                                        <div class="bg-gray-50 rounded-lg p-3">
                                                            <p class="text-xs text-gray-500">Luas</p>
                                                            <p class="font-semibold">{{ $item->luas }}</p>
                                                        </div>
                                                        <div class="bg-gray-50 rounded-lg p-3">
                                                            <p class="text-xs text-gray-500">Target SHM</p>
                                                            <p class="font-semibold">{{ $item->target_shm }}</p>
                                                        </div>
                                                        <div class="bg-gray-50 rounded-lg p-3">
                                                            <p class="text-xs text-gray-500">Sudah SHM</p>
                                                            <p class="font-semibold">{{ $item->realisasi_shm }}</p>
                                                        </div>
                                                        <div class="bg-gray-50 rounded-lg p-3">
                                                            <p class="text-xs text-gray-500">Belum SHM</p>
                                                            <p class="font-semibold">{{ $item->sisa_shm }}</p>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
                                                    <div class="bg-gray-50 rounded-lg p-3">
                                                        <p class="text-xs text-gray-500">Clear SHM</p>
                                                        <p class="font-semibold">{{ $item->clear_shm }}</p>
                                                    </div>
                                                    <div class="bg-gray-50 rounded-lg p-3">
                                                        <p class="text-xs text-gray-500">Bermasalah SHM</p>
                                                        <p class="font-semibold">{{ $item->bermasalah_shm }}</p>
                                                    </div>
                                                    <div class="bg-gray-50 rounded-lg p-3">
                                                        <p class="text-xs text-gray-500">Target Tahunan</p>
                                                        <p class="font-semibold">{{ $item->target_tahunan }}</p>
                                                    </div>
                                                    <div class="bg-gray-50 rounded-lg p-3">
                                                        <p class="text-xs text-gray-500">Bidang</p>
                                                        <p class="font-semibold">{{ $item->bidang }}</p>
                                                    </div>
                                                </div>
                                                
                                                <div class="bg-gray-50 rounded-lg p-3 mt-2">
                                                    <p class="text-xs text-gray-500">Deskripsi</p>
                                                    <p class="font-semibold">
                                                        {{ $item->deskripsi }}
                                                    </p>
                                                </div>

                                            </div>

                                            {{-- FOOTER --}}
                                            <div class="bg-white px-6 py-4 flex justify-end">
                                                <button
                                                    data-modal-hide="detail-modal-{{ $item->shm_id }}"
                                                    class="px-6 py-2.5 text-sm font-semibold text-white bg-slate-800 rounded-lg hover:bg-slate-900 transition-all shadow-md hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-slate-200 active:scale-95">
                                                    Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="edit-modal-{{ $item->shm_id }}" tabindex="-1"
                                        class="fixed inset-0 z-50 hidden bg-black/50 flex items-start justify-center overflow-y-auto">

                                        <div class="relative mx-auto mt-10 mb-10 w-[90%] max-w-3xl">
                                            <div class="bg-white rounded-xl shadow-xl p-6 max-h-[85vh] overflow-y-auto">

                                                <h3 class="text-lg font-semibold mb-4">
                                                    Edit SHM
                                                </h3>

                                                <form action="{{ route('updateShm', $item->shm_id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="border border-gray-200 rounded-lg p-4 mb-6">
                                                        <h3 class="text-md font-semibold text-gray-700 mb-3">
                                                            Data Wilayah
                                                        </h3>

                                                        <div class="grid grid-cols-2 gap-4">
                                                            <div>
                                                                <label class="block text-sm font-medium">Provinsi</label>

                                                                <select class="w-full border rounded-lg px-3 py-2 bg-gray-100" disabled>
                                                                    <option>
                                                                        {{ $item->kawasan?->desa?->kecamatan?->kabupaten?->provinsi?->nama_provinsi ?? '-' }}
                                                                    </option>
                                                                </select>
                                                                
                                                            </div>

                                                            <div>
                                                                <label class="block text-sm font-medium">Kabupaten</label>

                                                                <select class="w-full border rounded-lg px-3 py-2 bg-gray-100" disabled>
                                                                    <option>
                                                                        {{ $item->kawasan?->desa?->kecamatan?->kabupaten?->nama_kabupaten ?? '-' }}
                                                                    </option>
                                                                </select>
                                                            </div>

                                                            <div>
                                                                <label class="block text-sm font-medium">Kecamatan</label>
                                                                <input type="text" name="nama_kecamatan"
                                                                     value="{{ $item->kawasan?->desa?->kecamatan?->nama_kecamatan ?? '' }}"
                                                                    class="w-full border rounded-lg px-3 py-2" required>
                                                            </div>

                                                            <div>
                                                                <label class="block text-sm font-medium">Desa</label>
                                                                <input type="text" name="nama_desa"
                                                                   value="{{ $item->kawasan?->desa?->nama_desa ?? '' }}"
                                                                    class="w-full border rounded-lg px-3 py-2" required>
                                                            </div>

                                                            <div>
                                                                <label class="block text-sm font-medium">Nama Kawasan</label>
                                                                <input type="text" name="nama_kawasan"
                                                                    value="{{ $item->kawasan?->nama_kawasan ?? '' }}"
                                                                    class="w-full border rounded-lg px-3 py-2" required>
                                                            </div>

                                                            <div>
                                                                <label class="block text-sm font-medium">Nama Lokasi</label>
                                                                <input type="text" name="nama_lokasi"
                                                                    value="{{ $item->kawasan?->nama_lokasi ?? '' }}"
                                                                    class="w-full border rounded-lg px-3 py-2" required>
                                                            </div>

                                                        </div>


                                                        {{-- ================= SHM ================= --}}
                                                        <h3 class="text-md font-semibold mt-6 mb-3 border-b pb-1">
                                                            Data SHM
                                                        </h3>

                                                        <div class="grid grid-cols-3 gap-4">
                                                            <div>
                                                                <label class="block text-sm font-medium">Pola</label>
                                                                <input type="text" name="pola"  value="{{ $item->pola }}" class="w-full border rounded-lg" required>
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium">Target SHM</label>
                                                                <input type="number" id="target_shm_{{ $item->shm_id }}"
                                                                    name="target_shm"
                                                                    value="{{ $item->target_shm }}"
                                                                    class="w-full border rounded-lg px-3 py-2"
                                                                    oninput="hitungSisa({{ $item->shm_id }})"
                                                                    required>
                                                            </div>

                                                            <div>
                                                                <label class="block text-sm font-medium">Realisasi SHM</label>
                                                                <input type="number" id="realisasi_shm_{{ $item->shm_id }}"
                                                                    name="realisasi_shm"
                                                                    value="{{ $item->realisasi_shm }}"
                                                                    class="w-full border rounded-lg px-3 py-2"
                                                                    oninput="hitungSisa({{ $item->shm_id }})"
                                                                    required>
                                                            </div>

                                                            <div>
                                                                <label class="block text-sm font-medium">Sisa SHM</label>
                                                                <input type="number" id="sisa_shm_{{ $item->shm_id }}"
                                                                    name="sisa_shm"
                                                                    value="{{ $item->sisa_shm }}"
                                                                    class="w-full border rounded-lg px-3 py-2 bg-gray-100"
                                                                    readonly>
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium">Clear SHM</label>
                                                                <input type="number" name="clear_shm"  value="{{ $item->clear_shm }}" class="w-full border rounded-lg px-3 py-2" required>
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium">Bermasalah SHM</label>
                                                                <input type="number" name="bermasalah_shm"  value="{{ $item->bermasalah_shm }}" class="w-full border rounded-lg px-3 py-2" required>
                                                            </div>
                                                        </div>

                                                        <div class="space-y-4 mt-4">

                                                            {{-- HEADER --}}
                                                            <div class="flex justify-between items-center">
                                                                <label class="text-sm font-medium">
                                                                    Tahun Patan & Jumlah KK
                                                                </label>

                                                                <button type="button"
                                                                    onclick="tambahBarisTahun({{ $item->shm_id }})"
                                                                    class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs">
                                                                    + Tambah Tahun
                                                                </button>
                                                            </div>

                                                            {{-- WRAPPER --}}
                                                            <div id="tahun-wrapper-{{ $item->shm_id }}" class="space-y-3">

                                                                @php
                                                                    $listShm = $item->kawasan->shm;
                                                                @endphp

                                                                @foreach($listShm as $row)
                                                                    <div class="grid grid-cols-2 gap-3 tahun-row">

                                                                        {{-- IMPORTANT --}}
                                                                        <input type="hidden" name="shm_id[]" value="{{ $row->shm_id }}">

                                                                        <div>
                                                                            <label class="block text-sm mb-1">Tahun Patan</label>
                                                                            <input type="number"
                                                                                name="tahun_patan[]"
                                                                                value="{{ $row->tahun_patan }}"
                                                                                class="border rounded-lg px-3 py-2 w-full"
                                                                                required>
                                                                        </div>

                                                                        <div>
                                                                            <label class="block text-sm mb-1">Jumlah KK</label>
                                                                            <input type="number"
                                                                                name="jumlah_kk[]"
                                                                                value="{{ $row->jumlah_kk }}"
                                                                                class="border rounded-lg px-3 py-2 w-full"
                                                                                required>
                                                                        </div>

                                                                    </div>
                                                                @endforeach

                                                            </div>

                                                        </div>

                                                        <div class="grid grid-cols-3 gap-4 mt-4">
                                                            <div>
                                                                <label class="text-sm">Status HPL</label>
                                                                <select name="status_hpl" class="w-full border rounded-lg px-3 py-2" required>
                                                                    <option value="">Status HPL</option>
                                                                    <option value="Serah" {{ $item->status_hpl == 'Serah' ? 'selected' : '' }}>Serah</option>
                                                                    <option value="Belum" {{ $item->status_hpl == 'Belum' ? 'selected' : '' }}>Belum</option>
                                                                </select>
                                                            </div>
                                                            <div>
                                                                <label class="text-sm">Status UPT</label>
                                                                <select name="status_upt" class="w-full border rounded-lg px-3 py-2" required>
                                                                    <option value="">Status UPT</option>
                                                                    <option value="Serah" {{ $item->status_upt == 'Serah' ? 'selected' : '' }}>Serah</option>
                                                                    <option value="Bina" {{ $item->status_upt == 'Bina' ? 'selected' : '' }}>Bina</option>
                                                                </select>
                                                            </div>
                                                            <div>
                                                                <label class="text-sm">Luas</label>
                                                                <input type="number" name="luas" placeholder="Luas" value="{{ $item->luas }}"
                                                                    class="w-full border rounded-lg px-3 py-2" required>
                                                            </div>

                                                        </div>

                                                        <div class="grid grid-cols-2 gap-4 mt-4">
                                                            <div>
                                                                <label class="text-sm">Target Tahunan</label>
                                                                {{-- Target Tahunan --}}
                                                                <select name="target_tahunan"
                                                                    class="w-full border rounded-lg px-3 py-2">

                                                                    <option value="">-- Target Tahun --</option>

                                                                    @for ($year = 1999; $year <= date('Y'); $year++)
                                                                        <option value="{{ $year }}"
                                                                            {{ $item->target_tahunan == $year ? 'selected' : '' }}>
                                                                            {{ $year }}
                                                                        </option>
                                                                    @endfor

                                                                </select>
                                                            </div>
                                                            <div>
                                                                <label class="text-sm">Bidang</label>
                                                                {{-- Bidang --}}
                                                                <input type="number" name="bidang" placeholder="Bidang" value="{{ $item->bidang }}"
                                                                    class="w-full border rounded-lg px-3 py-2" required>
                                                            </div>

                                                        </div>

                                                        <div class="grid grid-cols-2 gap-4 mt-4">
                                                            <div>
                                                                <label class="text-sm font-medium">Tipologi</label>

                                                                <select name="nama_tipologi"
                                                                    class="w-full border rounded-lg px-3 py-2">

                                                                    <option value="">-- Pilih Tipologi --</option>

                                                                    @foreach($jenisPermasalahan as $jp)
                                                                        <option value="{{ $jp->nama_permasalahan }}"
                                                                            {{ $item->nama_tipologi == $jp->nama_permasalahan ? 'selected' : '' }}>
                                                                            {{ $jp->nama_permasalahan }}
                                                                        </option>
                                                                    @endforeach

                                                                </select>
                                                            </div>
                                                            <div>
                                                                <label class="text-sm">Bidang</label>
                                                                {{-- Bidang --}}
                                                                <input type="number" name="tipologi_bidang" placeholder="Tipologi Bidang" value="{{ $item->tipologi_bidang }}"
                                                                    class="w-full border rounded-lg px-3 py-2" required>
                                                            </div>

                                                        </div>

                                                        {{-- Deskripsi --}}
                                                        <div class="mt-4">
                                                            <label class="text-sm">Deskripsi</label>
                                                            <textarea name="deskripsi" rows="2"
                                                                placeholder="Deskripsi / keterangan"
                                                                class="w-full border rounded-lg px-3 py-2">{{ $item->deskripsi }}</textarea>
                                                        </div>
                                                        {{-- ================= BUTTON ================= --}}
                                                        <div class="flex justify-end space-x-3 mt-6">
                                                            <button type="button" data-modal-hide="edit-modal-{{ $item->shm_id }}"
                                                                class="px-4 py-2 bg-gray-300 rounded-lg">
                                                                Batal
                                                            </button>
                                                            <button type="submit"
                                                                class="px-4 py-2 bg-slate-800 text-white rounded-lg">
                                                                Simpan
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="delete-modal-{{ $item->shm_id }}" tabindex="-1"
                                        class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center">

                                        <div class="bg-white rounded-lg shadow max-w-md w-full p-6 text-center">

                                            <h3 class="text-lg font-semibold mb-4 text-red-600">
                                                Hapus Data SHM?
                                            </h3>

                                            <p class="text-sm text-gray-600 mb-6">
                                                Data SHM
                                                <span class="font-semibold text-gray-800">
                                                    {{ $item->kawasan?->nama_kawasan ?? 'Tanpa Kawasan' }}
                                                </span>
                                                akan dihapus secara permanen.
                                            </p>


                                            <div class="flex justify-center gap-4">
                                                <button data-modal-hide="delete-modal-{{ $item->shm_id }}"
                                                    class="px-4 py-2 bg-gray-300 rounded">
                                                    Batal
                                                </button>

                                                <form action="{{ route('deleteShm', $item->shm_id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                        class="px-4 py-2 bg-red-600 text-white rounded">
                                                        Hapus
                                                    </button>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                @empty
                                <tr>
                                    <td colspan="13" class="px-4 py-6 text-center text-gray-500">
                                        Data SHM belum tersedia
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
    <div id="tambah-modal" tabindex="-1"
        class="fixed inset-0 z-50 hidden bg-black/50 flex items-start justify-center overflow-y-auto">

        <div class="relative mx-auto mt-10 mb-10 w-[90%] max-w-3xl">
            <div class="bg-white rounded-xl shadow-xl p-6 max-h-[85vh] overflow-y-auto">

                <h3 class="text-lg font-bold mb-4">Tambah Data SHM</h3>

                @if ($errors->any())
                    <div class="p-3 mb-4 text-red-700 bg-red-100 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('storeShm') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="border border-gray-200 rounded-lg p-4 mb-6">

                        <h3 class="text-md font-semibold mb-3">Data Wilayah</h3>

                        <div class="grid grid-cols-2 gap-4">

                            <div>
                                <label class="text-sm">Provinsi</label>
                                <select name="provinsi_id" id="provinsi"
                                    class="w-full border rounded-lg px-3 py-2" required>
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach ($provinsi as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_provinsi }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-sm">Kabupaten</label>
                                <select name="kabupaten_id" id="kabupaten"
                                    class="w-full border rounded-lg px-3 py-2" required>
                                    <option value="">-- Pilih Kabupaten --</option>
                                </select>
                            </div>

                            <div>
                                <label class="text-sm">Kecamatan</label>
                                <input type="text" name="nama_kecamatan"
                                    class="w-full border rounded-lg px-3 py-2" required>
                            </div>

                            <div>
                                <label class="text-sm">Desa</label>
                                <input type="text" name="nama_desa"
                                    class="w-full border rounded-lg px-3 py-2" required>
                            </div>

                            <div>
                                <label class="text-sm">Nama Kawasan</label>
                                <input type="text" name="nama_kawasan" id="nama_kawasan"
                                    class="w-full border rounded-lg px-3 py-2" required>
                            </div>

                            <div>
                                <label class="text-sm">Nama Lokasi</label>
                                <input type="text" name="nama_lokasi"
                                    class="w-full border rounded-lg px-3 py-2" required>
                            </div>
                        </div>

                        {{-- SHM --}}
                        <h3 class="text-md font-semibold mt-6 mb-3 border-b pb-1">Data SHM</h3>

                        <div class="grid grid-cols-4 gap-4">
                            <div>
                                <label class="text-sm">Pola</label>
                                <input type="text" name="pola" placeholder="Pola"
                                    class="w-full border rounded-lg px-3 py-2" required>
                            </div>

                            <div>
                                <label class="text-sm">Tahun Patan</label>
                                <input type="number" name="tahun_patan" placeholder="Tahun Patan"
                                    class="w-full border rounded-lg px-3 py-2" required>
                            </div>
                            <div>
                                <label class="text-sm">Jumlah KK</label>
                                <input type="number" name="jumlah_kk" placeholder="Jumlah KK"
                                    class="w-full border rounded-lg px-3 py-2" required>
                            </div>
                            <div>
                                <label class="text-sm">Target SHM</label>
                                <input type="number" name="target_shm" placeholder="Target SHM" id="target_shm"
                                    class="w-full border rounded-lg px-3 py-2" required>
                            </div>
                            <div>
                                <label class="text-sm">Realisasi SHM</label>
                                <input type="number" name="realisasi_shm" placeholder="Realisasi SHM" id="realisasi_shm"
                                    class="w-full border rounded-lg px-3 py-2" required>
                            </div>
                            <div>
                                <label class="text-sm">Sisa SHM</label>
                                <input type="number" name="sisa_shm" placeholder="Sisa SHM" id="sisa_shm"
                                    class="w-full border rounded-lg px-3 py-2" readonly>
                            </div>
                            <div>
                                <label class="text-sm">Clear SHM</label>
                                <input type="number" name="clear_shm" placeholder="Clear SHM"
                                    class="w-full border rounded-lg px-3 py-2" required>
                            </div>
                            <div>
                                <label class="text-sm">Bermasalah SHM</label>
                                <input type="number" name="bermasalah_shm" placeholder="Bermasalah SHM"
                                    class="w-full border rounded-lg px-3 py-2" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 mt-4">
                            <div>
                                <label class="text-sm">Status HPL</label>
                                <select name="status_hpl"
                                    class="w-full border rounded-lg px-3 py-2" required>
                                    <option value="">Status HPL</option>
                                    <option value="Serah">Serah</option>
                                    <option value="Belum">Belum</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm">Status UPT</label>
                                <select name="status_upt"
                                    class="w-full border rounded-lg px-3 py-2" required>
                                    <option value="">Status UPT</option>
                                    <option value="Serah">Serah</option>
                                    <option value="Bina">Bina</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm">Luas</label>
                                <input type="number" name="luas" placeholder="Luas"
                                    class="w-full border rounded-lg px-3 py-2" required>
                            </div>

                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="text-sm">Target Tahunan</label>
                                {{-- Target Tahunan --}}
                                <select name="target_tahunan"
                                    class="w-full border rounded-lg px-3 py-2">
    
                                    <option value="">-- Target Tahun --</option>
    
                                    @for ($year = 2025; $year <= date('Y') + 5; $year++)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
    
                                </select>
                            </div>
                            <div>
                                <label class="text-sm">Bidang</label>
                                {{-- Bidang --}}
                                <input type="number" name="bidang" placeholder="Bidang"
                                    class="w-full border rounded-lg px-3 py-2" required>
                            </div>

                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="text-sm font-medium">Tipologi</label>

                                <select id="tipologi_select" name="tipologi"
                                    class="w-full border rounded-lg px-3 py-2">

                                    <option value="">-- Pilih Tipologi --</option>

                                    @foreach($jenisPermasalahan as $jp)
                                        <option value="{{ $jp->nama_permasalahan }}">
                                            {{ $jp->nama_permasalahan}}
                                        </option>
                                    @endforeach

                                    <option value="lainnya">Lainnya...</option>
                                </select>

                                {{-- input manual --}}
                                <input type="text"
                                    id="tipologi_manual"
                                    name="tipologi_manual"
                                    placeholder="Ketik tipologi lain..."
                                    class="w-full border rounded-lg px-3 py-2 mt-2 hidden">
                            </div>
                            <div>
                                <label class="text-sm">Bidang</label>
                                {{-- Bidang --}}
                                <input type="number" name="tipologi_bidang" placeholder="Tipologi Bidang"
                                    class="w-full border rounded-lg px-3 py-2" required>
                            </div>

                        </div>

                        {{-- Deskripsi --}}
                        <div class="mt-4">
                            <label class="text-sm">Deskripsi</label>
                            <textarea name="deskripsi" rows="2"
                                placeholder="Deskripsi / keterangan"
                                class="w-full border rounded-lg px-3 py-2"></textarea>
                        </div>

                        {{-- DOKUMEN --}}
                        <h3 class="text-md font-semibold mt-6 mb-3 border-b pb-1">Dokumen SHM</h3>

                        <input type="file" name="dokumen[]" multiple
                            class="w-full border rounded-lg px-3 py-2"
                            accept=".pdf,.jpg,.jpeg,.png">

                        {{-- BUTTON --}}
                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" data-modal-hide="tambah-modal"
                                class="px-4 py-2 bg-gray-300 rounded-lg">
                                Batal
                            </button>

                            <button type="submit"
                                class="px-4 py-2 bg-slate-800 text-white rounded-lg">
                                Simpan
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Dependensi Dropdown Provinsi -> Kabupaten
        document.getElementById('provinsi')?.addEventListener('change', function () {
            const provinsiId = this.value;
            const kabupatenSelect = document.getElementById('kabupaten');
            kabupatenSelect.innerHTML = '<option value="">Loading...</option>';

            if (provinsiId) {
                fetch(`{{ url('/get-kabupaten') }}/${provinsiId}`)
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

        // Fungsi Modal Daftar Dokumen
        function openDokumenModal(id) {
            const modal = document.getElementById('dokumen-modal-' + id);
            if (modal) modal.classList.remove('hidden');
        }

        function closeDokumenModal(id) {
            const modal = document.getElementById('dokumen-modal-' + id);
            if (modal) modal.classList.add('hidden');
        }

        // Fungsi Modal Tambah Dokumen
        function openTambahDokumen(shmId) {
            const input = document.getElementById('shm_id_input');
            const modal = document.getElementById('tambah-dokumen-modal');
            if (input) input.value = shmId;
            if (modal) modal.classList.remove('hidden');
        }

        function closeTambahDokumen() {
            document.getElementById('tambah-dokumen-modal').classList.add('hidden');
        }

        // Fungsi Modal Edit Dokumen
        function openEditDokumen(id, nama) {
            let url = "{{ route('updateDokumenShm', ':id') }}";
            url = url.replace(':id', id);

            const form = document.getElementById('editDokumenForm');
            if (form) form.action = url;

            const inputNama = document.getElementById('namaDokumen');
            if (inputNama) inputNama.value = nama;
            
            document.getElementById('editDokumenModal').classList.remove('hidden');
        }

        function closeEditDokumen() {
            document.getElementById('editDokumenModal').classList.add('hidden');
        }
        const kawasanInput = document.querySelector('input[name="nama_kawasan"]');

        kawasanInput.addEventListener('blur', function () {

            let value = this.value;

            if(!value) return;

            fetch("{{ route('cekKawasan') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    nama_kawasan: value
                })
            })
            .then(res => res.json())
            .then(data => {

                let warning = document.getElementById('kawasan-warning');

                if(data.exists){

                    if(!warning){
                        this.insertAdjacentHTML('afterend',
                            '<p id="kawasan-warning" class="text-red-500 text-sm mt-1">⚠ Nama kawasan sudah terdaftar</p>'
                        );
                    }

                } else {
                    warning?.remove();
                }

            });
        });
        document.getElementById('tipologi_select').addEventListener('change', function () {
            const manualInput = document.getElementById('tipologi_manual');

            if (this.value === 'lainnya') {
                manualInput.classList.remove('hidden');
                manualInput.required = true;
            } else {
                manualInput.classList.add('hidden');
                manualInput.required = false;
                manualInput.value = '';
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const targetInput = document.getElementById('target_shm');
            const realisasiInput = document.getElementById('realisasi_shm');
            const sisaInput = document.getElementById('sisa_shm');

            // guard biar tidak null
            if (!targetInput || !realisasiInput || !sisaInput) return;

            function hitungSisa() {
                const target = parseInt(targetInput.value) || 0;
                const realisasi = parseInt(realisasiInput.value) || 0;

                let sisa = target - realisasi;
                if (sisa < 0) sisa = 0;

                sisaInput.value = sisa;
            }

            targetInput.addEventListener('input', hitungSisa);
            realisasiInput.addEventListener('input', hitungSisa);
        });

        function tambahBarisTahun(id) {
            let wrapper = document.getElementById('tahun-wrapper-' + id);

            let html = `
                <div class="grid grid-cols-2 gap-3 tahun-row">

                    <input type="hidden" name="shm_id[]" value="">

                    <div>
                        <label class="block text-sm mb-1">Tahun Patan</label>
                        <input type="number"
                            name="tahun_patan[]"
                            class="border rounded-lg px-3 py-2 w-full"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Jumlah KK</label>
                        <input type="number"
                            name="jumlah_kk[]"
                            class="border rounded-lg px-3 py-2 w-full"
                            required>
                    </div>

                </div>
            `;

            wrapper.insertAdjacentHTML('beforeend', html);
        }

        function hitungSisa(id) {
            const target = parseInt(document.getElementById('target_shm_' + id)?.value) || 0;
            const realisasi = parseInt(document.getElementById('realisasi_shm_' + id)?.value) || 0;

            const sisa = Math.max(target - realisasi, 0);

            document.getElementById('sisa_shm_' + id).value = sisa;
        }

        function toggleTipologiManual(id) {
            const select = document.getElementById('tipologi_select_' + id);
            const input  = document.getElementById('tipologi_manual_' + id);

            if (select.value === 'lainnya') {
                input.classList.remove('hidden');
                input.required = true;
            } else {
                input.classList.add('hidden');
                input.required = false;
                input.value = '';
            }
        }
    </script>
    <script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js"></script>

</body>

</html>