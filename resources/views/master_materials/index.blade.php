@extends('layouts.app')

@section('title', 'Master Data Material & Spare Part')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- Top Header Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <a href="{{ route('pallet.index') }}" class="hover:text-blue-600 transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <span>/</span>
                <span class="text-slate-800">Master Data</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2.5">
                <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                <span>Master Data Material &amp; Spare Part Asli</span>
                <span class="text-xs font-mono font-bold px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                    {{ $totalMaterials }} Item Terdaftar
                </span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Kelola katalog referensi resmi material dan spare part yang digunakan dalam pembuatan stiker pallet.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <button type="button" 
                    onclick="openImportModal()" 
                    class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-sm hover:shadow-md transition active:scale-95 flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <span>📥 Import Excel / CSV</span>
            </button>

            <button type="button" 
                    onclick="openCreateModal()" 
                    class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-sm hover:shadow-md transition active:scale-95 flex items-center gap-2">
                <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Material Baru</span>
            </button>
        </div>
    </div>

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- 1. Total Material -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-extrabold text-slate-500 uppercase tracking-wider block">Total Material Asli</span>
                <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalMaterials }}</span>
                <span class="text-[11px] text-slate-400 block">Katalog master terdaftar</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>

        <!-- 2. Material Aktif -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-extrabold text-emerald-600 uppercase tracking-wider block">Material Aktif</span>
                <span class="text-3xl font-extrabold text-emerald-700 tracking-tight">{{ $totalActive }}</span>
                <span class="text-[11px] text-slate-400 block">Siap digunakan pada stiker</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <!-- 3. Material Nonaktif -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider block">Material Nonaktif</span>
                <span class="text-3xl font-extrabold text-slate-500 tracking-tight">{{ $totalInactive }}</span>
                <span class="text-[11px] text-slate-400 block">Diarsipkan sementara</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Filter and Search Toolbar -->
    <div class="p-4 bg-white border border-slate-200/90 rounded-2xl shadow-xs">
        <form method="GET" action="{{ route('master-materials.index') }}" class="flex items-center gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ $currentSearch }}" 
                       placeholder="Cari berdasarkan ID Material, nama material / spare part, atau spesifikasi..." 
                       class="w-full pl-10 pr-9 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition shadow-xs">
                @if(!empty($currentSearch))
                    <a href="{{ route('master-materials.index') }}" 
                       class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                @endif
            </div>
            <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-black text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <span>Cari Material</span>
            </button>
            @if(!empty($currentSearch))
                <a href="{{ route('master-materials.index') }}" class="px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Master Data Table -->
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden">
        
        <div class="bg-slate-50/90 border-b border-slate-200/90 px-6 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-800">Daftar Katalog Master Material</span>
            </div>
            <span class="text-xs text-slate-500 font-medium">Menampilkan {{ $materials->total() }} total data</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                <thead class="bg-slate-50/80 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th scope="col" class="py-3 px-4 w-12 text-center">NO</th>
                        <th scope="col" class="py-3 px-4 w-40">ID MATERIAL</th>
                        <th scope="col" class="py-3 px-4 min-w-[320px]">Nama Material / Spare Part &amp; Spesifikasi</th>
                        <th scope="col" class="py-3 px-4 w-28 text-center">Satuan Default</th>
                        <th scope="col" class="py-3 px-4 w-24 text-center">Status</th>
                        <th scope="col" class="py-3 px-4 w-28 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($materials as $index => $material)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="py-3 px-4 text-center font-mono text-slate-400 text-[11px]">
                                {{ $materials->firstItem() + $index }}
                            </td>
                            <td class="py-3 px-4">
                                @if(!empty($material->item_code))
                                    <span class="font-mono font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-200 text-[11px]">
                                        {{ $material->item_code }}
                                    </span>
                                @else
                                    <span class="text-slate-300 font-mono text-[11px]">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-900 text-xs">{{ $material->name }}</div>
                                @if(!empty($material->specification))
                                    <div class="text-[11px] text-slate-500 mt-0.5">{{ $material->specification }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="font-mono font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded text-[11px]">
                                    {{ $material->default_unit }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($material->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Edit Button -->
                                    <button type="button" 
                                            onclick='openEditModal(@json($material))'
                                            class="p-1.5 rounded-lg text-slate-500 hover:text-blue-700 hover:bg-blue-50 transition"
                                            title="Edit Material">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </button>
                                    <!-- Delete Button -->
                                    <button type="button" 
                                            onclick="confirmDelete({{ $material->id }}, '{{ addslashes($material->name) }}')"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition"
                                            title="Hapus Material">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400 bg-slate-50/50 space-y-2">
                                <div class="text-3xl">📦</div>
                                <div class="text-sm font-bold text-slate-700">Belum ada material / spare part asli terdaftar</div>
                                <p class="text-xs text-slate-400 max-w-sm mx-auto">
                                    Silakan klik tombol <strong>"Tambah Material Baru"</strong> untuk memasukkan master data resmi pertama Anda.
                                </p>
                                <div class="pt-2 flex items-center justify-center gap-2">
                                    <button type="button" 
                                            onclick="openCreateModal()" 
                                            class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-xs transition flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        <span>Tambah Material Baru</span>
                                    </button>
                                    <button type="button" 
                                            onclick="openImportModal()" 
                                            class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-xs transition flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                        </svg>
                                        <span>📥 Import Excel / CSV</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($materials->hasPages())
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                {{ $materials->links() }}
            </div>
        @endif

    </div>

<!-- Modal Form Tambah / Edit Material -->
<div id="materialModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-2xl shadow-2xl w-full overflow-hidden transition-all transform animate-in fade-in duration-150 my-auto" style="max-width: 520px;">
        
        <!-- Modal Header -->
        <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                <h3 class="text-sm font-extrabold text-slate-900" id="modalTitle">Tambah Material / Spare Part Asli</h3>
            </div>
            <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-700 transition p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Modal Body / Form -->
        <form id="materialForm" method="POST" action="{{ route('master-materials.store') }}" class="p-5 space-y-3.5">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <!-- ID Material -->
            <div>
                <label for="modal_item_code" class="block text-xs font-bold text-slate-700 mb-1">
                    ID Material (Opsional)
                </label>
                <input type="text" 
                       name="item_code" 
                       id="modal_item_code" 
                       placeholder="Contoh: 300944956 atau T0001" 
                       class="w-full px-3 py-2 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition shadow-xs">
            </div>

            <!-- Nama Material (Required) -->
            <div>
                <label for="modal_name" class="block text-xs font-bold text-slate-700 mb-1">
                    Nama Material / Sparepart <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       id="modal_name" 
                       required 
                       placeholder="Contoh: TURNKNIFE TK IV 330mm HHQ, Roll Dressing 450mm" 
                       class="w-full px-3 py-2 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition shadow-xs">
            </div>

            <!-- Satuan Default -->
            <div>
                <label for="modal_default_unit" class="block text-xs font-bold text-slate-700 mb-1">
                    Satuan Default <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       name="default_unit" 
                       id="modal_default_unit" 
                       required 
                       value="UNIT" 
                       placeholder="Contoh: PCS, SET, ROLL, BOX, UNIT" 
                       class="w-full px-3 py-2 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition shadow-xs">
            </div>

            <!-- Spesifikasi / Catatan Tambahan -->
            <div>
                <label for="modal_specification" class="block text-xs font-bold text-slate-700 mb-1">
                    Spesifikasi Teknis / Catatan (Opsional)
                </label>
                <textarea name="specification" 
                          id="modal_specification" 
                          rows="2" 
                          placeholder="Catatan ukuran teknis, kompatibilitas mesin, atau standar pabrik..." 
                          class="w-full px-3 py-2 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition shadow-xs"></textarea>
            </div>

            <!-- Checkbox Aktif -->
            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" 
                       name="is_active" 
                       id="modal_is_active" 
                       value="1" 
                       checked 
                       class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 cursor-pointer">
                <label for="modal_is_active" class="text-xs font-medium text-slate-700 cursor-pointer">
                    Status Aktif (Dapat dipilih pada pembuatan pallet)
                </label>
            </div>

            <!-- Modal Footer Buttons -->
            <div class="pt-3.5 border-t border-slate-200 flex items-center justify-end gap-2.5">
                <button type="button" 
                        onclick="closeModal()" 
                        class="px-4 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold transition shadow-xs">
                    Batal
                </button>
                <button type="submit" 
                        id="modalSubmitBtn"
                        class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs shadow-xs transition active:scale-95 flex items-center gap-1.5">
                    <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span id="modalSubmitText">Simpan Material</span>
                </button>
            </div>

        </form>

    </div>
</div>

<!-- Modal Import Excel / CSV -->
<div id="importModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-2xl shadow-2xl w-full p-5 space-y-3.5 my-auto" style="max-width: 520px;">
        <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Import Material dari Excel / CSV</h3>
                    <p class="text-[11px] text-slate-500">Unggah file spreadsheet untuk memasukkan data secara massal</p>
                </div>
            </div>
            <button type="button" onclick="closeImportModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="importForm" method="POST" action="{{ route('master-materials.import') }}" enctype="multipart/form-data" class="space-y-3" onsubmit="handleImportSubmit()">
            @csrf

            <!-- Download Template Banner -->
            <div class="p-2.5 bg-emerald-50/70 border border-emerald-200/80 rounded-xl flex items-center justify-between gap-2.5 text-xs">
                <div class="space-y-0.5">
                    <span class="font-extrabold text-emerald-950 block text-[11px]">Belum punya format file?</span>
                    <span class="text-emerald-800 text-[10px]">Gunakan template resmi agar nama kolom sesuai.</span>
                </div>
                <a href="{{ route('master-materials.template') }}" class="px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] shrink-0 flex items-center gap-1 transition shadow-2xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Unduh Template CSV</span>
                </a>
            </div>

            <!-- Drag & Drop / File Input Box -->
            <div class="space-y-1">
                <label class="text-[11px] font-bold text-slate-700 block">Pilih File Spreadsheet (.xlsx, .xls, .csv) *</label>
                <div id="dropZone" 
                     class="border-2 border-dashed border-slate-300 hover:border-emerald-500 rounded-xl p-4 text-center bg-slate-50/50 hover:bg-emerald-50/30 transition cursor-pointer"
                     onclick="document.getElementById('importFileInput').click()">
                    <input type="file" 
                           id="importFileInput" 
                           name="file" 
                           accept=".xlsx,.xls,.csv,.txt" 
                           required 
                           class="hidden" 
                           onchange="onFileSelected(this)">
                    <div class="space-y-1.5">
                        <div class="w-8 h-8 mx-auto rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <div id="filePromptText" class="text-xs text-slate-600">
                            <span class="font-extrabold text-emerald-700">Klik untuk memilih file</span> atau seret file ke sini
                        </div>
                        <p class="text-[10px] text-slate-400 font-mono">Format: .xlsx, .xls, .csv (Maksimal 10 MB)</p>
                    </div>
                </div>
            </div>

            <!-- Options -->
            <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200 text-xs space-y-1">
                <label class="flex items-start gap-2 cursor-pointer">
                    <input type="checkbox" 
                           name="update_existing" 
                           value="1" 
                           checked 
                           class="mt-0.5 w-3.5 h-3.5 rounded text-emerald-600 border-slate-300 focus:ring-emerald-500 cursor-pointer">
                    <div>
                        <span class="font-bold text-slate-800 text-[11px] block">Perbarui data jika ID Material atau Nama sudah terdaftar</span>
                        <span class="text-[10px] text-slate-500 block">Jika dicentang, data lama akan diperbarui. Jika tidak, data duplikat dilewati.</span>
                    </div>
                </label>
            </div>

            <!-- Actions -->
            <div class="pt-2 flex items-center justify-end gap-2 border-t border-slate-100">
                <button type="button" onclick="closeImportModal()" class="px-3.5 py-1.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold transition shadow-xs">
                    Batal
                </button>
                <button type="submit" 
                        id="btnSubmitImport"
                        class="px-4 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-xs transition active:scale-95 flex items-center gap-1.5">
                    <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <span id="btnSubmitImportText">Mulai Import Data</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-2xl shadow-2xl w-full p-5 space-y-3.5 my-auto" style="max-width: 440px;">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <div>
                <h4 class="text-sm font-extrabold text-slate-900">Hapus Material Master</h4>
                <p class="text-xs text-slate-500 mt-0.5">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
        </div>

        <p class="text-xs text-slate-600">
            Apakah Anda yakin ingin menghapus material <strong id="deleteMaterialName" class="text-slate-900"></strong> dari Master Data?
        </p>

        <form id="deleteForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="pt-2 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold transition shadow-xs">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs shadow-xs transition active:scale-95">
                    Ya, Hapus Material
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openCreateModal() {
        document.getElementById('modalTitle').textContent = 'Tambah Material / Spare Part Asli';
        document.getElementById('modalSubmitText').textContent = 'Simpan Material';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('materialForm').action = "{{ route('master-materials.store') }}";
        
        document.getElementById('modal_item_code').value = '';
        document.getElementById('modal_name').value = '';
        document.getElementById('modal_default_unit').value = 'UNIT';
        document.getElementById('modal_specification').value = '';
        document.getElementById('modal_is_active').checked = true;

        document.getElementById('materialModal').classList.remove('hidden');
        document.getElementById('modal_name').focus();
    }

    function openEditModal(material) {
        document.getElementById('modalTitle').textContent = 'Edit Material / Spare Part';
        document.getElementById('modalSubmitText').textContent = 'Simpan Perubahan';
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('materialForm').action = `/master-materials/${material.id}`;
        
        document.getElementById('modal_item_code').value = material.item_code || '';
        document.getElementById('modal_name').value = material.name || '';
        document.getElementById('modal_default_unit').value = material.default_unit || 'UNIT';
        document.getElementById('modal_specification').value = material.specification || '';
        document.getElementById('modal_is_active').checked = Boolean(material.is_active);

        document.getElementById('materialModal').classList.remove('hidden');
        document.getElementById('modal_name').focus();
    }

    function closeModal() {
        document.getElementById('materialModal').classList.add('hidden');
    }

    function confirmDelete(id, name) {
        document.getElementById('deleteMaterialName').textContent = `"${name}"`;
        document.getElementById('deleteForm').action = `/master-materials/${id}`;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Import Modal Functions
    function openImportModal() {
        document.getElementById('importModal').classList.remove('hidden');
        document.getElementById('importFileInput').value = '';
        document.getElementById('filePromptText').innerHTML = '<span class="font-extrabold text-emerald-700">Klik untuk memilih file</span> atau seret file ke sini';
    }

    function closeImportModal() {
        document.getElementById('importModal').classList.add('hidden');
    }

    function onFileSelected(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const sizeInKb = Math.round(file.size / 1024);
            document.getElementById('filePromptText').innerHTML = `<span class="font-bold text-slate-800">File dipilih:</span> <span class="text-emerald-700 font-extrabold">${file.name}</span> <span class="text-slate-400">(${sizeInKb} KB)</span>`;
        }
    }

    function handleImportSubmit() {
        const btn = document.getElementById('btnSubmitImport');
        const text = document.getElementById('btnSubmitImportText');
        if (btn && text) {
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');
            text.textContent = 'Mengimpor data...';
        }
    }

    // Drag and drop support for import modal
    const dropZone = document.getElementById('dropZone');
    if (dropZone) {
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.add('border-emerald-500', 'bg-emerald-50/50');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.remove('border-emerald-500', 'bg-emerald-50/50');
            }, false);
        });

        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files.length > 0) {
                const fileInput = document.getElementById('importFileInput');
                fileInput.files = files;
                onFileSelected(fileInput);
            }
        }, false);
    }

    // Close on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
            closeDeleteModal();
            closeImportModal();
        }
    });
</script>
@endpush
@endsection
