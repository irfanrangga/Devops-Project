<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Pelanggan - DesignPlus</title>

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
                            gray: '#f8f9fa',
                            green: '#25D366', 
                            greenDark: '#128C7E'
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
    </style>
</head>

<body class="bg-white text-gray-800 font-sans antialiased flex flex-col min-h-screen">

    <x-navbar></x-navbar>

    <main class="flex-grow">
        <section class="bg-brand-light py-16 md:py-24">
            <div class="container mx-auto px-4 lg:px-8 text-center max-w-3xl">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 tracking-tight">Pusat Layanan & Bantuan
                </h1>
                <p class="text-lg text-gray-600 mb-8">
                    Punya pertanyaan tentang produk, pesanan, atau butuh konsultasi desain?
                    Tim Customer Service kami siap membantu Anda dengan cepat.
                </p>

                <a href="https://wa.me/6281292839906?text=Halo%20Admin%20DesignPlus,%20saya%20butuh%20bantuan%20"
                    target="_blank"
                    class="inline-flex items-center gap-3 bg-brand-green hover:bg-brand-greenDark text-white font-bold py-4 px-8 rounded-full shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <i class="fa-brands fa-whatsapp text-2xl group-hover:scale-110 transition-transform"></i>
                    <span>Chat Customer Service via WhatsApp</span>
                </a>
                <p class="text-sm text-gray-500 mt-4">Respon cepat: Senin - Jumat (08.00 - 17.00 WIB)</p>
            </div>
        </section>

        <section class="py-16">
            <div class="container mx-auto px-4 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-start">

                    <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Hubungi Kami</h2>

                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-12 h-12 rounded-lg bg-brand-light text-brand-blue flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-location-dot text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-1">Alamat Kantor</h4>
                                    <p class="text-gray-600 leading-relaxed">
                                        Jl. Designplus No.32, Bekasi,<br>
                                        Cikarang Selatan, 27381
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div
                                    class="w-12 h-12 rounded-lg bg-green-50 text-brand-green flex items-center justify-center flex-shrink-0">
                                    <i class="fa-brands fa-whatsapp text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-1">WhatsApp & Telepon</h4>
                                    <a href="https://wa.me/6281292839906"
                                        class="text-gray-600 hover:text-brand-green transition block">
                                        +62 812-9283-9906 (Chat Only)
                                    </a>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div
                                    class="w-12 h-12 rounded-lg bg-blue-50 text-brand-blue flex items-center justify-center flex-shrink-0">
                                    <i class="fa-regular fa-envelope text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-1">Email Support</h4>
                                    <a href="mailto:cs@designplus.com"
                                        class="text-gray-600 hover:text-brand-blue transition">
                                        cs@designplus.com
                                    </a>
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-100 my-8">

                        <h3 class="font-bold text-gray-900 mb-4">Ikuti Kami</h3>
                        <div class="flex gap-4">
                            <a href="#"
                                class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-brand-blue hover:text-white transition">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                            <a href="#"
                                class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-blue-600 hover:text-white transition">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="#"
                                class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-black hover:text-white transition">
                                <i class="fa-brands fa-tiktok"></i>
                            </a>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-2xl border border-gray-100 shadow-sm h-full flex flex-col overflow-hidden">

                        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                <i class="fa-regular fa-circle-question text-brand-blue"></i>
                                Pertanyaan Umum (FAQ)
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">Jawaban untuk pertanyaan yang sering diajukan.</p>
                        </div>

                        <div class="flex-1 overflow-y-auto h-[500px] p-6 space-y-2 custom-scrollbar">

                            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 mt-1">Pemesanan &
                                Desain</div>

                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <button onclick="toggleFaq(this)"
                                    class="w-full flex justify-between items-center p-4 text-left bg-white hover:bg-gray-50 transition-colors focus:outline-none">
                                    <span class="font-semibold text-gray-800 text-sm">Bagaimana cara memesan jasa
                                        desain?</span>
                                    <i
                                        class="fa-solid fa-chevron-down text-gray-400 text-xs transition-transform duration-300"></i>
                                </button>
                                <div
                                    class="hidden bg-gray-50 p-4 text-sm text-gray-600 border-t border-gray-100 leading-relaxed">
                                    Anda bisa memilih paket layanan di halaman Produk, masukkan ke keranjang, dan
                                    lakukan checkout. Setelah itu, admin kami akan menghubungi Anda via WhatsApp untuk
                                    diskusi brief desain.
                                </div>
                            </div>

                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <button onclick="toggleFaq(this)"
                                    class="w-full flex justify-between items-center p-4 text-left bg-white hover:bg-gray-50 transition-colors focus:outline-none">
                                    <span class="font-semibold text-gray-800 text-sm">Berapa lama proses
                                        pengerjaannya?</span>
                                    <i
                                        class="fa-solid fa-chevron-down text-gray-400 text-xs transition-transform duration-300"></i>
                                </button>
                                <div
                                    class="hidden bg-gray-50 p-4 text-sm text-gray-600 border-t border-gray-100 leading-relaxed">
                                    Estimasi standar adalah 1-3 hari kerja untuk desain grafis (logo, poster). Untuk
                                    percetakan (banner, kaos), waktu produksi sekitar 3-7 hari kerja tergantung antrian
                                    dan jumlah pesanan.
                                </div>
                            </div>

                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <button onclick="toggleFaq(this)"
                                    class="w-full flex justify-between items-center p-4 text-left bg-white hover:bg-gray-50 transition-colors focus:outline-none">
                                    <span class="font-semibold text-gray-800 text-sm">Apakah ada batasan revisi
                                        desain?</span>
                                    <i
                                        class="fa-solid fa-chevron-down text-gray-400 text-xs transition-transform duration-300"></i>
                                </button>
                                <div
                                    class="hidden bg-gray-50 p-4 text-sm text-gray-600 border-t border-gray-100 leading-relaxed">
                                    Ya, setiap paket memiliki batas revisi berbeda. Umumnya kami memberikan **maksimal
                                    3x revisi minor** (ganti warna, geser layout). Revisi mayor (ganti konsep total)
                                    akan dikenakan biaya tambahan.
                                </div>
                            </div>

                            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 mt-6">Pembayaran &
                                Pengiriman</div>

                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <button onclick="toggleFaq(this)"
                                    class="w-full flex justify-between items-center p-4 text-left bg-white hover:bg-gray-50 transition-colors focus:outline-none">
                                    <span class="font-semibold text-gray-800 text-sm">Metode pembayaran apa yang
                                        tersedia?</span>
                                    <i
                                        class="fa-solid fa-chevron-down text-gray-400 text-xs transition-transform duration-300"></i>
                                </button>
                                <div
                                    class="hidden bg-gray-50 p-4 text-sm text-gray-600 border-t border-gray-100 leading-relaxed">
                                    Kami menerima pembayaran melalui Transfer Bank (BCA, Mandiri, BNI) dan E-Wallet
                                    (GoPay, OVO, Dana). Pembayaran dilakukan penuh di muka atau DP 50% untuk nominal
                                    transaksi di atas Rp 500.000.
                                </div>
                            </div>

                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <button onclick="toggleFaq(this)"
                                    class="w-full flex justify-between items-center p-4 text-left bg-white hover:bg-gray-50 transition-colors focus:outline-none">
                                    <span class="font-semibold text-gray-800 text-sm">Apakah bisa kirim ke luar
                                        kota?</span>
                                    <i
                                        class="fa-solid fa-chevron-down text-gray-400 text-xs transition-transform duration-300"></i>
                                </button>
                                <div
                                    class="hidden bg-gray-50 p-4 text-sm text-gray-600 border-t border-gray-100 leading-relaxed">
                                    Tentu! Untuk produk fisik (cetakan), kami bekerja sama dengan ekspedisi JNE, J&T,
                                    dan SiCepat untuk pengiriman ke seluruh Indonesia. Ongkir ditanggung pembeli.
                                </div>
                            </div>

                            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 mt-6">Teknis File
                            </div>

                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <button onclick="toggleFaq(this)"
                                    class="w-full flex justify-between items-center p-4 text-left bg-white hover:bg-gray-50 transition-colors focus:outline-none">
                                    <span class="font-semibold text-gray-800 text-sm">Format file apa yang akan saya
                                        terima?</span>
                                    <i
                                        class="fa-solid fa-chevron-down text-gray-400 text-xs transition-transform duration-300"></i>
                                </button>
                                <div
                                    class="hidden bg-gray-50 p-4 text-sm text-gray-600 border-t border-gray-100 leading-relaxed">
                                    Untuk jasa desain, Anda akan menerima file Master (AI/PSD/EPS) sesuai request, serta
                                    file export siap pakai seperti JPG, PNG (transparan), dan PDF High Resolution.
                                </div>
                            </div>

                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <button onclick="toggleFaq(this)"
                                    class="w-full flex justify-between items-center p-4 text-left bg-white hover:bg-gray-50 transition-colors focus:outline-none">
                                    <span class="font-semibold text-gray-800 text-sm">Apakah file desain saya
                                        aman?</span>
                                    <i
                                        class="fa-solid fa-chevron-down text-gray-400 text-xs transition-transform duration-300"></i>
                                </button>
                                <div
                                    class="hidden bg-gray-50 p-4 text-sm text-gray-600 border-t border-gray-100 leading-relaxed">
                                    Sangat aman. Kami menjamin kerahasiaan file project Anda dan menyimpannya di cloud
                                    storage kami selama 3 bulan sebagai backup jika Anda kehilangan file tersebut.
                                </div>
                            </div>

                        </div>

                        <div class="p-4 bg-brand-light border-t border-gray-100 text-center">
                            <p class="text-xs text-brand-blue font-medium">Masih punya pertanyaan lain? Chat kami!</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <a href="https://wa.me/6281292839906" target="_blank"
            class="fixed bottom-6 right-6 z-50 bg-brand-green text-white w-14 h-14 rounded-full shadow-xl flex items-center justify-center hover:bg-brand-greenDark hover:scale-110 transition-all duration-300 animate-bounce-slow"
            title="Chat WhatsApp">
            <i class="fa-brands fa-whatsapp text-3xl"></i>
        </a>

    </main>

    <x-footer></x-footer>


    <script>
        function toggleFaq(button) {
            const content = button.nextElementSibling;
            const icon = button.querySelector('i');
            content.classList.toggle('hidden');

            if (content.classList.contains('hidden')) {
                icon.style.transform = 'rotate(0deg)';
            } else {
                icon.style.transform = 'rotate(180deg)';
            }
        }
    </script>

    @stack('scripts')

    <style>


        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        .animate-bounce-slow {
            animation: bounce 3s infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }
    </style>

</body>

</html>