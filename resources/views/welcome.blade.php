<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pertanahan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Flowbite -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet"/>

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-slate-100 text-gray-800">

<!-- HEADER -->
<header style="background:#173f4f">
   <div class="max-w-7xl mx-auto px-6 min-h-[70px] flex items-center gap-4">

        <img src="https://www.transmigrasi.go.id/wp-content/uploads/2024/11/LOGO-KEMENTRANS-Bulat.png"
            class="h-10 w-10">

        <div>
            <h1 class="text-sm font-semibold uppercase tracking-wide text-white">
                Dashboard Pertanahan
            </h1>
            <p class="text-xs text-white">
                Kementerian Transmigrasi Republik Indonesia
            </p>
        </div>

    </div>
</header>

<!-- MAIN -->
<section class="min-h-[85vh] flex items-center justify-center px-4">

    <div class="max-w-lg w-full bg-white rounded-2xl shadow-xl p-10 text-center">

        <h2 class="text-2xl font-bold mb-3">
            Dashboard Pertanahan
        </h2>

        <p class="text-sm text-gray-500 mb-10 leading-relaxed">
            Portal internal pengelolaan sertifikat hak milik, permasalahan lahan
            dan hak pengelolaan lahan.
        </p>

        <a href="{{ route('login') }}"
            class="block w-full py-3 rounded-xl bg-[#173f4f] text-white font-medium
                   hover:bg-[#174f4f] transition">
            Masuk Sistem
        </a>

        <p class="text-xs text-gray-400 mt-6">
            Akses hanya untuk pengguna terdaftar
        </p>

    </div> 

</section>

<!-- FOOTER -->
<footer class="text-center text-xs text-gray-400 pb-6">
    © {{ date('Y') }} Kementerian Transmigrasi Republik Indonesia
</footer>

</body>

</html>
