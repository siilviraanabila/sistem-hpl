<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
        <link href="/resources/css/style.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <link href="{{ asset('DataTables-1.13.8/css/jquery.dataTables.css') }}" rel="stylesheet" type="text/css">
        <script src="{{ asset('DataTables-1.13.8/js/jquery.dataTables.js') }}" type="text/javascript" language="javascript"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.3/moment.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.5.1/fullcalendar.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.5.1/fullcalendar.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js" integrity="sha512-eyHL1atYNycXNXZMDndxrDhNAegH2BDWt1TmkXJPoGf1WLlNYt08CSjkqF5lnCRmdm3IrkHid8s2jOUY4NIZVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </head>
    <nav class="fixed top-0 left-0 z-50 w-full border-b border-gray-200"
     style="background:#173f4f">

        <div class="px-4 py-5 flex items-center justify-between">

            <!-- LEFT: Logo + Hamburger -->
            <div class="flex items-center gap-3">
                <!-- Hamburger (Mobile) -->
                <!-- <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar"
                    aria-controls="logo-sidebar" type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-white">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"/>
                    </svg>
                </button> -->

                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <img src="https://www.transmigrasi.go.id/wp-content/uploads/2024/11/LOGO-KEMENTRANS-Bulat.png"
                        class="h-10 w-10" alt="Logo">
                    <div class="leading-tight">
                        <h1 class="text-lg font-semibold text-white">Dashboard Pertanahan</h1>
                        <p class="text-xs text-white">Kementerian Transmigrasi RI</p>
                    </div>
                </div>
            </div>

            <!-- CENTER: Menu (Desktop) -->
            

            <!-- RIGHT: User -->
            <div class="flex items-center gap-3">
                @auth
                <ul class="hidden lg:flex items-center gap-6 text-sm font-medium text-white">

                    {{-- Dashboard (semua role) --}}
                    <li>
                        <a href="{{ auth()->user()->role == 'admin' 
                                    ? route('admin.dashboard') 
                                    : route('pimpinan.dashboard') }}"
                        class="hover:text-yellow-400">
                            Dashboard
                        </a>
                    </li>

                    {{-- Menu khusus admin --}}
                    @if(auth()->user()->role === 'admin')
                        <li>
                            <a href="{{ route('getShm') }}" class="hover:text-yellow-400">
                                Sertifikat Hak Milik
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('getHpl') }}" class="hover:text-yellow-400">
                                Hak Pengelolaan 
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('getPermasalahanLahan') }}" class="hover:text-yellow-400">
                                Permasalahan Lahan
                            </a>
                        </li>
                    @endif

                </ul>
                @endauth

                <button data-dropdown-toggle="dropdown-user"
                    class="flex items-center rounded-full focus:ring-2 focus:ring-white-200">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 16 16">
                        <path
                            d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4z"/>
                    </svg>
                </button>

                <!-- Dropdown -->
                <div id="dropdown-user"
                    class="hidden z-50 my-4 text-base bg-white rounded shadow divide-y divide-gray-100">
                    <div class="px-4 py-3">
                        <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                            Sign out
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</html>