<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1BA37A" id="browser-theme-color">
    <link rel="icon" href="/assets/icon-light.svg" type="image/svg+xml">
    <link rel="alternate icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="192x192" href="/assets/logo-light.png">
    <link rel="apple-touch-icon" href="/assets/logo-light.png">
    <title>{{ __('messages.auth.login') }} - Titik Simpan</title>
    <link rel="preconnect" href="https://cdn.tailwindcss.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Nunito:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>
    <style>
        body { font-family: 'Nunito', 'Inter', system-ui, -apple-system, sans-serif; -webkit-tap-highlight-color: transparent; }
        .font-brand { font-family: 'Poppins', 'Nunito', 'Inter', sans-serif; font-weight: 700; }
        .font-slogan { font-family: 'Nunito', 'Inter', sans-serif; font-weight: 600; }
        .font-bold, .font-extrabold { font-family: 'Poppins', 'Nunito', 'Inter', sans-serif; }
        .fade-in { animation: fadeIn 0.35s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .rounded-xl, .rounded-2xl, .rounded-3xl { border-radius: 0.5rem !important; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen flex items-center justify-center p-4">
    <div class="fixed top-4 right-4 flex items-center gap-2">
        <div class="relative" id="lang-wrap">
            <button onclick="toggleLangMenu(event)" class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-xs font-bold" title="Language">
                {{ strtoupper(app()->getLocale()) }}
            </button>
            <div id="lang-menu" class="hidden absolute right-0 mt-2 w-36 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden z-50">
                <a href="{{ route('lang.switch', 'id') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ app()->getLocale() === 'id' ? 'font-bold text-[#1BA37A] dark:text-[#6EE7B0]' : '' }}">
                    🇮🇩 Indonesia
                    @if(app()->getLocale() === 'id')<svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>@endif
                </a>
                <a href="{{ route('lang.switch', 'en') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors border-t border-gray-100 dark:border-gray-700 {{ app()->getLocale() === 'en' ? 'font-bold text-[#1BA37A] dark:text-[#6EE7B0]' : '' }}">
                    🇺🇸 English
                    @if(app()->getLocale() === 'en')<svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>@endif
                </a>
            </div>
        </div>
<div class="relative" id="theme-wrap">
            <button onclick="toggleTheme()" class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all" title="{{ __('messages.theme.title') }}" aria-label="{{ __('messages.theme.title') }}">
                <svg id="theme-icon-dark" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                <svg id="theme-icon-light" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </button>
        </div>
        </div>
    </div>

    <div class="w-full max-w-md fade-in">
        <div class="flex flex-col items-center text-center mb-8">
            <div class="p-3 rounded-[24px] bg-white dark:bg-gray-800 shadow-[0_15px_40px_-8px_rgba(27,163,122,0.45)] dark:shadow-[0_15px_40px_-8px_rgba(0,0,0,0.7)] ring-1 ring-gray-200 dark:ring-gray-700">
                <img src="/assets/icon-light.svg" alt="Titik Simpan" class="h-16 w-16 sm:h-20 sm:w-20 object-contain mx-auto dark:hidden">
                <img src="/assets/icon-dark.svg" alt="Titik Simpan" class="h-16 w-16 sm:h-20 sm:w-20 object-contain mx-auto hidden dark:block">
            </div>
            <h1 class="mt-5 text-2xl sm:text-3xl font-brand tracking-tight">
                <span class="text-[#1F3A56] dark:text-white">Titik</span> <span class="text-[#1BA37A]">Simpan</span>
            </h1>
            <p class="mt-2 text-sm sm:text-base font-slogan text-[#1F3A56] dark:text-white max-w-xs">
                {{ __('messages.auth.start_managing') }}
            </p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 md:p-8">
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-xl text-sm">
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-xl text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <a href="{{ route('google.redirect', [], false) }}" class="flex items-center justify-center gap-2 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 py-3 rounded-xl text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-600 transition-all btn-press shadow-sm mt-5">
                <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.27-4.74 3.27-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84A11 11 0 0012 23z"/><path fill="#FBBC05" d="M5.84 14.1a6.6 6.6 0 010-4.2V7.06H2.18a11 11 0 000 9.88l3.66-2.84z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15A11 11 0 002 7.06l3.84 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
                {{ __('messages.auth.login_with_google') }}
            </a>
            <div class="flex items-center gap-3 my-5">
                <span class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></span>
                <span class="text-xs text-gray-400 dark:text-gray-500 font-medium">{{ __('messages.auth.or') }}</span>
                <span class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></span>
            </div>

            <form action="{{ route('login.attempt', [], false) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.auth.email') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#1BA37A]/50">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">{{ __('messages.auth.password') }}</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required autocomplete="current-password" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#1BA37A]/50 pr-11">
                        <button type="button" onclick="togglePassword('password', this)" tabindex="-1" aria-label="show password" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="w-5 h-5 hidden" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                        </button>
                    </div>
                </div>
                <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 dark:border-gray-600 text-[#1BA37A] focus:ring-[#1BA37A]">
                    {{ __('messages.auth.remember_me') }}
                </label>
                <button type="submit" class="w-full bg-[#1BA37A] text-white py-3 rounded-xl text-sm font-semibold hover:bg-[#0F8F68] active:bg-[#0C7A59] transition-all btn-press shadow-sm">
                    {{ __('messages.auth.login') }}
                </button>
            </form>

            <p class="text-sm text-gray-500 dark:text-gray-400 text-center mt-5">
                {{ __('messages.auth.no_account') }}
                <a href="{{ route('register', [], false) }}" class="text-[#1BA37A] dark:text-[#6EE7B0] font-medium hover:underline">{{ __('messages.auth.register') }}</a>
            </p>
        </div>
    </div>

    <script>
        function getTheme() { return localStorage.getItem('theme') === 'dark' ? 'dark' : 'light'; }
        function setTheme(theme) {
            localStorage.setItem('theme', theme);
            document.documentElement.classList.toggle('dark', theme === 'dark');
            var darkIcon = document.getElementById('theme-icon-dark');
            var lightIcon = document.getElementById('theme-icon-light');
            darkIcon.classList.toggle('hidden', theme === 'dark');
            lightIcon.classList.toggle('hidden', theme === 'light');
        }
        function toggleTheme() { setTheme(getTheme() === 'dark' ? 'light' : 'dark'); }
        function togglePassword(id, btn) {
            var inp = document.getElementById(id);
            var show = inp.type === 'password';
            inp.type = show ? 'text' : 'password';
            btn.querySelectorAll('svg')[0].classList.toggle('hidden', show);
            btn.querySelectorAll('svg')[1].classList.toggle('hidden', !show);
        }
        function toggleLangMenu(e) {
            e.stopPropagation();
            document.getElementById('lang-menu').classList.toggle('hidden');
        }
        document.addEventListener('click', function(e) {
            var langMenu = document.getElementById('lang-menu');
            if (langMenu && !langMenu.contains(e.target) && !e.target.closest('#lang-wrap button')) langMenu.classList.add('hidden');
        });
        setTheme(getTheme());
    </script>
</body>
</html>