@extends('layouts.app')

@section('title', 'Detail Sticker ' . $sticker->pallet_code)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- Top Breadcrumb & Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mb-1">
                <a href="{{ route('pallet.index') }}" class="hover:text-blue-600 transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <span>/</span>
                <span class="text-slate-800">Detail Sticker Pallet</span>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight font-mono">
                    {{ $sticker->pallet_code }}
                </h1>
                
                @php
                    $siteBadge = match($sticker->site) {
                        'OKI II' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'IKPD' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'IKPP' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                        'TELL' => 'bg-purple-50 text-purple-700 border-purple-200',
                        'ISC' => 'bg-orange-50 text-orange-700 border-orange-200',
                        default => 'bg-slate-50 text-slate-700 border-slate-200',
                    };
                    $siteDot = match($sticker->site) {
                        'OKI II' => 'bg-blue-500',
                        'IKPD' => 'bg-emerald-500',
                        'IKPP' => 'bg-cyan-500',
                        'TELL' => 'bg-purple-500',
                        'ISC' => 'bg-orange-500',
                        default => 'bg-slate-500',
                    };
                @endphp
                <span class="inline-flex items-center gap-1.5 font-bold text-xs px-2.5 py-1 rounded-lg border {{ $siteBadge }}">
                    <span class="w-2 h-2 rounded-full {{ $siteDot }}"></span>
                    <span>{{ $sticker->site }}</span>
                </span>

                <span class="px-2.5 py-1 rounded-lg text-xs font-bold border {{ $sticker->category === 'Dressing' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'bg-amber-50 text-amber-700 border-amber-200' }}">
                    {{ $sticker->category }}
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Data spesifikasi teknis dan preview stiker termal siap cetak.
            </p>
        </div>

        <!-- Action Toolbar -->
        <div class="flex flex-wrap items-center gap-2">
            <!-- Edit Button -->
            <a href="{{ route('pallet.edit', $sticker->id) }}" 
               class="px-3.5 py-2 rounded-xl bg-white hover:bg-amber-50 text-amber-700 hover:text-amber-800 border border-slate-200 hover:border-amber-300 text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                <span>Edit Data</span>
            </a>

            <!-- Print PDF Stream -->
            <a href="{{ route('pallet.pdf.download', ['id' => $sticker->id, 'mode' => 'stream']) }}" 
               target="_blank"
               class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold shadow-sm hover:shadow-md shadow-blue-500/20 transition flex items-center gap-1.5 active:scale-95">
                <svg class="w-4 h-4 text-white stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>Cetak PDF</span>
            </a>

            <!-- Delete Button -->
            <form method="POST" action="{{ route('pallet.destroy', $sticker->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data sticker {{ $sticker->pallet_code }}? Data yang dihapus tidak dapat dikembalikan.')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="p-2 rounded-xl bg-white hover:bg-rose-50 text-slate-400 hover:text-rose-600 border border-slate-200 hover:border-rose-200 transition shadow-xs"
                        title="Hapus sticker">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Grid: Technical Specs on Left (7 cols), Physical Label on Right (5 cols) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Left Column: Technical Specifications -->
        <div class="lg:col-span-7 space-y-6">

            <!-- Card 1: Identifikasi Utama -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                    </div>
                    <h3 class="text-sm font-extrabold text-slate-900">Identifikasi & Lokasi Pallet</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/60 space-y-1">
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Site / Pabrik</div>
                        <div class="text-sm font-extrabold text-slate-900">{{ $sites[$sticker->site] ?? $sticker->site }}</div>
                        <div class="text-[10px] text-slate-500 font-mono">Kode Site: {{ $sticker->site }}</div>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/60 space-y-1">
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kategori Material</div>
                        <div class="text-sm font-extrabold text-slate-900">{{ $categories[$sticker->category] ?? $sticker->category }}</div>
                        <div class="text-[10px] text-slate-500 font-mono">Tipe: {{ $sticker->category }}</div>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/60 space-y-1">
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nomor Urut Pallet</div>
                        <div class="text-lg font-mono font-black text-slate-900">
                            #{{ str_pad($sticker->pallet_number, 3, '0', STR_PAD_LEFT) }}
                            <span class="text-xs font-normal text-slate-400">/ 500</span>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-xl bg-blue-50/50 border border-blue-100 space-y-1">
                        <div class="text-[11px] font-bold text-blue-600 uppercase tracking-wider">Kode Standar Barcode</div>
                        <div class="text-base font-mono font-black text-blue-800 tracking-tight">
                            {{ $sticker->pallet_code }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Rincian Komponen & Muatan Pallet -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-extrabold text-slate-900">Komponen & Isi Muatan Pallet</h3>
                    </div>
                    @if($sticker->components && $sticker->components->isNotEmpty())
                        <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 font-bold text-xs border border-blue-200 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                            <span>{{ $sticker->components->count() }} Komponen</span>
                        </span>
                    @endif
                </div>

                @if($sticker->components && $sticker->components->isNotEmpty())
                    <!-- Component Items Table -->
                    <div class="overflow-x-auto border border-slate-200 rounded-xl">
                        <table class="w-full text-xs text-left">
                            <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-200">
                                <tr>
                                    <th class="px-3.5 py-2.5 w-10 text-center">NO</th>
                                    <th class="px-3.5 py-2.5">Nama Komponen / Material</th>
                                    <th class="px-3.5 py-2.5 w-28">Jumlah / Qty</th>
                                    <th class="px-3.5 py-2.5 w-36">Batch No</th>
                                    <th class="px-3.5 py-2.5">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium text-slate-800">
                                @foreach($sticker->components as $idx => $comp)
                                    <tr class="hover:bg-blue-50/30 transition">
                                        <td class="px-3.5 py-2.5 text-center font-mono font-bold text-slate-400">{{ $idx + 1 }}</td>
                                        <td class="px-3.5 py-2.5 font-bold text-slate-900 flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-blue-500 shrink-0"></span>
                                            <span>{{ $comp->component_name }}</span>
                                        </td>
                                        <td class="px-3.5 py-2.5 font-bold text-slate-700 font-mono">{{ $comp->quantity ?: '-' }}</td>
                                        <td class="px-3.5 py-2.5 text-slate-600 font-mono text-[11px]">{{ $comp->batch_no ?: '-' }}</td>
                                        <td class="px-3.5 py-2.5 text-slate-500 italic text-[11px]">{{ $comp->notes ?: '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 text-xs">
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60">
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Ringkasan Label</div>
                            <div class="font-bold text-slate-800 truncate">{{ $sticker->material_name }}</div>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60">
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Nomor Batch Utama</div>
                            <div class="font-mono font-bold text-slate-800 truncate">{{ $sticker->batch_no ?: '-' }}</div>
                        </div>
                    </div>
                @else
                    <div class="space-y-3 text-xs">
                        <div>
                            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Material / Item</div>
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60 font-bold text-sm text-slate-900">
                                {{ $sticker->material_name ?: '-' }}
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nomor Batch Produksi</div>
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60 font-mono font-bold text-slate-900">
                                    {{ $sticker->batch_no ?: '-' }}
                                </div>
                            </div>

                            <div>
                                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Jumlah / Volume Satuan</div>
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60 font-bold text-slate-900">
                                    {{ $sticker->quantity ?: '1 PALLET' }}
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Catatan Tambahan</div>
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60 text-slate-700 italic">
                                {{ $sticker->notes ?: 'Tidak ada catatan khusus.' }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Card 3: Audit Trail & Operator -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs space-y-3 text-xs text-slate-600">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                    <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                    </div>
                    <h3 class="text-sm font-extrabold text-slate-900">Informasi Log & Audit</h3>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-1">
                    <div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase">Operator / Petugas</div>
                        <div class="font-mono font-bold text-slate-900 mt-0.5">{{ $sticker->user_id }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase">Waktu Dibuat</div>
                        <div class="font-mono text-slate-800 mt-0.5">{{ $sticker->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase">Terakhir Update</div>
                        <div class="font-mono text-slate-800 mt-0.5">{{ $sticker->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase">Cetak Terakhir</div>
                        <div class="font-mono text-slate-800 mt-0.5">{{ $sticker->printed_at ? $sticker->printed_at->format('d/m/Y H:i') : '-' }}</div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Physical Thermal Sticker Presentation -->
        <div class="lg:col-span-5 space-y-4 lg:sticky lg:top-20">

            <div class="flex items-center justify-between px-1">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#0047BA] animate-pulse"></span>
                    <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Format Fisik Sticker Pallet</h3>
                </div>
                <span class="text-[11px] font-mono text-slate-500">ANDRITZ Standard</span>
            </div>

            <!-- ANDRITZ Pallet Sticker Card -->
            <x-andritz-sticker 
                :pallet-number="$sticker->pallet_number" 
                :category="$sticker->category" 
                :category-code="$payload['category_code'] ?? null" 
                id-prefix="show" />

            <!-- Direct Print Button under preview -->
            <a href="{{ route('pallet.pdf.download', ['id' => $sticker->id, 'mode' => 'stream']) }}" 
               target="_blank"
               class="w-full py-2.5 rounded-xl bg-[#0047BA] hover:bg-blue-800 text-white font-extrabold text-xs flex items-center justify-center gap-2 shadow-sm hover:shadow-md transition active:scale-95">
                <svg class="w-4 h-4 text-white stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>Cetak Sticker ANDRITZ (PDF)</span>
            </a>


        </div>

    </div>

</div>
@endsection
