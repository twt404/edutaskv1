<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Barang</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Selamat Datang Kembali</h1>
            <p class="text-sm text-slate-500 mt-1">Silakan masuk untuk mengelola data barang</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="username" class="block text-sm font-medium text-slate-700 mb-1">Username</label>
                <input type="username" id="username" name="text" required 
                    placeholder="aaaaaa_asmin"
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input type="password" id="password" name="password" required
                    placeholder="••••••••"
                    class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
            </div>

            <button type="submit"
                class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm transition cursor-pointer">
                Masuk
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-slate-200 text-center">
            <p class="text-sm text-slate-600">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-500 transition">
                    Daftar di sini 
                </a>
            </p>
        </div>
    </div>

</body>
</html>
