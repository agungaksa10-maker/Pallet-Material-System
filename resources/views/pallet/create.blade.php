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

    <!-- Main Grid: Form on Left (7 cols), Live Summary on Right (5 cols) -->
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
                            <span>1. Site / Pabrik</span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[11px] text-blue-700 font-mono font-bold bg-blue-50 px-2 py-0.5 rounded-md border border-blue-200">Fasilitas: OKI II</span>
                    </div>

                    <div class="max-w-md">
                        @foreach($sites as $sKey => $sLabel)
                            <label class="cursor-pointer block">
                                <input type="radio" 
                                       name="site" 
                                       value="{{ $sKey }}" 
                                       class="sr-only site-radio"
                                       checked
                                       onchange="onSiteChange('{{ $sKey }}')">
                                <div class="site-tile p-3.5 rounded-xl border transition-all flex items-center gap-3 bg-blue-50/80 border-blue-500 text-blue-800 ring-2 ring-blue-500/20 shadow-xs">
                                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black text-xs shadow-xs shrink-0 tracking-wider">
                                        OKI
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <span class="font-extrabold text-sm tracking-tight text-blue-900">{{ $sKey }}</span>
                                            <span class="text-[10px] uppercase font-bold text-blue-700 bg-blue-100/80 px-2 py-0.5 rounded-md">Aktif</span>
                                        </div>
                                        <span class="text-[11px] text-slate-600 truncate block mt-0.5">{{ $sLabel }}</span>
                                    </div>
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

                <!-- 4. DAFTAR MATERIAL / SPARE PART PALLET (INPUT DATA ASLI) -->
                <div class="space-y-4 p-4.5 rounded-2xl bg-slate-50/90 border border-slate-200/90 shadow-xs">
                    
                    <!-- Header Section -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-2 border-b border-slate-200">
                        <div>
                            <label class="text-xs font-black uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                <span>DAFTAR MATERIAL / SPARE PART PALLET (INPUT DATA ASLI)</span>
                                <span class="text-rose-500">*</span>
                            </label>
                            <p class="text-[11px] text-slate-500 mt-0.5">
                                Masukkan data asli material / spare part yang dimuat di pallet ini (bisa lebih dari 1 item).
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-mono font-bold px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200" id="componentCountBadge">
                                0 Item Ditambahkan
                            </span>
                        </div>
                    </div>

                    <!-- Card Form Input Data Asli -->
                    <div class="p-3.5 bg-white border border-blue-200 rounded-xl space-y-3 shadow-2xs">
                        <div class="flex items-center justify-between text-xs font-bold text-slate-800">
                            <span class="flex items-center gap-1.5 text-blue-900 font-extrabold">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                                <span>Tambah Material / Spare Part Asli</span>
                            </span>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('master-materials.index') }}" target="_blank" class="text-[11px] text-blue-600 hover:text-blue-800 font-bold flex items-center gap-1 hover:underline">
                                    <span>⚙️ Kelola Master Data</span>
                                </a>
                            </div>
                        </div>

                        <!-- Quick Search & Select from Master Data (Modern Interactive Combobox) -->
                        <div class="relative bg-gradient-to-r from-blue-50/90 via-sky-50/40 to-indigo-50/40 rounded-xl p-3 border border-blue-200/90 shadow-2xs space-y-2.5">
                            
                            <!-- Top Bar: Title -->
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-blue-600 text-white flex items-center justify-center shadow-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </span>
                                <div>
                                    <span class="text-xs font-black text-slate-900 leading-tight">Cari Material dari Master Data</span>
                                    <span class="text-[10px] text-slate-500 font-semibold ml-1.5 hidden sm:inline">({{ count($masterMaterials ?? []) }} item terdaftar)</span>
                                </div>
                            </div>

                            <!-- Live Instant Search Input & Dropdown -->
                            <div class="relative" id="masterSearchWrapper">
                                <div class="relative flex items-center">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>

                                    <input type="text" 
                                           id="masterQuickSearchInput" 
                                           autocomplete="off"
                                           onfocus="openMasterDropdown()" 
                                           oninput="filterMasterMaterials(this.value)" 
                                           onkeydown="handleMasterSearchKeydown(event)"
                                           placeholder="Ketik Kode Part atau Nama Material (misal: 3009, Knife, TK IV, Roll, Blade)..." 
                                           class="w-full pl-9 pr-24 py-2.5 bg-white border border-blue-300 rounded-xl text-xs font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-100 shadow-xs transition">

                                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center gap-1">
                                        <button type="button" 
                                                id="masterSearchClearBtn" 
                                                onclick="clearMasterQuickSearch()" 
                                                class="hidden p-1 text-slate-400 hover:text-slate-600 rounded-md transition"
                                                title="Bersihkan pencarian">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                        <button type="button" 
                                                onclick="toggleMasterDropdown()" 
                                                class="px-2 py-1 text-[11px] font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition flex items-center gap-1 cursor-pointer">
                                            <span>Pilih</span>
                                            <svg class="w-3 h-3 transition-transform duration-200" id="masterChevronIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Floating Live Results Dropdown Container -->
                                <div id="masterDropdownMenu" 
                                     class="hidden absolute left-0 right-0 top-full mt-1.5 z-40 bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden animate-in fade-in zoom-in-95 duration-100">
                                    
                                    <!-- Category Quick Filter Chips inside Dropdown -->
                                    <div class="px-3 py-2 bg-slate-50 border-b border-slate-100 flex items-center justify-between text-[11px]">
                                        <div class="flex items-center gap-1">
                                            <span class="text-slate-500 font-bold text-[10px] uppercase tracking-wider mr-1">Filter:</span>
                                            <button type="button" onclick="setMasterDropdownCategory('ALL')" id="mChip_ALL" class="px-2 py-0.5 rounded-md font-bold text-[10px] bg-blue-600 text-white shadow-2xs">Semua (<span id="mCount_ALL">{{ count($masterMaterials ?? []) }}</span>)</button>
                                            <button type="button" onclick="setMasterDropdownCategory('Dressing')" id="mChip_Dressing" class="px-2 py-0.5 rounded-md font-semibold text-[10px] bg-white border border-slate-200 text-slate-700 hover:bg-slate-100">Dressing</button>
                                            <button type="button" onclick="setMasterDropdownCategory('Consumable')" id="mChip_Consumable" class="px-2 py-0.5 rounded-md font-semibold text-[10px] bg-white border border-slate-200 text-slate-700 hover:bg-slate-100">Consumable</button>
                                        </div>
                                        <span id="masterResultsCountText" class="text-[10px] text-slate-400 font-mono">{{ count($masterMaterials ?? []) }} item</span>
                                    </div>

                                    <!-- Scrollable Results List -->
                                    <div id="masterDropdownList" class="max-h-64 overflow-y-auto divide-y divide-slate-100 text-xs">
                                        <!-- Populated dynamically via JS -->
                                    </div>

                                    <!-- Dropdown Footer Info -->
                                    <div class="px-3 py-1.5 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between text-[10.5px] text-slate-500">
                                        <span class="flex items-center gap-1">
                                            <kbd class="px-1 py-0.2 bg-slate-200 rounded text-[9px] font-mono">&uarr;&darr;</kbd> Navigasi &bull; <kbd class="px-1 py-0.2 bg-slate-200 rounded text-[9px] font-mono">Enter</kbd> Pilih
                                        </span>
                                        <button type="button" onclick="closeMasterDropdown()" class="hover:text-slate-800 font-semibold text-[10px]">Tutup [Esc]</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Selected Material Pill / Info Bar -->
                            <div id="selectedMaterialPreviewBar" class="hidden p-2 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between gap-2 text-xs">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0 text-[11px] font-bold">✓</span>
                                    <div class="truncate">
                                        <span class="text-emerald-950 font-extrabold" id="selectedMatNameText">-</span>
                                        <span class="text-emerald-700 font-mono text-[11px] ml-1" id="selectedMatCodeText"></span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="text-[10px] text-emerald-800 font-semibold bg-emerald-100 px-2 py-0.5 rounded-md">Terpilih ke form</span>
                                    <button type="button" onclick="resetSelectedMaterialBar()" class="text-emerald-700 hover:text-rose-600 p-1" title="Batalkan pilihan master">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Hidden fallback select for backward-compatibility -->
                            <select id="masterMaterialSelect" class="hidden">
                                <option value="">--</option>
                                @foreach(($masterMaterials ?? []) as $mm)
                                    <option value="{{ $mm->id }}">
                                        {{ $mm->item_code ? '['.$mm->item_code.'] ' : '' }}{{ $mm->name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>

                        <!-- Form Input Komponen Material (2 Baris Rapi & Proporsional) -->
                        <div class="p-3.5 bg-slate-50/75 border border-slate-200/80 rounded-xl space-y-3">
                            <!-- Baris 1: ID Material & Nama Material (Lega & Leluasa) -->
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                                <!-- ID Material -->
                                <div class="sm:col-span-4">
                                    <label class="text-[11px] font-bold text-slate-700 block mb-1">ID Material / Kode Part</label>
                                    <input type="text" 
                                           id="newPartCode" 
                                           oninput="onPartCodeInput(this.value)"
                                           placeholder="Misal: 300944956" 
                                           class="w-full h-10 px-3 bg-white hover:border-slate-300 focus:bg-white border border-slate-200 rounded-lg text-xs font-mono font-bold text-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                                </div>

                                <!-- Nama Material / Spare Part (Required) -->
                                <div class="sm:col-span-8">
                                    <label class="text-[11px] font-bold text-slate-700 block mb-1">Nama Material / Sparepart <span class="text-rose-500">*</span></label>
                                    <input type="text" 
                                           id="newPartName" 
                                           list="masterMaterialDatalist"
                                           oninput="onPartNameInput(this.value)"
                                           onkeydown="if(event.key === 'Enter'){ event.preventDefault(); addRealMaterialItem(); }"
                                           placeholder="Ketik atau pilih nama material / sparepart..." 
                                           class="w-full h-10 px-3 bg-white hover:border-slate-300 focus:bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                                    <datalist id="masterMaterialDatalist">
                                        @foreach(($masterMaterials ?? []) as $mm)
                                            <option value="{{ $mm->name }}">{{ $mm->item_code ? 'Kode: '.$mm->item_code.' | ' : '' }}{{ $mm->default_unit }}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>

                            <!-- Baris 2: Qty, No. Batch, Kolom Rak, Tingkat Rak, & Tombol Tambah (Sejajar Sempurna) -->
                            <div class="grid grid-cols-2 sm:grid-cols-12 gap-2.5 items-end">
                                <!-- Jumlah / Qty -->
                                <div class="col-span-1 sm:col-span-2">
                                    <label class="text-[11px] font-bold text-slate-700 block mb-1 whitespace-nowrap">Jumlah (Qty) <span class="text-rose-500">*</span></label>
                                    <input type="text" 
                                           id="newPartQty" 
                                           value="1"
                                           onkeydown="if(event.key === 'Enter'){ event.preventDefault(); addRealMaterialItem(); }"
                                           placeholder="Misal: 1" 
                                           class="w-full h-10 px-2 bg-white hover:border-slate-300 focus:bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-800 text-center focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                                </div>

                                <!-- No. Batch -->
                                <div class="col-span-1 sm:col-span-3">
                                    <label class="text-[11px] font-bold text-slate-700 block mb-1 whitespace-nowrap">No. Batch</label>
                                    <input type="text" 
                                           id="newPartBatch" 
                                           placeholder="Opsional" 
                                           class="w-full h-10 px-3 bg-white hover:border-slate-300 focus:bg-white border border-slate-200 rounded-lg text-xs font-mono text-slate-700 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                                </div>

                                <!-- Kolom (A sampai Z) -->
                                <div class="col-span-1 sm:col-span-2">
                                    <label class="text-[11px] font-bold text-slate-700 block mb-1 whitespace-nowrap" title="Kolom Rak (A sampai Z)">Kolom Rak</label>
                                    <select id="newPartKolom" 
                                            class="w-full h-10 px-2 bg-white hover:border-slate-300 focus:bg-white border border-slate-200 rounded-lg text-xs font-extrabold text-blue-700 text-center focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition cursor-pointer">
                                        <option value="">- Pilih -</option>
                                        @foreach(range('A', 'Z') as $char)
                                            <option value="{{ $char }}">{{ $char }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tingkat (1 sampai 5) -->
                                <div class="col-span-1 sm:col-span-2">
                                    <label class="text-[11px] font-bold text-slate-700 block mb-1 whitespace-nowrap" title="Tingkat Rak (1 sampai 5)">Tingkat Rak</label>
                                    <select id="newPartTingkat" 
                                            class="w-full h-10 px-2 bg-white hover:border-slate-300 focus:bg-white border border-slate-200 rounded-lg text-xs font-extrabold text-indigo-700 text-center focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition cursor-pointer">
                                        <option value="">- Pilih -</option>
                                        @foreach(range(1, 5) as $lvl)
                                            <option value="{{ $lvl }}">{{ $lvl }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Action Button -->
                                <div class="col-span-2 sm:col-span-3">
                                    <button type="button" 
                                            onclick="addRealMaterialItem()" 
                                            class="w-full h-10 px-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-[11.5px] shadow-xs transition active:scale-98 flex items-center justify-center gap-1 cursor-pointer">
                                        <svg class="w-4 h-4 stroke-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        <span class="whitespace-nowrap">+ Tambah ke Pallet</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Material Yang Ditambahkan (Desain Tabel Bersih Image 2) -->
                    <div class="space-y-2 pt-1">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-black uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>DAFTAR MATERIAL TERPILIH DI PALLET INI (<span id="selectedCountBadge">0 ITEMS</span>)</span>
                            </label>
                            <span class="text-[11px] text-slate-400 font-medium">Bisa menambahkan lebih dari 1 item</span>
                        </div>

                        <div class="border border-slate-200/90 rounded-xl overflow-hidden bg-white shadow-xs">
                            <div class="overflow-x-auto max-h-72 overflow-y-auto">
                                <table class="min-w-full divide-y divide-slate-200 text-left text-xs" id="selectedTable">
                                    <thead class="bg-slate-50 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 sticky top-0 z-10 shadow-xs">
                                        <tr>
                                            <th scope="col" class="py-2.5 px-3 w-10 text-center">NO</th>
                                            <th scope="col" class="py-2.5 px-3 w-28">ID MATERIAL</th>
                                            <th scope="col" class="py-2.5 px-3 min-w-[200px]">Nama Material / Sparepart</th>
                                            <th scope="col" class="py-2.5 px-3 w-24 text-center">Jumlah / Qty</th>
                                            <th scope="col" class="py-2.5 px-3 w-28">No. Batch</th>
                                            <th scope="col" class="py-2.5 px-3 w-32 text-center" title="Kolom A-Z & Tingkat 1-5">Lokasi Rak</th>
                                            <th scope="col" class="py-2.5 px-2 w-14 text-center">Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white" id="selectedTableBody">
                                        <!-- Populated dynamically via JS -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Empty State (Tanpa Data Dummy) -->
                            <div id="selectedEmptyState" class="py-10 text-center text-slate-400 space-y-1.5 bg-slate-50/50">
                                <div class="text-sm font-semibold flex items-center justify-center gap-2 text-slate-600">
                                    <span class="text-2xl">📦</span>
                                    <span>Belum ada material / spare part yang diinput</span>
                                </div>
                                <p class="text-xs text-slate-400 max-w-md mx-auto">
                                    Silakan masukkan data asli material pada form di atas lalu klik <strong>"Tambah ke Pallet"</strong>.
                                </p>
                            </div>

                            <!-- Summary Footer Bar Sesuai Image 2 -->
                            <div class="px-4 py-2.5 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between text-xs font-bold text-slate-700 gap-2">
                                <div class="flex items-center gap-2">
                                    <span>Total: <span id="summaryTypesCount" class="text-blue-700 font-extrabold">0</span> Jenis Material</span>
                                    <span class="text-slate-300">|</span>
                                    <span>Total Unit: <span id="summaryUnitsCount" class="text-emerald-700 font-extrabold">0</span></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="clearAllSelected()" class="text-[11px] text-rose-600 hover:text-rose-800 hover:underline font-bold transition">
                                        Kosongkan Semua
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Inputs for Form Submission -->
                    <div id="hiddenFormComponents"></div>

                </div>

                <!-- 5. Lokasi Rak & Keterangan Pallet -->
                <div class="space-y-2.5 p-4 rounded-2xl bg-slate-50/90 border border-slate-200/90 shadow-2xs">
                    <label class="text-xs font-extrabold text-slate-800 flex items-center justify-between">
                        <span class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            <span>Lokasi Rak Pallet &amp; Keterangan</span>
                        </span>
                        <span class="text-[11px] text-slate-400 font-semibold">(Opsional)</span>
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5">
                        <div class="sm:col-span-3">
                            <label for="pallet_kolom" class="text-[10px] font-bold text-slate-600 block mb-1">Kolom Rak (A - Z)</label>
                            <select name="kolom" id="pallet_kolom" class="w-full px-2.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-extrabold text-blue-700 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition cursor-pointer">
                                <option value="">-- Pilih Kolom --</option>
                                @foreach(range('A', 'Z') as $char)
                                    <option value="{{ $char }}" {{ old('kolom') === $char ? 'selected' : '' }}>Kolom {{ $char }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-3">
                            <label for="pallet_tingkat" class="text-[10px] font-bold text-slate-600 block mb-1">Tingkat Rak (1 - 5)</label>
                            <select name="tingkat" id="pallet_tingkat" class="w-full px-2.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-extrabold text-indigo-700 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition cursor-pointer">
                                <option value="">-- Pilih Tingkat --</option>
                                @foreach(range(1, 5) as $lvl)
                                    <option value="{{ $lvl }}" {{ old('tingkat') == $lvl ? 'selected' : '' }}>Tingkat {{ $lvl }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-6">
                            <label for="notes" class="text-[10px] font-bold text-slate-600 block mb-1">Catatan / Keterangan Pallet</label>
                            <input type="text" 
                                   name="notes" 
                                   id="notes" 
                                   value="{{ old('notes') }}"
                                   placeholder="Contoh: Rak Utama, Line Produksi 3..."
                                   class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition shadow-xs">
                        </div>
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
        <!-- LIVE SUMMARY & KOMPONEN (5 COLUMNS)                      -->
        <!-- ======================================================== -->
        <div class="lg:col-span-5 space-y-4 lg:sticky lg:top-20">
            
            <div class="flex items-center justify-between px-1">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#0047BA] animate-pulse"></span>
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Ringkasan Pallet &amp; Komponen</h3>
                </div>
                <span class="text-[11px] font-mono text-slate-500">ANDRITZ Standard</span>
            </div>

            <!-- Pallet Quick Info Card (Clean card without huge sticker) -->
            <div class="p-5 bg-gradient-to-br from-blue-900 to-indigo-950 text-white rounded-2xl shadow-md space-y-3.5 border border-blue-800">
                <div class="flex items-center justify-between border-b border-blue-800/80 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-blue-600/40 flex items-center justify-center font-extrabold text-white text-xs border border-blue-400/30">
                            PLT
                        </div>
                        <div>
                            <span class="text-[10px] text-blue-300 font-bold uppercase tracking-wider block">Target Pallet</span>
                            <span class="font-mono font-extrabold text-base text-white tracking-tight" id="palletCodeSummary">
                                PLT-{{ $selectedSite === 'OKI II' ? 'OKI2' : $selectedSite }}-CON-{{ str_pad($selectedPallet, 3, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-mono font-bold bg-blue-500/30 text-blue-200 border border-blue-400/30">
                        Pallet #<span class="activePalletDisplay">{{ $selectedPallet }}</span>
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-2.5 text-xs">
                    <div class="bg-blue-950/60 p-2.5 rounded-xl border border-blue-800/60">
                        <span class="text-[10px] text-blue-300 block mb-0.5">Site / Pabrik:</span>
                        <span class="font-bold text-white activeSiteDisplay">{{ $selectedSite }}</span>
                    </div>
                    <div class="bg-blue-950/60 p-2.5 rounded-xl border border-blue-800/60">
                        <span class="text-[10px] text-blue-300 block mb-0.5">Kategori:</span>
                        <span class="font-bold text-white" id="categorySummaryText">{{ $selectedCategory }} Material</span>
                    </div>
                </div>
            </div>

            <!-- Live Components Inside Pallet Card -->
            <div class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-xs space-y-2.5">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span>Daftar Komponen di Pallet Ini</span>
                    </span>
                    <span class="font-mono font-bold text-slate-500 text-[11px]" id="previewTotalCountText">0 Items</span>
                </div>

                <div id="previewComponentsList" class="space-y-1.5 max-h-56 overflow-y-auto pr-1">
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
    const catalogData = @json($catalog);
    const masterMaterialsData = @json($masterMaterials ?? []);

    /* ------------------------------------------------------------------
     * Modern Master Material Search & Combobox Functionality
     * ------------------------------------------------------------------ */
    let currentMasterDropdownCategory = 'ALL';
    let currentMasterResults = [...masterMaterialsData];
    let highlightedMasterIndex = -1;

    function renderMasterDropdownItems() {
        const list = document.getElementById('masterDropdownList');
        if (!list) return;

        if (currentMasterResults.length === 0) {
            list.innerHTML = `
                <div class="p-4 text-center text-slate-500 text-xs">
                    <p class="font-bold text-slate-700">Material Tidak Ditemukan</p>
                    <p class="text-[11px] text-slate-400 mt-1">Coba kata kunci lain atau ketik langsung di form input bawah.</p>
                </div>
            `;
            return;
        }

        let html = '';
        currentMasterResults.forEach((mm, idx) => {
            const isHighlighted = (idx === highlightedMasterIndex);
            const categoryBadge = (mm.category === 'Dressing')
                ? '<span class="px-1.5 py-0.5 rounded text-[9.5px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">Dressing</span>'
                : (mm.category === 'Consumable' ? '<span class="px-1.5 py-0.5 rounded text-[9.5px] font-bold bg-amber-50 text-amber-700 border border-amber-200">Consumable</span>' : '');

            const codeBadge = mm.item_code 
                ? `<span class="font-mono text-[10px] font-bold text-blue-700 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-200 shrink-0">[${escapeHtml(mm.item_code)}]</span>`
                : '';

            const unitBadge = mm.default_unit
                ? `<span class="text-[10px] font-mono text-slate-500">Satuan: <strong>${escapeHtml(mm.default_unit)}</strong></span>`
                : '';

            const specPreview = mm.specification
                ? `<span class="text-[10px] text-slate-400 italic truncate ml-1 max-w-[200px] inline-block align-bottom">&bull; ${escapeHtml(mm.specification)}</span>`
                : '';

            html += `
                <div class="master-dropdown-item px-3 py-2.5 hover:bg-blue-50/80 cursor-pointer flex items-center justify-between gap-2 transition ${isHighlighted ? 'bg-blue-100/70' : ''}"
                     data-id="${mm.id}"
                     onclick="selectMasterItem(${mm.id})">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            ${codeBadge}
                            <span class="font-bold text-slate-900 text-xs">${escapeHtml(mm.name)}</span>
                            ${categoryBadge}
                        </div>
                        <div class="flex items-center gap-1 mt-0.5 text-slate-500">
                            ${unitBadge}
                            ${specPreview}
                        </div>
                    </div>
                    <button type="button" 
                            class="px-2.5 py-1 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-[10.5px] shadow-2xs shrink-0 transition"
                            onclick="event.stopPropagation(); selectMasterItem(${mm.id});">
                        Pilih
                    </button>
                </div>
            `;
        });

        list.innerHTML = html;
    }

    function openMasterDropdown() {
        const menu = document.getElementById('masterDropdownMenu');
        const icon = document.getElementById('masterChevronIcon');
        if (menu) menu.classList.remove('hidden');
        if (icon) icon.classList.add('rotate-180');
        renderMasterDropdownItems();
    }

    function closeMasterDropdown() {
        const menu = document.getElementById('masterDropdownMenu');
        const icon = document.getElementById('masterChevronIcon');
        if (menu) menu.classList.add('hidden');
        if (icon) icon.classList.remove('rotate-180');
        highlightedMasterIndex = -1;
    }

    function toggleMasterDropdown() {
        const menu = document.getElementById('masterDropdownMenu');
        if (menu && menu.classList.contains('hidden')) {
            openMasterDropdown();
            document.getElementById('masterQuickSearchInput')?.focus();
        } else {
            closeMasterDropdown();
        }
    }

    function filterMasterMaterials(val) {
        const query = (val || '').trim().toLowerCase();
        const clearBtn = document.getElementById('masterSearchClearBtn');
        if (clearBtn) {
            if (query.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }
        }

        currentMasterResults = masterMaterialsData.filter(m => {
            const matchCat = (currentMasterDropdownCategory === 'ALL' || m.category === currentMasterDropdownCategory);
            if (!matchCat) return false;
            if (!query) return true;

            const name = (m.name || '').toLowerCase();
            const code = (m.item_code || '').toLowerCase();
            const spec = (m.specification || '').toLowerCase();
            const cat = (m.category || '').toLowerCase();

            return name.includes(query) || code.includes(query) || spec.includes(query) || cat.includes(query);
        });

        const countEl = document.getElementById('masterResultsCountText');
        if (countEl) countEl.textContent = `${currentMasterResults.length} hasil`;

        highlightedMasterIndex = -1;
        openMasterDropdown();
    }

    function setMasterDropdownCategory(cat) {
        currentMasterDropdownCategory = cat;
        ['ALL', 'Dressing', 'Consumable'].forEach(c => {
            const chip = document.getElementById(`mChip_${c}`);
            if (chip) {
                if (c === cat) {
                    chip.className = 'px-2 py-0.5 rounded-md font-bold text-[10px] bg-blue-600 text-white shadow-2xs';
                } else {
                    chip.className = 'px-2 py-0.5 rounded-md font-semibold text-[10px] bg-white border border-slate-200 text-slate-700 hover:bg-slate-100';
                }
            }
        });

        const searchInp = document.getElementById('masterQuickSearchInput');
        filterMasterMaterials(searchInp ? searchInp.value : '');
    }

    function clearMasterQuickSearch() {
        const searchInp = document.getElementById('masterQuickSearchInput');
        if (searchInp) {
            searchInp.value = '';
            searchInp.focus();
        }
        filterMasterMaterials('');
    }

    function selectMasterItem(id) {
        if (!id) return;
        const mm = masterMaterialsData.find(m => String(m.id) === String(id));
        if (mm) {
            const codeInp = document.getElementById('newPartCode');
            const nameInp = document.getElementById('newPartName');
            const qtyInp = document.getElementById('newPartQty');
            const notesEl = document.getElementById('newPartNotes');

            if (codeInp) codeInp.value = mm.item_code || '';
            if (nameInp) nameInp.value = mm.name || '';
            if (qtyInp) {
                qtyInp.value = '1';
                qtyInp.focus();
                qtyInp.select();
            }
            if (notesEl && mm.specification) {
                notesEl.value = mm.specification;
            }

            // Sync hidden select
            const sel = document.getElementById('masterMaterialSelect');
            if (sel) sel.value = mm.id;

            // Show selected preview bar
            const prevBar = document.getElementById('selectedMaterialPreviewBar');
            const nameTxt = document.getElementById('selectedMatNameText');
            const codeTxt = document.getElementById('selectedMatCodeText');

            if (prevBar && nameTxt && codeTxt) {
                nameTxt.textContent = mm.name;
                codeTxt.textContent = mm.item_code ? `[${mm.item_code}]` : '';
                prevBar.classList.remove('hidden');
            }

            // Update search input to reflect selection
            const searchInp = document.getElementById('masterQuickSearchInput');
            if (searchInp) {
                searchInp.value = `${mm.item_code ? '[' + mm.item_code + '] ' : ''}${mm.name}`;
                document.getElementById('masterSearchClearBtn')?.classList.remove('hidden');
            }

            closeMasterDropdown();
        }
    }

    function resetSelectedMaterialBar() {
        const prevBar = document.getElementById('selectedMaterialPreviewBar');
        if (prevBar) prevBar.classList.add('hidden');

        const searchInp = document.getElementById('masterQuickSearchInput');
        if (searchInp) searchInp.value = '';
        document.getElementById('masterSearchClearBtn')?.classList.add('hidden');

        const codeInp = document.getElementById('newPartCode');
        const nameInp = document.getElementById('newPartName');
        const notesEl = document.getElementById('newPartNotes');
        if (codeInp) codeInp.value = '';
        if (nameInp) nameInp.value = '';
        if (notesEl) notesEl.value = '';

        const sel = document.getElementById('masterMaterialSelect');
        if (sel) sel.value = '';

        filterMasterMaterials('');
    }

    function handleMasterSearchKeydown(e) {
        const menu = document.getElementById('masterDropdownMenu');
        if (menu && menu.classList.contains('hidden')) {
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                openMasterDropdown();
                e.preventDefault();
                return;
            }
        }

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (highlightedMasterIndex < currentMasterResults.length - 1) {
                highlightedMasterIndex++;
                renderMasterDropdownItems();
                scrollHighlightedItemIntoView();
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (highlightedMasterIndex > 0) {
                highlightedMasterIndex--;
                renderMasterDropdownItems();
                scrollHighlightedItemIntoView();
            }
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (highlightedMasterIndex >= 0 && highlightedMasterIndex < currentMasterResults.length) {
                selectMasterItem(currentMasterResults[highlightedMasterIndex].id);
            } else if (currentMasterResults.length === 1) {
                selectMasterItem(currentMasterResults[0].id);
            }
        } else if (e.key === 'Escape') {
            closeMasterDropdown();
        }
    }

    function scrollHighlightedItemIntoView() {
        const list = document.getElementById('masterDropdownList');
        if (!list) return;
        const items = list.querySelectorAll('.master-dropdown-item');
        if (items[highlightedMasterIndex]) {
            items[highlightedMasterIndex].scrollIntoView({ block: 'nearest' });
        }
    }

    function onPartCodeInput(val) {
        if (!val || val.trim() === '') return;
        const cleanVal = val.trim().toLowerCase();
        const found = masterMaterialsData.find(m => (m.item_code || '').toLowerCase() === cleanVal);
        if (found) {
            selectMasterItem(found.id);
        }
    }

    function onSelectMasterMaterial(id) {
        selectMasterItem(id);
    }

    function onPartNameInput(val) {
        if (!val || val.trim() === '') return;
        const found = masterMaterialsData.find(m => m.name.toLowerCase() === val.trim().toLowerCase());
        if (found) {
            selectMasterItem(found.id);
        }
    }

    // Close dropdown on click outside
    document.addEventListener('click', function(e) {
        const wrapper = document.getElementById('masterSearchWrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            closeMasterDropdown();
        }
    });

    let currentSite = "{{ old('site', $selectedSite) }}";
    let currentCategory = "{{ old('category', $selectedCategory) }}";
    let currentPallet = parseInt("{{ old('pallet_number', $selectedPallet) }}", 10);
    
    // Multi-Item Real State
    let selectedItems = [];

    // Check for old form inputs on validation error
    @php
        $oldComponentsData = old('components', []);
    @endphp
    const initialOldComponents = @json($oldComponentsData);
    if (Array.isArray(initialOldComponents) && initialOldComponents.length > 0) {
        initialOldComponents.forEach((comp, idx) => {
            if (comp.component_name && comp.component_name.trim() !== '') {
                let code = '';
                let name = comp.component_name.trim();
                const dashIdx = name.indexOf(' - ');
                if (dashIdx > 0 && dashIdx <= 25) {
                    code = name.substring(0, dashIdx).trim();
                    name = name.substring(dashIdx + 3).trim();
                }
                selectedItems.push({
                    code: code,
                    name: name,
                    qty: comp.quantity || '1',
                    batch: comp.batch_no || '',
                    kolom: comp.kolom || '',
                    tingkat: comp.tingkat || '',
                    notes: comp.notes || ''
                });
            }
        });
    }

    const siteCodeMap = {
        'OKI II': 'OKI2'
    };

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

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

        const codeSummary = document.getElementById('palletCodeSummary');
        if (codeSummary) codeSummary.textContent = code;

        const catSummary = document.getElementById('categorySummaryText');
        if (catSummary) catSummary.textContent = (currentCategory === 'Dressing') ? 'Dressing Material' : 'Consumable Material';

        refreshLivePreview();
    }

    function onSiteChange(site) {
        currentSite = site;
        const siteDisplay = document.getElementById('catalogActiveSiteDisplay');
        if (siteDisplay) siteDisplay.textContent = site;

        // Update site radio visual
        document.querySelectorAll('.site-radio').forEach(radio => {
            const tile = radio.closest('label').querySelector('.site-tile');
            if (radio.value === site) {
                tile.className = 'site-tile p-3 rounded-xl border text-center transition-all flex flex-col items-center justify-center gap-1 bg-blue-50/80 border-blue-500 text-blue-800 ring-2 ring-blue-500/20 shadow-xs';
            } else {
                tile.className = 'site-tile p-3 rounded-xl border text-center transition-all flex flex-col items-center justify-center gap-1 bg-slate-50/50 hover:bg-slate-100/80 border-slate-200 text-slate-700';
            }
        });

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
        const batchEl = document.getElementById('batch_no');
        if (batchEl) batchEl.value = batch;
    }

    /* ------------------------------------------------------------------
     * Real Material Items Management Functions (Clean, No Dummy Data)
     * ------------------------------------------------------------------ */

    function addRealMaterialItem() {
        const codeInp = document.getElementById('newPartCode');
        const nameInp = document.getElementById('newPartName');
        const qtyInp = document.getElementById('newPartQty');
        const batchInp = document.getElementById('newPartBatch');
        const kolomInp = document.getElementById('newPartKolom');
        const tingkatInp = document.getElementById('newPartTingkat');
        const notesInp = document.getElementById('newPartNotes');

        const name = nameInp ? nameInp.value.trim() : '';
        if (!name) {
            alert('Silakan masukkan nama material / spare part terlebih dahulu.');
            nameInp?.focus();
            return;
        }

        const code = codeInp ? codeInp.value.trim() : '';
        const qty = (qtyInp && qtyInp.value.trim()) ? qtyInp.value.trim() : '1';
        const batch = batchInp ? batchInp.value.trim() : '';
        const kolom = kolomInp ? kolomInp.value.trim().toUpperCase() : '';
        const tingkat = tingkatInp ? tingkatInp.value.trim() : '';
        const notes = notesInp ? notesInp.value.trim() : '';

        selectedItems.push({
            code: code,
            name: name,
            qty: qty,
            batch: batch,
            kolom: kolom,
            tingkat: tingkat,
            notes: notes
        });

        // Auto-sync pallet level kolom and tingkat if still empty
        const palletKolomEl = document.getElementById('pallet_kolom');
        const palletTingkatEl = document.getElementById('pallet_tingkat');
        if (palletKolomEl && !palletKolomEl.value && kolom) {
            palletKolomEl.value = kolom;
        }
        if (palletTingkatEl && !palletTingkatEl.value && tingkat) {
            palletTingkatEl.value = tingkat;
        }

        // Reset input fields
        if (codeInp) codeInp.value = '';
        if (nameInp) nameInp.value = '';
        if (qtyInp) qtyInp.value = '1';
        if (batchInp) batchInp.value = '';
        if (kolomInp) kolomInp.value = '';
        if (tingkatInp) tingkatInp.value = '';
        if (notesInp) notesInp.value = '';

        // Focus code or name input for next fast entry
        if (codeInp) {
            codeInp.focus();
        } else if (nameInp) {
            nameInp.focus();
        }

        renderSelectedTable();
    }

    function removeSelectedItem(idx) {
        if (idx >= 0 && idx < selectedItems.length) {
            selectedItems.splice(idx, 1);
            renderSelectedTable();
        }
    }

    function updateSelectedQty(idx, val) {
        if (selectedItems[idx]) {
            selectedItems[idx].qty = val;
            updateSummaryCounters();
            updateHiddenFormInputs();
            refreshLivePreview();
        }
    }

    function updateSelectedBatch(idx, val) {
        if (selectedItems[idx]) {
            selectedItems[idx].batch = val;
            updateHiddenFormInputs();
        }
    }

    function updateSelectedKolom(idx, val) {
        if (selectedItems[idx]) {
            selectedItems[idx].kolom = val;
            updateHiddenFormInputs();
            refreshLivePreview();
        }
    }

    function updateSelectedTingkat(idx, val) {
        if (selectedItems[idx]) {
            selectedItems[idx].tingkat = val;
            updateHiddenFormInputs();
            refreshLivePreview();
        }
    }

    function updateSelectedNotes(idx, val) {
        if (selectedItems[idx]) {
            selectedItems[idx].notes = val;
            updateHiddenFormInputs();
        }
    }

    function clearAllSelected() {
        if (selectedItems.length === 0) return;
        if (confirm('Kosongkan semua material yang telah diinput di pallet ini?')) {
            selectedItems = [];
            renderSelectedTable();
        }
    }

    function renderSelectedTable() {
        const tbody = document.getElementById('selectedTableBody');
        const emptyState = document.getElementById('selectedEmptyState');
        const badge = document.getElementById('selectedCountBadge');
        const topBadge = document.getElementById('componentCountBadge');

        if (!tbody || !emptyState) return;

        const countText = `${selectedItems.length} ITEMS`;
        if (badge) badge.textContent = countText;
        if (topBadge) topBadge.textContent = `${selectedItems.length} Item Ditambahkan`;

        if (selectedItems.length === 0) {
            tbody.innerHTML = '';
            emptyState.classList.remove('hidden');
        } else {
            emptyState.classList.add('hidden');
            let html = '';
            selectedItems.forEach((item, idx) => {
                const kolomOptions = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z']
                    .map(ch => `<option value="${ch}" ${item.kolom === ch ? 'selected' : ''}>${ch}</option>`).join('');
                const tingkatOptions = [1,2,3,4,5]
                    .map(lvl => `<option value="${lvl}" ${String(item.tingkat) === String(lvl) ? 'selected' : ''}>${lvl}</option>`).join('');

                html += `
                    <tr class="hover:bg-blue-50/30 transition">
                        <td class="py-2.5 px-3 text-center font-bold text-slate-400 text-xs">${idx + 1}</td>
                        <td class="py-2.5 px-3 whitespace-nowrap">
                            ${item.code 
                                ? `<span class="font-mono text-[11px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">${escapeHtml(item.code)}</span>` 
                                : '<span class="text-slate-300 text-xs italic">-</span>'}
                        </td>
                        <td class="py-2.5 px-3">
                            <div class="font-bold text-slate-900 text-xs">${escapeHtml(item.name)}</div>
                        </td>
                        <td class="py-2.5 px-3 text-center whitespace-nowrap">
                            <input type="text" 
                                   value="${escapeHtml(item.qty)}" 
                                   oninput="updateSelectedQty(${idx}, this.value)" 
                                   class="w-20 px-2 py-1 bg-white border border-slate-300 rounded-lg text-center text-xs font-bold text-slate-800 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-100 shadow-2xs">
                        </td>
                        <td class="py-2.5 px-3 whitespace-nowrap">
                            <input type="text" 
                                   value="${escapeHtml(item.batch || '')}" 
                                   placeholder="Default batch" 
                                   oninput="updateSelectedBatch(${idx}, this.value)" 
                                   class="w-24 px-2 py-1 bg-white border border-slate-200 rounded-lg text-xs font-mono text-slate-700 focus:outline-none focus:border-blue-600">
                        </td>
                        <td class="py-2.5 px-3 text-center whitespace-nowrap">
                            <div class="inline-flex items-center gap-1 bg-slate-50 border border-slate-200 px-2 py-1 rounded-lg">
                                <span class="text-[10px] font-bold text-slate-400">Kolom:</span>
                                <select onchange="updateSelectedKolom(${idx}, this.value)" title="Pilih Kolom (A sampai Z)" class="px-1 py-0.5 bg-white border border-blue-200 rounded text-xs font-extrabold text-blue-700 cursor-pointer">
                                    <option value="">-</option>
                                    ${kolomOptions}
                                </select>
                                <span class="text-[10px] font-bold text-slate-400 ml-1">Tingkat:</span>
                                <select onchange="updateSelectedTingkat(${idx}, this.value)" title="Pilih Tingkat (1 sampai 5)" class="px-1 py-0.5 bg-white border border-indigo-200 rounded text-xs font-extrabold text-indigo-700 cursor-pointer">
                                    <option value="">-</option>
                                    ${tingkatOptions}
                                </select>
                            </div>
                        </td>
                        <td class="py-2.5 px-2 text-center whitespace-nowrap">
                            <button type="button" 
                                    onclick="removeSelectedItem(${idx})" 
                                    class="w-7 h-7 rounded-lg hover:bg-rose-50 text-rose-500 hover:text-rose-700 flex items-center justify-center font-bold text-base transition mx-auto" 
                                    title="Hapus material ini">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        }

        updateSummaryCounters();
        updateHiddenFormInputs();
        refreshLivePreview();
    }

    function updateSummaryCounters() {
        const typesEl = document.getElementById('summaryTypesCount');
        const unitsEl = document.getElementById('summaryUnitsCount');
        if (typesEl) typesEl.textContent = selectedItems.length;
        if (unitsEl) {
            const totalUnits = selectedItems.reduce((acc, item) => {
                const parsed = parseInt(item.qty, 10);
                return acc + (isNaN(parsed) ? 1 : parsed);
            }, 0);
            unitsEl.textContent = totalUnits;
        }
    }

    function updateHiddenFormInputs() {
        const container = document.getElementById('hiddenFormComponents');
        if (!container) return;
        container.innerHTML = '';

        const defaultBatch = document.getElementById('batch_no')?.value || '';

        selectedItems.forEach((item, idx) => {
            const fullName = item.code ? `${item.code} - ${item.name}` : item.name;
            const fullQty = item.qty || '1';
            const batchVal = item.batch || defaultBatch;

            container.innerHTML += `
                <input type="hidden" name="components[${idx}][component_name]" value="${escapeHtml(fullName)}">
                <input type="hidden" name="components[${idx}][quantity]" value="${escapeHtml(fullQty)}">
                <input type="hidden" name="components[${idx}][batch_no]" value="${escapeHtml(batchVal)}">
                <input type="hidden" name="components[${idx}][kolom]" value="${escapeHtml(item.kolom || '')}">
                <input type="hidden" name="components[${idx}][tingkat]" value="${escapeHtml(item.tingkat || '')}">
                <input type="hidden" name="components[${idx}][notes]" value="${escapeHtml(item.notes || '')}">
            `;
        });
    }

    function refreshLivePreview() {
        const previewList = document.getElementById('previewComponentsList');
        const countText = document.getElementById('previewTotalCountText');

        if (!previewList) return;
        previewList.innerHTML = '';

        let validCount = 0;
        selectedItems.forEach((item, idx) => {
            if (item.name && item.name.trim() !== '') {
                validCount++;
                const itemDiv = document.createElement('div');
                itemDiv.className = 'flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-200 text-xs';
                
                const locBadge = (item.kolom || item.tingkat) 
                    ? `<span class="text-[9.5px] font-mono font-bold px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200 shrink-0 whitespace-nowrap">${item.kolom ? `Kolom ${escapeHtml(item.kolom)}` : ''}${item.kolom && item.tingkat ? ' • ' : ''}${item.tingkat ? `Tingkat ${escapeHtml(item.tingkat)}` : ''}</span>`
                    : '';

                itemDiv.innerHTML = `
                    <div class="flex items-center gap-1.5 font-bold text-slate-800 truncate pr-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 shrink-0"></span>
                        ${item.code ? `<span class="font-mono text-[10px] text-blue-700 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-200 font-bold shrink-0">${escapeHtml(item.code)}</span>` : ''}
                        <span class="truncate">${escapeHtml(item.name)}</span>
                    </div>
                    <div class="flex items-center gap-1 shrink-0">
                        ${locBadge}
                        <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded bg-blue-100 text-blue-800">
                            ${escapeHtml(item.qty || '1')}
                        </span>
                    </div>
                `;
                previewList.appendChild(itemDiv);
            }
        });

        if (validCount === 0) {
            previewList.innerHTML = '<p class="text-slate-400 text-xs italic py-1">Belum ada material diinput.</p>';
        }

        if (countText) countText.textContent = `${validCount} Items`;
    }

    // Intercept form submit to alert if no item has been added
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('createPalletForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (selectedItems.length === 0) {
                    e.preventDefault();
                    alert('Silakan masukkan minimal 1 material / spare part asli pada form di atas.');
                    document.getElementById('newPartName')?.focus();
                    return false;
                }
            });
        }

        checkPalletAvailability();
        renderSelectedTable();
    });
</script>
@endpush
@endsection
