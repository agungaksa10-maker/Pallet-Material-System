@extends('layouts.app')

@section('title', 'Dashboard Pallet Material')

@section('content')
<div class="space-y-6">

    <!-- ======================================================== -->
    <!-- 1. METRICS & KPI HERO SECTION (INDUSTRIAL HIGH-PRECISION)-->
    <!-- ======================================================== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Total Pallet Card -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs hover:shadow-md hover:border-blue-400 transition-all duration-200 relative overflow-hidden group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Pallet</span>
                <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-slate-900 tracking-tight" id="kpiTotalPallet">{{ $totalPallet }}</span>
                <span class="text-xs font-semibold text-slate-500">Pallet terdaftar</span>
            </div>
            
            <!-- Pallet Capacity Utilization Bar (1 - 500) -->
            <div class="mt-3.5 space-y-1.5">
                <div class="flex items-center justify-between text-[11px] font-medium text-slate-500">
                    <span>Kapasitas (No. 1 hingga 500)</span>
                    <span class="font-mono font-bold text-slate-700">{{ number_format(($totalPallet / 500) * 100, 1) }}%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 h-full rounded-full transition-all duration-500" 
                         style="width: {{ min(100, max(2, ($totalPallet / 500) * 100)) }}%"></div>
                </div>
            </div>
        </div>

        <!-- Semua Site Card (Tepat di Sebelah Total Pallet) -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs hover:shadow-md hover:border-emerald-400 transition-all duration-200 relative overflow-hidden group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Semua Site</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 21h18M3 7v14M21 7v14M6 11h2M6 15h2M11 11h2M11 15h2M16 11h2M16 15h2M9 3h6v4H9z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-slate-900 tracking-tight" id="kpiTotalSite">{{ $totalSite }}</span>
                <span class="text-xs font-semibold text-slate-500">{{ count($sites) > 1 ? 'Pabrik aktif' : 'Fasilitas aktif' }}</span>
            </div>
            <div class="mt-3 flex flex-wrap gap-1">
                @foreach($sites as $siteKey => $siteDesc)
                    @php
                        $sitePillStyle = match($siteKey) {
                            'OKI II' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'IKPD' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'IKPP' => 'bg-purple-50 text-purple-700 border-purple-200',
                            'TELL' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'ISC' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                            default => 'bg-slate-50 text-slate-700 border-slate-200',
                        };
                    @endphp
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-md border {{ $sitePillStyle }}">{{ $siteKey }}</span>
                @endforeach
            </div>
        </div>

        <!-- Dressing Card -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs hover:shadow-md hover:border-indigo-400 transition-all duration-200 relative overflow-hidden group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Dressing</span>
                <div class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-indigo-600 tracking-tight" id="kpiDressing">{{ $totalDressing }}</span>
                <span class="text-xs font-semibold text-slate-500">Pallet Material</span>
            </div>
            <div class="mt-3.5 text-[11px] font-medium text-slate-500 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                <span>Roll Dressing, Stone, Blade</span>
            </div>
        </div>

        <!-- Consumable Card -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs hover:shadow-md hover:border-amber-400 transition-all duration-200 relative overflow-hidden group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Consumable</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                        <line x1="7" y1="7" x2="7.01" y2="7"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-amber-600 tracking-tight" id="kpiConsumable">{{ $totalConsumable }}</span>
                <span class="text-xs font-semibold text-slate-500">Pallet Material</span>
            </div>
            <div class="mt-3.5 text-[11px] font-medium text-slate-500 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                <span>Film, Strapping, Ribbon, Cover</span>
            </div>
        </div>

    </div>

    <!-- ======================================================== -->
    <!-- 2. CONTROL & FILTER TOOLBAR (MODERN ENTERPRISE BAR)      -->
    <!-- ======================================================== -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs space-y-4">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3.5 border-b border-slate-100">
            <div>
                <h2 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                    <span>Filter & Manajemen Data Pallet</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Pilih site atau kategori di bawah untuk menyaring data pada tabel secara real-time:
                </p>
            </div>

            <!-- Tombol Buat Sticker Baru (Primary Hero CTA) -->
            <div class="flex items-center gap-2">
                <a href="{{ route('pallet.create') }}" 
                   class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xs rounded-xl shadow-sm hover:shadow-md shadow-blue-500/20 active:scale-95 transition-all">
                    <svg class="w-4 h-4 text-white stroke-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Buat Sticker Baru (1-500)</span>
                </a>
            </div>
        </div>

        <!-- Filter Controls & Search Bar (Clean Single Row on Desktop) -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3" id="filterContainer">
            
            <div class="flex flex-wrap items-center gap-2.5">
                <!-- 1. Site Dropdown Filter -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10 text-slate-500">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <select id="siteFilterSelect" 
                            onchange="onSiteFilterChange(this.value)" 
                            class="pl-9 pr-8 py-2 bg-slate-100 hover:bg-slate-200/80 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition cursor-pointer appearance-none shadow-2xs">
                        <option value="ALL">Semua Site</option>
                        @foreach($sites as $siteKey => $siteDesc)
                            <option value="{{ $siteKey }}">{{ $siteKey }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                <!-- Separator -->
                <span class="hidden sm:inline-block w-px h-6 bg-slate-200 mx-0.5"></span>

                <!-- 2. Kategori Filter (Semua Kategori, Dressing, Consumable) -->
                <div class="inline-flex items-center rounded-xl bg-slate-100 p-1 border border-slate-200/80 gap-0.5">
                    <button type="button" 
                            onclick="onCategoryFilterChange('ALL')" 
                            id="btn_cat_ALL"
                            class="cat-filter-btn px-3 py-1 rounded-lg text-xs font-bold transition-all bg-white text-blue-700 shadow-xs">
                        Semua Kategori
                    </button>
                    <button type="button" 
                            onclick="onCategoryFilterChange('Dressing')" 
                            id="btn_cat_Dressing"
                            class="cat-filter-btn px-3 py-1 rounded-lg text-xs font-semibold text-slate-600 hover:text-indigo-600 transition-all flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                        <span>Dressing</span>
                    </button>
                    <button type="button" 
                            onclick="onCategoryFilterChange('Consumable')" 
                            id="btn_cat_Consumable"
                            class="cat-filter-btn px-3 py-1 rounded-lg text-xs font-semibold text-slate-600 hover:text-amber-600 transition-all flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                        <span>Consumable</span>
                    </button>
                </div>
            </div>

            <!-- 3. Live Search Bar (Fixed layout & perfectly aligned icon) -->
            <div class="relative w-full sm:w-72 md:w-80 lg:w-96 shrink-0">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none z-10 text-slate-400">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" 
                       id="liveSearchInput" 
                       oninput="onSearchChange(this.value)"
                       placeholder="Cari nomor, material, batch, catatan..." 
                       class="w-full pl-10 pr-4 py-2 bg-slate-50 hover:bg-white border border-slate-200 rounded-xl text-xs text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-all shadow-xs leading-normal">
            </div>

        </div>

        <!-- Filter Status Bar -->
        <div class="flex items-center justify-between text-xs pt-2 text-slate-500 border-t border-slate-100">
            <div class="flex items-center gap-2">
                <span class="font-medium text-slate-600">Status filter:</span>
                <span id="activeFilterBadge" class="font-bold text-blue-700 bg-blue-50 border border-blue-200 px-2.5 py-0.5 rounded-lg text-[11px]">
                    Semua Site • Semua Kategori
                </span>
            </div>
            <div class="text-[11px] text-slate-500 font-mono">
                Menampilkan <span id="visibleRowCount" class="font-bold text-slate-900">{{ count($stickers) }}</span> dari {{ count($stickers) }} baris data
            </div>
        </div>

    </div>

    <!-- ======================================================== -->
    <!-- 3. TABEL DASHBOARD (HIGH-PRECISION INDUSTRIAL DATA GRID)   -->
    <!-- ======================================================== -->
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden">
        
        <!-- Table Header Bar -->
        <div class="bg-slate-50/90 border-b border-slate-200/90 px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-slate-200/70 text-slate-700 flex items-center justify-center">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 leading-tight">Daftar Sticker Pallet Terdaftar</h3>
                    <p class="text-[11px] text-slate-500">Seluruh riwayat pembuatan label dan barcode aktif</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2 text-xs">
                <!-- Batch Print Link -->
                <button type="button" 
                        onclick="openBatchModal()"
                        class="px-3 py-1.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 hover:text-slate-900 border border-slate-200 text-xs font-bold transition-all shadow-xs hover:shadow-sm flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>Cetak Batch (PDF)</span>
                </button>
            </div>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700" id="palletDashboardTable">
                <thead class="bg-slate-50/60 text-[11px] uppercase tracking-wider text-slate-500 font-bold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Site</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">No. Pallet</th>
                        <th class="py-3 px-4">Nama Material</th>
                        <th class="py-3 px-4">Catatan</th>
                        <th class="py-3 px-4">Operator</th>
                        <th class="py-3 px-4">Waktu Pembuatan</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white" id="palletTableBody">
                    @forelse($stickers as $idx => $item)
                        @php
                            $siteBadge = match($item->site) {
                                'OKI II' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'IKPD' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'IKPP' => 'bg-purple-50 text-purple-700 border-purple-200',
                                'TELL' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'ISC' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                default => 'bg-slate-100 text-slate-800 border-slate-200',
                            };
                            $siteDot = match($item->site) {
                                'OKI II' => 'bg-blue-600',
                                'IKPD' => 'bg-emerald-600',
                                'IKPP' => 'bg-purple-600',
                                'TELL' => 'bg-amber-600',
                                'ISC' => 'bg-cyan-600',
                                default => 'bg-slate-600',
                            };
                            $noteText = $item->notes ?: $item->components->pluck('notes')->filter()->first();

                            $hasComponents = $item->components && $item->components->isNotEmpty();
                            $allComponentsList = $hasComponents ? $item->components : collect();
                            $allComponentsNames = $hasComponents 
                                ? $item->components->pluck('component_name')->filter()->implode(' ') 
                                : $item->material_name;
                            $allSearchableMaterial = strtolower($allComponentsNames . ' ' . $item->material_name);
                        @endphp
                        <tr class="pallet-row hover:bg-slate-50/80 transition-colors duration-150 group"
                            data-site="{{ $item->site }}"
                            data-category="{{ $item->category }}"
                            data-pallet="{{ $item->pallet_number }}"
                            data-material="{{ $allSearchableMaterial }}"
                            data-code="{{ strtolower($item->pallet_code) }}"
                            data-batch="{{ strtolower($item->batch_no) }}"
                            data-notes="{{ strtolower($noteText ?? '') }}">
                            
                            <!-- Index -->
                            <td class="py-3.5 px-4 text-center font-mono text-slate-400 row-index">
                                {{ $idx + 1 }}
                            </td>

                            <!-- Site -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 font-bold text-[11px] px-2.5 py-1 rounded-lg border {{ $siteBadge }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $siteDot }}"></span>
                                    <span>{{ $item->site }}</span>
                                </span>
                            </td>

                            <!-- Kategori -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold border {{ $item->category === 'Dressing' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'bg-amber-50 text-amber-700 border-amber-200' }}">
                                    {{ $item->category }}
                                </span>
                            </td>

                            <!-- No Pallet (1 - 500) -->
                            <td class="py-3.5 px-4 font-mono font-extrabold text-slate-900 whitespace-nowrap">
                                #{{ str_pad($item->pallet_number, 3, '0', STR_PAD_LEFT) }}
                                <span class="text-[10px] text-slate-400 font-medium">/500</span>
                            </td>

                            <!-- Nama Material (Tampil Semua Komponen / Material) -->
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                @if($hasComponents && $allComponentsList->count() > 1)
                                    <div class="space-y-1.5 py-0.5">
                                        @foreach($allComponentsList as $comp)
                                            <div class="flex items-start gap-1.5 text-xs text-slate-900 font-bold leading-snug">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600 shrink-0 mt-1.5"></span>
                                                <div>
                                                    <span>{{ $comp->component_name }}</span>
                                                    @if(!empty($comp->quantity) && $comp->quantity !== '1' && $comp->quantity !== '1 PALLET')
                                                        <span class="text-[10px] font-mono font-semibold text-slate-500 ml-1">({{ $comp->quantity }})</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($hasComponents && $allComponentsList->count() === 1)
                                    <div class="text-xs font-bold text-slate-900 leading-snug">
                                        {{ $allComponentsList->first()->component_name }}
                                    </div>
                                @else
                                    <div class="text-xs font-bold text-slate-900 leading-snug">
                                        {{ $item->material_name }}
                                    </div>
                                @endif
                            </td>

                            <!-- Catatan & Lokasi Rak -->
                            <td class="py-3.5 px-4">
                                <div class="flex flex-col gap-1 items-start">
                                    @if(!empty($item->kolom) || !empty($item->tingkat))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-mono font-extrabold bg-blue-50 text-blue-700 border border-blue-200 whitespace-nowrap">
                                            {{ $item->kolom ? 'Kolom '.$item->kolom : '' }}{{ $item->kolom && $item->tingkat ? ' • ' : '' }}{{ $item->tingkat ? 'Tingkat '.$item->tingkat : '' }}
                                        </span>
                                    @endif
                                    @if(!empty($noteText))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 max-w-[200px] truncate" title="{{ $noteText }}">
                                            {{ $noteText }}
                                        </span>
                                    @elseif(empty($item->kolom) && empty($item->tingkat))
                                        <span class="text-slate-300 italic text-xs">&mdash;</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Operator -->
                            <td class="py-3.5 px-4 font-mono text-[11px] text-slate-600 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700">{{ $item->user_id }}</span>
                            </td>

                            <!-- Waktu Cetak -->
                            <td class="py-3.5 px-4 text-[11px] text-slate-500 whitespace-nowrap">
                                {{ $item->created_at->format('d/m/Y H:i') }}
                            </td>

                            <!-- Aksi -->
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Detail Show Page -->
                                    <a href="{{ route('pallet.show', $item->id) }}" 
                                       class="p-1.5 rounded-xl bg-white hover:bg-slate-100 text-slate-600 hover:text-blue-600 border border-slate-200 transition-all shadow-xs"
                                       title="Lihat Halaman Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    <!-- Edit Sticker Form -->
                                    <a href="{{ route('pallet.edit', $item->id) }}" 
                                       class="p-1.5 rounded-xl bg-white hover:bg-amber-50 text-slate-500 hover:text-amber-700 border border-slate-200 hover:border-amber-200 transition-all shadow-xs"
                                       title="Edit Data Sticker">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </a>

                                    <!-- Print PDF Sticker Stream -->
                                    <a href="{{ route('pallet.pdf.download', ['id' => $item->id, 'mode' => 'stream']) }}" 
                                       target="_blank"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-xs hover:shadow-sm transition-all duration-150"
                                       title="Cetak Dokumen PDF">
                                        <svg class="w-3.5 h-3.5 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                        </svg>
                                        <span>Cetak PDF</span>
                                    </a>

                                    <!-- Delete Button -->
                                    <form method="POST" action="{{ route('pallet.destroy', $item->id) }}" onsubmit="return confirm('Hapus data pallet {{ $item->pallet_code }}?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-1.5 rounded-xl bg-white hover:bg-rose-50 text-slate-400 hover:text-rose-600 border border-slate-200 hover:border-rose-200 transition-all shadow-xs"
                                                title="Hapus riwayat">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="9" class="py-14 text-center">
                                <div class="max-w-xs mx-auto space-y-3">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto text-slate-400">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                                        </svg>
                                    </div>
                                    <div class="text-sm font-bold text-slate-800">Belum Ada Pallet Terdaftar</div>
                                    <p class="text-xs text-slate-500">Mulai buat label sticker pallet baru dari rentang nomor 1 hingga 500.</p>
                                    <a href="{{ route('pallet.create') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700 transition">
                                        Buat Sticker Sekarang
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    
                    <!-- Dynamic No Results Message when filtered out -->
                    <tr id="noResultsFilteredRow" class="hidden">
                        <td colspan="9" class="py-14 text-center">
                            <div class="max-w-xs mx-auto space-y-3">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto text-slate-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                </div>
                                <div class="text-sm font-bold text-slate-800">Tidak Ada Data Yang Sesuai</div>
                                <div class="text-xs text-slate-500">Coba ubah kata kunci pencarian atau pilih filter site yang lain.</div>
                                <button type="button" onclick="resetAllFilters()" class="mt-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 border border-slate-200 rounded-xl text-xs font-bold transition">
                                    Reset Filter & Tampilkan Semua
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

