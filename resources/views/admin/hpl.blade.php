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
                            data-modal-target="tambah-modal"
                            data-modal-toggle="tambah-modal"
                            class="flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 hover:shadow-none transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah HPL
                        </button>
                    </div>
                </div>

                <div class="mt-4 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-slate-600">
                            <thead class="text-xs uppercase bg-slate-50 border-b border-slate-200 text-slate-500 font-bold">
                                <tr>
                                    <th class="px-6 py-4 text-center">No</th>
                                    <th class="px-4 py-4">Wilayah</th>
                                    <th class="px-4 py-4">Kawasan / Lokasi</th>
                                    <th class="px-4 py-4">Dokumen HPL</th>
                                    <th class="px-4 py-4">Status</th>
                                    <th class="px-4 py-4 text-center">File</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($hpl as $index => $item)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-center font-medium">{{ $index + 1 }}</td>
                                    <td class="px-4 py-4">
                                        <div class="font-bold text-slate-800">{{ $item->kawasan?->desa?->kecamatan?->kabupaten?->nama_kabupaten ?? '-' }}</div>
                                        <div class="text-xs text-slate-400">{{ $item->kawasan?->desa?->kecamatan?->kabupaten?->provinsi?->nama_provinsi ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="font-medium text-slate-700">{{ $item->kawasan?->nama_kawasan ?? '-' }}</div>
                                        <div class="text-xs text-blue-500 italic">{{ $item->kawasan?->desa?->nama_desa ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-xs font-semibold text-slate-400 uppercase">No SK:</div>
                                        <div class="text-sm font-medium">{{ $item->no_sk_hpl ?? '-' }}</div>
                                        <div class="text-[11px] text-slate-400 italic">No Sertif: {{ $item->no_sertifikat ?? '-' }}</div>
                                    </td>
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
                                            <button data-modal-target="detail-modal-{{ $item->hpl_id }}" data-modal-toggle="detail-modal-{{ $item->hpl_id }}" class="p-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-600 hover:text-white transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </button>
                                            <button data-modal-target="edit-modal-{{ $item->hpl_id }}" data-modal-toggle="edit-modal-{{ $item->hpl_id }}" class="p-2 text-amber-600 bg-amber-50 rounded-lg hover:bg-amber-600 hover:text-white transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            </button>
                                            <button data-modal-target="delete-modal-{{ $item->hpl_id }}" data-modal-toggle="delete-modal-{{ $item->hpl_id }}" class="p-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-600 hover:text-white transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v3H9V4a1 1 0 011-1z"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                {{-- MODAL LIST DOKUMEN --}}
                                <div id="dokumen-modal-{{ $item->hpl_id }}" tabindex="-1" class="hidden fixed inset-0 z-[60] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
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

                                {{-- MODAL DETAIL --}}
                                <div id="detail-modal-{{ $item->hpl_id }}" tabindex="-1" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
                                    <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col overflow-hidden">
                                        <div class="w-full bg-slate-800 px-8 py-6 border-b border-slate-700 rounded-t-3xl text-white">
                                            <h3 class="text-xl font-black uppercase tracking-tight">Detail Informasi HPL</h3>
                                            <p class="text-blue-400 text-xs font-bold uppercase tracking-widest mt-1">{{ $item->kawasan?->nama_kawasan }}</p>
                                        </div>
                                        <div class="p-8 overflow-y-auto space-y-8">
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                                    <p class="text-[10px] text-slate-400 uppercase font-black mb-1">Provinsi</p>
                                                    <p class="text-slate-800 font-bold">{{ $item->kawasan?->desa?->kecamatan?->kabupaten?->provinsi?->nama_provinsi ?? '-' }}</p>
                                                </div>
                                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                                    <p class="text-[10px] text-slate-400 uppercase font-black mb-1">Kabupaten</p>
                                                    <p class="text-slate-800 font-bold">{{ $item->kawasan?->desa?->kecamatan?->kabupaten?->nama_kabupaten ?? '-' }}</p>
                                                </div>
                                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                                    <p class="text-[10px] text-slate-400 uppercase font-black mb-1">Desa</p>
                                                    <p class="text-slate-800 font-bold">{{ $item->kawasan?->desa?->nama_desa ?? '-' }}</p>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 pt-6 border-t border-slate-100">
                                                <div class="space-y-1">
                                                    <p class="text-[10px] text-slate-400 font-black uppercase">No SK</p>
                                                    <p class="text-slate-800 font-bold text-sm">{{ $item->no_sk_hpl ?? '-' }}</p>
                                                </div>
                                                <div class="space-y-1">
                                                    <p class="text-[10px] text-slate-400 font-black uppercase">Luas SK</p>
                                                    <p class="text-slate-800 font-bold text-sm">{{ $item->luas_sk }} Ha</p>
                                                </div>
                                                <div class="space-y-1 col-span-2">
                                                    <p class="text-[10px] text-slate-400 font-black uppercase">No Sertifikat</p>
                                                    <p class="text-blue-600 font-bold text-sm">{{ $item->no_sertifikat ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-6 bg-slate-50 border-t flex justify-end">
                                            <button data-modal-hide="detail-modal-{{ $item->hpl_id }}" class="px-8 py-2.5 text-sm font-bold text-white bg-slate-800 rounded-xl hover:bg-slate-900 transition-all shadow-md">Tutup</button>
                                        </div>
                                    </div>
                                </div>

                                {{-- MODAL EDIT (LENGKAP DENGAN UPLOAD) --}}
                                <div id="edit-modal-{{ $item->hpl_id }}" tabindex="-1" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
                                    <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col overflow-hidden border border-slate-200">
                                        <div class="w-full bg-amber-600 px-8 py-6 border-b border-amber-700 rounded-t-3xl text-white">
                                            <div class="flex items-center gap-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                <h3 class="text-xl font-black uppercase tracking-tight">Edit Informasi HPL</h3>
                                            </div>
                                        </div>
                                        <form action="{{ route('updateHpl', $item->hpl_id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col h-full overflow-hidden">
                                            @csrf @method('PUT')
                                            <div class="p-8 overflow-y-auto space-y-6">
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-[10px] font-black text-slate-500 uppercase mb-2">Kecamatan</label>
                                                        <input type="text" name="nama_kecamatan" value="{{ $item->kawasan?->desa?->kecamatan?->nama_kecamatan }}" class="w-full border-slate-200 rounded-xl py-3 px-4 font-bold text-slate-700 focus:ring-amber-500 transition-all shadow-sm">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-black text-slate-500 uppercase mb-2">Desa</label>
                                                        <input type="text" name="nama_desa" value="{{ $item->kawasan?->desa?->nama_desa }}" class="w-full border-slate-200 rounded-xl py-3 px-4 font-bold text-slate-700 focus:ring-amber-500 transition-all shadow-sm">
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-slate-100">
                                                    <div>
                                                        <label class="block text-[10px] font-black text-slate-500 uppercase mb-2">No SK</label>
                                                        <input type="text" name="no_sk_hpl" value="{{ $item->no_sk_hpl }}" class="w-full border-slate-200 rounded-xl py-3 px-4 font-bold text-slate-700 focus:ring-amber-500 transition-all shadow-sm">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-black text-slate-500 uppercase mb-2">Tanggal Terbit</label>
                                                        <input type="date" name="tgl_hpl" value="{{ $item->tgl_hpl }}" class="w-full border-slate-200 rounded-xl py-3 px-4 font-bold text-slate-700 focus:ring-amber-500 transition-all shadow-sm">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-black text-slate-500 uppercase mb-2">Luas HPL (Ha)</label>
                                                        <input type="number" step="0.01" name="luas_sk" value="{{ $item->luas_sk }}" class="w-full border-slate-200 rounded-xl py-3 px-4 font-bold text-slate-700 focus:ring-amber-500 transition-all shadow-sm">
                                                    </div>
                                                </div>

                                                <div class="p-5 bg-amber-50/50 rounded-2xl border border-amber-100 space-y-4">
                                                    <label class="block text-[11px] font-black text-amber-600 uppercase mb-1 flex justify-between items-center">
                                                        Update Nomor Sertifikat 
                                                        <button type="button" onclick="tambahEditSertifikat('{{ $item->hpl_id }}')" class="bg-amber-600 text-white w-6 h-6 rounded-lg font-bold shadow-md hover:bg-amber-700">+</button>
                                                    </label>
                                                    <div id="edit-sertifikat-wrapper-{{ $item->hpl_id }}" class="space-y-2">
                                                        @foreach($item->sertifikatGroup as $sert)
                                                            <div class="flex gap-2">
                                                                <input type="text" name="no_sertifikat[]" value="{{ $sert->no_sertifikat }}" class="flex-1 border-slate-200 rounded-xl py-2.5 px-4 font-medium shadow-sm">
                                                                <button type="button" onclick="this.parentElement.remove()" class="px-4 text-red-500 font-bold bg-white rounded-xl border border-red-100 hover:bg-red-50">×</button>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="p-8 bg-slate-50 border-t flex justify-end gap-3">
                                                <button type="button" data-modal-hide="edit-modal-{{ $item->hpl_id }}" class="px-6 py-3 text-sm font-bold text-slate-500 bg-white rounded-xl border border-slate-200 hover:bg-slate-100">Batal</button>
                                                <button type="submit" class="px-8 py-3 text-sm font-bold text-white bg-amber-600 rounded-xl hover:bg-amber-700 shadow-xl shadow-amber-200 transition-all">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                {{-- MODAL DELETE --}}
                                <div id="delete-modal-{{ $item->hpl_id }}" tabindex="-1" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
                                    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 text-center border border-slate-100">
                                        <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6 text-2xl font-bold">!</div>
                                        <h3 class="text-xl font-black text-slate-800 mb-2 uppercase tracking-tight">Hapus Data HPL?</h3>
                                        <p class="text-slate-500 text-sm mb-8 leading-relaxed">Anda akan menghapus data kawasan <span class="font-bold text-slate-800">{{ $item->kawasan?->nama_kawasan }}</span>. Tindakan ini tidak dapat dibatalkan.</p>
                                        <div class="flex gap-3 justify-center">
                                            <button data-modal-hide="delete-modal-{{ $item->hpl_id }}" class="flex-1 px-6 py-3 text-sm font-bold text-slate-500 bg-slate-100 rounded-xl hover:bg-slate-200">Batal</button>
                                            <form action="{{ route('deleteHpl', $item->hpl_id) }}" method="POST" class="flex-1">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-full px-6 py-3 text-sm font-bold text-white bg-red-600 rounded-xl hover:bg-red-700 shadow-lg shadow-red-200 transition-all">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr><td colspan="7" class="px-6 py-20 text-center text-slate-400 italic">Data HPL belum tersedia</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH (FULL AESTHETIC) --}}
    <div id="tambah-modal" tabindex="-1" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col overflow-hidden border border-slate-200">
            <div class="w-full bg-blue-600 px-8 py-6 border-b border-blue-700 rounded-t-3xl text-white flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-black uppercase tracking-tight">Input Data HPL Baru</h3>
                    <p class="text-blue-100 text-[10px] font-bold uppercase tracking-[0.2em] mt-1">Sistem Pendaftaran Lahan Transmigrasi</p>
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
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2"><span class="w-8 h-px bg-slate-200"></span> Detail Teknis & Berkas SK</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Nama Kawasan</label>
                                <input type="text" name="nama_kawasan" class="w-full border-slate-200 rounded-xl py-3 px-4 font-bold text-slate-700 shadow-sm" placeholder="Contoh: Kawasan Terpadu A..." required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2 text-blue-600">Status HPL</label>
                                <select name="status_hpl" onchange="handleStatusHpl(this.value)" class="w-full border-slate-200 rounded-xl py-3 px-4 font-black text-blue-700 uppercase" required>
                                    <option value="sk">SK (Surat Keputusan)</option>
                                    <option value="sertifikat">Sertifikat</option>
                                    <option value="usulan">Usulan</option>
                                </select>
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

                        {{-- Multi Sertifikat --}}
                        <div class="bg-blue-50/50 p-6 rounded-3xl border border-blue-100 mb-6">
                            <label class="block text-[11px] font-black text-blue-600 uppercase mb-3 flex justify-between items-center">
                                Daftar Nomor Sertifikat 
                                <button type="button" onclick="tambahSertifikat()" class="bg-blue-600 text-white w-6 h-6 rounded-lg font-bold shadow-lg hover:bg-blue-700 transition-all">+</button>
                            </label>
                            <div id="sertifikat-wrapper" class="space-y-2">
                                <input type="text" name="no_sertifikat[]" class="w-full border-slate-200 rounded-xl py-2.5 px-4 font-medium shadow-sm" placeholder="Input Nomor Sertifikat 1...">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2 text-emerald-600">Luas HPL (Ha)</label>
                                <input type="number" step="0.01" name="luas_sk" class="w-full border-slate-200 rounded-xl py-3 px-4 font-black text-emerald-700 shadow-sm" placeholder="0.00" required>
                            </div>
                            <div class="flex items-center gap-6 pt-5">
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" id="peta" name="peta" value="1" class="rounded-lg border-slate-300 text-blue-600 focus:ring-blue-500 w-6 h-6 transition-all group-hover:scale-110">
                                    <span class="text-xs font-black text-slate-700 uppercase tracking-tighter">Lampirkan Peta?</span>
                                </label>
                                <div id="upload-peta" class="hidden flex-1 animate-slideUp">
                                    <input type="file" name="file_peta" class="w-full text-[10px] text-slate-400 file:bg-slate-800 file:text-white file:border-0 file:rounded-xl file:px-4 file:py-2 file:mr-4 file:font-black hover:file:bg-slate-700 cursor-pointer shadow-sm">
                                </div>
                            </div>
                        </div>

                        {{-- Dokumen Pendukung --}}
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Upload Berkas Pendukung (Multiple PDF/JPG)</label>
                            <input type="file" name="dokumen[]" multiple class="block w-full text-xs text-slate-500 file:bg-blue-600 file:text-white file:border-0 file:rounded-2xl file:px-6 file:py-3 hover:file:bg-blue-700 shadow-md transition-all cursor-pointer" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                </div>
                <div class="p-8 bg-slate-50 border-t flex justify-end gap-3">
                    <button type="button" data-modal-hide="tambah-modal" class="px-8 py-3 text-xs font-bold text-slate-500 rounded-xl hover:bg-slate-200 transition-all uppercase tracking-widest border border-slate-200 bg-white">Batal</button>
                    <button type="submit" class="px-10 py-3 text-xs font-black text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-xl shadow-blue-200 uppercase tracking-widest transition-all active:scale-95">Simpan Data HPL</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL UPLOAD DOKUMEN SATUAN --}}
    <div id="tambah-dokumen-modal" class="hidden fixed inset-0 z-[100] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 border border-slate-100">
            <h3 class="text-xl font-black text-slate-800 mb-6 uppercase tracking-tight text-center">Upload Berkas Baru</h3>
            <form action="{{ route('storeDokumenHpl') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="hpl_id" id="hpl_id_input">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Nama Dokumen</label>
                    <input type="text" name="nama_dokumen" class="w-full border-slate-200 rounded-xl py-3 px-4 font-bold text-sm shadow-sm" placeholder="Misal: Sertifikat_KTM_X" required>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest text-blue-600">Pilih Berkas (PDF)</label>
                    <input type="file" name="dokumen[]" multiple accept="application/pdf" class="w-full text-xs text-slate-500 file:bg-blue-600 file:text-white file:border-0 file:rounded-xl file:px-4 file:py-2 file:font-black shadow-sm" required>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeTambahDokumen()" class="flex-1 px-4 py-3 text-[10px] font-black text-slate-400 bg-slate-100 rounded-xl hover:bg-slate-200 uppercase tracking-widest transition-all">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-3 text-[10px] font-black text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 uppercase tracking-widest transition-all">Upload Berkas</button>
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
        function openDokumenModal(id) { document.getElementById('dokumen-modal-' + id).classList.remove('hidden'); }
        function closeDokumenModal(id) { document.getElementById('dokumen-modal-' + id).classList.add('hidden'); }
        function openTambahDokumen(hplId) { 
            document.getElementById('hpl_id_input').value = hplId;
            document.getElementById('tambah-dokumen-modal').classList.remove('hidden'); 
        }
        function closeTambahDokumen() { document.getElementById('tambah-dokumen-modal').classList.add('hidden'); }

        // DYNAMIC STATUS TOGGLE
        function handleStatusHpl(status) {
            const stepLanjut = document.getElementById('step-lanjut');
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
    </style>

    <script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js"></script>
</body>
</html>