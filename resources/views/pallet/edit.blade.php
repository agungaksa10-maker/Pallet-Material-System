@extends('layouts.app')

@section('title', 'Edit Sticker Pallet ' . $sticker->pallet_code)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- Top Breadcrumb & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <a href="{{ route('pallet.index') }}" class="hover:text-blue-600 transition">Dashboard</a>
                <span>/</span>
                <a href="{{ route('pallet.show', $sticker->id) }}" class="hover:text-blue-600 transition font-mono">{{ $sticker->pallet_code }}</a>
                <span>/</span>
                <span class="text-slate-800">Edit Data</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2.5">
                <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                <span>Edit Sticker Pallet</span>
                <span class="text-xs font-mono font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200">
                    {{ $sticker->pallet_code }}
                </span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Perbarui rincian material, nomor batch, jumlah satuan, atau nomor pallet (1 &ndash; 500).
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('pallet.show', $sticker->id) }}" 
               class="px-4 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Lihat Detail</span>
            </a>

            <a href="{{ route('pallet.index') }}" 
               class="px-4 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span>Batal</span>
            </a>
        </div>
    </div>

    <!-- Main Grid: Form on Left (7 cols), Live Thermal Label on Right (5 cols) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- ======================================================== -->
        <!-- FORMULIR EDIT (7 COLUMNS)                                 -->
        <!-- ======================================================== -->
        <div class="lg:col-span-7 bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs space-y-6">
            
            <form action="{{ route('pallet.update', $sticker->id) }}" method="POST" id="editPalletForm" class="space-y-6">
                @csrf
                @method('PUT')

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
                                       {{ old('site', $sticker->site) === $sKey ? 'checked' : '' }}
                                       onchange="onSiteChange('{{ $sKey }}')">
                                <div class="site-tile p-3 rounded-xl border text-center transition-all flex flex-col items-center justify-center gap-1 {{ old('site', $sticker->site) === $sKey ? 'bg-blue-50/80 border-blue-500 text-blue-800 ring-2 ring-blue-500/20 shadow-xs' : 'bg-slate-50/50 hover:bg-slate-100/80 border-slate-200 text-slate-700' }}">
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
                                   {{ old('category', $sticker->category) === 'Dressing' ? 'checked' : '' }}
                                   onchange="onCategoryChange('Dressing')">
                            <div class="cat-tile p-3.5 rounded-xl border transition-all flex items-center gap-3 {{ old('category', $sticker->category) === 'Dressing' ? 'bg-indigo-50 border-indigo-500 text-indigo-900 ring-2 ring-indigo-500/20 shadow-xs' : 'bg-slate-50/50 hover:bg-slate-100 border-slate-200 text-slate-700' }}">
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
                                   {{ old('category', $sticker->category) === 'Consumable' ? 'checked' : '' }}
                                   onchange="onCategoryChange('Consumable')">
                            <div class="cat-tile p-3.5 rounded-xl border transition-all flex items-center gap-3 {{ old('category', $sticker->category) === 'Consumable' ? 'bg-amber-50 border-amber-500 text-amber-900 ring-2 ring-amber-500/20 shadow-xs' : 'bg-slate-50/50 hover:bg-slate-100 border-slate-200 text-slate-700' }}">
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
                <div class="space-y-2 p-4 rounded-xl bg-slate-50 border border-slate-200/90">
                    <div class="flex items-center justify-between">
                        <label for="pallet_number" class="text-xs font-extrabold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                            <span>3. Nomor Pallet (1 &ndash; 500)</span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[11px] font-mono font-bold text-slate-500" id="palletCodePreviewText">
                            {{ $sticker->pallet_code }}
                        </span>
                    </div>

                    <!-- Availability Status Alert -->
                    <div id="palletStatusBox">
                        <div id="palletAvailableBadge" class="p-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Pallet #<span class="activePalletDisplay">{{ $sticker->pallet_number }}</span> <strong>Tersedia</strong> untuk Site <span class="activeSiteDisplay">{{ $sticker->site }}</span></span>
                            </span>
                            <span class="text-[10px] font-mono bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-md font-bold">Valid</span>
                        </div>

                        <div id="palletUsedWarning" class="hidden p-2.5 rounded-xl bg-rose-50 border border-rose-300 text-rose-800 text-xs font-semibold flex items-center justify-between">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <span>Pallet #<span class="activePalletDisplay">{{ $sticker->pallet_number }}</span> <strong>sudah digunakan</strong> oleh pallet lain di Site <span class="activeSiteDisplay">{{ $sticker->site }}</span> &mdash; pilih nomor lain!</span>
                            </span>
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
                                   value="{{ old('pallet_number', $sticker->pallet_number) }}"
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
                    <div class="pt-2">
                        <input type="range" 
                                min="1" 
                                max="500" 
                                value="{{ old('pallet_number', $sticker->pallet_number) }}" 
                                id="palletSlider"
                                oninput="onSliderChange(this.value)"
                                class="w-full accent-blue-600 cursor-pointer">
                    </div>

                    <!-- Quick Preset Pills -->
                    <div class="flex items-center gap-1.5 pt-1 overflow-x-auto text-[11px]">
                        <span class="text-slate-400 font-medium mr-1">Cepat:</span>
                        @foreach([1, 10, 25, 50, 100, 250, 500] as $presetNum)
                            <button type="button" 
                                    onclick="setPalletNumber({{ $presetNum }})"
                                    class="px-2.5 py-1 rounded-lg bg-white hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 border border-slate-200 font-mono font-bold text-slate-600 transition shadow-xs">
                                #{{ $presetNum }}
                            </button>
                        @endforeach
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
                            {{ max(1, $sticker->components->count()) }} Komponen
                        </span>
                    </div>

                    <!-- Dynamic Component Rows Container -->
                    <div id="componentsContainer" class="space-y-3 pt-1">
                        @php
                            $existingComponents = old('components', $sticker->components->isNotEmpty() ? $sticker->components->toArray() : [
                                [
                                    'component_name' => $sticker->material_name,
                                    'quantity' => $sticker->quantity,
                                    'batch_no' => $sticker->batch_no,
                                    'notes' => $sticker->notes,
                                ]
                            ]);
                        @endphp

                        @foreach($existingComponents as $index => $comp)
                            <div class="component-row p-3.5 bg-white border border-slate-200 rounded-xl shadow-xs space-y-2.5 transition relative">
                                <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                                    <span class="flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                        <span class="row-label">Komponen #{{ $index + 1 }}</span>
                                    </span>
                                    <button type="button" 
                                            onclick="removeComponentRow(this)" 
                                            class="text-rose-500 hover:text-rose-700 text-xs font-semibold remove-btn {{ count($existingComponents) > 1 ? '' : 'hidden' }}">
                                        &times; Hapus
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5">
                                    <div class="sm:col-span-7">
                                        <label class="text-[10px] font-bold text-slate-500 block mb-1">Nama Komponen / Material *</label>
                                        <input type="text" 
                                               name="components[{{ $index }}][component_name]" 
                                               value="{{ $comp['component_name'] ?? '' }}" 
                                               required
                                               oninput="refreshLivePreview()"
                                               placeholder="Contoh: Knife Run, Roll Dressing, Diamond Stone..." 
                                               class="w-full px-3 py-2 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition component-name-input">
                                    </div>

                                    <div class="sm:col-span-5">
                                        <label class="text-[10px] font-bold text-slate-500 block mb-1">Jumlah / Qty</label>
                                        <input type="text" 
                                               name="components[{{ $index }}][quantity]" 
                                               value="{{ $comp['quantity'] ?? '1 UNIT' }}" 
                                               oninput="refreshLivePreview()"
                                               placeholder="Contoh: 10 PCS, 2 UNIT" 
                                               class="w-full px-3 py-2 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition component-qty-input">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5">
                                    <div class="sm:col-span-6">
                                        <input type="text" 
                                               name="components[{{ $index }}][batch_no]" 
                                               value="{{ $comp['batch_no'] ?? '' }}" 
                                               placeholder="No. Batch komponen (opsional)" 
                                               class="w-full px-3 py-1.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-lg text-xs font-mono text-slate-700 focus:outline-none focus:border-blue-600 transition">
                                    </div>
                                    <div class="sm:col-span-6">
                                        <input type="text" 
                                               name="components[{{ $index }}][notes]" 
                                               value="{{ $comp['notes'] ?? '' }}" 
                                               placeholder="Catatan / Spesifikasi (opsional)" 
                                               class="w-full px-3 py-1.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:border-blue-600 transition">
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
                            @foreach($materialPresets[$sticker->category] ?? [] as $presetItem)
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

                <!-- 5. Nomor Batch & Quantity Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    
                    <!-- No. Batch -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="batch_no" class="text-xs font-bold text-slate-700">No. Batch Produksi</label>
                            <button type="button" 
                                    onclick="generateTodayBatch()"
                                    class="text-[10px] font-bold text-blue-600 hover:underline">
                                Auto-Generate
                            </button>
                        </div>
                        <input type="text" 
                               name="batch_no" 
                               id="batch_no" 
                               value="{{ old('batch_no', $sticker->batch_no) }}"
                               oninput="onBatchChange(this.value)"
                               placeholder="Contoh: BATCH-20260904-001"
                               class="w-full px-3 py-2 bg-slate-50 hover:bg-white border border-slate-200 rounded-xl font-mono text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition shadow-xs">
                        @error('batch_no')
                            <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quantity -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="quantity" class="text-xs font-bold text-slate-700">Jumlah / Satuan</label>
                            <span class="text-[10px] text-slate-400">Default: 1 PALLET</span>
                        </div>
                        <input type="text" 
                               name="quantity" 
                               id="quantity" 
                               value="{{ old('quantity', $sticker->quantity) }}"
                               oninput="onQuantityChange(this.value)"
                               placeholder="Contoh: 24 PCS, 50 ROLL"
                               class="w-full px-3 py-2 bg-slate-50 hover:bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition shadow-xs">
                        @error('quantity')
                            <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- 6. Catatan Tambahan (Opsional) -->
                <div class="space-y-1.5">
                    <label for="notes" class="text-xs font-bold text-slate-700">Catatan Khusus / Lokasi Penyimpanan (Opsional)</label>
                    <textarea name="notes" 
                              id="notes" 
                              rows="2" 
                              oninput="onNotesChange(this.value)"
                              placeholder="Keterangan tambahan untuk label atau tujuan pengiriman..."
                              class="w-full px-3 py-2 bg-slate-50 hover:bg-white border border-slate-200 rounded-xl text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition shadow-xs">{{ old('notes', $sticker->notes) }}</textarea>
                    @error('notes')
                        <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Action Buttons -->
                <div class="pt-4 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-end gap-3">
                    <a href="{{ route('pallet.show', $sticker->id) }}" 
                       class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs text-center transition shadow-xs">
                        Batal
                    </a>

                    <!-- Save & Print Directly -->
                    <button type="submit" 
                            name="action" 
                            value="print"
                            class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-sm transition active:scale-95">
                        <svg class="w-4 h-4 text-slate-300 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        <span>Simpan & Cetak PDF</span>
                    </button>

                    <!-- Save to Database -->
                    <button type="submit" 
                            name="action" 
                            value="save"
                            class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white font-extrabold text-xs flex items-center justify-center gap-2 shadow-sm hover:shadow-md shadow-amber-500/20 transition active:scale-95">
                        <svg class="w-4 h-4 text-white stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>

            </form>

        </div>

        <!-- ======================================================== -->
        <!-- LIVE THERMAL LABEL PREVIEW (5 COLUMNS)                   -->
        <!-- ======================================================== -->
        <div class="lg:col-span-5 space-y-4 lg:sticky lg:top-20">
            
            <div class="flex items-center justify-between px-1">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#0047BA] animate-pulse"></span>
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Preview Label Terkini</h3>
                </div>
                <span class="text-[11px] font-mono text-slate-500">ANDRITZ Standard</span>
            </div>

            <!-- ANDRITZ Pallet Sticker Card -->
            <x-andritz-sticker 
                :pallet-number="$sticker->pallet_number" 
                :category="$sticker->category" 
                :category-code="'I-COS'" 
                id-prefix="preview" />

            <div class="p-3.5 rounded-xl bg-amber-50/70 border border-amber-200/80 text-amber-950 text-xs flex items-start gap-2.5">
                <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <p class="leading-relaxed">
                    Perubahan pada <strong>Site</strong>, <strong>Kategori</strong>, atau <strong>Nomor Pallet</strong> akan memperbarui kode identifikasi pallet secara otomatis.
                </p>
            </div>

        </div>

    </div>

