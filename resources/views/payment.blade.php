<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran #{{ $order->number }} - DesignPlus</title>

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
            padding-top: 80px; /* Space untuk navbar fixed */
        }
    </style>
</head>

<body class="bg-brand-gray text-gray-800 font-sans antialiased">

    <x-navbar></x-navbar>

    <main class="min-h-screen py-8">
        <div class="container mx-auto px-4 lg:px-8 max-w-5xl">

            <nav class="flex text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="/" class="hover:text-brand-blue">Home</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fa-solid fa-chevron-right text-xs mx-2"></i>
                            <span class="text-gray-800 font-medium">Pembayaran</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-6">

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center relative overflow-hidden">
                        
                        @if($order->payment_status == '2') 
                            <div class="absolute top-0 left-0 w-full h-1 bg-green-500"></div>
                            <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-check text-4xl text-green-500"></i>
                            </div>
                            <h1 class="text-2xl font-bold text-gray-900">Pembayaran Berhasil!</h1>
                            <p class="text-gray-500 mt-2">Terima kasih, pesanan Anda sedang kami proses.</p>
                        
                        @elseif($order->payment_status == '3') 
                            <div class="absolute top-0 left-0 w-full h-1 bg-red-500"></div>
                            <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-xmark text-4xl text-red-500"></i>
                            </div>
                            <h1 class="text-2xl font-bold text-gray-900">Pesanan Kedaluwarsa</h1>
                            <p class="text-gray-500 mt-2">Batas waktu pembayaran telah habis. Silakan pesan ulang.</p>
                        
                        @else 
                            <div class="absolute top-0 left-0 w-full h-1 bg-brand-blue"></div>
                            <div class="w-20 h-20 bg-brand-light rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa-regular fa-clock text-4xl text-brand-blue animate-pulse"></i>
                            </div>
                            <h1 class="text-2xl font-bold text-gray-900">Menunggu Pembayaran</h1>
                            <p class="text-gray-500 mt-2">Selesaikan pembayaran Anda sebelum waktu habis.</p>

                            <div class="mt-6 inline-flex items-center gap-3 bg-orange-50 border border-orange-100 px-6 py-3 rounded-lg">
                                <i class="fa-solid fa-hourglass-half text-orange-500"></i>
                                <div class="text-left">
                                    <p class="text-xs text-orange-600 font-semibold uppercase tracking-wider">Sisa Waktu</p>
                                    <p id="countdown-timer" class="text-xl font-bold text-orange-700 font-mono tracking-widest">-- : -- : --</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-bag-shopping text-brand-blue"></i> Rincian Produk
                        </h2>
                        
                        <div class="space-y-6">
                            @foreach($order->items as $item)
                            <div class="flex gap-4 sm:gap-6 border-b border-gray-50 last:border-0 pb-6 last:pb-0">
                                <div class="w-20 h-20 sm:w-24 sm:h-24 flex-shrink-0 overflow-hidden rounded-lg bg-gray-100 border border-gray-200">
                                    @if($item->product)
                                        <img src="{{ asset($item->product->file) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="flex items-center justify-center h-full text-gray-400">
                                            <i class="fa-solid fa-image text-xl"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-semibold text-gray-900 text-lg">{{ $item->product_name }}</h3>
                                            <p class="text-sm text-gray-500 mt-1">
                                                {{ $item->quantity }} x Rp {{ number_format($item->product_price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                        <p class="font-bold text-brand-blue">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @if($item->bahan)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                                Bahan: {{ $item->bahan }}
                                            </span>
                                        @endif
                                        @if($item->warna)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                                Warna: {{ $item->warna }}
                                            </span>
                                        @endif
                                        @if(!empty($item->custom_file) && $item->custom_file !== 'null' && strtolower($item->custom_file) !== 'standard') 
                                            <a href="{{ asset('storage/' . $item->custom_file) }}" target="_blank" class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 transition">
                                                <i class="fa-solid fa-paperclip mr-1"></i> File Custom
                                            </a>
                                        @endif
                                    </div>

                                    @if($item->note)
                                        <div class="mt-2 text-xs text-gray-500 bg-gray-50 p-2 rounded border border-dashed border-gray-200">
                                            <i class="fa-regular fa-comment-dots mr-1"></i> Note: {{ $item->note }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 sticky top-28">
                        <h2 class="text-lg font-bold text-gray-900 mb-6">Ringkasan Tagihan</h2>

                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>No. Invoice</span>
                                <span class="font-mono font-medium text-gray-900">{{ $order->number }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Tanggal Order</span>
                                <span>{{ $order->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Tujuan</span>
                                <span class="font-medium text-gray-900 text-right">{{ $order->shipping_address ?? '-' }}</span>
                            </div>

                            <hr class="border-dashed border-gray-200 my-2">

                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Subtotal Barang</span>
                                <span>Rp {{ number_format($order->items->sum('subtotal'), 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Pajak (PPN 11%)</span>
                                <span>Rp {{ number_format($order->items->sum('subtotal') * 0.11, 0, ',', '.') }}</span>
                            </div>

                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Ongkos Kirim</span>
                                <span class="font-medium text-gray-900">
                                    Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Status</span>
                                @if($order->payment_status == '2')
                                    <span class="text-green-600 font-bold bg-green-50 px-2 rounded">Lunas</span>
                                @elseif($order->payment_status == '3')
                                    <span class="text-red-600 font-bold bg-red-50 px-2 rounded">Expired</span>
                                @else
                                    <span class="text-brand-blue font-bold bg-blue-50 px-2 rounded">Pending</span>
                                @endif
                            </div>
                        </div>

                        <hr class="border-dashed border-gray-200 my-4">

                        <div class="flex justify-between items-center mb-8">
                            <span class="text-lg font-bold text-gray-900">Total Bayar</span>
                            <span class="text-2xl font-bold text-brand-blue">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>

                        @if($order->payment_status == '1')
                            <a href="{{ $order->snap_token }}" target="_blank" 
                               class="w-full bg-brand-blue hover:bg-brand-dark text-white font-semibold py-4 px-4 rounded-lg transition-colors duration-200 shadow-lg shadow-blue-500/30 flex justify-center items-center gap-2 mb-3">
                                <span>Bayar Sekarang</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-sm"></i>
                            </a>
                            <div class="text-center text-xs text-gray-400">
                                <i class="fa-solid fa-lock mr-1"></i> Pembayaran Aman via Xendit
                            </div>
                        @else
                            <a href="{{ route('home') }}" 
                               class="w-full border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex justify-center items-center gap-2">
                                <i class="fa-solid fa-house text-sm"></i>
                                <span>Kembali ke Beranda</span>
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </main>

    <x-footer></x-footer>


    <script>
        function startCountdown(deadline) {

            const timerElement = document.getElementById('countdown-timer');
            // Jika elemen tidak ada (misal status sudah paid), hentikan fungsi
            if (!timerElement) return;

            const interval = setInterval(() => {
                const now = new Date().getTime();
                const distance = deadline - now;

                if (distance < 0) {
                    clearInterval(interval);
                    timerElement.innerHTML = "MEMERIKSA STATUS...";
                    timerElement.classList.remove('text-orange-700');
                    timerElement.classList.add('text-red-600');
                    
                    // Tambah delay 2 detik sebelum reload agar tidak kaget
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                    
                    return;
                }

                // Hitung jam, menit, detik
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Format angka jadi 2 digit (01, 05, dll)
                const fHours = hours < 10 ? "0" + hours : hours;
                const fMinutes = minutes < 10 ? "0" + minutes : minutes;
                const fSeconds = seconds < 10 ? "0" + seconds : seconds;

                timerElement.innerHTML = `${fHours} : ${fMinutes} : ${fSeconds}`;
            }, 1000);
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Logika Waktu: Waktu Order Dibuat + 12 Jam
            // Mengambil waktu created_at dari server PHP
            const createdTime = new Date("{{ $order->created_at->format('Y-m-d H:i:s') }}").getTime();
            
            // Tambah 12 jam (12 jam * 60 menit * 60 detik * 1000 milidetik)
            const duration = 12 * 60 * 60 * 1000; 
            const deadline = createdTime + duration;

            startCountdown(deadline);
        });

    </script>

    @stack('scripts')
</body>

</html>
