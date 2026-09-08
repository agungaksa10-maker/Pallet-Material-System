@extends('layouts.app')

@section('title', 'Buat Password Baru')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- ======================================================== -->
    <!-- 1. BREADCRUMB & HEADER SECTION                           -->
    <!-- ======================================================== -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <!-- Breadcrumb Navigation -->
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('pallet.index') }}" class="hover:text-blue-600 transition-colors">Pallet System</a>
                <span>&rsaquo;</span>
                <span class="text-slate-700 font-semibold">Keamanan Akun</span>
                <span>&rsaquo;</span>
                <span class="text-blue-700 font-bold">Buat Password Baru</span>
            </div>
            
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </span>
                <span>Manajemen &amp; Buat Password Baru</span>
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Perbarui kata sandi akun Anda untuk menjaga keamanan akses sistem Pallet Material ANDRITZ.
            </p>
        </div>

        <!-- Back to Dashboard -->
        <a href="{{ route('pallet.index') }}" 
           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold border border-slate-200 transition shadow-xs self-start sm:self-auto">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>

    <!-- ======================================================== -->
    <!-- 2. ALERTS (SUCCESS / ERROR)                              -->
    <!-- ======================================================== -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-start gap-3 shadow-xs">
            <div class="w-6 h-6 rounded-lg bg-emerald-600 text-white flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <strong class="font-bold text-emerald-900">Berhasil!</strong>
                <p class="mt-0.5 text-emerald-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm flex items-start gap-3 shadow-xs">
            <div class="w-6 h-6 rounded-lg bg-rose-600 text-white flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <div class="space-y-1">
                <strong class="font-bold text-rose-900">Terjadi Kesalahan:</strong>
                <ul class="list-disc list-inside text-rose-700 space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- ======================================================== -->
    <!-- 3. MAIN FORM GRID                                        -->
    <!-- ======================================================== -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: User Profile & Security Guidelines -->
        <div class="space-y-5">
            <!-- Active Account Card -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-700 to-[#007AC1] text-white flex items-center justify-center font-extrabold text-lg shadow-md shadow-blue-600/20">
                        {{ strtoupper(substr($currentUser->name ?? 'A', 0, 2)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="text-sm font-extrabold text-slate-900 truncate">{{ $currentUser->name }}</div>
                        <div class="text-xs font-mono text-slate-500 truncate">ID: {{ $currentUser->user_id }}</div>
                        <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $currentUser->role === 'admin' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                            {{ $currentUser->role }}
                        </span>
                    </div>
                </div>

                <hr class="border-slate-100">

                <div class="text-xs text-slate-600 space-y-2">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Email Terdaftar:</span>
                        <span class="font-semibold text-slate-800">{{ $currentUser->email ?: '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Status Akun:</span>
                        <span class="font-bold text-emerald-600 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Aktif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Password Security Tips -->
            <div class="bg-gradient-to-br from-blue-50/70 to-sky-50/40 border border-blue-100 rounded-2xl p-5 text-xs text-slate-700 space-y-3 shadow-2xs">
                <div class="font-bold text-blue-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Ketentuan Password Baru:</span>
                </div>
                <ul class="space-y-1.5 text-[11.5px] text-slate-600 list-disc list-inside">
                    <li>Minimal memiliki <strong>6 karakter</strong>.</li>
                    <li>Gunakan kombinasi huruf, angka, atau simbol untuk keamanan lebih baik.</li>
                    <li>Password baru tidak boleh sama persis dengan password lama.</li>
                    <li>Pastikan kedua kolom password baru dan konfirmasi terisi sama.</li>
                </ul>
            </div>
        </div>

        <!-- Right Column: The Change Password Form -->
        <div class="lg:col-span-2">
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-xs space-y-6">

                @if($currentUser->role === 'admin' && $allUsers->count() > 1)
                    <!-- Admin Tab Switching: Own Password vs Other User -->
                    <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl text-xs">
                        <button type="button" 
                                onclick="switchTargetMode('self')" 
                                id="tab_self"
                                class="flex-1 py-2 rounded-lg font-bold text-xs bg-white text-blue-700 shadow-xs transition cursor-pointer text-center">
                            Ganti Password Saya
                        </button>
                        <button type="button" 
                                onclick="switchTargetMode('other')" 
                                id="tab_other"
                                class="flex-1 py-2 rounded-lg font-semibold text-xs text-slate-600 hover:text-slate-900 transition cursor-pointer text-center">
                            Buat Password User Lain (Admin)
                        </button>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}" class="space-y-5" id="changePasswordForm">
                    @csrf

                    <!-- Hidden target user id -->
                    <input type="hidden" name="target_user_id" id="targetUserIdInput" value="{{ old('target_user_id', $currentUser->user_id) }}">
                    <input type="hidden" name="admin_override" id="adminOverrideInput" value="{{ $currentUser->role === 'admin' ? '1' : '0' }}">

                    <!-- Target User Selection (Shown when admin wants to reset other user) -->
                    <div id="targetUserSelectContainer" class="{{ old('target_user_id', $currentUser->user_id) !== $currentUser->user_id ? '' : 'hidden' }} space-y-1.5 p-4 rounded-xl bg-slate-50 border border-slate-200">
                        <label for="selectUserDropdown" class="block text-xs font-bold text-slate-800">
                            Pilih Akun Pengguna yang Ingin Diubah Passwordnya:
                        </label>
                        <select id="selectUserDropdown" 
                                onchange="onSelectUserChange(this.value)"
                                class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition">
                            @foreach($allUsers as $u)
                                <option value="{{ $u->user_id }}" {{ old('target_user_id', $currentUser->user_id) === $u->user_id ? 'selected' : '' }}>
                                    {{ $u->name }} (ID: {{ $u->user_id }}) — [{{ strtoupper($u->role) }}]
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-slate-500">
                            Sebagai Administrator, Anda dapat langsung membuatkan password baru untuk akun ini tanpa perlu memasukkan password lamanya.
                        </p>
                    </div>

                    <!-- Field 1: Current Password (Only needed for own password if not admin override) -->
                    @if($currentUser->role !== 'admin')
                    <div id="currentPasswordContainer" class="space-y-1.5">
                        <label for="current_password" class="block text-xs font-bold text-slate-800">
                            Password Saat Ini <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="current_password" 
                                   id="current_password" 
                                   autocomplete="current-password"
                                   placeholder="Masukkan password yang aktif saat ini" 
                                   class="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border @error('current_password') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl text-xs font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition shadow-2xs">
                            <button type="button" 
                                    onclick="togglePasswordVisibility('current_password', this)"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                                <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                        @error('current_password')
                            <span class="text-[11px] text-rose-600 font-semibold">{{ $message }}</span>
                        @enderror
                    </div>
                    @endif

                    <!-- Field 2: New Password -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="new_password" class="block text-xs font-bold text-slate-800">
                                Password Baru <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] text-slate-400">Min. 6 karakter</span>
                        </div>
                        <div class="relative">
                            <input type="password" 
                                   name="password" 
                                   id="new_password" 
                                   required 
                                   minlength="6"
                                   autocomplete="new-password"
                                   oninput="checkPasswordStrength(this.value)"
                                   placeholder="Ketik kata sandi baru yang diinginkan" 
                                   class="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border @error('password') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl text-xs font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition shadow-2xs">
                            <button type="button" 
                                    onclick="togglePasswordVisibility('new_password', this)"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                                <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                        <!-- Strength Meter Bar -->
                        <div class="h-1 w-full bg-slate-100 rounded-full overflow-hidden mt-1.5 hidden" id="strengthBarContainer">
                            <div id="strengthBar" class="h-full w-0 transition-all duration-300"></div>
                        </div>
                        @error('password')
                            <span class="text-[11px] text-rose-600 font-semibold">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Field 3: Confirm New Password -->
                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-800">
                            Ulangi Password Baru <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   required 
                                   minlength="6"
                                   autocomplete="new-password"
                                   placeholder="Ketik ulang password baru untuk konfirmasi" 
                                   class="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition shadow-2xs">
                            <button type="button" 
                                    onclick="togglePasswordVisibility('password_confirmation', this)"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                                <svg class="w-4 h-4 eye-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                    </div>

                    <hr class="border-slate-100 pt-2">

                    <!-- Submit & Cancel Buttons -->
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('pallet.index') }}" 
                           class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition cursor-pointer">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold text-xs shadow-md shadow-blue-500/20 active:scale-98 transition-all flex items-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Simpan Password Baru</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>

</div>

@push('scripts')
<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';

        const svg = btn.querySelector('svg');
        if (isPassword) {
            // Show slashed eye icon
            svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>';
            svg.classList.add('text-blue-600');
        } else {
            // Show normal eye icon
            svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            svg.classList.remove('text-blue-600');
        }
    }

    function checkPasswordStrength(val) {
        const container = document.getElementById('strengthBarContainer');
        const bar = document.getElementById('strengthBar');
        if (!container || !bar) return;

        if (!val) {
            container.classList.add('hidden');
            return;
        }

        container.classList.remove('hidden');
        const len = val.length;

        if (len < 6) {
            bar.className = 'h-full bg-rose-500 w-1/4 transition-all duration-300';
        } else if (len < 8) {
            bar.className = 'h-full bg-amber-500 w-2/4 transition-all duration-300';
        } else if (len < 12) {
            bar.className = 'h-full bg-blue-500 w-3/4 transition-all duration-300';
        } else {
            bar.className = 'h-full bg-emerald-500 w-full transition-all duration-300';
        }
    }

    function switchTargetMode(mode) {
        const tabSelf = document.getElementById('tab_self');
        const tabOther = document.getElementById('tab_other');
        const selectContainer = document.getElementById('targetUserSelectContainer');
        const targetUserIdInput = document.getElementById('targetUserIdInput');
        const currentUserId = "{{ $currentUser->user_id }}";

        if (mode === 'self') {
            if (tabSelf) tabSelf.className = 'flex-1 py-2 rounded-lg font-bold text-xs bg-white text-blue-700 shadow-xs transition cursor-pointer text-center';
            if (tabOther) tabOther.className = 'flex-1 py-2 rounded-lg font-semibold text-xs text-slate-600 hover:text-slate-900 transition cursor-pointer text-center';
            if (selectContainer) selectContainer.classList.add('hidden');
            if (targetUserIdInput) targetUserIdInput.value = currentUserId;
        } else {
            if (tabSelf) tabSelf.className = 'flex-1 py-2 rounded-lg font-semibold text-xs text-slate-600 hover:text-slate-900 transition cursor-pointer text-center';
            if (tabOther) tabOther.className = 'flex-1 py-2 rounded-lg font-bold text-xs bg-white text-blue-700 shadow-xs transition cursor-pointer text-center';
            if (selectContainer) selectContainer.classList.remove('hidden');
            
            const dropdown = document.getElementById('selectUserDropdown');
            if (dropdown && targetUserIdInput) {
                targetUserIdInput.value = dropdown.value;
            }
        }
    }

    function onSelectUserChange(val) {
        const targetUserIdInput = document.getElementById('targetUserIdInput');
        if (targetUserIdInput) {
            targetUserIdInput.value = val;
        }
    }
</script>
@endpush
@endsection
