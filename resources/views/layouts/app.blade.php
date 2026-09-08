<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') &middot; Pallet Material System</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/andritz-logo.svg') }}">

    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
        }
        .font-mono {
            font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, monospace;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            .print-only {
                display: block !important;
            }
            body {
                background: white !important;
                color: black !important;
            }
            .main-content-wrapper {
                padding-left: 0 !important;
            }
        }
        .sidebar-dark {
            background-color: #121212 !important;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-full antialiased text-slate-800 selection:bg-blue-500/20 selection:text-blue-900">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div id="sidebarBackdrop" onclick="toggleSidebar()" class="fixed inset-0 z-40 bg-black/60 backdrop-blur-xs hidden lg:hidden no-print transition-opacity"></div>

    <div class="min-h-screen flex bg-slate-50">

        <!-- ======================================================== -->
        <!-- LEFT SIDEBAR (DARK/BLACK ENTERPRISE MENU LIKE REFERENCE) -->
        <!-- ======================================================== -->
        <aside id="appSidebar" class="no-print fixed inset-y-0 left-0 z-50 w-64 lg:w-72 sidebar-dark text-slate-300 flex flex-col border-r border-neutral-800/80 transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 shadow-2xl lg:shadow-none">
            
            <!-- Top Brand Header in Sidebar -->
            <div class="h-18 px-5 flex items-center justify-between border-b border-neutral-800/90 shrink-0">
                <a href="{{ route('pallet.index') }}" class="flex items-center gap-3 group">
                    <div class="h-9 px-2.5 bg-white rounded-xl shadow-md flex items-center justify-center group-hover:scale-105 transition-transform border border-white/20">
                        <img src="{{ asset('images/andritz-logo.svg') }}" alt="ANDRITZ" class="h-5 w-auto object-contain">
                    </div>
                    <div class="flex flex-col">
                        <span class="font-extrabold text-sm text-white tracking-tight leading-tight">
                            Pallet System
                        </span>
                        <span class="text-[10px] text-slate-400 font-mono tracking-wider uppercase">
                            ANDRITZ Operations
                        </span>
                    </div>
                </a>

                <!-- Mobile Close Button -->
                <button type="button" onclick="toggleSidebar()" class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-neutral-800 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation Links (Scrollable Container) -->
            <div class="flex-1 overflow-y-auto px-3.5 py-4 space-y-6">

                <!-- Main Menu Section -->
                <div>
                    <div class="px-3 pb-2 text-[10px] font-mono font-bold tracking-wider text-slate-500 uppercase">
                        Menu Utama
                    </div>

                    <div class="space-y-1.5">
                        
                        <!-- 1. Dashboard -->
                        @php $isDashboard = request()->routeIs('pallet.index'); @endphp
                        <a href="{{ route('pallet.index') }}" 
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-150 {{ $isDashboard ? 'bg-[#0047BA] text-white shadow-md shadow-blue-950/40 ring-1 ring-white/10' : 'text-slate-300 hover:text-white hover:bg-neutral-800/70' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-lg flex items-center justify-center {{ $isDashboard ? 'bg-white/20 text-white' : 'text-slate-400' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                </div>
                                <span class="tracking-tight text-[13px]">Dashboard &amp; Tabel Data</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 {{ $isDashboard ? 'text-white' : 'opacity-40' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>

                        <!-- 2. Buat Sticker Baru -->
                        @php $isCreate = request()->routeIs('pallet.create'); @endphp
                        <a href="{{ route('pallet.create') }}" 
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-150 {{ $isCreate ? 'bg-[#0047BA] text-white shadow-md shadow-blue-950/40 ring-1 ring-white/10' : 'text-slate-300 hover:text-white hover:bg-neutral-800/70' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-lg flex items-center justify-center {{ $isCreate ? 'bg-white/20 text-white' : 'text-slate-400' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                                <span class="tracking-tight text-[13px]">Buat Sticker Pallet Baru</span>
                            </div>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-mono {{ $isCreate ? 'bg-white/20 text-white' : 'bg-neutral-800 text-slate-400' }}">
                                Multi
                            </span>
                        </a>

                        <!-- 3. Master Material / Spare Part -->
                        @php $isMaster = request()->routeIs('master-materials.*'); @endphp
                        <a href="{{ route('master-materials.index') }}" 
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-150 {{ $isMaster ? 'bg-[#0047BA] text-white shadow-md shadow-blue-950/40 ring-1 ring-white/10' : 'text-slate-300 hover:text-white hover:bg-neutral-800/70' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-lg flex items-center justify-center {{ $isMaster ? 'bg-white/20 text-white' : 'text-slate-400' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <span class="tracking-tight text-[13px]">Master Material</span>
                            </div>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-mono font-bold {{ $isMaster ? 'bg-white/20 text-white' : 'bg-neutral-800 text-slate-400' }}">
                                Data
                            </span>
                        </a>

                        <!-- 4. Buat Password Baru -->
                        @php $isPassword = request()->routeIs('password.*'); @endphp
                        <a href="{{ route('password.change') }}" 
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all duration-150 {{ $isPassword ? 'bg-[#0047BA] text-white shadow-md shadow-blue-950/40 ring-1 ring-white/10' : 'text-slate-300 hover:text-white hover:bg-neutral-800/70' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-lg flex items-center justify-center {{ $isPassword ? 'bg-white/20 text-white' : 'text-slate-400' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                </div>
                                <span class="tracking-tight text-[13px]">Buat Password Baru</span>
                            </div>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-mono {{ $isPassword ? 'bg-white/20 text-white' : 'bg-neutral-800 text-slate-400' }}">
                                Akun
                            </span>
                        </a>

                    </div>
                </div>

            </div>

            <!-- Bottom User & Logout in Sidebar -->
            <div class="p-3.5 border-t border-neutral-800/90 bg-neutral-950/60 shrink-0">
                @auth
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="h-8 px-2 bg-white rounded-xl border border-white/20 shadow-xs flex items-center justify-center shrink-0">
                                <img src="{{ asset('images/andritz-logo.svg') }}" alt="ANDRITZ" class="h-3.5 w-auto object-contain">
                            </div>
                            <div class="flex flex-col min-w-0">
                                <span class="text-xs font-bold text-white truncate leading-tight">{{ Auth::user()->name }}</span>
                                <span class="text-[10px] font-mono text-slate-400 truncate">ID: {{ Auth::user()->user_id }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-1.5 shrink-0">
                            <!-- Change Password Quick Link -->
                            <a href="{{ route('password.change') }}" 
                               class="p-2 rounded-xl bg-neutral-800/80 hover:bg-blue-600/30 hover:text-blue-300 text-slate-400 border border-neutral-700/60 hover:border-blue-500/30 transition shadow-xs cursor-pointer"
                               title="Buat / Ubah Password">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                            </a>

                            <!-- Logout Form -->
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="p-2 rounded-xl bg-neutral-800/80 hover:bg-rose-500/20 hover:text-rose-400 text-slate-400 border border-neutral-700/60 hover:border-rose-500/30 transition shadow-xs cursor-pointer"
                                        title="Keluar dari sistem">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>

        </aside>

        <!-- ======================================================== -->
        <!-- MAIN CONTENT AREA (TO THE RIGHT OF THE SIDEBAR)          -->
        <!-- ======================================================== -->
        <div class="flex-1 flex flex-col min-w-0 lg:pl-72 min-h-screen main-content-wrapper transition-all duration-300">

            <!-- Top Header Bar (With Breadcrumb like screenshot & Mobile Hamburger) -->
            <header class="no-print sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200/90 shadow-2xs">
                <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
                    
                    <!-- Left: Mobile Toggle & Breadcrumb Navigation -->
                    <div class="flex items-center gap-3 min-w-0">
                        <!-- Mobile Hamburger Button -->
                        <button type="button" 
                                onclick="toggleSidebar()" 
                                class="lg:hidden p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 border border-slate-200 transition shrink-0"
                                aria-label="Buka Menu">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>

                        <!-- Breadcrumb (Content / Menus / List style like reference screenshot) -->
                        <nav class="flex items-center text-xs font-semibold text-slate-500 overflow-hidden text-ellipsis whitespace-nowrap">
                            <a href="{{ route('pallet.index') }}" class="hover:text-blue-600 transition flex items-center gap-1.5 shrink-0">
                                <span>Pallet System</span>
                            </a>
                            <span class="mx-2 text-slate-300">/</span>
                            @if(request()->routeIs('pallet.index'))
                                <span class="text-slate-900 font-bold">Dashboard</span>
                            @elseif(request()->routeIs('pallet.create'))
                                <span class="text-slate-900 font-bold">Buat Sticker Baru</span>
                            @elseif(request()->routeIs('pallet.components'))
                                <span class="text-slate-900 font-bold">Cari Komponen</span>
                            @elseif(request()->routeIs('master-materials.*'))
                                <span class="text-slate-900 font-bold">Master Material &amp; Spare Part</span>
                            @elseif(request()->routeIs('password.*'))
                                <span class="text-slate-900 font-bold">Buat Password Baru</span>
                            @elseif(request()->routeIs('pallet.show'))
                                <a href="{{ route('pallet.index') }}" class="hover:text-blue-600">Dashboard</a>
                                <span class="mx-2 text-slate-300">/</span>
                                <span class="text-slate-900 font-bold">Detail Pallet</span>
                            @elseif(request()->routeIs('pallet.edit'))
                                <a href="{{ route('pallet.index') }}" class="hover:text-blue-600">Dashboard</a>
                                <span class="mx-2 text-slate-300">/</span>
                                <span class="text-slate-900 font-bold">Edit Pallet</span>
                            @else
                                <span class="text-slate-900 font-bold">@yield('title')</span>
                            @endif
                        </nav>
                    </div>

                    <!-- Right: Quick Action & Live Status -->
                    <div class="flex items-center gap-3 shrink-0">

                        <!-- System Status Badge -->
                        <div class="hidden sm:flex items-center gap-2 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <span>5 Sites Active</span>
                        </div>


                    </div>

                </div>
            </header>

            <!-- Global Alerts (Floating Style) -->
            <div class="no-print px-4 sm:px-6 lg:px-8 mt-4 w-full">
                @if(session('success'))
                    <div class="mb-4 flex items-center justify-between p-4 rounded-xl bg-emerald-50/90 border border-emerald-200 text-emerald-900 text-xs shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <span class="font-bold text-emerald-950">Berhasil!</span>
                                <span class="ml-1 text-emerald-800">{{ session('success') }}</span>
                            </div>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 p-1 rounded-md hover:bg-emerald-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="mb-4 flex items-center justify-between p-4 rounded-xl bg-blue-50/90 border border-blue-200 text-blue-900 text-xs shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="font-bold text-blue-950">Informasi:</span>
                                <span class="ml-1 text-blue-800">{{ session('info') }}</span>
                            </div>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-blue-600 hover:text-blue-900 p-1 rounded-md hover:bg-blue-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 flex items-center justify-between p-4 rounded-xl bg-rose-50/90 border border-rose-200 text-rose-900 text-xs shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <div>
                                <span class="font-bold text-rose-950">Terjadi Kesalahan!</span>
                                <span class="ml-1 text-rose-800">{{ session('error') }}</span>
                            </div>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900 p-1 rounded-md hover:bg-rose-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-4 rounded-xl bg-rose-50/90 border border-rose-200 text-rose-900 text-xs shadow-sm">
                        <div class="flex items-center gap-2.5 font-bold text-rose-950 mb-1.5">
                            <div class="w-6 h-6 rounded-md bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <span>Terdapat kendala data:</span>
                        </div>
                        <ul class="list-disc list-inside space-y-1 pl-8 text-rose-800 font-medium">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Main Dynamic Content -->
            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6">
                @yield('content')
            </main>

            <!-- Professional Industrial Footer -->
            <footer class="no-print mt-auto border-t border-slate-200/80 bg-white py-5 text-xs text-slate-500">
                <div class="px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3 text-slate-600">
                        <div class="h-6 px-2 bg-slate-100 border border-slate-200 rounded flex items-center justify-center">
                            <img src="{{ asset('images/andritz-logo.svg') }}" alt="ANDRITZ" class="h-3 w-auto object-contain">
                        </div>
                        <span class="font-medium">&copy; {{ date('Y') }} Pallet Material System &middot; Manufacturing Operations Control</span>
                    </div>
                    <div class="flex items-center gap-3 text-slate-500 font-mono text-[11px]">
                        <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200">Sites: OKI II &bull; IKPD &bull; IKPP &bull; TELL &bull; ISC</span>
                        <span class="text-slate-300">&bull;</span>
                        <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200">Format Label: 100 &times; 150 mm</span>
                    </div>
                </div>
            </footer>

        </div>

    </div>

    <!-- Script for mobile sidebar toggle -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('appSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
