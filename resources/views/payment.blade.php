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
                        
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                            <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow duration-200">
                                <!-- Product Header -->
                                <div class="flex flex-col sm:flex-row gap-4 sm:gap-5 p-5 bg-gradient-to-br from-gray-50 to-white">
                                    <!-- Product Image -->
                                    <div class="w-28 h-28 sm:w-32 sm:h-32 flex-shrink-0 overflow-hidden rounded-lg bg-gray-100 border border-gray-200 shadow-sm">
                                        @if($item->product)
                                            <img src="{{ asset($item->product->file) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="flex items-center justify-center h-full text-gray-400 text-2xl">
                                                <i class="fa-solid fa-image"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-1 flex flex-col justify-between">
                                        <div>
                                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2 mb-3">
                                                <div>
                                                    <h3 class="font-bold text-gray-900 text-base sm:text-lg leading-snug">{{ $item->product_name }}</h3>
                                                    <p class="text-sm text-gray-500 mt-1.5">
                                                        <span class="font-semibold text-gray-700">{{ $item->quantity }}x</span> @ Rp {{ number_format($item->product_price, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Subtotal</p>
                                                    <p class="font-bold text-brand-blue text-lg">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Specifications Tags -->
                                        @if($item->bahan || $item->warna)
                                        <div class="flex flex-wrap gap-2 pt-3 border-t border-gray-200">
                                            @if($item->bahan)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                                    <i class="fa-solid fa-shirt text-amber-600"></i>
                                                    <span>{{ $item->bahan }}</span>
                                                </span>
                                            @endif
                                            @if($item->warna)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200">
                                                    <i class="fa-solid fa-palette text-purple-600"></i>
                                                    <span>{{ $item->warna }}</span>
                                                </span>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Custom Design File Section -->
                                @if(!empty($item->custom_file) && $item->custom_file !== 'null' && strtolower($item->custom_file) !== 'standard') 
                                <div class="border-t border-gray-200 bg-blue-50 px-5 py-3">
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            @php
                                                $fileExtension = strtolower(pathinfo($item->custom_file, PATHINFO_EXTENSION));
                                                $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                                $isPdf = $fileExtension === 'pdf';
                                            @endphp
                                            
                                            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-white border border-blue-200 flex-shrink-0">
                                                @if($isImage)
                                                    <i class="fa-solid fa-image text-lg text-blue-500"></i>
                                                @elseif($isPdf)
                                                    <i class="fa-solid fa-file-pdf text-lg text-red-500"></i>
                                                @else
                                                    <i class="fa-solid fa-file text-lg text-blue-500"></i>
                                                @endif
                                            </div>
                                            
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-blue-700 uppercase tracking-wide">📎 Desain Custom</p>
                                                <p class="text-xs font-medium text-gray-800 truncate">{{ basename($item->custom_file) }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex gap-1.5 flex-shrink-0">
                                            @if($isImage)
                                                <button onclick="viewDesignModal('{{ route('design.view', ['orderId' => $order->id, 'itemId' => $item->id]) }}')" 
                                                        class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" title="Lihat desain">
                                                    <i class="fa-solid fa-eye text-sm"></i>
                                                </button>
                                            @endif
                                            <a href="{{ route('design.download', ['orderId' => $order->id, 'itemId' => $item->id]) }}" 
                                               download
                                               class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" 
                                               title="Download file">
                                                <i class="fa-solid fa-download text-sm"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Notes Section -->
                                @if($item->note)
                                <div class="border-t border-gray-200 bg-gray-50 p-4 sm:p-5">
                                    <div class="text-sm text-gray-700">
                                        <p class="font-semibold text-gray-800 mb-2"><i class="fa-regular fa-comment-dots mr-1.5 text-gray-600"></i>Catatan Pesanan</p>
                                        <p class="text-gray-600 leading-relaxed ml-5">{{ $item->note }}</p>
                                    </div>
                                </div>
                                @endif
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

        // Fungsi untuk menampilkan desain di modal
        function viewDesignModal(imageUrl) {
            const modal = document.getElementById('designModal');
            const modalImage = document.getElementById('modalDesignImage');
            
            if (!modal) {
                // Buat modal jika belum ada
                const newModal = document.createElement('div');
                newModal.id = 'designModal';
                newModal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden';
                newModal.innerHTML = `
                    <div class="bg-white rounded-lg max-w-2xl w-full mx-4 overflow-hidden shadow-2xl">
                        <div class="bg-gray-900 p-4 flex justify-between items-center">
                            <h3 class="text-white font-bold">Preview Desain</h3>
                            <button onclick="closeDesignModal()" class="text-white hover:text-gray-300 text-2xl">×</button>
                        </div>
                        <div class="p-6 text-center bg-gray-50 max-h-96 overflow-auto flex items-center justify-center">
                            <img id="modalDesignImage" src="" alt="Desain" class="max-w-full max-h-96 object-contain">
                        </div>
                        <div class="bg-gray-100 p-4 text-right border-t">
                            <button onclick="closeDesignModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition">Tutup</button>
                        </div>
                    </div>
                `;
                document.body.appendChild(newModal);
            }
            
            const modal2 = document.getElementById('designModal');
            const modalImage2 = document.getElementById('modalDesignImage');
            modalImage2.src = imageUrl;
            modal2.classList.remove('hidden');
        }

        function closeDesignModal() {
            const modal = document.getElementById('designModal');
            if (modal) {
                modal.classList.add('hidden');
            }
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
</body>

</html>