</div>

<!-- ======================================================== -->
<!-- 5. MODAL QUICK PREVIEW STICKER (DARI TABEL)               -->
<!-- ======================================================== -->
<div id="previewModal" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-md hidden items-center justify-center p-4">
    <div class="bg-white border border-slate-200/90 rounded-3xl max-w-sm w-full shadow-2xl relative p-5 animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between pb-3 mb-3.5 border-b border-slate-100">
            <h4 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                <span>Detail Sticker Pallet</span>
            </h4>
            <button type="button" onclick="closePreviewModal()" class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div id="previewModalContent">
            <!-- Populated via Javascript -->
        </div>

        <div class="mt-4 pt-3.5 border-t border-slate-100 flex gap-2">
            <a id="previewModalPdfLink" 
               href="#" 
               target="_blank" 
               class="flex-1 py-2 text-center bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                Cetak Dokumen PDF
            </a>
            <button type="button" onclick="closePreviewModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl border border-slate-200">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- 6. MODAL BATCH RANGE PRINT (RENTANG 1 - 500)              -->
<!-- ======================================================== -->
<div id="batchModal" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-md hidden items-center justify-center p-4">
    <div class="bg-white border border-slate-200/90 rounded-3xl max-w-md w-full shadow-2xl relative p-6 animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between pb-3.5 mb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <h4 class="text-sm font-extrabold text-slate-900">
                    Cetak Sticker Rentang (Batch 1-500)
                </h4>
            </div>
            <button type="button" onclick="closeBatchModal()" class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <p class="text-xs text-slate-500 mb-4 leading-relaxed">
            Cetak beberapa nomor pallet sekaligus dalam 1 dokumen PDF multi-halaman label standar 100 &times; 150 mm.
        </p>

        <form method="GET" action="{{ route('pallet.pdf.download') }}" target="_blank" class="space-y-3.5">
            <input type="hidden" name="mode" value="stream">

            <div>
                <label class="block text-xs font-bold text-slate-800 mb-1">Pilih Site</label>
                <select name="site" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    @foreach($sites as $siteKey => $siteDesc)
                        <option value="{{ $siteKey }}">{{ $siteKey }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-800 mb-1">Pilih Kategori</label>
                <select name="category" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600">
                    <option value="Dressing">Dressing</option>
                    <option value="Consumable">Consumable</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-2.5">
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1">Dari No. Pallet</label>
                    <input type="number" name="start" min="1" max="500" value="1" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 font-mono text-xs focus:bg-white focus:border-blue-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1">Sampai No. Pallet</label>
                    <input type="number" name="end" min="1" max="500" value="10" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 font-mono text-xs focus:bg-white focus:border-blue-600">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" 
                        class="w-full py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xs rounded-xl shadow-md shadow-blue-500/20 active:scale-98 transition-all">
                    Generate Batch PDF Sticker
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let currentSite = 'ALL';
    let currentCategory = 'ALL';
    let currentSearchTerm = '';

    const siteCodeMap = {
        'OKI II': 'OKI2',
        'IKPD': 'IKPD',
        'IKPP': 'IKPP',
        'TELL': 'TELL',
        'ISC': 'ISC'
    };

    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const siteParam = urlParams.get('filter_site') || urlParams.get('site');
        const catParam = urlParams.get('filter_category') || urlParams.get('category');

        if (siteParam) {
            currentSite = siteParam;
            const siteSelect = document.getElementById('siteFilterSelect');
            if (siteSelect) siteSelect.value = siteParam;
        }

        if (catParam) {
            currentCategory = catParam;
        }

        updateCategoryButtonStyles();
        updateFilterBadge();
        filterTableRows();
    });

    function onSiteFilterChange(siteVal) {
        currentSite = siteVal;
        const siteSelect = document.getElementById('siteFilterSelect');
        if (siteSelect && siteSelect.value !== siteVal) {
            siteSelect.value = siteVal;
        }
        updateFilterBadge();
        filterTableRows();
    }

    function onCategoryFilterChange(catVal) {
        currentCategory = catVal;
        updateCategoryButtonStyles();
        updateFilterBadge();
        filterTableRows();
    }

    function resetAllFilters() {
        currentSite = 'ALL';
        currentCategory = 'ALL';
        currentSearchTerm = '';
        
        const siteSelect = document.getElementById('siteFilterSelect');
        if (siteSelect) siteSelect.value = 'ALL';

        const searchInput = document.getElementById('liveSearchInput');
        if (searchInput) searchInput.value = '';

        updateCategoryButtonStyles();
        updateFilterBadge();
        filterTableRows();
    }

    // Backward compatibility helper
    function applyFilter(val, type = 'site') {
        if (type === 'site') {
            onSiteFilterChange(val);
        } else if (type === 'category') {
            onCategoryFilterChange(val);
        }
    }

    function updateCategoryButtonStyles() {
        const buttons = document.querySelectorAll('.cat-filter-btn');
        buttons.forEach(btn => {
            btn.className = 'cat-filter-btn px-3 py-1 rounded-lg text-xs font-semibold text-slate-600 hover:text-slate-900 transition-all flex items-center gap-1.5';
        });

        const activeBtn = document.getElementById('btn_cat_' + currentCategory);
        if (activeBtn) {
            if (currentCategory === 'Dressing') {
                activeBtn.className = 'cat-filter-btn px-3 py-1 rounded-lg text-xs font-bold bg-white text-indigo-700 shadow-xs transition-all flex items-center gap-1.5';
            } else if (currentCategory === 'Consumable') {
                activeBtn.className = 'cat-filter-btn px-3 py-1 rounded-lg text-xs font-bold bg-white text-amber-700 shadow-xs transition-all flex items-center gap-1.5';
            } else {
                activeBtn.className = 'cat-filter-btn px-3 py-1 rounded-lg text-xs font-bold bg-white text-blue-700 shadow-xs transition-all flex items-center gap-1.5';
            }
        }
    }

    function updateFilterBadge() {
        const badge = document.getElementById('activeFilterBadge');
        if (!badge) return;

        const siteText = (currentSite === 'ALL') ? 'Semua Site' : 'Site: ' + currentSite;
        const catText = (currentCategory === 'ALL') ? 'Semua Kategori' : 'Kategori: ' + currentCategory;

        if (currentSite === 'ALL' && currentCategory === 'ALL') {
            badge.innerText = 'Semua Site • Semua Kategori (Seluruh Data)';
            badge.className = 'font-bold text-blue-700 bg-blue-50 border border-blue-200 px-2.5 py-0.5 rounded-lg text-[11px]';
        } else {
            badge.innerText = `${siteText} • ${catText}`;
            badge.className = 'font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-2.5 py-0.5 rounded-lg text-[11px]';
        }
    }

    function onSearchChange(term) {
        currentSearchTerm = term.trim().toLowerCase();
        filterTableRows();
    }

    function filterTableRows() {
        const rows = document.querySelectorAll('.pallet-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const rowSite = row.getAttribute('data-site');
            const rowCategory = row.getAttribute('data-category');
            const rowPallet = row.getAttribute('data-pallet');
            const rowMaterial = (row.getAttribute('data-material') || '').toLowerCase();
            const rowCode = (row.getAttribute('data-code') || '').toLowerCase();
            const rowBatch = (row.getAttribute('data-batch') || '').toLowerCase();
            const rowNotes = (row.getAttribute('data-notes') || '').toLowerCase();

            const matchSite = (currentSite === 'ALL' || rowSite === currentSite);
            const matchCategory = (currentCategory === 'ALL' || rowCategory === currentCategory);

            let matchSearch = true;
            if (currentSearchTerm) {
                matchSearch = rowPallet.includes(currentSearchTerm) ||
                              rowMaterial.includes(currentSearchTerm) ||
                              rowCode.includes(currentSearchTerm) ||
                              rowBatch.includes(currentSearchTerm) ||
                              rowNotes.includes(currentSearchTerm) ||
                              rowSite.toLowerCase().includes(currentSearchTerm);
            }

            if (matchSite && matchCategory && matchSearch) {
                row.classList.remove('hidden');
                visibleCount++;
                const indexCell = row.querySelector('.row-index');
                if (indexCell) indexCell.innerText = visibleCount;
            } else {
                row.classList.add('hidden');
            }
        });

        const visibleRowCountEl = document.getElementById('visibleRowCount');
        if (visibleRowCountEl) {
            visibleRowCountEl.innerText = visibleCount;
        }

        const noResultsRow = document.getElementById('noResultsFilteredRow');
        if (noResultsRow) {
            if (visibleCount === 0 && rows.length > 0) {
                noResultsRow.classList.remove('hidden');
            } else {
                noResultsRow.classList.add('hidden');
            }
        }
    }

    // Modal creation handlers
    let modalSite = 'OKI II';
    let modalCategory = 'Dressing';
    let modalPallet = 1;

    function openCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
        document.getElementById('createModal').classList.add('flex');
        updateModalPreview();
    }

    function closeCreateModal() {
        document.getElementById('createModal').classList.remove('flex');
        document.getElementById('createModal').classList.add('hidden');
    }

    function onModalSiteChange(site) {
        modalSite = site;
        updateModalPreview();
    }

    function onModalCategoryChange(cat) {
        modalCategory = cat;
        if (cat === 'Dressing') {
            document.getElementById('modal_material_name').value = 'Roll Dressing Unit 450mm';
        } else {
            document.getElementById('modal_material_name').value = 'Stretch Film Roll 500mm x 300m';
        }
        updateModalPreview();
    }

    function adjustModalPallet(delta) {
        let val = parseInt(document.getElementById('modal_pallet_number').value) || 1;
        val += delta;
        if (val < 1) val = 1;
        if (val > 500) val = 500;
        document.getElementById('modal_pallet_number').value = val;
        document.getElementById('modalPalletSlider').value = val;
        onModalPalletInput(val);
    }

    function onModalPalletInput(val) {
        let num = parseInt(val) || 1;
        if (num < 1) num = 1;
        if (num > 500) num = 500;
        modalPallet = num;
        document.getElementById('modalPalletSlider').value = num;
        
        const numPadded = String(num).padStart(3, '0');
        const today = new Date();
        const ymd = today.getFullYear() + String(today.getMonth() + 1).padStart(2, '0') + String(today.getDate()).padStart(2, '0');
        document.getElementById('modal_batch_no').value = `BATCH-${ymd}-${numPadded}`;
        updateModalPreview();
    }

    function updateModalPreview() {
        const catCode = 'I-COS';
        const catTitle = modalCategory === 'Dressing' ? 'Dressing Material' : 'Consumable Material';

        const numEl = document.getElementById('modalCardPalletNumber');
        if (numEl) numEl.textContent = modalPallet;

        const codeEl = document.getElementById('modalCardCategoryCode');
        if (codeEl) codeEl.textContent = catCode;

        const titleEl = document.getElementById('modalCardCategoryTitle');
        if (titleEl) titleEl.textContent = catTitle;
    }

    function directDownloadFromModal() {
        const site = modalSite;
        const category = modalCategory;
        const pallet = modalPallet;
        const material = encodeURIComponent(document.getElementById('modal_material_name').value);
        const batch = encodeURIComponent(document.getElementById('modal_batch_no').value);
        const qty = encodeURIComponent(document.getElementById('modal_quantity').value);

        const url = `{{ route('pallet.pdf.download') }}?site=${encodeURIComponent(site)}&category=${encodeURIComponent(category)}&pallet=${pallet}&material_name=${material}&batch_no=${batch}&quantity=${qty}&mode=stream`;
        window.open(url, '_blank');
    }

    function previewStickerModal(item) {
        const content = document.getElementById('previewModalContent');
        const pdfUrl = `{{ url('/pallet/pdf') }}/${item.id}?mode=stream`;
        const catCode = 'I-COS';
        const catTitle = item.category === 'Dressing' ? 'Dressing Material' : 'Consumable Material';

        content.innerHTML = `
            <div class="w-full bg-white border-[6px] border-[#0047BA] rounded-[22px] shadow-2xl overflow-hidden select-none font-sans relative flex flex-col justify-between" style="aspect-ratio: 430 / 345;">
                <div class="px-4 pt-4 pb-1 flex flex-col justify-between flex-1">
                    <!-- 1. OFFICIAL ANDRITZ LOGO -->
                    <div class="w-full flex justify-center items-center">
                        <svg viewBox="0 0 1065 210" class="w-[82%] max-w-[280px] h-auto" xmlns="http://www.w3.org/2000/svg">
                            <g transform="translate(120, -290)" fill="#0047BA">
                                <path d="M 52.156894,493.69536 L 26.281784,433.16297 L -19.436146,433.16297 L 9.6166143,370.94573 L 65.173024,493.69536 L 143.26976,493.69536 L 43.104104,293.58229 L -25.468436,293.58229 L -119.91608,493.69536 L 52.156894,493.69536 z" />
                                <path d="M 574.18111,293.58229 L 574.18111,352.9187 L 654.22461,352.9187 L 654.22461,495.43259 L 719.47111,495.43259 L 719.47111,353.16314 L 749.04761,353.16314 L 774.46011,293.58229 L 574.18111,293.58229 z" />
                                <path d="M 793.51721,293.58229 L 766.88261,353.16314 L 794.73941,353.16314 L 730.47061,495.43259 L 943.07601,495.43259 L 914.73041,434.78671 L 832.37351,434.78671 L 888.34021,293.58229 L 793.51721,293.58229 z" />
                                <path d="M 95.936814,373.64323 L 95.936814,293.58229 L 157.47313,293.58229 L 216.83573,383.61266 L 216.83573,293.58229 L 280.86877,293.58229 C 366.70884,293.58229 395.52589,352.62189 395.52589,352.62189 L 395.52589,293.58229 L 493.88448,293.58229 C 522.18651,293.58229 556.45091,302.18987 564.53471,345.11426 C 572.40021,386.89506 532.76701,401.32541 532.76701,401.32541 L 574.18111,459.073 L 574.18111,365.33247 L 639.60221,365.33247 L 639.60221,495.43259 L 515.44711,495.43259 L 462.12549,400.55719 L 462.12549,495.43259 L 395.52589,495.43259 L 395.52589,435.58985 C 359.52422,501.22918 290.03506,495.43259 290.03506,495.43259 L 290.03506,435.20574 C 290.03506,435.20574 336.42518,433.66057 336.42518,392.08929 C 336.42518,350.50928 280.41482,353.39884 280.41482,353.39884 L 280.41482,495.43259 L 214.19934,495.43259 L 160.10953,405.75142 L 160.10953,493.69536 L 154.52246,493.69536 L 95.936814,373.64323 z M 461.78503,386.40619 C 472.66235,390.92822 500.14375,386.88633 500.14375,365.90863 C 500.14375,344.92221 461.78503,350.70133 461.78503,350.70133 L 461.78503,386.40619 z" />
                            </g>
                        </svg>
                    </div>
                    <!-- Blue Line -->
                    <div class="w-full h-[3px] bg-[#0047BA] my-1"></div>
                    <!-- Hero Row -->
                    <div class="flex items-center justify-center gap-3 my-0.5">
                        <span class="w-10 h-[5px] bg-[#0047BA] rounded-xs shrink-0"></span>
                        <span class="text-2xl sm:text-3xl font-black text-black tracking-tight text-center font-sans whitespace-nowrap leading-none">
                            Pallet #${item.pallet_number}
                        </span>
                        <span class="w-10 h-[5px] bg-[#0047BA] rounded-xs shrink-0"></span>
                    </div>
                    <!-- Banner -->
                    <div class="w-full my-0.5">
                        <svg viewBox="0 0 620 66" class="w-full h-auto" xmlns="http://www.w3.org/2000/svg">
                            <polygon points="15,62 36,62 58,4 37,4" fill="#0047BA" />
                            <polygon points="49,62 571,62 593,4 71,4" fill="#0047BA" />
                            <polygon points="584,62 605,62 627,4 606,4" fill="#0047BA" />
                            <text x="325" y="47" font-family="'Plus Jakarta Sans', Arial, Helvetica, sans-serif" font-weight="900" font-size="38" fill="#ffffff" text-anchor="middle" letter-spacing="2.5px">${catCode}</text>
                        </svg>
                    </div>
                    <!-- Subtitle -->
                    <div class="text-center font-black text-black text-lg tracking-tight leading-tight">
                        ${catTitle}
                    </div>
                    <!-- Warning Row -->
                    <div class="flex items-center gap-2.5 my-1">
                        <div class="shrink-0 w-10 h-8 flex items-center justify-center">
                            <svg viewBox="0 0 54 48" class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                                <path d="M 27 3 L 51 43 C 51.8 44.5 50.8 46 49 46 L 5 46 C 3.2 46 2.2 44.5 3 43 L 27 3 Z" fill="#0047BA" stroke="#0047BA" stroke-width="2" stroke-linejoin="round"/>
                                <path d="M 27 8 L 47 42 L 7 42 Z" stroke="#ffffff" stroke-width="2.2" stroke-linejoin="round" fill="#0047BA"/>
                                <rect x="25.5" y="16" width="3" height="13" rx="1.5" fill="#ffffff"/>
                                <circle cx="27" cy="35" r="2" fill="#ffffff"/>
                            </svg>
                        </div>
                        <div class="flex-1 border-[2.2px] border-[#0047BA] rounded-lg py-1.5 px-2 text-center font-black text-black text-xs tracking-tight bg-white">
                            Dont Open Without Confirmation
                        </div>
                    </div>
                </div>
                <!-- Footer -->
                <div class="bg-[#0047BA] text-white px-3 py-1.5 flex items-center justify-between mt-0.5">
                    <div class="flex items-center min-w-0">
                        <svg viewBox="0 0 264 202" class="h-5 w-auto shrink-0" fill="#ffffff" xmlns="http://www.w3.org/2000/svg">
                            <g transform="translate(120, -292)">
                                <path d="M 52.156894,493.69536 L 26.281784,433.16297 L -19.436146,433.16297 L 9.6166143,370.94573 L 65.173024,493.69536 L 143.26976,493.69536 L 43.104104,293.58229 L -25.468436,293.58229 L -119.91608,493.69536 L 52.156894,493.69536 z" />
                            </g>
                        </svg>
                        <div class="w-[1.5px] h-4 bg-white mx-2 shrink-0"></div>
                        <span class="font-black text-[11px] sm:text-xs tracking-wide whitespace-nowrap leading-none">Andritz For The Change</span>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <div class="w-5 h-5 border-[1.2px] border-white rounded-[3px] flex items-center justify-center p-0.5" title="This Way Up">
                            <svg viewBox="0 0 28 28" class="w-full h-full" fill="none" stroke="#ffffff" xmlns="http://www.w3.org/2000/svg"><line x1="4" y1="23" x2="24" y2="23" stroke-width="2.5" stroke-linecap="round"/><path d="M9 20 L9 9 M6 11 L9 5 L12 11" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><path d="M19 20 L19 9 M16 11 L19 5 L22 11" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <div class="w-5 h-5 border-[1.2px] border-white rounded-[3px] flex items-center justify-center p-0.5" title="Fragile">
                            <svg viewBox="0 0 28 28" class="w-full h-full" xmlns="http://www.w3.org/2000/svg"><path d="M7 5 L21 5 C21 13 16 15 15 15 L15 21 L19 21 L19 23 L9 23 L9 21 L13 21 L13 15 C12 15 7 13 7 5 Z" fill="#ffffff"/><path d="M12 5 L10 9 L13 11 L11 14" stroke="#0047BA" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>
                        </div>
                        <div class="w-5 h-5 border-[1.2px] border-white rounded-[3px] flex items-center justify-center p-0.5" title="Keep Dry">
                            <svg viewBox="0 0 28 28" class="w-full h-full" xmlns="http://www.w3.org/2000/svg"><path d="M5 16 C5 10 9 6.5 14 6.5 C19 6.5 23 10 23 16 C23 16 20 14.5 17 15 C14.5 15.5 13.5 15.5 11 15 C8.5 14.5 5 16 5 16 Z" fill="#ffffff"/><path d="M14 7 L14 20 C14 22 12.5 23 11 23 C9.5 23 9 22 9 21" stroke="#ffffff" stroke-width="1.8" stroke-linecap="round" fill="none"/><line x1="18" y1="3.5" x2="17" y2="5.5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round"/><line x1="21" y1="4.5" x2="20" y2="6.5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round"/><line x1="23" y1="6.5" x2="22" y2="8.5" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round"/></svg>
                        </div>
                        <div class="w-5 h-5 border-[1.2px] border-white rounded-[3px] flex items-center justify-center p-0.5" title="Protect">
                            <svg viewBox="0 0 28 28" class="w-full h-full" fill="none" stroke="#ffffff" xmlns="http://www.w3.org/2000/svg"><rect x="4" y="4" width="20" height="20" rx="1.5" stroke-width="1.6"/><line x1="5" y1="5" x2="23" y2="23" stroke-width="1.6"/><line x1="23" y1="5" x2="5" y2="23" stroke-width="1.6"/><polygon points="14,7 21,14 14,21 7,14" stroke-width="1.4" fill="#0047BA"/></svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-2.5 p-2 rounded-xl bg-slate-50 border border-slate-200 text-[10.5px] text-slate-600 space-y-0.5">
                <div class="flex justify-between font-mono"><span>Kode: <strong>${item.pallet_code}</strong></span> <span>Site: <strong>${item.site}</strong></span></div>
                <div>Material: <strong class="text-slate-900">${item.material_name || '-'}</strong> (${item.quantity || '1 PALLET'})</div>
            </div>
        `;

        document.getElementById('previewModalPdfLink').href = pdfUrl;
        document.getElementById('previewModal').classList.remove('hidden');
        document.getElementById('previewModal').classList.add('flex');
    }

    function closePreviewModal() {
        document.getElementById('previewModal').classList.remove('flex');
        document.getElementById('previewModal').classList.add('hidden');
    }

    function openBatchModal() {
        document.getElementById('batchModal').classList.remove('hidden');
        document.getElementById('batchModal').classList.add('flex');
    }

    function closeBatchModal() {
        document.getElementById('batchModal').classList.remove('flex');
        document.getElementById('batchModal').classList.add('hidden');
    }
</script>
@endpush
