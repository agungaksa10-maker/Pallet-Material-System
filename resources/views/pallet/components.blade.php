@extends('layouts.app')

@section('title', 'Cari Komponen & Lokasi Pallet')

@section('content')
<div class="space-y-6">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <a href="{{ route('pallet.index') }}" class="hover:text-blue-600 transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <span>/</span>
                <span class="text-slate-800 font-bold">Cari Komponen di Pallet</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2.5">
                <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                <span>Component Locator &amp; Pelacak Pallet</span>
                <span class="text-xs font-mono font-bold px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                    Pencarian Real-Time
                </span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Lacak keberadaan komponen atau material spesifik (contoh: <em>Knife Run</em>, <em>Roll Dressing</em>, dsb) dan temukan nomor pallet serta site penyimpanannya.
            </p>
        </div>

    </div>

    <!-- Metrics Summary Banner -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Total Components -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Komponen Terdata</span>
                <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $totalComponents }}</div>
                <span class="text-[11px] text-slate-400">Tersimpan dalam sistem</span>
            </div>
        </div>

        <!-- Pallets with components -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Pallet Berisi Komponen</span>
                <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $totalPalletsWithComponents }}</div>
                <span class="text-[11px] text-slate-400">Pallet aktif terdaftar</span>
            </div>
        </div>

        <!-- Site Coverage -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Pabrik &amp; Fasilitas</span>
                <div class="text-2xl font-black text-slate-900 tracking-tight">5 Site</div>
                <span class="text-[11px] text-slate-400">OKI II, IKPD, IKPP, TELL, ISC</span>
            </div>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs space-y-5">
        <form method="GET" action="{{ route('pallet.components') }}" id="searchForm" class="space-y-4">
            
            <!-- Large Search Input Box -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Ketik nama komponen (contoh: Knife Run, Roll Dressing, Diamond Tool, Blade, dll)..." 
                       class="w-full pl-12 pr-28 py-3.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-2xl text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition shadow-inner">
                
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center gap-1.5">
                    @if(!empty($search) || !empty($selectedSite) || !empty($selectedCategory))
                        <a href="{{ route('pallet.components') }}" 
                           class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold transition"
                           title="Reset filter">
                            Reset
                        </a>
                    @endif
                    <button type="submit" 
                            class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-xs transition">
                        Cari
                    </button>
                </div>
            </div>

            <!-- Site Filter Buttons -->
            <div class="space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold text-slate-700 uppercase tracking-wider text-[11px] flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                        Filter Berdasarkan Site / Pabrik:
                    </span>
                    @if(!empty($selectedSite))
                        <span class="font-mono text-[11px] text-blue-600 font-bold">Terpilih: {{ $selectedSite }}</span>
                    @endif
                </div>
                
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('pallet.components', array_merge(request()->query(), ['site' => ''])) }}" 
                       class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ empty($selectedSite) ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        <span>Semua Site</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ empty($selectedSite) ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-600' }}">
                            {{ $totalComponents }}
                        </span>
                    </a>

                    @foreach($sites as $siteKey => $siteLabel)
                        @php
                            $isSelected = ($selectedSite === $siteKey);
                            $badgeColor = match($siteKey) {
                                'OKI II' => 'border-blue-300 text-blue-800 bg-blue-50 hover:bg-blue-100',
                                'IKPD' => 'border-emerald-300 text-emerald-800 bg-emerald-50 hover:bg-emerald-100',
                                'IKPP' => 'border-purple-300 text-purple-800 bg-purple-50 hover:bg-purple-100',
                                'TELL' => 'border-amber-300 text-amber-800 bg-amber-50 hover:bg-amber-100',
                                'ISC' => 'border-cyan-300 text-cyan-800 bg-cyan-50 hover:bg-cyan-100',
                                default => 'border-slate-300 text-slate-800 bg-slate-50',
                            };
                            $activeColor = match($siteKey) {
                                'OKI II' => 'bg-blue-600 text-white border-blue-600',
                                'IKPD' => 'bg-emerald-600 text-white border-emerald-600',
                                'IKPP' => 'bg-purple-600 text-white border-purple-600',
                                'TELL' => 'bg-amber-600 text-white border-amber-600',
                                'ISC' => 'bg-cyan-600 text-white border-cyan-600',
                                default => 'bg-blue-600 text-white',
                            };
                        @endphp
                        <a href="{{ route('pallet.components', array_merge(request()->query(), ['site' => $isSelected ? '' : $siteKey])) }}" 
                           class="px-3.5 py-1.5 rounded-xl text-xs font-bold border transition flex items-center gap-1.5 {{ $isSelected ? $activeColor : $badgeColor }}">
                            <span>{{ $siteKey }}</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $isSelected ? 'bg-white/20 text-white' : 'bg-white/60 text-slate-700' }}">
                                {{ $siteCounts[$siteKey] ?? 0 }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

        </form>
    </div>

    <!-- Results Table / Cards -->
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden">
        
        <div class="p-4 sm:px-6 border-b border-slate-200 bg-slate-50/60 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                    Daftar Lokasi Pallet Komponen ({{ $components->total() }})
                </span>
            </div>
            <span class="text-[11px] font-mono text-slate-500">
                Menampilkan {{ $components->firstItem() ?? 0 }} - {{ $components->lastItem() ?? 0 }} dari {{ $components->total() }}
            </span>
        </div>

        @if($components->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-100/70 border-b border-slate-200 text-slate-600 font-bold text-[11px] uppercase tracking-wider">
                            <th class="py-3.5 px-4 sm:px-6">Nama Komponen</th>
                            <th class="py-3.5 px-4">Nomor Pallet</th>
                            <th class="py-3.5 px-4 text-center">Lokasi Rak</th>
                            <th class="py-3.5 px-4">Site Pabrik</th>
                            <th class="py-3.5 px-4">Kategori</th>
                            <th class="py-3.5 px-4">Catatan</th>
                            <th class="py-3.5 px-4">Jumlah</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($components as $item)
                            @php
                                $pallet = $item->palletSticker;
                                $siteColor = match($pallet?->site) {
                                    'OKI II' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'IKPD' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'IKPP' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'TELL' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'ISC' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                    default => 'bg-slate-50 text-slate-700 border-slate-200',
                                };
                                $itemKolom = $item->kolom ?: $pallet?->kolom;
                                $itemTingkat = $item->tingkat ?: $pallet?->tingkat;
                            @endphp
                            <tr class="hover:bg-blue-50/30 transition-colors group">
                                
                                <!-- Component Name -->
                                <td class="py-4 px-4 sm:px-6">
                                    <div class="font-extrabold text-sm text-slate-900 group-hover:text-blue-600 transition flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-blue-500 shrink-0"></span>
                                        <span>{{ $item->component_name }}</span>
                                    </div>
                                </td>

                                <!-- Pallet Number -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg bg-slate-900 text-white font-mono font-black text-xs shadow-xs">
                                        Pallet #{{ $pallet?->pallet_number }}
                                    </span>
                                </td>

                                <!-- Lokasi Rak -->
                                <td class="py-4 px-4 whitespace-nowrap text-center">
                                    @if(!empty($itemKolom) || !empty($itemTingkat))
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg font-mono font-extrabold text-xs bg-blue-50 text-blue-800 border border-blue-200 shadow-2xs whitespace-nowrap">
                                            {{ $itemKolom ? 'Kolom '.$itemKolom : '' }}{{ $itemKolom && $itemTingkat ? ' • ' : '' }}{{ $itemTingkat ? 'Tingkat '.$itemTingkat : '' }}
                                        </span>
                                    @else
                                        <span class="text-slate-300 italic text-xs">&mdash;</span>
                                    @endif
                                </td>

                                <!-- Site -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-extrabold border {{ $siteColor }}">
                                        {{ $pallet?->site }}
                                    </span>
                                </td>

                                <!-- Category -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $pallet?->category === 'Dressing' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                        {{ $pallet?->category }}
                                    </span>
                                </td>

                                <!-- Catatan Pallet -->
                                <td class="py-4 px-4">
                                    @php
                                        $noteText = $pallet?->notes ?: $item->notes;
                                    @endphp
                                    @if(!empty($noteText))
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 max-w-[240px] truncate" title="{{ $noteText }}">
                                            {{ $noteText }}
                                        </span>
                                    @else
                                        <span class="text-slate-300 italic text-xs">&mdash;</span>
                                    @endif
                                </td>

                                <!-- Quantity -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="font-bold text-slate-800">{{ $item->quantity ?: '1' }}</div>
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if($pallet)
                                            <a href="{{ route('pallet.show', $pallet->id) }}" 
                                               class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-700 font-semibold text-xs transition flex items-center gap-1"
                                               title="Lihat Detail Pallet">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                <span>Pallet #{{ $pallet->pallet_number }}</span>
                                            </a>

                                            <a href="{{ route('pallet.pdf.download', ['id' => $pallet->id, 'mode' => 'stream']) }}" 
                                               target="_blank"
                                               class="p-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-[#0047BA] transition"
                                               title="Cetak Stiker PDF">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                                </svg>
                                            </a>

                                            <!-- Hapus Pallet -->
                                            <form method="POST" action="{{ route('pallet.destroy', $pallet->id) }}" onsubmit="return confirm('Hapus data pallet #{{ $pallet->pallet_number }} ({{ $pallet->pallet_code }})?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="p-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 transition cursor-pointer"
                                                        title="Hapus Pallet">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($components->hasPages())
                <div class="p-4 border-t border-slate-200">
                    {{ $components->links() }}
                </div>
            @endif

        @else
            <!-- Empty State -->
            <div class="text-center py-16 px-4">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 border border-blue-100 text-blue-600 mx-auto flex items-center justify-center mb-4 shadow-xs">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>
                    </svg>
                </div>
                <h3 class="text-base font-extrabold text-slate-900 tracking-tight">
                    Komponen Tidak Ditemukan
                </h3>
                <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto leading-relaxed">
                    @if(!empty($search))
                        Tidak ada komponen bernama <strong>"{{ $search }}"</strong>
                        @if(!empty($selectedSite)) di Site <strong>{{ $selectedSite }}</strong> @endif
                        yang tersimpan di dalam pallet manapun.
                    @else
                        Belum ada data komponen yang terdaftar untuk filter yang dipilih.
                    @endif
                </p>
                <div class="mt-5 flex items-center justify-center gap-3">
                    <a href="{{ route('pallet.components') }}" 
                       class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                        Bersihkan Filter
                    </a>
                    <a href="{{ route('pallet.create') }}" 
                       class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition">
                        + Tambah Komponen ke Pallet Baru
                    </a>
                </div>
            </div>
        @endif

    </div>

</div>
@endsection