</div>

@push('scripts')
<script>
    const materialPresets = @json($materialPresets);
    const usedPalletsBySite = @json($usedPalletsBySite);
    let currentSite = "{{ old('site', $sticker->site) }}";
    let currentCategory = "{{ old('category', $sticker->category) }}";
    let currentPallet = parseInt("{{ old('pallet_number', $sticker->pallet_number) }}", 10);

    const siteCodeMap = {
        'OKI II': 'OKI2',
        'IKPD': 'IKPD',
        'IKPP': 'IKPP',
        'TELL': 'TELL',
        'ISC': 'ISC'
    };

    function calculateCode(site, category, num) {
        const sCode = siteCodeMap[site] || site.replace(/[^A-Za-z0-9]/g, '');
        const cCode = (category === 'Dressing') ? 'DRS' : 'CON';
        const nCode = String(num).padStart(3, '0');
        return `PLT-${sCode}-${cCode}-${nCode}`;
    }

    function checkPalletAvailability() {
        const usedList = usedPalletsBySite[currentSite] || [];
        const isUsed = usedList.includes(currentPallet);

        const availableBadge = document.getElementById('palletAvailableBadge');
        const usedWarning = document.getElementById('palletUsedWarning');
        const palletInput = document.getElementById('pallet_number');
        const submitButtons = document.querySelectorAll('button[type="submit"]');

        document.querySelectorAll('.activePalletDisplay').forEach(el => el.textContent = currentPallet);
        document.querySelectorAll('.activeSiteDisplay').forEach(el => el.textContent = currentSite);

        if (isUsed) {
            if (availableBadge) availableBadge.classList.add('hidden');
            if (usedWarning) usedWarning.classList.remove('hidden');
            if (palletInput) {
                palletInput.classList.add('border-rose-500', 'bg-rose-50', 'text-rose-900');
                palletInput.classList.remove('border-slate-200', 'bg-white', 'text-slate-900');
            }
            submitButtons.forEach(btn => {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            });
        } else {
            if (availableBadge) availableBadge.classList.remove('hidden');
            if (usedWarning) usedWarning.classList.add('hidden');
            if (palletInput) {
                palletInput.classList.remove('border-rose-500', 'bg-rose-50', 'text-rose-900');
                palletInput.classList.add('border-slate-200', 'bg-white', 'text-slate-900');
            }
            submitButtons.forEach(btn => {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            });
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

        checkPalletAvailability();
        refreshLivePreview();
    }

    function onSiteChange(site) {
        currentSite = site;
        document.querySelectorAll('.site-radio').forEach(radio => {
            const tile = radio.closest('label').querySelector('.site-tile');
            if (radio.value === site) {
                tile.className = 'site-tile p-3 rounded-xl border text-center transition-all flex flex-col items-center justify-center gap-1 bg-blue-50/80 border-blue-500 text-blue-800 ring-2 ring-blue-500/20 shadow-xs';
            } else {
                tile.className = 'site-tile p-3 rounded-xl border text-center transition-all flex flex-col items-center justify-center gap-1 bg-slate-50/50 hover:bg-slate-100/80 border-slate-200 text-slate-700';
            }
        });
        updateThermalPreview();
    }

    function onCategoryChange(cat) {
        currentCategory = cat;
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

        const knifeBtn = document.createElement('button');
        knifeBtn.type = 'button';
        knifeBtn.className = 'px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-800 border border-blue-200 text-[11px] font-bold transition shadow-2xs';
        knifeBtn.textContent = '+ Knife Run';
        knifeBtn.onclick = () => addPresetAsComponent('Knife Run');
        container.appendChild(knifeBtn);

        updateThermalPreview();
    }

    function onPalletNumberChange(val) {
        let num = parseInt(val, 10);
        if (isNaN(num)) num = 1;
        if (num < 1) num = 1;
        if (num > 500) num = 500;
        currentPallet = num;
        document.getElementById('palletSlider').value = num;
        updateThermalPreview();
    }

    function onSliderChange(val) {
        let num = parseInt(val, 10);
        currentPallet = num;
        document.getElementById('pallet_number').value = num;
        updateThermalPreview();
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
        onPalletNumberChange(num);
    }

    /* ---------------------------------------------
     * Multi-Component Repeater Handlers
     * --------------------------------------------- */
    function addComponentRow(name = '', qty = '1 UNIT', batch = '', notes = '') {
        const container = document.getElementById('componentsContainer');
        const nextIndex = container.querySelectorAll('.component-row').length;

        const rowHtml = `
            <div class="component-row p-3.5 bg-white border border-slate-200 rounded-xl shadow-xs space-y-2.5 transition relative">
                <div class="flex items-center justify-between text-xs font-bold text-slate-700">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span class="row-label">Komponen #${nextIndex + 1}</span>
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
                               name="components[${nextIndex}][component_name]" 
                               value="${escapeHtml(name)}" 
                               required
                               oninput="refreshLivePreview()"
                               placeholder="Contoh: Knife Run, Roll Dressing, Diamond Stone..." 
                               class="w-full px-3 py-2 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition component-name-input">
                    </div>

                    <div class="sm:col-span-5">
                        <label class="text-[10px] font-bold text-slate-500 block mb-1">Jumlah / Qty</label>
                        <input type="text" 
                               name="components[${nextIndex}][quantity]" 
                               value="${escapeHtml(qty)}" 
                               oninput="refreshLivePreview()"
                               placeholder="Contoh: 10 PCS, 2 UNIT" 
                               class="w-full px-3 py-2 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition component-qty-input">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5">
                    <div class="sm:col-span-6">
                        <input type="text" 
                               name="components[${nextIndex}][batch_no]" 
                               value="${escapeHtml(batch)}" 
                               placeholder="No. Batch komponen (opsional)" 
                               class="w-full px-3 py-1.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-lg text-xs font-mono text-slate-700 focus:outline-none focus:border-blue-600 transition">
                    </div>
                    <div class="sm:col-span-6">
                        <input type="text" 
                               name="components[${nextIndex}][notes]" 
                               value="${escapeHtml(notes)}" 
                               placeholder="Catatan / Spesifikasi (opsional)" 
                               class="w-full px-3 py-1.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:border-blue-600 transition">
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', rowHtml);
        renumberComponentRows();
        refreshLivePreview();
    }

    function removeComponentRow(btn) {
        const row = btn.closest('.component-row');
        const container = document.getElementById('componentsContainer');
        if (container.querySelectorAll('.component-row').length > 1) {
            row.remove();
            renumberComponentRows();
            refreshLivePreview();
        }
    }

    function renumberComponentRows() {
        const container = document.getElementById('componentsContainer');
        const rows = container.querySelectorAll('.component-row');
        rows.forEach((row, idx) => {
            row.querySelector('.row-label').textContent = `Komponen #${idx + 1}`;
            row.querySelectorAll('input').forEach(input => {
                const currentName = input.getAttribute('name');
                if (currentName) {
                    input.setAttribute('name', currentName.replace(/components\[\d+\]/, `components[${idx}]`));
                }
            });
            const removeBtn = row.querySelector('.remove-btn');
            if (removeBtn) {
                if (rows.length === 1) {
                    removeBtn.classList.add('hidden');
                } else {
                    removeBtn.classList.remove('hidden');
                }
            }
        });

        const badge = document.getElementById('componentCounterBadge');
        if (badge) {
            badge.textContent = `${rows.length} Komponen`;
        }
    }

    function addPresetAsComponent(presetName) {
        const container = document.getElementById('componentsContainer');
        const firstRow = container.querySelector('.component-row');
        const firstInput = firstRow ? firstRow.querySelector('.component-name-input') : null;

        if (firstInput && (!firstInput.value || firstInput.value.trim() === '')) {
            firstInput.value = presetName;
            refreshLivePreview();
        } else {
            addComponentRow(presetName, '1 UNIT');
        }
    }

    function refreshLivePreview() {
        const container = document.getElementById('componentsContainer');
        if (!container) return;

        const nameInputs = container.querySelectorAll('.component-name-input');
        const names = [];
        nameInputs.forEach(inp => {
            if (inp.value && inp.value.trim() !== '') {
                names.push(inp.value.trim());
            }
        });

        let summary = 'Standard Material Unit';
        if (names.length === 1) {
            summary = names[0];
        } else if (names.length === 2) {
            summary = `${names[0]}, ${names[1]}`;
        } else if (names.length > 2) {
            summary = `${names[0]}, ${names[1]} (+${names.length - 2} lainnya)`;
        }

        const previewMat = document.getElementById('previewMaterialText');
        if (previewMat) previewMat.textContent = summary;
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function onBatchChange(val) {
        document.getElementById('previewBatchText').textContent = val || '-';
    }

    function onQuantityChange(val) {
        document.getElementById('previewQuantityText').textContent = val || '1 PALLET';
    }

    function onNotesChange(val) {
        const noteEl = document.getElementById('previewNotesText');
        if (val && val.trim() !== '') {
            noteEl.textContent = val;
        } else {
            noteEl.textContent = 'Pallet material stiker resmi pabrik';
        }
    }

    function generateTodayBatch() {
        const dateStr = new Date().toISOString().slice(0,10).replace(/-/g, '');
        const pad = String(currentPallet).padStart(3, '0');
        const batch = `BATCH-${dateStr}-${pad}`;
        document.getElementById('batch_no').value = batch;
        onBatchChange(batch);
    }

    // Initialize preview state on page load
    document.addEventListener('DOMContentLoaded', () => {
        updateThermalPreview();
    });
</script>
@endpush
@endsection
