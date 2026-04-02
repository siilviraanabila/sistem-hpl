<nav id="logo-sidebar" class="fixed pt-12 top-5 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 bg-[#1e293b]" aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto custom-scrollbar">
        <ul class="space-y-2 font-medium">
            
            @if(auth()->user()->role == 'admin')
            {{-- DASHBOARD --}}
            <li>
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center p-3 text-slate-300 rounded-xl hover:bg-slate-800 hover:text-white hover:shadow-md transition-all duration-200 group">
                    <svg class="w-6 h-6 text-slate-400 transition duration-75 group-hover:text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                        <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                        <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                    </svg>
                    <span class="ms-3 tracking-wide">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('getShm') }}"
                    class="flex items-center p-3 text-slate-300 rounded-xl hover:bg-slate-800 hover:text-white hover:shadow-md transition-all duration-200 group">
                    <svg class="w-8 h-8 text-slate-400 transition duration-75 group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M10 3v4a1 1 0 0 1-1 1H5m14-4v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z"/>
                    </svg>

                    <span class="ms-3 tracking-wide">Sertifikat Hak Milik (SHM)</span>
                </a>
            </li>
            <li>
                <a href="{{ route('getHpl') }}"
                    class="flex items-center p-3 text-slate-300 rounded-xl hover:bg-slate-800 hover:text-white hover:shadow-md transition-all duration-200 group">
                    <svg class="w-9 h-9 text-slate-400 transition duration-75 group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 8H4m0-2v13a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1h-5.032a1 1 0 0 1-.768-.36l-1.9-2.28a1 1 0 0 0-.768-.36H5a1 1 0 0 0-1 1Z"/>
                    </svg>

                    <span class="ms-3 tracking-wide">Hak Pengelolaan Lahan (HPL)</span>
                </a>
            </li>
            <li>
                <a href="{{ route('getPermasalahanLahan') }}"
                    class="flex items-center p-3 text-slate-300 rounded-xl hover:bg-slate-800 hover:text-white hover:shadow-md transition-all duration-200 group">
                    <svg class="w-7 h-7 text-slate-400 transition duration-75 group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M10 12v1h4v-1m4 7H6a1 1 0 0 1-1-1V9h14v9a1 1 0 0 1-1 1ZM4 5h16a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"/>
                    </svg>

                    <span class="ms-3 tracking-wide">Permasalahan Lahan</span>
                </a>
            </li>
        
            @endif
        </ul>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Saya sesuaikan ID nya agar script berfungsi dengan benar sesuai ID di HTML
        const toggleButton = document.getElementById('btn-dropdown-profil'); // Menggunakan ID yang saya tambahkan di tombol
        const dropdownMenu = document.getElementById('dropdown-profil');

        if(toggleButton && dropdownMenu) {
            toggleButton.addEventListener('click', function () {
                dropdownMenu.classList.toggle('hidden');
                // Optional: Rotasi panah kecil
                const arrow = toggleButton.querySelector('svg:last-child');
                if(arrow) arrow.classList.toggle('rotate-180');
            });
        }
    });
</script>