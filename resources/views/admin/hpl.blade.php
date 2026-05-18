<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>Hak Pengelolaan (HPL)</title>
    @include('layouts.header')
    @vite('resources/css/app.css') 
</head>
<body class="bg-slate-50 min-h-screen font-sans">
    
    <div class="p-4 pt-20 space-y-10">
        <div class="items-center justify-between lg:flex">
            <div class="p-2 w-full">

                <div class="flex flex-col md:flex-row items-start justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <h2 class="text-2xl font-bold text-slate-800 tracking-tight">
                        Manajemen <span class="text-blue-600">Hak Pengelolaan</span>
                    </h2>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button
                            onclick="openModal('tambah-modal')"
                            class="flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 hover:shadow-none transition-all duration-300">
                            
                            Tambah HPL
                        </button>
                    </div>
                </div>

                <div class="mt-2 bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-gray-700">
                            <thead class="bg-gray-300 text-slate-700 uppercase text-xs tracking-wide">
                                <tr class="border-b">
                                    <th class="px-6 py-3 text-center">No</th>
                                    <th class="px-4 py-3 text-center">Provinsi</th>
                                    <th class="px-4 py-3 text-center">Kabupaten</th>
                                    <th class="px-4 py-3 text-center">Kecamatan</th>
                                    <th class="px-4 py-3 text-center">Kawasan</th>
                                    <th class="px-4 py-3 text-center">Lokasi</th>
                                    <th class="px-4 py-3 text-center">Status</th>
                                    <th class="px-4 py-3 text-center">Dokumen</th>
                                    <th class="px-6 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($hpl as $index => $item)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-3 text-center">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 text-center">{{ $item->kawasan?->desa?->kecamatan?->kabupaten?->provinsi?->nama_provinsi ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center">{{ $item->kawasan?->desa?->kecamatan?->kabupaten?->nama_kabupaten ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center">{{ $item->kawasan?->desa?->kecamatan?->nama_kecamatan ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center">{{ $item->kawasan?->nama_kawasan ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center">{{ $item->kawasan?->nama_lokasi ?? '-' }} </td>
                                    <td class="px-4 py-4">
                                        @php
                                            $stClass = [
                                                'sk' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                                'sertifikat' => 'bg-blue-50 text-blue-700 border-blue-100',
                                                'usulan' => 'bg-amber-50 text-amber-700 border-amber-100',
                                            ];
                                            $stLabel = ['sk' => 'SK HPL', 'sertifikat' => 'Sertifikat', 'usulan' => 'Usulan'];
                                            $st = strtolower($item->status_hpl);
                                        @endphp
                                        <span class="px-3 py-1 text-[10px] font-black rounded-full border {{ $stClass[$st] ?? 'bg-slate-50 text-slate-600' }}">
                                            {{ $stLabel[$st] ?? $item->status_hpl }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <button onclick="openDokumenModal({{ $item->hpl_id }})" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg transition-all font-bold text-xs shadow-md 
                                            {{ $item->dokumen->count() > 0 
                                                ? 'bg-blue-600 text-white hover:bg-blue-700 shadow-blue-100' 
                                                : 'bg-slate-100 text-slate-400 hover:bg-slate-200 shadow-none border border-slate-200' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                            {{ $item->dokumen->count() }} File
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <button onclick="openModal('detail-modal-{{ $item->hpl_id }}')" class="p-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-600 hover:text-white transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </button>
                                            <button onclick="openEditModal({{ $item->hpl_id }})" class="p-2 text-amber-600 bg-amber-50 rounded-lg hover:bg-amber-600 hover:text-white transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            </button>
                                            <button onclick="openModal('delete-modal-{{ $item->hpl_id }}')" class="p-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-600 hover:text-white transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v3H9V4a1 1 0 011-1z"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-20 text-center text-slate-400 italic">Data HPL belum tersedia</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @foreach ($hpl as $item)
                        <div id="delete-modal-{{ $item->hpl_id }}" tabindex="-1" class="hidden fixed inset-0 z-[9999] bg-slate-900/60 backdrop-blur-sm items-center justify-center p-4">
                            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 text-center border border-slate-100">
                                <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6 text-2xl font-bold">!</div>
                                <h3 class="text-xl font-black text-slate-800 mb-2 uppercase tracking-tight">Hapus Data HPL?</h3>
                                <p class="text-slate-500 text-sm mb-8 leading-relaxed">Anda akan menghapus data kawasan <span class="font-bold text-slate-800">{{ $item->kawasan?->nama_kawasan }}</span>. Tindakan ini tidak dapat dibatalkan.</p>
                                <div class="flex gap-3 justify-center">
                                    <button  onclick="closeModal('delete-modal-{{ $item->hpl_id }}')" class="flex-1 px-6 py-3 text-sm font-bold text-slate-500 bg-slate-100 rounded-xl hover:bg-slate-200">Batal</button>
                                    <form action="{{ route('deleteHpl', $item->hpl_id) }}" method="POST" class="flex-1">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full px-6 py-3 text-sm font-bold text-white bg-red-600 rounded-xl hover:bg-red-700 shadow-lg shadow-red-200 transition-all">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- MODAL DETAIL --}}
                        <div id="detail-modal-{{ $item->hpl_id }}" 
                            class="hidden fixed inset-0 z-[99999] bg-black/50 items-center justify-center">

                            <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden">

                                {{-- HEADER --}}
                                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 flex justify-between items-center">
                                    <div>
                                        <h3 class="text-lg font-bold text-black">Detail Hak Pengelolaan</h3>
                                        <p class="text-sm text-blue-700">
                                            {{ $item->kawasan->nama_kawasan ?? '-' }}
                                        </p>
                                    </div>

                                    <button onclick="closeModal('detail-modal-{{ $item->hpl_id }}')" 
                                        class="text-black text-xl">✕</button>
                                </div>

                                {{-- BODY --}}
                                <div class="p-6 overflow-y-auto space-y-6 bg-slate-50 text-sm">

                                    {{-- ================= WILAYAH ================= --}}
                                    <div class="bg-white rounded-xl border p-5">
                                        <h4 class="font-semibold mb-4 border-b pb-2">Informasi Wilayah</h4>

                                        <div class="grid md:grid-cols-3 gap-4">

                                            <div class="p-3 bg-gray-50 rounded border">
                                                <p class="text-xs text-gray-500">Provinsi</p>
                                                <p class="font-semibold">
                                                    {{ $item->kawasan?->desa?->kecamatan?->kabupaten?->provinsi?->nama_provinsi ?? '-' }}
                                                </p>
                                            </div>

                                            <div class="p-3 bg-gray-50 rounded border">
                                                <p class="text-xs text-gray-500">Kabupaten</p>
                                                <p class="font-semibold">
                                                    {{ $item->kawasan?->desa?->kecamatan?->kabupaten?->nama_kabupaten ?? '-' }}
                                                </p>
                                            </div>

                                            <div class="p-3 bg-gray-50 rounded border">
                                                <p class="text-xs text-gray-500">Kecamatan</p>
                                                <p class="font-semibold">
                                                    {{ $item->kawasan?->desa?->kecamatan?->nama_kecamatan ?? '-' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="grid md:grid-cols-2 gap-4 mt-4">
                                            <div class="p-3 bg-gray-50 rounded border">
                                                <p class="text-xs text-gray-500">Desa</p>
                                                <p class="font-semibold">
                                                    {{ $item->kawasan?->desa?->nama_desa ?? '-' }}
                                                </p>
                                            </div>

                                            <div class="p-3 bg-gray-50 rounded border">
                                                <p class="text-xs text-gray-500">Lokasi Kawasan</p>
                                                <p class="font-semibold">
                                                    {{ $item->lokasi_kawasan ?? '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ================= DATA HPL ================= --}}
                                    <div class="bg-white rounded-xl border p-5">
                                        <h4 class="font-semibold mb-4 border-b pb-2">Data HPL</h4>

                                        <div class="grid md:grid-cols-4 gap-4">

                                            <div class="p-3 bg-gray-50 rounded border">
                                                <p class="text-xs text-gray-500">Status HPL</p>
                                                <p class="font-bold uppercase text-blue-600">
                                                    {{ $item->status_hpl }}
                                                </p>
                                            </div>

                                            <div class="p-3 bg-gray-50 rounded border">
                                                <p class="text-xs text-gray-500">No SK</p>
                                                <p class="font-semibold">
                                                    {{ $item->no_sk_hpl ?? '-' }}
                                                </p>
                                            </div>

                                            <div class="p-3 bg-gray-50 rounded border">
                                                <p class="text-xs text-gray-500">Tanggal SK</p>
                                                <p class="font-semibold">
                                                    {{ $item->tgl_hpl ?? '-' }}
                                                </p>
                                            </div>

                                            <div class="p-3 bg-gray-50 rounded border">
                                                <p class="text-xs text-gray-500">Luas SK</p>
                                                <p class="font-bold text-emerald-600">
                                                    {{ $item->luas_sk ?? 0 }} Ha
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ================= DOKUMEN ================= --}}
                                    <div class="bg-white rounded-xl border p-5">
                                        <h4 class="font-semibold mb-4 border-b pb-2">Dokumen HPL</h4>

                                        @forelse ($item->dokumen->groupBy('jenis_dokumen') as $jenis => $list)

                                            <div class="mb-6">
                                                <h5 class="font-bold uppercase text-blue-600 mb-3">
                                                    {{ $jenis }}
                                                </h5>

                                                <div class="overflow-x-auto">
                                                    <table class="w-full text-sm border rounded-lg overflow-hidden">
                                                        <thead class="bg-gray-100 text-xs uppercase">
                                                            <tr>
                                                                <th class="px-3 py-2 border">No</th>
                                                                <th class="px-3 py-2 border">Nomor</th>
                                                                <th class="px-3 py-2 border">Tanggal</th>
                                                                <th class="px-3 py-2 border">Luas</th>
                                                                <th class="px-3 py-2 border">File</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            @foreach($list as $i => $dok)
                                                                <tr class="text-center">
                                                                    <td class="border px-2 py-1">{{ $i+1 }}</td>
                                                                    <td class="border px-2 py-1">{{ $dok->nomor ?? '-' }}</td>
                                                                    <td class="border px-2 py-1">{{ $dok->tanggal ?? '-' }}</td>
                                                                    <td class="border px-2 py-1">{{ $dok->luas ?? '-' }}</td>
                                                                    <td class="border px-2 py-1">
                                                                        @if($dok->path_file)
                                                                            <a href="{{ asset('storage/'.$dok->path_file) }}" 
                                                                            target="_blank"
                                                                            class="text-blue-600 underline text-xs">
                                                                                Lihat
                                                                            </a>
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        @empty
                                            <p class="text-gray-400 text-sm">Tidak ada dokumen</p>
                                        @endforelse
                                    </div>

                                </div>

                                {{-- FOOTER --}}
                                <div class="p-4 bg-white flex justify-end border-t">
                                    <button onclick="closeModal('detail-modal-{{ $item->hpl_id }}')"
                                        class="px-5 py-2 bg-gray-800 text-white rounded-lg text-sm">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                        {{-- MODAL UPLOAD DOKUMEN SATUAN --}}
                        <div id="tambah-dokumen-modal-{{ $item->hpl_id }}"
                            class="hidden fixed inset-0 z-[99999] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">

                            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 border border-slate-100">

                                <h3 class="text-xl font-black text-slate-800 mb-6 uppercase tracking-tight text-center">
                                    Upload Berkas Baru
                                </h3>

                                <form action="{{ route('storeDokumenTambahan') }}"
                                    method="POST"
                                    enctype="multipart/form-data"
                                    class="space-y-6">

                                    @csrf

                                    <input type="hidden"
                                        name="hpl_id"
                                        id="hpl_id_input_{{ $item->hpl_id }}">

                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">
                                            Nama Dokumen
                                        </label>

                                        <input type="text"
                                            name="nama_dokumen"
                                            class="w-full border border-slate-200 rounded-xl py-3 px-4 font-bold text-sm shadow-sm"
                                            placeholder="Misal: Sertifikat HPL"
                                            required>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-black text-blue-600 uppercase mb-2 tracking-widest">
                                            Pilih Berkas PDF
                                        </label>

                                        <input type="file"
                                            name="dokumen_file[]"
                                            multiple
                                            accept="application/pdf"
                                            class="w-full text-xs text-slate-500
                                                file:bg-blue-600
                                                file:text-white
                                                file:border-0
                                                file:rounded-xl
                                                file:px-4
                                                file:py-2
                                                file:font-black
                                                shadow-sm"
                                            required>
                                    </div>

                                    <div class="flex gap-3 pt-4">

                                        <button type="button"
                                            onclick="closeTambahDokumen({{ $item->hpl_id }})"
                                            class="flex-1 px-4 py-3 text-[10px] font-black text-slate-400 bg-slate-100 rounded-xl hover:bg-slate-200 uppercase tracking-widest transition-all">
                                            Batal
                                        </button>

                                        <button type="submit"
                                            class="flex-1 px-4 py-3 text-[10px] font-black text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 uppercase tracking-widest transition-all">
                                            Upload Berkas
                                        </button>

                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="dokumen-modal-{{ $item->hpl_id }}" tabindex="-1" class="hidden fixed inset-0 z-[9999] bg-slate-900/60 backdrop-blur-sm items-center justify-center p-4">
                            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[80vh] flex flex-col overflow-hidden border border-slate-200">
                                <div class="w-full bg-slate-800 px-8 py-6 border-b border-slate-700 flex justify-between items-center rounded-t-3xl">
                                    <div>
                                        <h3 class="text-lg font-black text-white uppercase tracking-tight">Berkas Dokumen</h3>
                                        <p class="text-blue-400 text-[10px] uppercase font-bold tracking-[0.2em] mt-1">{{ $item->kawasan?->nama_kawasan ?? 'Kawasan' }}</p>
                                    </div>
                                    <button onclick="openTambahDokumen({{ $item->hpl_id }})" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-bold text-xs transition-all shadow-lg">+ Tambah</button>
                                </div>
                                <div class="p-6 overflow-y-auto bg-slate-50 flex-1">
                                    @forelse($item->dokumen as $dok)
                                        <div class="flex items-center justify-between p-4 bg-white rounded-2xl border border-slate-100 shadow-sm mb-3">
                                            <span class="text-sm font-bold text-slate-700 truncate max-w-[250px]">{{ $dok->nama_dokumen }}</span>
                                            <div class="flex gap-1">
                                                <a href="{{ Storage::url($dok->path_file) }}" target="_blank" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                                                <button onclick="openEditDokumen({{ $dok->id }}, @js($dok->nama_dokumen))"
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
                                                <form action="{{ route('deleteDokumenHpl', $dok->id) }}" method="POST" onsubmit="return confirm('Hapus dokumen ini?')">@csrf @method('DELETE')<button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v3H9V4a1 1 0 011-1z"/></svg></button></form>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-center text-slate-400 py-10 italic">Belum ada file pendukung.</p>
                                    @endforelse
                                </div>
                                <div class="p-5 bg-white border-t flex justify-end">
                                    <button onclick="closeDokumenModal({{ $item->hpl_id }})" class="px-8 py-2.5 text-sm font-bold text-slate-500 hover:bg-slate-100 rounded-xl transition-all">Tutup</button>
                                </div>
                            </div>
                        </div>
                        <div id="editDokumenModal" class="fixed inset-0 hidden bg-black/80 flex items-center justify-center z-[99999]">
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
                        <div id="edit-modal-{{ $item->hpl_id }}" 
                            class="hidden fixed inset-0 z-[9999] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-6">

                            <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full h-[90vh] flex flex-col overflow-hidden">

                                {{-- HEADER --}}
                                <div class="bg-blue-600 px-8 py-6 text-white rounded-t-3xl">
                                    <h3 class="text-xl font-black uppercase tracking-tight">
                                        Edit Data HPL
                                    </h3>
                                </div>

                                <form action="{{ route('updateHpl', $item->hpl_id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col h-full">
                                    @csrf
                                    @method('PUT')

                                    @if ($errors->any())
                                        <div class="bg-red-100 text-red-600 p-3 rounded-lg text-sm">
                                            {{ implode(', ', $errors->all()) }}
                                        </div>
                                    @endif
                                    <div class="flex-1 overflow-y-auto px-6 py-6 space-y-6 pb-28">

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

                                        {{-- ================= DATA HPL ================= --}}
                                        <div class="border border-gray-200 rounded-2xl p-6 bg-white">
                                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">
                                                Data Hak Pengelolaan
                                            </h4>
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Status HPL</label>
                                                    <select name="status_hpl" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                        <option value="sk" {{ $item->status_hpl == 'sk' ? 'selected' : '' }}>SK</option>
                                                        <option value="sertifikat" {{ $item->status_hpl == 'sertifikat' ? 'selected' : '' }}>Sertifikat</option>
                                                        <option value="usulan" {{ $item->status_hpl == 'usulan' ? 'selected' : '' }}>Usulan</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Lokasi Kawasan</label>
                                                    <select name="lokasi_kawasan"
                                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                                        <option value="didalam" {{ $item->lokasi_kawasan == 'didalam' ? 'selected' : '' }}>
                                                            Didalam Kawasan
                                                        </option>
                                                        <option value="diluar" {{ $item->lokasi_kawasan == 'diluar' ? 'selected' : '' }}>
                                                            Diluar Kawasan
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-3 gap-4 mt-4">
                                                <div>
                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Luas HPL</label>
                                                    <input type="text" name="luas_sk" value="{{ $item->luas_sk }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">No SK HPL</label>
                                                    <input type="text" name="no_sk_hpl" value="{{ $item->no_sk_hpl }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Tanggal Terbit HPL</label>
                                                    <input type="date" name="tgl_hpl" value="{{ $item->tgl_hpl }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                </div>
                                            </div>
                                        </div>
                        
                                        {{-- ================= DOKUMEN ================= --}}
                                        <div class="border border-gray-200 rounded-2xl p-5 bg-white shadow-sm">

                                            {{-- HEADER --}}
                                            <div class="flex justify-between items-center mb-4">
                                                <h4 class="text-sm font-semibold text-gray-700">Dokumen</h4>

                                                <button type="button"
                                                    onclick="tambahDokumen({{ $item->hpl_id }})"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 text-xs rounded-lg transition">
                                                    + Tambah
                                                </button>
                                            </div>

                                            {{-- LIST DOKUMEN --}}
                                            <div id="dokumen-wrapper-{{ $item->hpl_id }}" class="space-y-3">

                                                @foreach($item->dokumen as $i => $dok)
                                                <div class="grid grid-cols-1 md:grid-cols-6 gap-3 items-center border rounded-lg p-3 bg-gray-50">

                                                    {{-- JENIS --}}

                                                    <input type="hidden" name="dokumen_detail[{{ $i }}][id]" value="{{ $dok->id }}">
                                                    
                                                    <select name="dokumen_detail[{{ $i }}][jenis]" 
                                                        class="border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-2 focus:ring-blue-500">
                                                        <option value="sk" {{ $dok->jenis_dokumen == 'sk' ? 'selected' : '' }}>SK</option>
                                                        <option value="sertifikat" {{ $dok->jenis_dokumen == 'sertifikat' ? 'selected' : '' }}>Sertifikat</option>
                                                        <option value="peta" {{ $dok->jenis_dokumen == 'peta' ? 'selected' : '' }}>Peta</option>
                                                    </select>

                                                    {{-- NOMOR --}}
                                                    <input type="text"
                                                        name="dokumen_detail[{{ $i }}][nomor]"
                                                        value="{{ $dok->nomor }}"
                                                        placeholder="Nomor"
                                                        class="border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-2 focus:ring-blue-500">

                                                    {{-- TANGGAL --}}
                                                    <input type="date"
                                                        name="dokumen_detail[{{ $i }}][tanggal]"
                                                        value="{{ $dok->tanggal }}"
                                                        class="border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-2 focus:ring-blue-500">

                                                    {{-- LUAS --}}
                                                    <input type="number"
                                                        name="dokumen_detail[{{ $i }}][luas]"
                                                        value="{{ $dok->luas }}"
                                                        placeholder="Luas"
                                                        class="border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-2 focus:ring-blue-500">

                                                    {{-- FILE --}}
                                                    <input type="file"
                                                        name="dokumen_file[{{ $i }}]"
                                                        class="text-xs border border-gray-300 rounded-md p-1 file:mr-2 file:px-2 file:py-1 file:border-0 file:bg-blue-600 file:text-white file:rounded">

                                                    {{-- AKSI --}}
                                                    <div class="flex items-center gap-2">
                                                        <a href="{{ asset('storage/'.$dok->path_file) }}"
                                                            target="_blank"
                                                            class="text-blue-600 text-xs hover:underline">
                                                            Lihat
                                                        </a>

                                                        <button type="button"
                                                            onclick="this.closest('.grid').remove()"
                                                            class="text-red-500 hover:text-red-700 text-sm">
                                                            ✕
                                                        </button>
                                                    </div>

                                                </div>
                                                @endforeach

                                            </div>

                                        </div>

                                        {{-- FOOTER --}}
                                        
                                        <div class="p-4 border-t flex justify-end gap-3">
                                            <button type="button"
                                                onclick="closeModal('edit-modal-{{ $item->hpl_id }}')"
                                                class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
                                                Batal
                                            </button>

                                            <button type="submit"
                                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                                Simpan
                                            </button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- MODAL TAMBAH (FULL AESTHETIC) --}}
    <div id="tambah-modal" tabindex="-1" class="hidden fixed inset-0 z-[9999] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col overflow-hidden border border-slate-200">
            <div class="w-full bg-blue-600 px-8 py-6 border-b border-blue-700 rounded-t-3xl text-white flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-black uppercase tracking-tight">Input Data Hak Pengelolaan</h3>
                </div>
            </div>
            <form action="{{ route('storeHpl') }}" method="POST" enctype="multipart/form-data" class="flex flex-col overflow-hidden">
                @csrf
                <div class="p-8 overflow-y-auto space-y-8 bg-white">
                    {{-- Section 1: Wilayah --}}
                    <div>
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2"><span class="w-8 h-px bg-slate-200"></span> Informasi Wilayah</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Provinsi</label>
                                <select name="provinsi_id" id="provinsi" class="w-full border-slate-200 rounded-xl py-3 px-4 focus:ring-blue-500 shadow-sm font-semibold" required>
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach ($provinsi as $item) <option value="{{ $item->id }}">{{ $item->nama_provinsi }}</option> @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Kabupaten</label>
                                <select name="kabupaten_id" id="kabupaten" class="w-full border-slate-200 rounded-xl py-3 px-4 focus:ring-blue-500 shadow-sm font-semibold" required>
                                    <option value="">-- Pilih Kabupaten --</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Kecamatan</label>
                                <input type="text" name="nama_kecamatan" class="w-full border-slate-200 rounded-xl py-3 px-4 font-semibold shadow-sm" placeholder="Input Nama Kecamatan..." required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Desa</label>
                                <input type="text" name="nama_desa" class="w-full border-slate-200 rounded-xl py-3 px-4 font-semibold shadow-sm" placeholder="Input Nama Desa..." required>
                            </div>
                        </div>
                    </div>

                    {{-- Section 2: Data Teknis & Upload --}}
                    <div class="pt-6 border-t border-slate-100">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2"><span class="w-8 h-px bg-slate-200"></span> Detail Berkas HPL</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Nama Kawasan</label>
                                <input type="text" name="nama_kawasan" class="w-full border-slate-200 rounded-xl py-3 px-4 font-bold text-slate-700 shadow-sm" placeholder="Contoh: Kawasan Terpadu A..." required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2 text-blue-600">Lokasi Kawasan</label>
                                <select name="lokasi_kawasan" onchange="handleStatusHpl(this.value)" class="w-full border-slate-200 rounded-xl py-3 px-4 font-black text-blue-700 " required>
                                    <option value="diluar">Diluar Kawasan</option>
                                    <option value="didalam">Didalam Kawasan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2 text-blue-600">Status HPL</label>
                                <select name="status_hpl" onchange="handleStatusHpl(this.value)" class="w-full border-slate-200 rounded-xl py-3 px-4 font-black text-blue-700 " required>
                                    <option value="sk">SK (Surat Keputusan)</option>
                                    <option value="sertifikat">Sertifikat</option>
                                    <option value="usulan">Usulan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2 text-emerald-600">Luas HPL (Ha)</label>
                                <input type="number" step="0.01" name="luas_sk" class="w-full border-slate-200 rounded-xl py-3 px-4 font-black text-emerald-700 shadow-sm" placeholder="0.00" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">No SK HPL</label>
                                <input type="text" name="no_sk_hpl" class="w-full border-slate-200 rounded-xl py-3 px-4 font-semibold shadow-sm" placeholder="Contoh: 123/HPL/2024">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Tanggal Terbit SK</label>
                                <input type="date" name="tgl_hpl" class="w-full border-slate-200 rounded-xl py-3 px-4 font-semibold shadow-sm">
                            </div>
                        </div>

                        <div class="bg-blue-50/50 p-6 rounded-3xl border border-blue-100 mb-6">
                            <label class="block text-[11px] font-black text-blue-600 uppercase mb-4">
                                Jenis Dokumen HPL
                            </label>

                            <div class="flex gap-6 mb-4">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="jenis_dokumen[]" value="sk" onchange="toggleDokumen()"> SK
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="jenis_dokumen[]" value="sertifikat" onchange="toggleDokumen()"> Sertifikat
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="jenis_dokumen[]" value="peta" onchange="toggleDokumen()"> Peta
                                </label>
                            </div>

                            <div id="dokumen-container" class="space-y-4"></div>
                        </div>
                    </div>                                                                                                    
                </div>
                <div class="p-6 bg-slate-50 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('tambah-modal')"
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
        // DEPENDENT DROPDOWN
        document.getElementById('provinsi')?.addEventListener('change', function () {
            const provinsiId = this.value;
            const kabupatenSelect = document.getElementById('kabupaten');
            kabupatenSelect.innerHTML = '<option value="">Loading...</option>';
            if (provinsiId) {
                fetch(`{{ url('/get-kabupaten') }}/${provinsiId}`)
                    .then(response => response.json())
                    .then(data => {
                        kabupatenSelect.innerHTML = '<option value="">-- Pilih Kabupaten --</option>';
                        data.forEach(item => { kabupatenSelect.innerHTML += `<option value="${item.id}">${item.nama_kabupaten}</option>`; });
                    });
            } else { kabupatenSelect.innerHTML = '<option value="">-- Pilih Kabupaten --</option>'; }
        });

        // MODAL HELPERS
        function openTambahDokumen(id) {

            const modal = document.getElementById(
                'tambah-dokumen-modal-' + id
            );

            const input = document.getElementById(
                'hpl_id_input_' + id
            );

            if (input) {
                input.value = id;
            }

            if (modal) {
                modal.classList.remove('hidden');
            }
        }

        function closeTambahDokumen(id) {

            const modal = document.getElementById(
                'tambah-dokumen-modal-' + id
            );

            if (modal) {
                modal.classList.add('hidden');
            }
        }

        // DYNAMIC STATUS TOGGLE
        function handleStatusHpl(status) {
            const stepLanjut = document.getElementById('step-lanjut');
            if (!stepLanjut) return; // 🔥 penting

            if (status === 'sk' || status === 'sertifikat') {
                stepLanjut.classList.remove('hidden');
            } else {
                stepLanjut.classList.add('hidden');
            }
        }

        // SERTIFIKAT ARRAY HELPER
        function tambahSertifikat() {
            const wrapper = document.getElementById('sertifikat-wrapper');
            const input = document.createElement('input');
            input.type = 'text'; input.name = 'no_sertifikat[]';
            input.className = 'w-full border-slate-200 rounded-xl py-2.5 px-4 shadow-sm font-medium mt-2 animate-slideUp';
            input.placeholder = 'Input Nomor Sertifikat Selanjutnya...';
            wrapper.appendChild(input);
        }

        function tambahEditSertifikat(id) {
            const wrapper = document.getElementById('edit-sertifikat-wrapper-'+id);
            const div = document.createElement('div');
            div.className = 'flex gap-2 mt-2 animate-slideUp';
            div.innerHTML = `<input type="text" name="no_sertifikat[]" class="w-full border-slate-200 rounded-xl py-2.5 px-4 bg-slate-50 font-bold text-slate-700 shadow-sm"><button type="button" onclick="this.parentElement.remove()" class="px-4 text-red-500 font-bold bg-white rounded-xl border border-red-100 hover:bg-red-50 transition-all">×</button>`;
            wrapper.appendChild(div);
        }

        // TOGGLE PETA
        document.getElementById('peta')?.addEventListener('change', function() {
            document.getElementById('upload-peta').classList.toggle('hidden', !this.checked);
        });
        function toggleDokumen() {
            let checked = document.querySelectorAll('input[type=checkbox]:checked');
            let container = document.getElementById('dokumen-container');

            checked.forEach(item => {
                let type = item.value;

                // 🔥 kalau sudah ada, skip
                if (document.getElementById(type + '-wrapper')) return;

                if (type === 'peta') {
                    container.insertAdjacentHTML('beforeend', `
                        <div id="peta-wrapper" class="p-4 bg-white rounded-xl border">
                            <p class="font-bold text-sm mb-2">Upload Peta</p>
                            <input type="file" name="peta_file[]" class="w-full">
                        </div>
                    `);
                } else {
                    container.insertAdjacentHTML('beforeend', `
                        <div class="p-4 bg-white rounded-xl border">
                            <div class="flex justify-between items-center mb-3">
                                <p class="font-bold text-sm uppercase">${type}</p>
                                <button type="button" onclick="tambahRow('${type}')" class="bg-blue-600 text-white px-2 py-1 rounded text-xs">+</button>
                            </div>

                            <div id="${type}-wrapper" class="space-y-2"></div>
                        </div>
                    `);

                    // 🔥 tambah row pertama
                    document.getElementById(type + '-wrapper')
                        .insertAdjacentHTML('beforeend', generateRow(type));
                }
            });
        }

        let index = 0;

        function generateRow(type) {
            const i = index++; // 🔥 AUTO INCREMENT

            return `
                <div class="grid grid-cols-5 gap-2 items-center border p-2 rounded">

                    <input type="hidden" name="dokumen_detail[${i}][jenis]" value="${type}">

                    <input type="text" name="dokumen_detail[${i}][nomor]" placeholder="Nomor" class="border p-1">

                    <input type="date" name="dokumen_detail[${i}][tanggal]" class="border p-1">

                    <input type="number" name="dokumen_detail[${i}][luas]" placeholder="Luas" class="border p-1">

                    <input type="file" name="dokumen_file[${i}]" class="border p-1">

                </div>
            `;
        }

        function tambahRow(type) {
            let wrapper = document.getElementById(type + '-wrapper');

            wrapper.insertAdjacentHTML('beforeend', generateRow(type));
        }

        function hapusRow(btn) {
            btn.closest('div').remove();
        }

        function openModal(id) {

            document.querySelectorAll(`
                [id^="detail-modal-"],
                [id^="edit-modal-"],
                [id^="delete-modal-"],
                [id^="dokumen-modal-"],
                [id^="tambah-dokumen-modal-"],
                #tambah-modal
            `).forEach(el => {
                el.classList.add('hidden');
                el.classList.remove('flex');
            });

            let el = document.getElementById(id);

            if (!el) {
                console.log('Modal tidak ditemukan:', id);
                return;
            }

            el.classList.remove('hidden');
            el.classList.add('flex');
        }
        function closeModal(id) {
            let el = document.getElementById(id);
            if (!el) return;

            el.classList.add('hidden');
            el.classList.remove('flex'); // 🔥 biar bersih
        }

        function openDokumenModal(id) {
            openModal('dokumen-modal-' + id);
        }

        function closeDokumenModal(id) {
            let el = document.getElementById('dokumen-modal-' + id);
            if (!el) return;

            el.classList.add('hidden');
            el.classList.remove('flex');
        }
        function tambahDokumen(hplId) {
            let wrapper = document.getElementById('dokumen-wrapper-' + hplId);
            let index = Date.now();

            let html = `
            <div class="grid grid-cols-6 gap-2 mb-2">

                <select name="dokumen_detail[${index}][jenis]" class="border p-1">
                    <option value="sk">SK</option>
                    <option value="sertifikat">Sertifikat</option>
                    <option value="peta">Peta</option>
                </select>

                <input type="text" name="dokumen_detail[${index}][nomor]" placeholder="Nomor" class="border p-1">
                <input type="date" name="dokumen_detail[${index}][tanggal]" class="border p-1">
                <input type="number" name="dokumen_detail[${index}][luas]" placeholder="Luas" class="border p-1">

                <input type="file" name="dokumen_file[${index}]" class="border p-1">

                <button type="button" onclick="this.parentElement.remove()" class="text-red-500">✕</button>
            </div>
            `;

            wrapper.insertAdjacentHTML('beforeend', html);
        }

        function openEditDokumen(id, nama) {
            let url = "{{ route('updateDokumenHpl', ':id') }}";
            url = url.replace(':id', id);

            const form = document.getElementById('editDokumenForm');
            const inputNama = document.getElementById('namaDokumen');
            const modal = document.getElementById('editDokumenModal');

            if (form) {
                form.action = url;
            }

            if (inputNama) {
                inputNama.value = nama;
            }

            if (modal) {
                modal.classList.remove('hidden');
            }
        }

        function closeEditDokumen() {
            const modal = document.getElementById('editDokumenModal');

            if (modal) {
                modal.classList.add('hidden');
            }
        }

        function openEditModal(id) {
            openModal('edit-modal-' + id);
        }
    </script>

    <style>
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(10px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .animate-fadeIn { animation: fadeIn 0.3s ease-out; }
        .animate-slideUp { animation: slideUp 0.4s ease-out; }
        /* Custom scrollbar for aesthetic */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .modal-hidden {
            display: none !important;
            pointer-events: none !important;
        }
    </style>
</body>
</html>