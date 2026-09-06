<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk &middot; Pallet Material System &middot; ANDRITZ</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/andritz-logo.svg') }}">

    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #020c1b;
            color: #f8fafc;
        }
        .font-mono {
            font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, monospace;
        }
        .andritz-gradient-mesh {
            background: 
                radial-gradient(circle at 50% 15%, rgba(0, 122, 193, 0.35) 0%, transparent 60%),
                radial-gradient(circle at 10% 80%, rgba(0, 71, 186, 0.25) 0%, transparent 50%),
                radial-gradient(circle at 90% 85%, rgba(0, 163, 224, 0.20) 0%, transparent 50%),
                linear-gradient(145deg, rgba(2, 12, 27, 0.90) 0%, rgba(3, 22, 48, 0.82) 40%, rgba(1, 10, 22, 0.94) 100%);
        }
        .cad-grid-pattern {
            background-image: 
                linear-gradient(to right, rgba(0, 122, 193, 0.08) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0, 122, 193, 0.08) 1px, transparent 1px);
            background-size: 32px 32px;
        }
        .cad-crosshairs {
            background-image: 
                radial-gradient(circle, rgba(0, 163, 224, 0.3) 1px, transparent 1px);
            background-size: 64px 64px;
        }
    </style>
</head>
<body class="min-h-full flex flex-col justify-between selection:bg-[#007AC1]/40 selection:text-white relative overflow-x-hidden">

    <!-- Layer 1: Photographic ANDRITZ Industrial Paper Plant Background -->
    <div class="fixed inset-0 z-0 bg-cover bg-center bg-no-repeat transition-all duration-700 transform scale-[1.02]"
         style="background-image: url('{{ asset('images/andritz-plant-bg.jpg') }}');">
    </div>

    <!-- Layer 2: ANDRITZ Signature Prussian Blue Vignette & Mesh Overlay -->
    <div class="fixed inset-0 z-0 andritz-gradient-mesh backdrop-blur-[2px]"></div>

    <!-- Layer 3: Engineering Technical Grid & Blueprint Motifs -->
    <div class="fixed inset-0 z-0 cad-grid-pattern [mask-image:radial-gradient(ellipse_85%_75%_at_50%_45%,#000_65%,transparent_100%)] pointer-events-none opacity-80"></div>
    <div class="fixed inset-0 z-0 cad-crosshairs pointer-events-none opacity-40"></div>

    <!-- Layer 4: Grand Subtle Watermark - Iconic ANDRITZ Vector Monogram -->
    <div class="fixed -right-24 -bottom-24 z-0 pointer-events-none opacity-[0.035] text-white">
        <svg viewBox="0 0 1065 210" class="w-[850px] lg:w-[1200px] h-auto" xmlns="http://www.w3.org/2000/svg">
            <g transform="translate(120, -290)" fill="currentColor">
                <path d="M 52.156894,493.69536 L 26.281784,433.16297 L -19.436146,433.16297 L 9.6166143,370.94573 L 65.173024,493.69536 L 143.26976,493.69536 L 43.104104,293.58229 L -25.468436,293.58229 L -119.91608,493.69536 L 52.156894,493.69536 z" />
                <path d="M 574.18111,293.58229 L 574.18111,352.9187 L 654.22461,352.9187 L 654.22461,495.43259 L 719.47111,495.43259 L 719.47111,353.16314 L 749.04761,353.16314 L 774.46011,293.58229 L 574.18111,293.58229 z" />
                <path d="M 793.51721,293.58229 L 766.88261,353.16314 L 794.73941,353.16314 L 730.47061,495.43259 L 943.07601,495.43259 L 914.73041,434.78671 L 832.37351,434.78671 L 888.34021,293.58229 L 793.51721,293.58229 z" />
            </g>
        </svg>
    </div>

    <!-- Main Content Box -->
    <main class="relative z-10 flex-1 flex flex-col justify-center py-8 sm:py-12 px-4 sm:px-6 lg:px-8">
        
        <!-- Header: Official ANDRITZ Logo & System Name -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
            <!-- Official ANDRITZ Logo Container with Crisp White Plate & Signature Blue Accent -->
            <div class="inline-flex items-center justify-center px-6 py-3.5 sm:px-8 sm:py-4 rounded-2xl bg-white shadow-2xl shadow-[#007AC1]/30 mb-4 ring-4 ring-white/15 hover:scale-[1.02] transition-transform duration-200">
                <img src="{{ asset('images/andritz-logo.svg') }}" alt="ANDRITZ" class="h-9 sm:h-10 w-auto object-contain">
            </div>

            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white drop-shadow-md">
                PALLET Material System
            </h1>
        </div>

        <!-- Login Form Container -->
        <div class="mt-7 sm:mx-auto sm:w-full sm:max-w-[400px]">
            
            <!-- Error Alert -->
            @if($errors->any())
                <div class="mb-4 p-3.5 rounded-2xl bg-rose-950/85 border border-rose-600/70 text-rose-200 text-xs flex items-start gap-2.5 shadow-xl backdrop-blur-xl animate-shake">
                    <svg class="w-4 h-4 text-rose-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div class="font-medium leading-relaxed">{{ $errors->first() }}</div>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-4 p-3.5 rounded-2xl bg-[#0047BA]/40 border border-cyan-500/40 text-cyan-100 text-xs flex items-center gap-2.5 shadow-xl backdrop-blur-xl">
                    <svg class="w-4 h-4 text-cyan-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="font-medium">{{ session('info') }}</div>
                </div>
            @endif

            <!-- ANDRITZ Enterprise High-Tech Glass Card -->
            <div class="bg-slate-900/80 border border-white/15 rounded-3xl p-6 sm:p-7 shadow-2xl shadow-black/80 backdrop-blur-2xl ring-1 ring-[#007AC1]/30 relative overflow-hidden">
                <!-- Top Accent Line in ANDRITZ Cyan -->
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-[#007AC1] to-transparent"></div>

                <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                    @csrf

                    <!-- User ID -->
                    <div>
                        <label for="user_id" class="block text-xs font-bold text-slate-200 mb-1.5 flex items-center justify-between">
                            <span>User ID Terdaftar</span>
                            <span class="text-[10px] font-mono text-cyan-400 uppercase tracking-wider font-semibold">// ID Karyawan</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-cyan-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input type="text" 
                                   name="user_id" 
                                   id="user_id" 
                                   value="{{ old('user_id') }}" 
                                   required 
                                   autofocus
                                   autocomplete="username"
                                   placeholder="admin atau operator01"
                                   class="w-full pl-10 pr-3 py-2.5 text-xs bg-slate-950/80 border border-slate-700/90 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-[#007AC1] focus:ring-2 focus:ring-[#007AC1]/30 transition-all font-medium">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-bold text-slate-200">
                                Password
                            </label>
                            <button type="button" 
                                    onclick="togglePasswordVisibility()" 
                                    class="text-[11px] font-semibold text-cyan-400 hover:text-cyan-300 transition flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <span>Lihat</span>
                            </button>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-cyan-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                            </div>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   required 
                                   autocomplete="current-password"
                                   placeholder="Masukkan password"
                                   class="w-full pl-10 pr-3 py-2.5 text-xs bg-slate-950/80 border border-slate-700/90 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-[#007AC1] focus:ring-2 focus:ring-[#007AC1]/30 transition-all font-medium">
                        </div>
                    </div>

                    <!-- Remember me -->
                    <div class="flex items-center text-xs pt-0.5">
                        <label class="inline-flex items-center gap-2 cursor-pointer text-slate-300 select-none hover:text-white transition">
                            <input type="checkbox" 
                                   name="remember" 
                                   id="remember" 
                                   value="1" 
                                   class="rounded-md border-slate-700 bg-slate-950 text-[#007AC1] focus:ring-[#007AC1]/30 w-4 h-4">
                            <span>Ingat sesi saya di perangkat ini</span>
                        </label>
                    </div>

                    <!-- Submit Button with Official ANDRITZ Blue Styling -->
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full py-2.5 px-4 bg-gradient-to-r from-[#007AC1] to-[#0047BA] hover:from-[#008bdc] hover:to-[#0054db] text-white font-bold text-xs rounded-xl shadow-lg shadow-[#007AC1]/35 focus:outline-none focus:ring-2 focus:ring-[#007AC1] focus:ring-offset-2 focus:ring-offset-slate-950 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                            <span>Masuk ke Sistem</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Quick Demo Credentials Box -->
            <div class="mt-4 bg-slate-900/70 border border-white/10 rounded-2xl p-3.5 text-xs backdrop-blur-xl shadow-lg">
                <div class="text-[11px] font-bold text-slate-300 mb-2 flex items-center justify-between">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        <span>Akun Terdaftar (Klik untuk Isi Cepat):</span>
                    </span>
                    <span class="text-[10px] font-mono px-2 py-0.5 rounded-full bg-[#007AC1]/20 text-cyan-300 border border-[#007AC1]/30 font-semibold">
                        1-KLIK
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-2.5">
                    <button type="button" 
                            onclick="fillCredentials('admin', 'password')"
                            class="p-2.5 rounded-xl border border-slate-800 bg-slate-950/70 hover:border-[#007AC1]/60 hover:bg-[#007AC1]/10 text-left transition-all group">
                        <div class="font-bold text-slate-200 text-xs group-hover:text-cyan-300 transition flex items-center justify-between">
                            <span>admin</span>
                            <span class="text-[9px] font-mono text-cyan-400/80 uppercase">SUPER</span>
                        </div>
                        <div class="text-[10px] font-mono text-slate-400 mt-0.5">password</div>
                    </button>

                    <button type="button" 
                            onclick="fillCredentials('operator01', 'password123')"
                            class="p-2.5 rounded-xl border border-slate-800 bg-slate-950/70 hover:border-[#007AC1]/60 hover:bg-[#007AC1]/10 text-left transition-all group">
                        <div class="font-bold text-slate-200 text-xs group-hover:text-cyan-300 transition flex items-center justify-between">
                            <span>operator01</span>
                            <span class="text-[9px] font-mono text-slate-400 uppercase">OPERATOR</span>
                        </div>
                        <div class="text-[10px] font-mono text-slate-400 mt-0.5">password123</div>
                    </button>
                </div>
            </div>

        </div>

    </main>

    <!-- Bottom Corporate Footer -->
    <footer class="relative z-10 w-full py-4 text-center text-xs text-slate-400 border-t border-white/10 bg-slate-950/40 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 space-y-1">

        </div>
    </footer>

    <script>
        function togglePasswordVisibility() {
            const pass = document.getElementById('password');
            pass.type = (pass.type === 'password') ? 'text' : 'password';
        }

        function fillCredentials(userId, pass) {
            document.getElementById('user_id').value = userId;
            document.getElementById('password').value = pass;
            document.getElementById('user_id').focus();
        }
    </script>
</body>
</html>


