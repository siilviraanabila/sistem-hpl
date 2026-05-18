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
<body class="bg-slate-50 min-h-screen">
    <div class="p-4 pt-20 space-y-10">
        <div class="items-center justify-between lg:flex">
            <div class="p-2 w-full">

                <div class="flex flex-col md:flex-row items-start justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">

                    <h2 class="text-2xl font-bold text-slate-800 tracking-tight">
                        Manajemen <span class="text-blue-600">Sertifikat Hak Milik</span>
                    </h2>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button
                            data-modal-target="tambah-modal"
                            data-modal-toggle="tambah-modal"
                            class="flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 hover:shadow-none transition-all duration-300">
                            
                            Tambah SHM
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
                                @forelse ($groupedShm as $index => $group)
                                @php
                                    $item = $group->first();

                                    $totalKK = $group->sum('jumlah_kk');
            
                                @endphp
                                <tr class="hover:bg-gray-50">

                                    <td class="px-4 py-2 text-center">{{ $shm->firstItem() + $loop->index }}</td>

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
                                        {{ $totalKK }}
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

                                        @php
                                            $totalDok = $item->dokumen->count();
                                        @endphp

                                        @if($totalDok > 0)

                                            <button
                                                onclick="openDokumenModal({{ $item->shm_id }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg transition-all font-bold text-xs shadow-md
                                                {{ $totalDok > 0
                                                    ? 'bg-blue-600 text-white hover:bg-blue-700 shadow-blue-100'
                                                    : 'bg-slate-100 text-slate-400 hover:bg-slate-200 shadow-none border border-slate-200'
                                                }}">

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor">

                                                    <path stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                </svg>

                                                {{ $totalDok }} File
                                            </button>

                                        @else

                                            <button
                                                onclick="openTambahDokumen({{ $item->shm_id }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 text-slate-400 hover:bg-slate-200 shadow-none border border-slate-200 transition-all font-bold text-xs">

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor">

                                                    <path stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 4v16m8-8H4" />
                                                </svg>

                                                Tambah
                                            </button>

                                        @endif

                                    </td>


                                    <td class="px-4 py-2 text-center">
                                        <div class="flex justify-center gap-3">

                                            {{-- DETAIL --}}
                                            <button data-modal-target="detail-modal-{{ $item->shm_id }}"
                                                data-modal-toggle="detail-modal-{{ $item->shm_id }}"
                                                class="p-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-600 hover:text-white transition-all"
                                                title="Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </button>

                                            {{-- EDIT --}}
                                            <button data-modal-target="edit-modal-{{ $item->shm_id }}"
                                                data-modal-toggle="edit-modal-{{ $item->shm_id }}"
                                            class="p-2 text-amber-600 bg-amber-50 rounded-lg hover:bg-amber-600 hover:text-white transition-all"
                                            title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            </button>

                                            {{-- DELETE --}}
                                            <button data-modal-target="delete-modal-{{ $item->shm_id }}"
                                                data-modal-toggle="delete-modal-{{ $item->shm_id }}"
                                                method="POST"
                                                   class="p-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-600 hover:text-white transition-all" 
                                                    title="Hapus">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v3H9V4a1 1 0 011-1z"/></svg>
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

                                    <div id="detail-modal-{{ $item->shm_id }}" class="fixed inset-0 z-50 hidden pt-6 bg-black/50 flex items-center justify-center overflow-y-auto">
    
                                        <div class="bg-white rounded-xl shadow-xl w-[90%] max-w-3xl max-h-[85vh] flex flex-col overflow-hidden">

                                            {{-- HEADER --}}
                                            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 shrink-0 flex justify-between items-center">
                                                <div>
                                                    <h3 class="text-lg font-bold text-black">Detail Sertifikat Hak Milik (SHM)</h3>
                                                    <p class="text-sm text-blue-700 mt-0.5">{{ $item->kawasan->nama_kawasan }}</p>
                                                </div>
                                                <button onclick="closeModal('detail-modal-{{ $item->shm_id }}')" class="text-white hover:text-gray-200 p-2">
                                                    ✕
                                                </button>
                                            </div>

                                            {{-- BODY --}}
                                            <div class="p-6 overflow-y-auto max-h-[70vh] space-y-6 text-sm text-gray-700 bg-slate-50">

                                                {{-- ================= WILAYAH ================= --}}
                                                <div class="bg-white border rounded-xl p-5 shadow-sm">
                                                    <h4 class="font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Wilayah</h4>

                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                        <div class="bg-gray-50 rounded-lg p-4 border">
                                                            <p class="text-xs text-gray-500 mb-1">Provinsi</p>
                                                            <p class="font-semibold">
                                                                {{ $item->kawasan?->desa?->kecamatan?->kabupaten?->provinsi?->nama_provinsi ?? '-' }}
                                                            </p>
                                                        </div>

                                                        <div class="bg-gray-50 rounded-lg p-4 border">
                                                            <p class="text-xs text-gray-500 mb-1">Kabupaten</p>
                                                            <p class="font-semibold">
                                                                {{ $item->kawasan?->desa?->kecamatan?->kabupaten?->nama_kabupaten ?? '-' }}
                                                            </p>
                                                        </div>

                                                        <div class="bg-gray-50 rounded-lg p-4 border">
                                                            <p class="text-xs text-gray-500 mb-1">Kecamatan</p>
                                                            <p class="font-semibold">
                                                                {{ $item->kawasan?->desa?->kecamatan?->nama_kecamatan ?? '-' }}
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="grid grid-cols-2 gap-4 mt-4">
                                                        <div class="bg-gray-50 rounded-lg p-4 border">
                                                            <p class="text-xs text-gray-500 mb-1">Desa</p>
                                                            <p class="font-semibold">
                                                                {{ $item->kawasan?->desa?->nama_desa ?? '-' }}
                                                            </p>
                                                        </div>

                                                        <div class="bg-gray-50 rounded-lg p-4 border">
                                                            <p class="text-xs text-gray-500 mb-1">Lokasi</p>
                                                            <p class="font-semibold">
                                                                {{ $item->kawasan?->nama_lokasi ?? '-' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- ================= DATA SHM ================= --}}
                                                <div class="bg-white border rounded-xl p-5 shadow-sm">
                                                    <h4 class="font-semibold text-gray-800 mb-4 border-b pb-2">Data SHM</h4>

                                                    <div class="grid md:grid-cols-4 gap-4">
                                                        <div class="bg-gray-50 rounded-lg p-4 border">
                                                            <p class="text-xs text-gray-500">Pola</p>
                                                            <p class="font-bold text-gray-900">{{ $item->pola }}</p>
                                                        </div>

                            
                                                        <div class="bg-gray-50 rounded-lg p-4 border">
                                                            <p class="text-xs text-gray-500">Status HPL</p>
                                                            <p class="font-bold text-blue-700 uppercase">{{ $item->status_hpl }}</p>
                                                        </div>

                                                        <div class="bg-gray-50 rounded-lg p-4 border">
                                                            <p class="text-xs text-gray-500">Status UPT</p>
                                                            <p class="font-bold">{{ $item->status_upt }}</p>
                                                        </div>

                                                        <div class="bg-gray-50 rounded-lg p-4 border">
                                                            <p class="text-xs text-gray-500">Luas</p>
                                                            <p class="font-bold">{{ $item->luas }}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="bg-gray-50 rounded-lg p-4 border">
                                                    <p class="text-xs text-gray-500 mb-3">Tahun Patan & Jumlah KK</p>

                                                    <div class="grid grid-cols-2 gap-2">
                                                        @foreach($group as $row)
                                                            <div class="bg-white border rounded-lg p-2 text-center shadow-sm">
                                                                <p class="text-xs text-gray-500">{{ $row->tahun_patan }}</p>
                                                                <p class="font-bold text-blue-600">{{ $row->jumlah_kk }} KK</p>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                {{-- ================= PROGRESS SHM ================= --}}
                                                <div class="bg-white border rounded-xl p-5 shadow-sm">
                                                    <h4 class="font-semibold text-gray-800 mb-4 border-b pb-2">Progress SHM</h4>

                                                    <div class="grid md:grid-cols-3 gap-4">
                                                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                                            <p class="text-xs text-green-600">Sudah SHM</p>
                                                            <p class="font-bold text-green-700 text-lg">{{ $item->realisasi_shm }}</p>
                                                        </div>

                                                        <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                                                            <p class="text-xs text-red-600">Belum SHM</p>
                                                            <p class="font-bold text-red-700 text-lg">{{ $item->sisa_shm }}</p>
                                                        </div>

                                                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                                            <p class="text-xs text-blue-600">Target SHM</p>
                                                            <p class="font-bold text-blue-700 text-lg">{{ $item->target_shm }}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- ================= RINCIAN TAMBAHAN ================= --}}
                                                <div class="bg-white border rounded-xl p-5 shadow-sm">
                                                    <h4 class="font-semibold text-gray-800 mb-4 border-b pb-2">Rincian Tambahan</h4>

                                                    <div class="grid md:grid-cols-2 gap-4">
                                                        <div class="bg-gray-50 rounded-lg p-4 border">
                                                            <p class="text-xs text-gray-500">Clear SHM</p>
                                                            <p class="font-bold">{{ $item->clear_shm }}</p>
                                                        </div>

                                                        <div class="bg-gray-50 rounded-lg p-4 border">
                                                            <p class="text-xs text-gray-500">Bermasalah SHM</p>
                                                            <p class="font-bold">{{ $item->bermasalah_shm }}</p>
                                                        </div>

                                                        <div class="bg-gray-50 rounded-lg p-4 border">
                                                            <p class="text-xs text-gray-500">Target Tahunan</p>
                                                            <p class="font-bold">{{ $item->target_tahunan }}</p>
                                                        </div>

                                                        <div class="bg-gray-50 rounded-lg p-4 border">
                                                            <p class="text-xs text-gray-500">Bidang</p>
                                                            <p class="font-bold">{{ $item->bidang }}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- ================= DESKRIPSI ================= --}}
                                                <div class="bg-white border rounded-xl p-5 shadow-sm">
                                                    <h4 class="font-semibold text-gray-800 mb-4 border-b pb-2">Deskripsi</h4>

                                                    <div class="bg-gray-50 rounded-lg p-4 border">
                                                        <p class="text-gray-700 leading-relaxed">
                                                            {{ $item->deskripsi ?? 'Tidak ada deskripsi.' }}
                                                        </p>
                                                    </div>
                                                </div>

                                            </div>

                                            {{-- FOOTER --}}
                                            <div class="bg-white px-6 py-4 flex justify-end"> 
                                                <button data-modal-hide="detail-modal-{{ $item->shm_id }}" class="px-6 py-2.5 text-sm font-semibold text-white bg-slate-800 rounded-lg hover:bg-slate-900 transition-all shadow-md hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-slate-200 active:scale-95"> Tutup </button>
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
                                                    {{ $item->kawasan?->nama_kawasan }}
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
                        <div class="mt-4">
                            {{ $shm->links() }}
                        </div>
                        
                        <div id="edit-modal-{{ $item->shm_id }}"
                            class="fixed inset-0 z-50 hidden bg-black/50 flex items-start justify-center overflow-y-auto p-4">

                            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-4xl max-h-[85vh] flex flex-col">

                                <form action="{{ route('updateShm', $item->shm_id) }}" method="POST" class="flex flex-col max-h-[85vh]">
                                    @csrf
                                    @method('PUT')

                                    <!-- HEADER -->
                                    <div class="bg-blue-600 px-8 py-6 text-white rounded-t-3xl">
                                        <h3 class="text-xl font-black uppercase tracking-tight">
                                            Edit Data SHM
                                        </h3>
                                    </div>

                                    <!-- CONTENT -->
                                    <div class="flex-1 overflow-y-auto px-6 py-6 space-y-6">

                                        <!-- WILAYAH -->
                                        <div class="border border-gray-200 rounded-2xl p-6 bg-white">
                                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">
                                                Data Wilayah
                                            </h4>

                                            <div class="grid md:grid-cols-2 gap-5">
                                                <div>
                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Kecamatan</label>
                                                    <input type="text" name="nama_kecamatan"
                                                        value="{{ $item->kawasan?->desa?->kecamatan?->nama_kecamatan }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                </div>

                                                <div>
                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Kawasan</label>
                                                    <input type="text" name="nama_kawasan" value="{{ $item->kawasan?->nama_kawasan }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                </div>

                                                <div>
                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Desa</label>
                                                    <input type="text" name="nama_desa"
                                                        value="{{ $item->kawasan?->desa?->nama_desa }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                </div>

                                                <div>
                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Lokasi</label>
                                                    <input type="text" name="nama_lokasi" value="{{ $item->kawasan?->nama_lokasi }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- SHM -->
                                        <div class="border border-gray-200 rounded-2xl p-6 bg-white">
                                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">
                                                Data Sertifikat Hak Milik
                                            </h4>

                                            <div class="grid md:grid-cols-3 gap-5">
                                                <div>
                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Pola</label>
                                                    <input type="text" name="pola" value="{{ $item->pola }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                </div>

                                                <div>
                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Target SHM</label>
                                                    <input type="number" id="target_{{ $item->shm_id }}"
                                                        name="target_shm" value="{{ $item->target_shm }}"
                                                        oninput="hitungSisa({{ $item->shm_id }})" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                </div>

                                                <div>
                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Realisasi SHM</label>
                                                    <input type="number" id="realisasi_{{ $item->shm_id }}"
                                                        name="realisasi_shm" value="{{ $item->realisasi_shm }}"
                                                        oninput="hitungSisa({{ $item->shm_id }})" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                </div>

                                                <div>
                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Sisa SHM</label>
                                                    <input type="number" id="sisa_{{ $item->shm_id }}"
                                                        name="sisa_shm" value="{{ $item->sisa_shm }}"
                                                        class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" readonly>
                                                </div>

                                                <div>
                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Clear SHM</label>
                                                    <input type="number" name="clear_shm" value="{{ $item->clear_shm }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                </div>

                                                <div>
                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Bermasalah SHM</label>
                                                    <input type="number" name="bermasalah_shm" value="{{ $item->bermasalah_shm }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- TAHUN PATAN -->
                                        <div class="border border-gray-200 rounded-2xl p-6 bg-white">
                                            @php 
                                                $shmList = $item->kawasan?->shm ?? collect();
                                                $startIndex = $shmList->count();
                                            @endphp
                                            <div class="flex justify-between items-center mb-4">
                                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                                    Tahun Patan & Jumlah KK 
                                                </h4>

                                                <button type="button"
                                                    onclick="tambahBarisTahun({{ $item->shm_id }})"
                                                    class="px-3 py-1.5 bg-green-600 text-white rounded-lg text-xs hover:bg-green-700">
                                                    + Tambah
                                                </button>
                                            </div>

                                            <div id="tahun-wrapper-{{ $item->shm_id }}" class="space-y-3">
                                                @foreach($shmList as $i => $row)
                                                <div class="grid grid-cols-2 gap-4">
                                                    <input type="hidden" name="rows[{{ $i }}][shm_id]" value="{{ $row->shm_id }}">

                                                    <input type="number" name="rows[{{ $i }}][tahun_patan]" value="{{ $row->tahun_patan }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                                                    <input type="number" name="rows[{{ $i }}][jumlah_kk]" value="{{ $row->jumlah_kk }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="grid md:grid-cols-2 gap-5">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Target Tahunan</label>
                                                <input type="number" name="target_tahunan"
                                                    value="{{ $item->target_tahunan }}"
                                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                            </div>

                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Bidang</label>
                                                <input type="number" name="bidang"
                                                    value="{{ $item->bidang }}"
                                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                            </div>
                                        </div>
                                        <!-- STATUS -->
                                        <div class="grid md:grid-cols-3 gap-5">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Status HPL</label>
                                                <select name="status_hpl" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                    <option value="Serah" {{ $item->status_hpl == 'Serah' ? 'selected' : '' }}>Serah</option>
                                                    <option value="Belum" {{ $item->status_hpl == 'Belum' ? 'selected' : '' }}>Belum</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Status UPT</label>
                                                <select name="status_upt" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                    <option value="Serah" {{ $item->status_upt == 'Serah' ? 'selected' : '' }}>Serah</option>
                                                    <option value="Bina" {{ $item->status_upt == 'Bina' ? 'selected' : '' }}>Bina</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Luas</label>
                                                <input type="number" name="luas" value="{{ $item->luas }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Deskripsi</label>
                                            <textarea name="deskripsi" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ $item->deskripsi }}</textarea>
                                        </div>
                                    </div>

                                    <!-- FOOTER -->
                                    <div class="p-4 border-t flex justify-end gap-3 bg-gray-50 rounded-b-3xl">
                                        <button type="button"
                                            data-modal-hide="edit-modal-{{ $item->shm_id }}"
                                            class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
                                            Batal
                                        </button>

                                        <button type="submit"
                                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                            Simpan
                                        </button>
                                    </div>

                                </form>
                            </div>
                        </div>
                       
                    </div>
                    <div class="px-6 py-4 bg-white border-t">
                        {{ $shm->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="tambah-modal" tabindex="-1"
        class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">

        <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col overflow-hidden border border-slate-200">

            <!-- HEADER -->
            <div class="bg-blue-600 px-8 py-6 text-white flex justify-between items-center">
                <h3 class="text-xl font-black uppercase tracking-tight">
                    Input Data Sertifikat Hak Milik
                </h3>
            </div>

            <form action="{{ route('storeShm') }}" method="POST" enctype="multipart/form-data" class="flex flex-col overflow-hidden">
            @csrf

            <div class="p-8 overflow-y-auto space-y-8 bg-white">

                <!-- ================= WILAYAH ================= -->
                <div>
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">
                        Informasi Wilayah
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Provinsi</label>
                            <select name="provinsi_id" id="provinsi" class="w-full border-slate-200 rounded-xl py-3 px-4 focus:ring-blue-500 shadow-sm font-semibold" required>
                                <option value="">-- Provinsi --</option>
                                @foreach ($provinsi as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_provinsi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Kabupaten</label>
                            <select name="kabupaten_id" id="kabupaten" class="w-full border-slate-200 rounded-xl py-3 px-4 focus:ring-blue-500 shadow-sm font-semibold" required>
                                <option value="">-- Kabupaten --</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Kecamatan</label>
                            <input type="text" name="nama_kecamatan" placeholder="Nama Kecamatan" class="w-full border-slate-200 rounded-xl py-3 px-4 focus:ring-blue-500 shadow-sm font-semibold" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Desa</label>
                            <input type="text" name="nama_desa" placeholder="Nama Desa" class="w-full border-slate-200 rounded-xl py-3 px-4 focus:ring-blue-500 shadow-sm font-semibold" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Kawasan</label>
                            <input type="text" name="nama_kawasan" placeholder="Nama Kawasan" class="w-full border-slate-200 rounded-xl py-3 px-4 focus:ring-blue-500 shadow-sm font-semibold" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Lokasi</label>
                            <input type="text" name="nama_lokasi" placeholder="Nama Lokasi" class="w-full border-slate-200 rounded-xl py-3 px-4 focus:ring-blue-500 shadow-sm font-semibold" required>
                        </div>
                    </div>
                </div>

                <!-- ================= DATA SHM ================= -->
                <div class="pt-6 border-t border-slate-100">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2"><span class="w-8 h-px bg-slate-200"></span> Detail Berkas SHM</h4>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Pola</label>
                            <input type="text" name="pola" placeholder="Pola" class="w-full border-slate-200 rounded-xl py-3 px-4 font-semibold shadow-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Tahun Patan</label>
                            <input type="number" name="tahun_patan" placeholder="Tahun Patan" class="w-full border-slate-200 rounded-xl py-3 px-4 font-semibold shadow-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Jumlah KK</label>
                            <input type="number" name="jumlah_kk" placeholder="Jumlah KK" class="w-full border-slate-200 rounded-xl py-3 px-4 font-semibold shadow-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Target SHM</label>
                            <input type="number" name="target_shm" id="target_shm" placeholder="Target SHM" class="w-full border-slate-200 rounded-xl py-3 px-4 font-semibold shadow-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mt-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Realisasi SHM</label>
                            <input type="number" name="realisasi_shm" id="realisasi_shm" placeholder="Realisasi SHM" class="w-full border-slate-200 rounded-xl py-3 px-4 font-semibold shadow-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Sisa SHM</label>
                            <input type="number" name="sisa_shm" id="sisa_shm" placeholder="Sisa SHM" class="bg-gray-100 w-full border-slate-200 rounded-xl py-3 px-4 font-semibold shadow-sm"disabled>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Clear SHM</label>
                            <input type="number" name="clear_shm" placeholder="Clear SHM" class="w-full border-slate-200 rounded-xl py-3 px-4 font-semibold shadow-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Bermasalah SHM</label>
                            <input type="number" name="bermasalah_shm" placeholder="Bermasalah SHM" class="w-full border-slate-200 rounded-xl py-3 px-4 font-semibold shadow-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Status HPL</label>
                            <select name="status_hpl" class="w-full border-slate-200 rounded-xl py-3 px-4 font-black text-blue-700" >
                                <option value="">Status HPL</option>
                                <option value="Serah">Serah</option>
                                <option value="Belum">Belum</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Status UPT</label>
                            <select name="status_upt" class="w-full border-slate-200 rounded-xl py-3 px-4 font-black text-blue-700" >
                                <option value="">Status UPT</option>
                                <option value="Serah">Serah</option>
                                <option value="Bina">Bina</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Luas</label>
                            <input type="number" name="luas" placeholder="Luas" class="w-full border-slate-200 rounded-xl py-3 px-4 font-semibold shadow-sm">
                        </div>
                    </div>
                </div>

                <!-- ================= TARGET & TIPOLOGI ================= -->
                <div class="pt-6 border-t">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2"><span class="w-8 h-px bg-slate-200"></span>Target & Tipologi</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Target Tahunan</label>
                            <select name="target_tahunan" class="w-full border-slate-200 rounded-xl py-3 px-4 font-semibold shadow-sm">
                                <option value="">Target Tahun</option>
                                @for ($year = 2025; $year <= date('Y') + 5; $year++)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Bidang</label>
                            <input type="number" name="bidang" placeholder="Bidang" class="w-full border-slate-200 rounded-xl py-3 px-4 font-semibold shadow-sm">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Tipologi</label>
                            <select id="tipologi_select" name="tipologi" class="w-full border-slate-200 rounded-xl py-3 px-4 font-semibold shadow-sm">
                                <option value="">-- Tipologi --</option>
                                @foreach($jenisPermasalahan as $jp)
                                    <option value="{{ $jp->nama_permasalahan }}">{{ $jp->nama_permasalahan }}</option>
                                @endforeach
                                <option value="lainnya">Lainnya...</option>
                            </select>
                            <input type="text" id="tipologi_manual" name="tipologi_manual"
                                placeholder="Tipologi lainnya..." class="input hidden">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Bidang</label>
                            <input type="number" name="tipologi_bidang" placeholder="Bidang Tipologi" class="w-full border-slate-200 rounded-xl py-3 px-4 font-semibold shadow-sm">
                        </div>
                    </div>
                    
                    <div class="pt-6 border-t">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Deskripsi</label>
                        <textarea name="deskripsi" rows="2"
                            placeholder="Deskripsi"
                            class="w-full border-slate-200 rounded-xl py-3 px-4 font-semibold shadow-sm"></textarea>
                    </div>
                </div>

                <!-- ================= DOKUMEN ================= -->
                <div class="pt-6 border-t">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Dokumen</label>
                    <input type="file" name="dokumen[]" multiple
                        class="w-full text-xs file:bg-blue-600 file:text-white file:px-4 file:py-2 file:rounded-lg">
                </div>

            </div>

            <!-- FOOTER -->
            <div class="p-6 bg-slate-50 flex justify-end gap-3">
                <button type="button" data-modal-hide="tambah-modal"
                    class="px-6 py-2 bg-gray-200 rounded-xl">
                    Batal
                </button>

                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-xl shadow">
                    Simpan
                </button>
            </div>

            </form>
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

        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function () {
                console.log('FORM SUBMIT:', this);
                console.log([...this.querySelectorAll('[name^="rows"]')]);
            });
        });

        let rowIndexMap = {};

        function tambahBarisTahun(id) {
            const modal = document.getElementById('edit-modal-' + id);
            const form = modal.querySelector('form');
            const wrapper = form.querySelector('#tahun-wrapper-' + id);

            const index = wrapper.querySelectorAll('[name^="rows"]').length / 2;

            const html = `
                <div class="grid grid-cols-2 gap-4">
                    <input type="number"
                        name="rows[${Math.floor(index)}][tahun_patan]"
                        placeholder="Tahun Patan"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">

                    <input type="number"
                        name="rows[${Math.floor(index)}][jumlah_kk]"
                        placeholder="Jumlah KK"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">

                    <button type="button" onclick="hapusRow(this)"
                        class="text-red-500 text-xs">Hapus</button>
                </div>
            `;

            wrapper.insertAdjacentHTML('beforeend', html);
        }

        function hapusRow(btn) {
            btn.parentElement.remove();
        }

        function openEditModal(id) {
            const modal = document.getElementById('edit-modal-' + id);
            if (modal) {
                modal.classList.remove('hidden');
                hitungSisa(id); // 🔥 langsung hitung saat buka
            }
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
        document.addEventListener('input', function (e) {
            const targetEl = document.getElementById('target_shm');
            const realisasiEl = document.getElementById('realisasi_shm');
            const sisaEl = document.getElementById('sisa_shm');

            if (!targetEl || !realisasiEl || !sisaEl) return;

            const target = parseInt(targetEl.value) || 0;
            const realisasi = parseInt(realisasiEl.value) || 0;

            let sisa = target - realisasi;
            if (sisa < 0) sisa = 0;

            sisaEl.value = sisa;
        });

        function hitungSisa(id) {
            const targetEl = document.getElementById('target_' + id);
            const realisasiEl = document.getElementById('realisasi_' + id);
            const sisaEl = document.getElementById('sisa_' + id);

            if (!targetEl || !realisasiEl || !sisaEl) return;

            const target = parseInt(targetEl.value) || 0;
            const realisasi = parseInt(realisasiEl.value) || 0;

            let sisa = target - realisasi;
            if (sisa < 0) sisa = 0;

            sisaEl.value = sisa;
        }
    </script>
    <script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js"></script>
    <style>
        .input {
            @apply w-full border border-gray-300 rounded-lg px-3 py-2 text-sm 
                focus:ring-2 focus:ring-blue-500 focus:outline-none;
        }

        .label {
            @apply block text-xs font-medium text-gray-600 mb-1;
        }
    
    </style>
</body>

</html>