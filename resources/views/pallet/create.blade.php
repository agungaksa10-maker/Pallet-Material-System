@extends('layouts.app')

@section('title', 'Buat Sticker Pallet Baru')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- Top Breadcrumb & Action Bar -->
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
                <span class="text-slate-800">Formulir Tambah Sticker</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2.5">
                <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                <span>Buat Sticker Pallet Baru</span>
                <span class="text-xs font-mono font-bold px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                    Rentang 1 s/d 500
                </span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Lengkapi formulir di bawah ini untuk mengenerate stiker palet, kode identifikasi, dan barcode cetak termal.
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('pallet.index') }}" 
               class="px-4 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span>Batal / Kembali</span>
            </a>
        </div>
    </div>

    <!-- Main Grid: Form on Left (7 cols), Live Thermal Label on Right (5 cols) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- ======================================================== -->
        <!-- FORMULIR INPUT (7 COLUMNS)                               -->
        <!-- ======================================================== -->
        <div class="lg:col-span-7 bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs space-y-6">
            
            <form action="{{ route('pallet.store') }}" method="POST" id="createPalletForm" class="space-y-6">
                @csrf
                <input type="hidden" name="from_create" value="1">

                <!-- 1. Pemilihan Site / Pabrik -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-extrabold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                            <span>1. Pilih Site / Pabrik</span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[11px] text-slate-400 font-mono">5 Fasilitas Produksi</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                        @foreach($sites as $sKey => $sLabel)
                            <label class="cursor-pointer">
                                <input type="radio" 
                                       name="site" 
                                       value="{{ $sKey }}" 
                                       class="sr-only site-radio"
                                       {{ old('site', $selectedSite) === $sKey ? 'checked' : '' }}
                                       onchange="onSiteChange('{{ $sKey }}')">
                                <div class="site-tile p-3 rounded-xl border text-center transition-all flex flex-col items-center justify-center gap-1 {{ old('site', $selectedSite) === $sKey ? 'bg-blue-50/80 border-blue-500 text-blue-800 ring-2 ring-blue-500/20 shadow-xs' : 'bg-slate-50/50 hover:bg-slate-100/80 border-slate-200 text-slate-700' }}">
                                    <span class="font-extrabold text-xs tracking-tight">{{ $sKey }}</span>
                                    <span class="text-[10px] text-slate-500 truncate w-full text-center">{{ explode(' ', $sLabel)[0] }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('site')
                        <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 2. Pemilihan Kategori -->
                <div class="space-y-2">
                    <label class="text-xs font-extrabold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                        <span>2. Kategori Material</span>
                        <span class="text-rose-500">*</span>
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" 
                                   name="category" 
                                   value="Dressing" 
                                   class="sr-only cat-radio"
                                   {{ old('category', $selectedCategory) === 'Dressing' ? 'checked' : '' }}
                                   onchange="onCategoryChange('Dressing')">
                            <div class="cat-tile p-3.5 rounded-xl border transition-all flex items-center gap-3 {{ old('category', $selectedCategory) === 'Dressing' ? 'bg-indigo-50 border-indigo-500 text-indigo-900 ring-2 ring-indigo-500/20 shadow-xs' : 'bg-slate-50/50 hover:bg-slate-100 border-slate-200 text-slate-700' }}">
                                <div class="w-9 h-9 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-extrabold text-xs">Dressing Material</div>
                                    <div class="text-[11px] text-slate-500">Roll, stone, blade, tooling</div>
                                </div>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" 
                                   name="category" 
                                   value="Consumable" 
                                   class="sr-only cat-radio"
                                   {{ old('category', $selectedCategory) === 'Consumable' ? 'checked' : '' }}
                                   onchange="onCategoryChange('Consumable')">
                            <div class="cat-tile p-3.5 rounded-xl border transition-all flex items-center gap-3 {{ old('category', $selectedCategory) === 'Consumable' ? 'bg-amber-50 border-amber-500 text-amber-900 ring-2 ring-amber-500/20 shadow-xs' : 'bg-slate-50/50 hover:bg-slate-100 border-slate-200 text-slate-700' }}">
                                <div class="w-9 h-9 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-extrabold text-xs">Consumable Material</div>
                                    <div class="text-[11px] text-slate-500">Stretch film, strapping, ribbon</div>
                                </div>
                            </div>
                        </label>
                    </div>
                    @error('category')
                        <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 3. Nomor Pallet (1 hingga 500) -->
                <div class="space-y-3 p-4 rounded-xl bg-slate-50 border border-slate-200/90">
                    <div class="flex items-center justify-between">
                        <label for="pallet_number" class="text-xs font-extrabold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                            <span>3. Nomor Pallet (Unik &amp; Berurutan)</span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[11px] font-mono font-bold text-slate-600" id="palletCodePreviewText">
                            PLT-{{ $selectedSite === 'OKI II' ? 'OKI2' : $selectedSite }}-CON-{{ str_pad($selectedPallet, 3, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>

                    <!-- Availability Status Alert -->
                    <div id="palletStatusBox">
                        <div id="palletAvailableBadge" class="p-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Pallet #<span class="activePalletDisplay">{{ $selectedPallet }}</span> <strong>Tersedia</strong> untuk Site <span class="activeSiteDisplay">{{ $selectedSite }}</span></span>
                            </span>
                            <span class="text-[10px] font-mono bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-md font-bold">Siap Pakai</span>
                        </div>

                        <div id="palletUsedWarning" class="hidden p-2.5 rounded-xl bg-rose-50 border border-rose-300 text-rose-800 text-xs font-semibold flex items-center justify-between animate-shake">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <span>Pallet #<span class="activePalletDisplay">{{ $selectedPallet }}</span> <strong>sudah digunakan</strong> di Site <span class="activeSiteDisplay">{{ $selectedSite }}</span> &mdash; tidak bisa dipakai lagi!</span>
                            </span>
                            <button type="button" onclick="autoSelectNextPallet()" class="text-[11px] font-bold text-rose-700 underline hover:text-rose-900 whitespace-nowrap ml-2">
                                Pilih Urutan Berikutnya &rarr;
                            </button>
                        </div>
                    </div>

                    <!-- Stepper & Direct Input -->
                    <div class="flex items-center gap-3">
                        <button type="button" 
                                onclick="adjustPalletNumber(-1)"
                                class="w-11 h-11 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-extrabold text-lg flex items-center justify-center shadow-xs active:scale-95 transition">
                            &minus;
                        </button>

                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-mono font-bold text-sm">
                                #
                            </span>
                            <input type="number" 
                                   name="pallet_number" 
                                   id="pallet_number" 
                                   min="1" 
                                   max="500" 
                                   required
                                   value="{{ old('pallet_number', $selectedPallet) }}"
                                   oninput="onPalletNumberChange(this.value)"
                                   class="w-full pl-8 pr-16 py-2.5 bg-white border border-slate-200 rounded-xl font-mono text-center text-lg font-extrabold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition shadow-xs">
                            <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400 font-mono text-xs">
                                / 500
                            </span>
                        </div>

                        <button type="button" 
                                onclick="adjustPalletNumber(1)"
                                class="w-11 h-11 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-extrabold text-lg flex items-center justify-center shadow-xs active:scale-95 transition">
                            +
                        </button>
                    </div>

                    <!-- Slider for quick range picking -->
                    <div class="pt-1">
                        <input type="range" 
                               min="1" 
                               max="500" 
                               value="{{ old('pallet_number', $selectedPallet) }}" 
                               id="palletSlider"
                               oninput="onSliderChange(this.value)"
                               class="w-full accent-blue-600 cursor-pointer">
                    </div>

                    <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1">
                        <span>Nomor urut pallet otomatis disarankan dari data terakhir.</span>
                        <button type="button" onclick="autoSelectNextPallet()" class="text-blue-600 font-bold hover:underline">
                            Urutan Otomatis
                        </button>
                    </div>

                    @error('pallet_number')
                        <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 4. Multi-Komponen Dinamis (Bisa beberapa component dalam 1 pallet) -->
                <div class="space-y-3 p-4 rounded-xl bg-slate-50/80 border border-slate-200/90">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="text-xs font-extrabold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                <span>4. Komponen &amp; Material Pallet</span>
                                <span class="text-rose-500">*</span>
                            </label>
                            <p class="text-[11px] text-slate-500 mt-0.5">
                                Pallet ini dapat diisi lebih dari 1 komponen. Tekan "+ Tambah Komponen" untuk memasukkan item berikutnya.
                            </p>
                        </div>
                        <span class="text-[11px] font-mono px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-bold" id="componentCounterBadge">
                            1 Komponen
                        </span>
                    </div>

                    <!-- Dynamic Component Rows Container -->
                    <div id="componentsContainer" class="space-y-3 pt-1">
                        <!-- Row 1 (Default) -->
                        <div class="component-row p-3.5 bg-white border border-slate-200 rounded-xl shadow-xs space-y-2.5 transition relative">
                            <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                                <span class="flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                    <span class="row-label">Komponen #1</span>
                                </span>
                                <button type="button" 
                                        onclick="removeComponentRow(this)" 
                                        class="text-rose-500 hover:text-rose-700 text-xs font-semibold remove-btn hidden">
                                    &times; Hapus
                                </button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5">
                                <div class="sm:col-span-7">
                                    <label class="text-[10px] font-bold text-slate-500 block mb-1">Nama Komponen / Material *</label>
                                    <input type="text" 
                                           name="components[0][component_name]" 
                                           value="{{ old('components.0.component_name', old('material_name', $selectedCategory === 'Dressing' ? 'Knife Run' : 'Stretch Film Roll 500mm x 300m')) }}" 
                                           required
                                           oninput="refreshLivePreview()"
                                           placeholder="Contoh: Knife Run, Roll Dressing, Diamond Stone..." 
                                           class="w-full px-3 py-2 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition component-name-input">
                                </div>

                                <div class="sm:col-span-5">
                                    <label class="text-[10px] font-bold text-slate-500 block mb-1">Jumlah / Qty</label>
                                    <input type="text" 
                                           name="components[0][quantity]" 
                                           value="{{ old('components.0.quantity', '1 UNIT') }}" 
                                           oninput="refreshLivePreview()"
                                           placeholder="Contoh: 10 PCS, 2 UNIT" 
                                           class="w-full px-3 py-2 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition component-qty-input">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5">
                                <div class="sm:col-span-6">
                                    <input type="text" 
                                           name="components[0][batch_no]" 
                                           value="{{ old('components.0.batch_no') }}" 
                                           placeholder="No. Batch komponen (opsional)" 
                                           class="w-full px-3 py-1.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-lg text-xs font-mono text-slate-700 focus:outline-none focus:border-blue-600 transition">
                                </div>
                                <div class="sm:col-span-6">
                                    <input type="text" 
                                           name="components[0][notes]" 
                                           value="{{ old('components.0.notes') }}" 
                                           placeholder="Catatan / Spesifikasi (opsional)" 
                                           class="w-full px-3 py-1.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:border-blue-600 transition">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Button to Add Component Row -->
                    <div class="pt-1 flex items-center justify-between gap-3">
                        <button type="button" 
                                onclick="addComponentRow()"
                                class="px-3.5 py-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 font-extrabold text-xs border border-blue-200 transition flex items-center gap-1.5 shadow-xs">
                            <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>+ Tambah Komponen Lain di Pallet Ini</span>
                        </button>

                        <span class="text-[11px] text-slate-400 font-medium">Bisa diisi beberapa komponen</span>
                    </div>

                    <!-- Preset Chips to quick-add -->
                    <div class="pt-2 border-t border-slate-200 space-y-1.5">
                        <span class="text-[11px] text-slate-500 font-bold">Preset Komponen Cepat (Klik untuk Tambah):</span>
                        <div class="flex flex-wrap gap-1.5" id="presetChipsContainer">
                            @foreach($materialPresets[$selectedCategory] ?? [] as $presetItem)
                                <button type="button" 
                                        onclick="addPresetAsComponent('{{ addslashes($presetItem) }}')"
                                        class="px-2.5 py-1 rounded-lg bg-white hover:bg-blue-50 hover:text-blue-700 hover:border-blue-300 border border-slate-200 text-slate-700 text-[11px] font-medium transition shadow-2xs">
                                    + {{ $presetItem }}
                                </button>
                            @endforeach
                            <!-- Popular Knife Run preset -->
                            <button type="button" 
                                    onclick="addPresetAsComponent('Knife Run')"
                                    class="px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-800 border border-blue-200 text-[11px] font-bold transition shadow-2xs">
                                + Knife Run
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 5. Nomor Batch Pallet & Catatan Umum -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- No. Batch Pallet -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="batch_no" class="text-xs font-bold text-slate-700">No. Batch Pallet</label>
                            <button type="button" 
                                    onclick="generateTodayBatch()"
                                    class="text-[10px] font-bold text-blue-600 hover:underline">
                                Auto-Generate
                            </button>
                        </div>
                        <input type="text" 
                               name="batch_no" 
                               id="batch_no" 
                               value="{{ old('batch_no', 'BATCH-'.date('Ymd').'-'.str_pad($selectedPallet, 3, '0', STR_PAD_LEFT)) }}"
                               placeholder="Contoh: BATCH-20260904-001"
                               class="w-full px-3 py-2 bg-slate-50 hover:bg-white border border-slate-200 rounded-xl font-mono text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition shadow-xs">
                    </div>

                    <!-- Catatan / Lokasi Pallet -->
                    <div class="space-y-1.5">
                        <label for="notes" class="text-xs font-bold text-slate-700">Lokasi / Keterangan Pallet</label>
                        <input type="text" 
                               name="notes" 
                               id="notes" 
                               value="{{ old('notes') }}"
                               placeholder="Contoh: Rak A-02, Line Produksi 3..."
                               class="w-full px-3 py-2 bg-slate-50 hover:bg-white border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition shadow-xs">
                    </div>
                </div>

                <!-- Submit Action Buttons -->
                <div class="pt-4 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-end gap-3">
                    <a href="{{ route('pallet.index') }}" 
                       class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs text-center transition shadow-xs">
                        Batal
                    </a>

                    <!-- Save & Print Directly -->
                    <button type="submit" 
                            name="action" 
                            value="print"
                            id="btnSubmitPrint"
                            class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-sm transition active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4 text-slate-300 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        <span>Simpan &amp; Cetak PDF</span>
                    </button>

                    <!-- Save to Database -->
                    <button type="submit" 
                            name="action" 
                            value="save"
                            id="btnSubmitSave"
                            class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold text-xs flex items-center justify-center gap-2 shadow-sm hover:shadow-md shadow-blue-500/20 transition active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4 text-white stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Simpan Sticker Pallet</span>
                    </button>
                </div>

            </form>

        </div>

        <!-- ======================================================== -->
        <!-- LIVE THERMAL LABEL PREVIEW & COMPONENT SUMMARY (5 COLS)  -->
        <!-- ======================================================== -->
        <div class="lg:col-span-5 space-y-4 lg:sticky lg:top-20">
            
            <div class="flex items-center justify-between px-1">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#0047BA] animate-pulse"></span>
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Live Preview Output Sticker</h3>
                </div>
                <span class="text-[11px] font-mono text-slate-500">ANDRITZ Standard</span>
            </div>

            <!-- ANDRITZ Pallet Sticker Card -->
            <x-andritz-sticker 
                :pallet-number="$selectedPallet" 
                :category="$selectedCategory" 
                :category-code="'I-COS'" 
                id-prefix="preview" />

            <!-- Live Components Inside Pallet Card -->
            <div class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-xs space-y-2.5">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span>Daftar Komponen di Pallet Ini</span>
                    </span>
                    <span class="font-mono font-bold text-slate-500 text-[11px]" id="previewTotalCountText">1 Item</span>
                </div>

                <div id="previewComponentsList" class="space-y-1.5 max-h-48 overflow-y-auto pr-1">
                    <!-- Populated dynamically via JS -->
                </div>
            </div>

            <div class="p-3.5 rounded-xl bg-blue-50/70 border border-blue-200/80 text-blue-900 text-xs flex items-start gap-2.5">
                <svg class="w-4 h-4 text-[#0047BA] shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <p class="leading-relaxed">
                    Stiker ini dicetak sesuai format standar <strong>ANDRITZ</strong>. Komponen yang tersimpan di pallet ini akan dapat dicari di menu <strong>Cari Komponen di Pallet</strong>.
                </p>
            </div>

        </div>

    </div>

</div>

@push('scripts')
<script>
    const materialPresets = @json($materialPresets);
    const usedPalletsBySite = @json($usedPalletsBySite);
    let currentSite = "{{ old('site', $selectedSite) }}";
    let currentCategory = "{{ old('category', $selectedCategory) }}";
    let currentPallet = parseInt("{{ old('pallet_number', $selectedPallet) }}", 10);
    let componentCounter = 1;

    const siteCodeMap = {
        'OKI II': 'OKI2',
        'IKPD': 'IKPD',
        'IKPP': 'IKPP',
        'TELL': 'TELL',
        'ISC': 'ISC'
    };

    function calculateCode(site, category, num) {
        const sCode = siteCodeMap[site] || site.replace(/[^A-Za-z0-9]/g, '');
        const cCode = 'CON';
        const nCode = String(num).padStart(3, '0');
        return `PLT-${sCode}-${cCode}-${nCode}`;
    }

    function checkPalletAvailability() {
        const usedList = usedPalletsBySite[currentSite] || [];
        const isUsed = usedList.includes(currentPallet);

        document.querySelectorAll('.activePalletDisplay').forEach(el => el.textContent = currentPallet);
        document.querySelectorAll('.activeSiteDisplay').forEach(el => el.textContent = currentSite);

        const availableBadge = document.getElementById('palletAvailableBadge');
        const usedWarning = document.getElementById('palletUsedWarning');
        const numInput = document.getElementById('pallet_number');
        const btnSave = document.getElementById('btnSubmitSave');
        const btnPrint = document.getElementById('btnSubmitPrint');

        if (isUsed) {
            availableBadge.classList.add('hidden');
            usedWarning.classList.remove('hidden');
            numInput.classList.add('border-rose-500', 'bg-rose-50', 'text-rose-900', 'ring-2', 'ring-rose-200');
            numInput.classList.remove('border-slate-200');
            if (btnSave) btnSave.disabled = true;
            if (btnPrint) btnPrint.disabled = true;
        } else {
            availableBadge.classList.remove('hidden');
            usedWarning.classList.add('hidden');
            numInput.classList.remove('border-rose-500', 'bg-rose-50', 'text-rose-900', 'ring-2', 'ring-rose-200');
            numInput.classList.add('border-slate-200');
            if (btnSave) btnSave.disabled = false;
            if (btnPrint) btnPrint.disabled = false;
        }

        updateThermalPreview();
    }

    function autoSelectNextPallet() {
        const usedList = usedPalletsBySite[currentSite] || [];
        for (let i = 1; i <= 500; i++) {
            if (!usedList.includes(i)) {
                setPalletNumber(i);
                break;
            }
        }
    }

    function updateThermalPreview() {
        const catCode = 'I-COS';
        const catTitle = (currentCategory === 'Dressing') ? 'Dressing Material' : 'Consumable Material';

        const numEl = document.getElementById('previewPalletNumber');
        if (numEl) numEl.textContent = currentPallet;

        const codeEl = document.getElementById('previewCategoryCode');
        if (codeEl) codeEl.textContent = catCode;

        const titleEl = document.getElementById('previewCategoryTitle');
        if (titleEl) titleEl.textContent = catTitle;

        const code = calculateCode(currentSite, currentCategory, currentPallet);
        const codePreview = document.getElementById('palletCodePreviewText');
        if (codePreview) codePreview.textContent = code;

        refreshLivePreview();
    }

    function onSiteChange(site) {
        currentSite = site;
        // Update site radio visual
        document.querySelectorAll('.site-radio').forEach(radio => {
            const tile = radio.closest('label').querySelector('.site-tile');
            if (radio.value === site) {
                tile.className = 'site-tile p-3 rounded-xl border text-center transition-all flex flex-col items-center justify-center gap-1 bg-blue-50/80 border-blue-500 text-blue-800 ring-2 ring-blue-500/20 shadow-xs';
            } else {
                tile.className = 'site-tile p-3 rounded-xl border text-center transition-all flex flex-col items-center justify-center gap-1 bg-slate-50/50 hover:bg-slate-100/80 border-slate-200 text-slate-700';
            }
        });

        // Automatically pick next available pallet number for new site if current is used
        const usedList = usedPalletsBySite[site] || [];
        if (usedList.includes(currentPallet)) {
            autoSelectNextPallet();
        } else {
            checkPalletAvailability();
        }
    }

    function onCategoryChange(cat) {
        currentCategory = cat;
        // Update category visual
        document.querySelectorAll('.cat-radio').forEach(radio => {
            const tile = radio.closest('label').querySelector('.cat-tile');
            if (radio.value === cat) {
                if (cat === 'Dressing') {
                    tile.className = 'cat-tile p-3.5 rounded-xl border transition-all flex items-center gap-3 bg-indigo-50 border-indigo-500 text-indigo-900 ring-2 ring-indigo-500/20 shadow-xs';
                } else {
                    tile.className = 'cat-tile p-3.5 rounded-xl border transition-all flex items-center gap-3 bg-amber-50 border-amber-500 text-amber-900 ring-2 ring-amber-500/20 shadow-xs';
                }
            } else {
                tile.className = 'cat-tile p-3.5 rounded-xl border transition-all flex items-center gap-3 bg-slate-50/50 hover:bg-slate-100 border-slate-200 text-slate-700';
            }
        });

        // Update presets chips
        const container = document.getElementById('presetChipsContainer');
        container.innerHTML = '';
        const presets = materialPresets[cat] || [];
        presets.forEach(p => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'px-2.5 py-1 rounded-lg bg-white hover:bg-blue-50 hover:text-blue-700 hover:border-blue-300 border border-slate-200 text-slate-700 text-[11px] font-medium transition shadow-2xs';
            btn.textContent = `+ ${p}`;
            btn.onclick = () => addPresetAsComponent(p);
            container.appendChild(btn);
        });

        // Add popular Knife Run button for Dressing
        if (cat === 'Dressing') {
            const krBtn = document.createElement('button');
            krBtn.type = 'button';
            krBtn.className = 'px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-800 border border-blue-200 text-[11px] font-bold transition shadow-2xs';
            krBtn.textContent = '+ Knife Run';
            krBtn.onclick = () => addPresetAsComponent('Knife Run');
            container.appendChild(krBtn);
        }

        updateThermalPreview();
    }

    function onPalletNumberChange(val) {
        let num = parseInt(val, 10);
        if (isNaN(num)) num = 1;
        if (num < 1) num = 1;
        if (num > 500) num = 500;
        currentPallet = num;
        document.getElementById('palletSlider').value = num;
        checkPalletAvailability();
    }

    function onSliderChange(val) {
        let num = parseInt(val, 10);
        currentPallet = num;
        document.getElementById('pallet_number').value = num;
        checkPalletAvailability();
    }

    function adjustPalletNumber(delta) {
        let input = document.getElementById('pallet_number');
        let num = (parseInt(input.value, 10) || 1) + delta;
        if (num < 1) num = 1;
        if (num > 500) num = 500;
        input.value = num;
        onPalletNumberChange(num);
    }

    function setPalletNumber(num) {
        document.getElementById('pallet_number').value = num;
        document.getElementById('palletSlider').value = num;
        onPalletNumberChange(num);
    }

    function generateTodayBatch() {
        const dateStr = new Date().toISOString().slice(0,10).replace(/-/g, '');
        const pad = String(currentPallet).padStart(3, '0');
        const batch = `BATCH-${dateStr}-${pad}`;
        document.getElementById('batch_no').value = batch;
    }

    /* ----------------------------------------------------
     * Dynamic Component Management Functions
     * ---------------------------------------------------- */
    function addComponentRow(name = '', qty = '1 UNIT', batch = '', notes = '') {
        const container = document.getElementById('componentsContainer');
        const idx = container.querySelectorAll('.component-row').length;

        const row = document.createElement('div');
        row.className = 'component-row p-3.5 bg-white border border-slate-200 rounded-xl shadow-xs space-y-2.5 transition relative';
        row.innerHTML = `
            <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    <span class="row-label">Komponen #${idx + 1}</span>
                </span>
                <button type="button" 
                        onclick="removeComponentRow(this)" 
                        class="text-rose-500 hover:text-rose-700 text-xs font-semibold remove-btn">
                    &times; Hapus
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5">
                <div class="sm:col-span-7">
                    <label class="text-[10px] font-bold text-slate-500 block mb-1">Nama Komponen / Material *</label>
                    <input type="text" 
                           name="components[${idx}][component_name]" 
                           value="${name}" 
                           required
                           oninput="refreshLivePreview()"
                           placeholder="Contoh: Knife Run, Roll Dressing, Diamond Stone..." 
                           class="w-full px-3 py-2 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition component-name-input">
                </div>

                <div class="sm:col-span-5">
                    <label class="text-[10px] font-bold text-slate-500 block mb-1">Jumlah / Qty</label>
                    <input type="text" 
                           name="components[${idx}][quantity]" 
                           value="${qty}" 
                           oninput="refreshLivePreview()"
                           placeholder="Contoh: 10 PCS, 2 UNIT" 
                           class="w-full px-3 py-2 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition component-qty-input">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5">
                <div class="sm:col-span-6">
                    <input type="text" 
                           name="components[${idx}][batch_no]" 
                           value="${batch}" 
                           placeholder="No. Batch komponen (opsional)" 
                           class="w-full px-3 py-1.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-lg text-xs font-mono text-slate-700 focus:outline-none focus:border-blue-600 transition">
                </div>
                <div class="sm:col-span-6">
                    <input type="text" 
                           name="components[${idx}][notes]" 
                           value="${notes}" 
                           placeholder="Catatan / Spesifikasi (opsional)" 
                           class="w-full px-3 py-1.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:border-blue-600 transition">
                </div>
            </div>
        `;

        container.appendChild(row);
        updateRowNumbers();
        refreshLivePreview();

        // Focus the new component input
        const newInp = row.querySelector('.component-name-input');
        if (newInp) newInp.focus();
    }

    function removeComponentRow(btn) {
        const container = document.getElementById('componentsContainer');
        const rows = container.querySelectorAll('.component-row');
        if (rows.length <= 1) return;

        btn.closest('.component-row').remove();
        updateRowNumbers();
        refreshLivePreview();
    }

    function updateRowNumbers() {
        const container = document.getElementById('componentsContainer');
        const rows = container.querySelectorAll('.component-row');

        rows.forEach((row, idx) => {
            row.querySelector('.row-label').textContent = `Komponen #${idx + 1}`;
            const removeBtn = row.querySelector('.remove-btn');
            if (rows.length > 1) {
                removeBtn.classList.remove('hidden');
            } else {
                removeBtn.classList.add('hidden');
            }

            // Update input name attributes
            row.querySelectorAll('input').forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace(/components\[\d+\]/, `components[${idx}]`));
                }
            });
        });

        const badge = document.getElementById('componentCounterBadge');
        if (badge) badge.textContent = `${rows.length} Komponen`;
    }

    function addPresetAsComponent(name) {
        const container = document.getElementById('componentsContainer');
        const rows = container.querySelectorAll('.component-row');
        
        // If first row input is empty, fill it
        if (rows.length === 1) {
            const firstInput = rows[0].querySelector('.component-name-input');
            if (!firstInput.value || firstInput.value.trim() === '') {
                firstInput.value = name;
                refreshLivePreview();
                return;
            }
        }

        // Otherwise add new row
        addComponentRow(name);
    }

    function refreshLivePreview() {
        const container = document.getElementById('componentsContainer');
        const rows = container.querySelectorAll('.component-row');
        const previewList = document.getElementById('previewComponentsList');
        const countText = document.getElementById('previewTotalCountText');

        if (!previewList) return;
        previewList.innerHTML = '';

        let validCount = 0;
        rows.forEach((row, idx) => {
            const name = row.querySelector('.component-name-input')?.value || '';
            const qty = row.querySelector('.component-qty-input')?.value || '1 UNIT';

            if (name.trim() !== '') {
                validCount++;
                const itemDiv = document.createElement('div');
                itemDiv.className = 'flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-200 text-xs';
                itemDiv.innerHTML = `
                    <div class="flex items-center gap-1.5 font-bold text-slate-800 truncate pr-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 shrink-0"></span>
                        <span class="truncate">${name}</span>
                    </div>
                    <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded bg-blue-100 text-blue-800 shrink-0">
                        ${qty}
                    </span>
                `;
                previewList.appendChild(itemDiv);
            }
        });

        if (validCount === 0) {
            previewList.innerHTML = '<p class="text-slate-400 text-xs italic py-1">Belum ada komponen diisi.</p>';
        }

        if (countText) countText.textContent = `${validCount} Komponen`;
    }

    // Initialize state on page load
    document.addEventListener('DOMContentLoaded', () => {
        checkPalletAvailability();
        updateRowNumbers();
        refreshLivePreview();
    });
</script>
@endpush
@endsection
