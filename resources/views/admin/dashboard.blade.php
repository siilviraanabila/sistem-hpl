<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Dashboard Pertanahan</title>

@vite('resources/css/app.css')

</head>

<body class="bg-slate-100 min-h-screen pt-20">

@include('layouts.header')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/11.4.0/highcharts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/11.4.0/modules/exporting.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/11.4.0/modules/accessibility.js"></script>

    <div class="p-6 space-y-8">

        <!-- ===================== SUMMARY WILAYAH ===================== -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-6">

            <div class="rounded-2xl shadow-lg p-6 text-black bg-white">
                <p class="text-sm opacity-90">Jumlah Provinsi</p>
                <h3 class="text-3xl font-bold mt-2">{{ $totalProvinsi }}</h3>
            </div>

            <div class="rounded-2xl shadow-lg p-6 text-black bg-white">
                <p class="text-sm opacity-90">Jumlah Kabupaten</p>
                <h3 class="text-3xl font-bold mt-2">{{ $totalKabupaten }}</h3>
            </div>

            <div class="rounded-2xl shadow-lg p-6 text-black bg-white">
                <p class="text-sm opacity-90">Jumlah Lokasi</p>
                <h3 class="text-3xl font-bold mt-2">{{ $totalLokasi }}</h3>
            </div>

        </div>

                <!-- ===================== FILTER GLOBAL ===================== -->
        <div class="bg-white rounded-2xl shadow p-6 mx-6 mt-6">

            <form method="GET" action="{{ route('admin.dashboard') }}"
                class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <!-- Provinsi -->
                <div>
                    <label class="block text-sm font-medium mb-1">Provinsi</label>
                    <select name="provinsi"
                        class="w-full border rounded-lg px-3 py-2 text-sm shadow-sm">
                        <option value="">Semua Provinsi</option>
                        @foreach($provinsiList as $prov)
                            <option value="{{ $prov->id }}"
                                {{ request('provinsi') == $prov->id ? 'selected' : '' }}>
                                {{ $prov->nama_provinsi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Search Kabupaten -->
                <div>
                    <label class="block text-sm font-medium mb-1">Kabupaten</label>
                    <input type="text" name="kabupaten"
                        value="{{ request('kabupaten') }}"
                        placeholder="Cari Kabupaten..."
                        class="w-full border rounded-lg px-3 py-2 text-sm shadow-sm">
                </div>

                <!-- Search Kawasan -->
                <div>
                    <label class="block text-sm font-medium mb-1">Kawasan</label>
                    <input type="text" name="kawasan"
                        value="{{ request('kawasan') }}"
                        placeholder="Cari Kawasan..."
                        class="w-full border rounded-lg px-3 py-2 text-sm shadow-sm">
                </div>

                <!-- Search Lokasi -->
                <div>
                    <label class="block text-sm font-medium mb-1">Lokasi</label>
                    <input type="text" name="lokasi"
                        value="{{ request('lokasi') }}"
                        placeholder="Cari Lokasi..."
                        class="w-full border rounded-lg px-3 py-2 text-sm shadow-sm">
                </div>

                <!-- Button -->
                <div class="md:col-span-4 flex justify-end gap-3 mt-2">
                    <a href="{{ route('admin.dashboard') }}"
                        class="px-4 py-2 bg-gray-200 rounded-lg text-sm">
                        Reset
                    </a>

                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">
                        Filter
                    </button>
                </div>

            </form>
        </div>

        <!-- ===================== SECTION SHM ===================== -->
        <div class="bg-white rounded-2xl shadow">
            <button onclick="toggleSection('sectionSHM')" 
                class="w-full flex items-center justify-between p-6 text-left">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        Sertifikat Hak Milik (SHM)
                    </h2>
                    <p class="text-sm text-gray-500">
                        Ringkasan capaian dan status Sertifikat Hak Milik
                    </p>
                </div>
                <span id="icon-sectionSHM" class="text-xl">▼</span>
            </button>

            <div id="sectionSHM" class="px-6 pb-6">

                <!-- FILTER ROW -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">

                    <h3 class="text-lg font-semibold text-gray-700">
                        
                    </h3>

                    <form method="GET" class="flex items-center gap-3">
                        <label class="text-sm text-gray-600 font-medium">
                            Filter Tahun:
                        </label>

                        <select name="tahun" 
                                onchange="this.form.submit()" 
                                class="px-4 py-2 border rounded-lg shadow-sm text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                            <option value="">Semua Tahun</option>

                            @foreach($listTahun as $tahun)
                                <option value="{{ $tahun }}" 
                                    {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                    {{ $tahun }}
                                </option>
                            @endforeach

                        </select>
                    </form>

                </div>

                <!-- PIE ROW -->
                <div class="bg-white rounded-xl shadow p-4">
                    <div id="pie_chart" style="height: 400px;"></div>
                </div>

                <!-- GRAFIK TAHUNAN -->
                <div class="mt-10 bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">
                        Target Tahunan Bidang SHM
                    </h3>
                    <div id="chart_bidang_tahunan" style="height:400px;"></div>
                </div>

            </div>
        </div>

        <!-- ===================== SECTION HPL ===================== -->
        <div class="bg-white rounded-2xl shadow">

            <!-- HEADER -->
            <button onclick="toggleSection('sectionHPL')" 
                class="w-full flex items-center justify-between p-6 text-left">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        Hak Pengelolaan (HPL)
                    </h2>
                    <p class="text-sm text-gray-500">
                        Ringkasan status legalitas Hak Pengelolaan 
                    </p>
                </div>
                <span id="icon-sectionHPL" class="text-xl transition-transform">▼</span>
            </button>

            <!-- CONTENT (yang di-toggle) -->
            <div id="sectionHPL" class="px-6 pb-6 hidden">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- PIE STATUS HPL -->
                    <div class="bg-white rounded-xl shadow p-4 h-full">
                        <div id="pie_status_hpl" style="height:400px;"></div>
                    </div>

                    <!-- PIE PETA HPL -->
                    <div class="bg-white rounded-xl shadow p-4 h-full">
                        <div id="pie_peta_hpl" style="height:400px;"></div>
                    </div>

                </div>

            </div>
        </div>


        <!-- ================= SECTION PERMASALAHAN LAHAN ================= -->
        <div class="bg-white rounded-2xl shadow">
            <button onclick="toggleSection('sectionPL')" 
                class="w-full flex items-center justify-between p-6 text-left">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        Permasalahan Lahan
                    </h2>
                    <p class="text-sm text-gray-500">
                        Distribusi jenis permasalahan lahan pada kawasan transmigrasi
                    </p>
                </div>
                <span id="icon-sectionPL" class="text-xl">▼</span>
            </button>
            <div id="sectionPL" class="px-6 pb-6">
                <div class="bg-white rounded-xl shadow p-4">
                    <div id="pie_permasalahan_lahan" style="height:400px;"></div>
                </div>
            </div>
        </div>

    </div>

    <script>
        window.toggleSection = function(id) {
            const section = document.getElementById(id);
            const icon = document.getElementById('icon-' + id);

            section.classList.toggle('hidden');

            if (section.classList.contains('hidden')) {
                icon.style.transform = "rotate(0deg)";
            } else {
                icon.style.transform = "rotate(180deg)";
                // Hitung ulang dimensi chart saat dibuka
                setTimeout(() => {
                    if (typeof Highcharts !== 'undefined') {
                        Highcharts.charts.forEach(chart => {
                            if (chart) chart.reflow();
                        });
                    }
                }, 50);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Highcharts === 'undefined') return;

            // Format DataLabels Pie yang 100% AMAN dari Bug (Nama: 00.0%)
            const safePieDataLabels = {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    fontSize: '13px'
                }
            };

            // 1. Grafik Pie SHM
            try {
                Highcharts.chart('pie_chart', {
                    chart: { type: 'pie' },
                    title: { text: 'Capaian Sertipikasi Tanah Transmigrasi' },
                    plotOptions: { 
                        pie: { size: '80%', allowPointSelect: true, cursor: 'pointer', dataLabels: safePieDataLabels } 
                    },
                    series: [{ name: 'SHM', colorByPoint: true, data: {!! json_encode($pie) !!} }]
                });
            } catch(e) { console.error("Error Grafik Pie SHM:", e); }

            // 2. Grafik Kolom Tahunan
            try {
                Highcharts.chart('chart_bidang_tahunan', {
                    chart: { type: 'column' },
                    title: { text: null },
                    xAxis: { categories: {!! json_encode($tahunList) !!}, title: { text: 'Tahun' } },
                    yAxis: { title: { text: 'Total Bidang' } },
                    tooltip: { pointFormat: '<b>{point.y} bidang</b>' },
                    series: [{ name: 'Bidang', data: {!! json_encode($dataBidang) !!} }],
                    credits: { enabled: false }
                });
            } catch(e) { console.error("Error Grafik Tahunan:", e); }

            // 3. Grafik Pie Status HPL
            try {
                Highcharts.chart('pie_status_hpl', {
                    chart: { type: 'pie' },
                    title: { text: 'Status Hak Pengelolaan' },
                    plotOptions: { 
                        pie: { allowPointSelect: true, cursor: 'pointer', dataLabels: safePieDataLabels } 
                    },
                    series: [{ name: 'Jumlah Kawasan', colorByPoint: true, data: {!! json_encode($pieStatusHpl) !!} }]
                });
            } catch(e) { console.error("Error Grafik Status HPL:", e); }

            // 4. Grafik Pie Peta HPL
            try {
                Highcharts.chart('pie_peta_hpl', {
                    chart: { type: 'pie' },
                    title: { text: 'Ketersediaan Peta Kawasan' },
                    plotOptions: { 
                        pie: { allowPointSelect: true, cursor: 'pointer', dataLabels: safePieDataLabels } 
                    },
                    series: [{ name: 'Jumlah Kawasan', colorByPoint: true, data: {!! json_encode($piePetaHpl) !!} }],
                    credits: { enabled: false }
                });
            } catch(e) { console.error("Error Grafik Peta HPL:", e); }

            // 5. Grafik Pie Permasalahan
            try {
                Highcharts.chart('pie_permasalahan_lahan', {
                    chart: { type: 'pie' },
                    title: { text: 'Distribusi Jenis Permasalahan' },
                    tooltip: { pointFormat: '<b>{point.percentage:.1f}%</b><br>Total: {point.y}' },
                    plotOptions: { 
                        pie: { size: '80%', allowPointSelect: true, cursor: 'pointer', dataLabels: safePieDataLabels } 
                    },
                    series: [{ name: 'Total', colorByPoint: true, data: {!! json_encode($pieJenisPermasalahan) !!} }]
                });
            } catch(e) { console.error("Error Grafik Permasalahan:", e); }
        });
    </script>
    
</body>
</html>
