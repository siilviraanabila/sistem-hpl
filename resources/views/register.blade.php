<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi | Sistem HPL</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800 flex items-center justify-center px-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">
                Registrasi Akun
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Sistem Informasi Hak Pengelolaan Lahan (HPL)
            </p>
        </div>

        <!-- Form -->
        <form action="{{ route('register') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Nama Lengkap -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Lengkap
                </label>
                <input type="text" name="name" required
                    placeholder="Masukkan nama lengkap"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3
                           focus:border-blue-600 focus:ring-0 transition">
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email
                </label>
                <input type="email" name="email" required
                    placeholder="contoh@email.com"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3
                           focus:border-blue-600 focus:ring-0 transition">
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Password
                </label>
                <input type="password" name="password" required
                    placeholder="Minimal 6 karakter"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3
                           focus:border-blue-600 focus:ring-0 transition">
            </div>

            <div>
                <label class="block text-sm font-medium">Role</label>
                <select name="role" class="w-full border rounded px-3 py-2">
                    <option value="admin">Admin</option>
                    <option value="pimpinan">Pimpinan</option>
                </select>
            </div>


            <!-- Button -->
            <button type="submit"
                class="w-full bg-blue-700 hover:bg-blue-800 text-white
                       font-semibold py-3 rounded-lg transition">
                Daftar
            </button>
        </form>

        <!-- Footer -->
        <p class="text-center text-sm text-gray-500 mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-blue-700 font-medium hover:underline">
                Login
            </a>
        </p>

    </div>

</body>
</html>
