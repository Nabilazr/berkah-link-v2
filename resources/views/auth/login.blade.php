<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Berkah-Link</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="min-h-screen bg-stone-50 flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        {{-- Logo & Brand --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex flex-col items-center gap-2">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-700 to-teal-600 flex items-center justify-center shadow-lg">
                    <svg viewBox="0 0 24 24" fill="white" class="w-6 h-6">
                        <path d="M12 21.593c-5.63-5.539-11-10.297-11-14.402 0-3.791 3.068-5.191 5.281-5.191 1.312 0 4.151.501 5.719 4.457 1.59-3.968 4.464-4.447 5.726-4.447 2.54 0 5.274 1.621 5.274 5.181 0 4.069-5.136 8.625-11 14.402z"/>
                    </svg>
                </div>
                <span class="text-2xl font-bold text-emerald-900" style="font-family:'Playfair Display',serif;">Berkah-Link</span>
            </a>
            <p class="text-stone-500 text-sm mt-1">Masuk ke akun kamu</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-stone-200 p-8">

            {{-- Session Status --}}
            @if (session('status'))
                <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-stone-700 mb-1.5">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           required autofocus autocomplete="username"
                           placeholder="emailkamu@gmail.com"
                           class="w-full border rounded-xl px-4 py-2.5 text-sm text-stone-800 placeholder-stone-300 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-stone-200' }}">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-stone-700 mb-1.5">Password</label>
                    <input id="password" type="password" name="password"
                           required autocomplete="current-password"
                           placeholder="••••••••"
                           class="w-full border rounded-xl px-4 py-2.5 text-sm text-stone-800 placeholder-stone-300 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-stone-200' }}">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember + Forgot --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" id="remember_me"
                               class="rounded border-stone-300 text-emerald-600 focus:ring-emerald-400">
                        <span class="text-sm text-stone-600">Ingat saya</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-sm text-emerald-700 hover:text-emerald-900 font-medium transition-colors">
                            Lupa password?
                        </a>
                    @endif
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full bg-emerald-700 hover:bg-emerald-800 active:scale-95 text-white font-bold py-3 rounded-xl transition-all duration-150 text-sm shadow-md shadow-emerald-200">
                    Masuk
                </button>

                {{-- Register link --}}
                <p class="text-center text-sm text-stone-500">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-emerald-700 font-semibold hover:text-emerald-900 transition-colors">
                        Daftar sekarang
                    </a>
                </p>

            </form>
        </div>

        {{-- Back to home --}}
        <p class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-sm text-stone-400 hover:text-stone-600 transition-colors flex items-center justify-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Beranda
            </a>
        </p>

    </div>
</body>
</html>