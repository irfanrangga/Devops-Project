<nav class="fixed top-0 left-0 w-full bg-white/50 backdrop-blur-md shadow-sm z-50 border-b border-gray-100 h-[80px]">
    <div class="h-full px-6 lg:px-10 flex items-center justify-between">

        <a href="{{ route('home') }}" class="text-2xl font-bold text-brand-blue tracking-tighter shrink-0 mr-8">
            Designplus.
        </a>

        <div class="hidden lg:flex items-center gap-8 ml-8">
            <ul class="flex items-center gap-6 text-[15px] font-medium text-gray-600">
                <li>
                    <a href="{{ route('home') }}"
                        class="{{ request()->routeIs('home') ? 'text-brand-blue font-bold' : 'hover:text-brand-blue transition' }}">
                        Beranda
                    </a>
                </li>
                <li>
                    <a href="{{ route('product.index') }}"
                        class="{{ request()->routeIs('product.*') ? 'text-brand-blue font-bold' : 'hover:text-brand-blue transition' }}">
                        Produk
                    </a>
                </li>
                <li>
                    <a href="{{ route('layanan') }}"
                        class="{{ request()->routeIs('layanan') ? 'text-brand-blue font-bold' : 'hover:text-brand-blue transition' }}">
                        Layanan
                    </a>
                </li>
            </ul>

            <div class="h-6 w-px bg-gray-200"></div>

            <div class="flex items-center gap-6">
                <a href="{{ route('cart') }}" class="text-xl text-gray-700 hover:text-brand-blue transition">
                    <i class="fa-solid fa-cart-shopping"></i>
                </a>

                @if(Auth::check())
                    <div class="group relative">
                        <a href="{{ route('profile') }}"
                            class="flex items-center gap-2 text-gray-700 hover:text-brand-blue hover:bg-blue-50 transition rounded-md p-2">
                            {{-- <i class="fa-regular fa-user text-xl group-hover:text-brand-blue"></i> --}}
                            <img src="{{ Auth::user()->avatar ?? asset('assets/icon/profile.png') }}" alt="{{ Auth::user()->name }}" class="w-7 h-7 rounded-full object-cover border border-blue-300">
                            {{-- <span class="font-medium text-[15px]">{{ Auth::user()->name }}</span> --}}
                            <i class="fa-solid fa-caret-down text-xs ml-1"></i>
                        </a>

                        <div
                            class="absolute right-0 mt-3 w-40 bg-white border border-gray-200 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform scale-95 group-hover:scale-100 p-2">

                            <a href="{{ route('profile') }}"
                                class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition">
                                <i class="fa-solid fa-gear"></i> Profile
                            </a>
                            @if (Auth::user()->role === 'admin')
                                @csrf
                                <a href="{{ route('dashboard') }}"
                                    class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 rounded-md transition">
                                    <i class="fa-solid fa-gauge"></i>Dashboard
                                </a>
                            @endif

                            <form action="{{ route('logout') }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit"
                                    class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-md transition justify-start">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="flex items-center gap-2 text-gray-700 hover:text-brand-blue transition group">
                        <i class="fa-regular fa-user text-xl group-hover:text-brand-blue"></i>
                        <span class="font-medium text-[15px]">Masuk</span>
                    </a>
                @endif
            </div>
        </div>

        <button id="mobile-menu-btn" class="lg:hidden text-2xl text-gray-700 focus:outline-none">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>

    <div id="mobile-menu" class="lg:hidden bg-white border-t border-gray-100 absolute w-full left-0 top-[80px] shadow-lg p-5 flex flex-col gap-4 opacity-0 -translate-y-3 pointer-events-none transition-all duration-300 ease-out">
        <div class="flex items-center bg-brand-light rounded-xl px-4 py-3 w-full mb-4">
            <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
            <input type="text" placeholder="Cari produk"
                class="w-full bg-transparent border-none outline-none ml-3 text-sm">
        </div>

        <div class="border border-gray-200 rounded-xl overflow-hidden">

            <a href="{{ route('home') }}"
                class="block px-4 py-4 border-b border-gray-200
                {{ request()->routeIs('home') ? 'bg-blue-50 text-brand-blue font-semibold' : 'text-gray-700' }}">
                Beranda
            </a>

            <a href="{{ route('product.index') }}"
                class="block px-4 py-4 border-b border-gray-200
                {{ request()->routeIs('product.*') ? 'bg-blue-50 text-brand-blue font-semibold' : 'text-gray-700' }}">
                Produk
            </a>

            <a href="{{ route('layanan') }}"
                class="block px-4 py-4
                {{ request()->routeIs('layanan') ? 'bg-blue-50 text-brand-blue font-semibold' : 'text-gray-700' }}">
                Layanan
            </a>

        </div>

        <hr class="border-gray-100">

        @if (Auth::check())
           <div class="mt-2 border-t border-gray-200 pt-4">

            <div class="mb-6">
                <span class="text-gray-500 text-sm">
                    Selamat datang,
                </span>

                <p class="font-semibold text-lg text-gray-900">
                    {{ Auth::user()->name }}
                </p>
            </div>

            <div class="border-t border-gray-200 mt-5 pt-4">

                <div class="grid grid-cols-2 gap-3">

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf

                        <button type="submit"
                            class="w-full border border-red-200 rounded-xl py-3
                                text-red-600 font-medium
                                flex items-center justify-center gap-2
                                hover:bg-red-50 transition">

                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            Keluar
                        </button>
                    </form>

                    <a href="{{ route('profile') }}"
                        class="border border-blue-200 rounded-xl py-3
                            text-brand-blue font-medium
                            flex items-center justify-center gap-2
                            hover:bg-blue-50 transition">

                        <i class="fa-solid fa-gear"></i>
                        Pengaturan
                    </a>

                </div>

            </div>

            @if (Auth::user()->role === 'admin')
                <a href="{{ route('dashboard') }}"
                    class="mt-4 flex items-center gap-2 text-gray-700">
                    <i class="fa-solid fa-gauge"></i>
                    Dashboard
                </a>
            @endif

        </div>
        @else
            <div class="flex justify-between items-center py-2">
                <span class="text-gray-600 font-medium">Status</span>
                <a href="{{ route('login') }}" class="text-brand-blue font-semibold hover:underline">
                    Masuk / Daftar
                </a>
            </div>
        @endif
    </div>
</nav>

@push('scripts')
    <script>
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');

        btn.addEventListener('click', () => {

            const isOpen = !menu.classList.contains('pointer-events-none');

            if (isOpen) {

                menu.classList.add(
                    'opacity-0',
                    '-translate-y-3',
                    'pointer-events-none'
                );

            } else {

                menu.classList.remove(
                    'opacity-0',
                    '-translate-y-3',
                    'pointer-events-none'
                );

            }

        });

        // Config Tailwind tetap sama
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            blue: '#005BEC',
                            dark: '#0A43C3',
                            light: '#EEF2FF',
                            gray: '#f8f9fa'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
@endpush

