<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Kopiku</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        'brand-primary': {
                            500: '#06b6d4', // cyan-500
                            600: '#0891b2', // cyan-600
                        },
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: #f7f9fb; /* Background netral */
        }

        .brand-gradient {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); /* Cyan gradient */
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>

{{-- Background diganti ke warna netral yang sangat terang --}}
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        {{-- Logo & Header --}}
        <div class="text-center mb-8">
            <a href="{{ route('order.index') }}" class="inline-flex items-center gap-2">
                <div
                    {{-- Logo menggunakan brand color (Cyan) --}}
                    class="w-14 h-14 bg-gradient-to-br from-cyan-400 to-teal-500 rounded-2xl flex items-center justify-center shadow-xl shadow-cyan-200">
                    <span class="text-white text-2xl font-black">☕</span>
                </div>
            </a>
            {{-- Teks menggunakan brand gradient --}}
            <h1 class="text-3xl font-black mt-4 brand-gradient">Yuk, Gabung!</h1>
            <p class="text-gray-500 mt-1">Ayo buat akun</p>
        </div>

        {{-- Card Daftar --}}
        <div class="bg-white rounded-3xl shadow-xl shadow-cyan-50/50 border border-gray-100 p-8">

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        {{-- Fokus border dan ring diganti ke Cyan --}}
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100 outline-none transition"
                        placeholder="Nama kamu">
                    @error('name')
                        <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        {{-- Fokus border dan ring diganti ke Cyan --}}
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100 outline-none transition"
                        placeholder="email@contoh.com">
                    @error('email')
                        <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <input id="password" type="password" name="password" required
                        {{-- Fokus border dan ring diganti ke Cyan --}}
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100 outline-none transition"
                        placeholder="Min. 8 karakter">
                    @error('password')
                        <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi
                        Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        {{-- Fokus border dan ring diganti ke Cyan --}}
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100 outline-none transition"
                        placeholder="Ulangi password">
                    @error('password_confirmation')
                        <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                    {{-- Tombol utama menggunakan Brand Gradient Cyan --}}
                    class="w-full py-4 bg-gradient-to-r from-cyan-600 to-brand-primary-500 text-white font-bold text-lg rounded-2xl shadow-xl shadow-cyan-200 hover:shadow-cyan-300/70 hover:scale-[1.01] active:scale-[0.99] transition-all duration-200">
                    Daftar Sekarang 🎉
                </button>
            </form>

            {{-- Divider --}}
            <div class="relative my-7">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-400">atau</span>
                </div>
            </div>

            {{-- Login Link --}}
            <a href="{{ route('login') }}"
                class="block w-full py-3.5 text-center bg-gray-100 text-gray-700 font-semibold rounded-2xl hover:bg-gray-200 transition-all">
                Udah punya akun? Login aja!
            </a>
        </div>

        {{-- Footer --}}
        <p class="text-center text-gray-400 text-sm mt-6">
            © {{ date('Y') }} Felix Kopi.
        </p>
    </div>

</body>

</html>