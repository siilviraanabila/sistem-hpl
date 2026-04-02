<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | Dashboard Pertanahan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800">

<div class="min-h-screen flex">

    <!-- LEFT BRANDING -->
    <div class="hidden lg:flex w-1/2 flex-col justify-center px-16 text-white">
        <img src="https://www.transmigrasi.go.id/wp-content/uploads/2024/11/LOGO-KEMENTRANS-Bulat.png"
             class="h-20 w-fit mb-6">

        <h1 class="text-4xl font-bold leading-tight mb-4">
            Dashboard Pertanahan 
        </h1>

        <!-- <p class="text-blue-200 max-w-lg">
            Mendukung tata kelola kendaraan dinas yang transparan,
            terkontrol, dan akuntabel sesuai prinsip SPBE.
        </p> -->

        <div class="mt-flex text-sm text-blue-300">
            © {{ date('Y') }} Kementerian Transmigrasi Republik Indonesia
        </div>
    </div>

    <!-- RIGHT FORM -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-6">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">

            <h2 class="text-2xl text-center font-semibold text-gray-800 mb-2">
                Login
            </h2>

            @if($errors->any())
                <div class="mb-4 p-3 text-sm text-red-700 bg-red-100 rounded-lg">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" required class="w-full rounded-lg border px-4 py-2">
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" required class="w-full rounded-lg border px-4 py-2">
                </div>

                <button type="submit"
                    class="w-full bg-blue-700 hover:bg-blue-800 text-white font-semibold py-3 rounded-lg transition">
                    Masuk
                </button>
            </form>

            <!-- <p class="text-center text-sm text-gray-500 mt-6">
                Belum memiliki akun?
                <a href="{{ route('register') }}" class="text-blue-700 font-medium hover:underline">
                    Daftar di sini
                </a>
            </p> -->

        </div>
    </div>

</div>
</body>
</html>
