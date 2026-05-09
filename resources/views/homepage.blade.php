<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage - Designplus</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
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
    <style>
        body {
            padding-top: 80px;
        }

        html {
            scroll-behavior: smooth;
        }

        @keyframes infinite-scroll {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .animate-scroll {
            animation: infinite-scroll 25s linear infinite;
        }
    </style>
</head>

<body class="bg-white text-gray-800 font-sans antialiased">

    <x-navbar></x-navbar>
    <x-chatbox></x-chatbox>
    <main>

        <section class="w-full px-6 md:px-12 lg:px-16 pt-24 pb-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">

                <div class="rounded-xl overflow-hidden shadow-lg order-1 h-[300px] md:h-[360px] lg:h-[400px]">
                    <img src="{{ asset('assets/bg_home.png') }}" class="w-full h-full object-cover" alt="Hero Image">
                </div>

                <div class="order-2">
                    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-5">
                        Solusi Cetak <span class="text-brand-blue">Profesional</span> dan <span
                            class="text-brand-dark">Terpercaya</span>
                    </h1>
                    <p class="text-gray-600 text-lg leading-relaxed mb-7">
                        Kami hadir untuk membantu Anda menghasilkan produk berkualitas tinggi,
                        mulai dari kebutuhan bisnis, promosi, hingga souvenir dengan hasil maksimal.
                    </p>
                    <a href="{{ route('product.index') }}"
                        class="px-6 py-3 bg-brand-blue text-white font-semibold rounded-lg shadow hover:bg-brand-dark transition">
                        Pesan Sekarang →
                    </a>
                </div>

            </div>
        </section>

        <section class="w-full px-6 md:px-12 lg:px-16 py-10 text-center overflow-hidden">
            <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">
                Mitra yang Sudah Bekerja Sama dengan Kami
            </h2>

            <div class="w-full inline-flex flex-nowrap overflow-hidden [mask-image:_linear-gradient(to_right,transparent_0,_black_128px,_black_calc(100%-200px),transparent_100%)]">
                <ul class="flex items-center justify-center md:justify-start [&_li]:mx-12 [&_img]:max-w-none animate-scroll">
                    <li><img src="{{ asset('assets/paramount.png') }}" ></li>
                    <li><img src="{{ asset('assets/mane.png') }}"></li>
                    <li><img src="{{ asset('assets/season.png') }}"></li>
                    <li><img src="{{ asset('assets/continental.png') }}"></li>
                </ul>
                <ul class="flex items-center justify-center md:justify-start [&_li]:mx-12 [&_img]:max-w-none animate-scroll" aria-hidden="true">
                    <li><img src="{{ asset('assets/paramount.png') }}" ></li>
                    <li><img src="{{ asset('assets/mane.png') }}"></li>
                    <li><img src="{{ asset('assets/season.png') }}"></li>
                    <li><img src="{{ asset('assets/continental.png') }}"></li>
                </ul>
            </div>
        </section>

        <section class="w-full px-6 md:px-12 lg:px-16 py-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Apapun Kebutuhan Cetak Anda</h2>

            <div class="grid md:grid-cols-3 gap-8">

                <div class="rounded-xl overflow-hidden shadow border hover:shadow-lg transition">
                    <img src="{{ asset('assets/cetak.jpg') }}" class="w-full h-48 object-cover"
                        alt="Cetak Offset & Digital">
                    <div class="p-5">
                        <h3 class="font-bold text-lg">Cetak Offset & Digital</h3>
                        <p class="text-gray-600 text-sm mt-2">Cetak brosur, poster, booklet, dan kebutuhan bisnis
                            lainnya.</p>
                    </div>
                </div>

                <div class="rounded-xl overflow-hidden shadow border hover:shadow-lg transition">
                    <img src="{{ asset('assets/souvenir.jpg') }}" class="w-full h-48 object-cover"
                        alt="Souvenir & Merchandise">
                    <div class="p-5">
                        <h3 class="font-bold text-lg">Souvenir & Merchandise</h3>
                        <p class="text-gray-600 text-sm mt-2">Merchandise custom untuk event, kantor, dan komunitas.</p>
                    </div>
                </div>

                <div class="rounded-xl overflow-hidden shadow border hover:shadow-lg transition">
                    <img src="{{ asset('assets/promosi.jpg') }}" class="w-full h-48 object-cover" alt="Media Promosi">
                    <div class="p-5">
                        <h3 class="font-bold text-lg">Media Promosi</h3>
                        <p class="text-gray-600 text-sm mt-2">Banner, X-banner, stiker, kartu nama, dan lainnya.</p>
                    </div>
                </div>

            </div>
        </section>

        <section class="w-full px-6 md:px-12 lg:px-16 py-20 bg-white">
            <h2 class="text-2xl font-bold text-gray-900 text-center mb-14">Mengapa Harus Kami?</h2>

            <div class="grid md:grid-cols-3 gap-10 text-center">

                <div>
                    <div class="text-5xl mb-3">⭐</div>
                    <h3 class="font-semibold text-lg">Produk Berkualitas Tinggi</h3>
                    <p class="text-gray-600 text-sm mt-2">Menggunakan material dan mesin terbaik untuk hasil maksimal.
                    </p>
                </div>

                <div>
                    <div class="text-5xl mb-3">⚡</div>
                    <h3 class="font-semibold text-lg">Waktu Proses Cepat</h3>
                    <p class="text-gray-600 text-sm mt-2">Pengerjaan yang cepat tanpa mengurangi kualitas produksi.</p>
                </div>

                <div>
                    <div class="text-5xl mb-3">🤝</div>
                    <h3 class="font-semibold text-lg">Pelayanan Responsif</h3>
                    <p class="text-gray-600 text-sm mt-2">Tim kami siap membantu kebutuhan Anda setiap waktu.</p>
                </div>

            </div>
        </section>

    </main>

    <x-footer></x-footer>
    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const menuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');

            menuBtn.addEventListener('click', function () {
                mobileMenu.classList.toggle('hidden');

                // Ganti ikon hamburger menjadi X atau sebaliknya
                const icon = menuBtn.querySelector('i');
                if (mobileMenu.classList.contains('hidden')) {
                    icon.classList.remove('fa-xmark');
                    icon.classList.add('fa-bars');
                } else {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-xmark');
                }
            });
        });
    </script>
</body>

</html>