<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Etalase Produk</title>

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
    </style>
</head>

<body class="bg-white text-gray-800 font-sans antialiased">

    <x-navbar></x-navbar>

    <main>
        <section class="relative h-[400px] flex items-center justify-center text-center text-white mb-10">
            <div class="absolute inset-0 bg-black/50 z-10"></div>
            <div class="absolute inset-0 bg-cover bg-center z-0"
                style="background-image: url('{{ asset("assets/bg_home.png") }}')"></div>

            <div class="relative z-20 px-4 max-w-3xl">
                <h2 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight drop-shadow-md">Produk Kami</h2>
                <p class="text-lg md:text-xl opacity-90 font-light drop-shadow-sm">Temukan produk atau layanan Kami yang
                    sesuai dengan kebutuhan Anda.</p>
            </div>
        </section>

        <div class="w-full px-6 md:px-12 lg:px-16 mx-auto">

            <nav class="flex items-center text-sm text-gray-500 mb-8">
                <a href="#" class="text-brand-blue font-medium hover:underline">Home</a>
                <span class="mx-2 text-gray-300">/</span>
                <span class="text-gray-800 font-medium">Produk</span>
            </nav>

            <div class="mb-10 border-b border-gray-100 pb-4">
                <h2 class="text-3xl font-bold text-gray-900 mb-2 tracking-tight">Etalase Produk</h2>
                <p class="text-gray-500">Temukan produk unggulan kami dan sesuaikan dengan kebutuhan Anda.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 pb-20">

                @forelse($products as $product)
                    <div class="group relative bg-white border rounded-xl overflow-hidden">

                        <a href="{{ route('product.show', $product->_id) }}" class="absolute inset-0 z-10"></a>

                        <button type="button" onclick="toggleWishlist(this, {{ $product->_id }}, event)"
                            class="absolute top-3 right-3 z-30 w-9 h-9 rounded-full bg-white/90 shadow-sm transition-transform active:scale-95 flex items-center justify-center cursor-pointer hover:bg-gray-50 group-hover/btn:ring-2 group-hover/btn:ring-pink-500">
                            <i class="fa-regular fa-heart text-gray-400 text-lg transition-colors duration-200"></i>
                        </button>

                        <div class="h-[220px] overflow-hidden bg-gray-50 border-b border-gray-100 relative z-0">
                            <img src="{{ asset($product->product_image) }}" alt="{{ $product->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500 ease-out">
                        </div>

                        <div class="p-5 flex flex-col flex-grow relative z-10">
                            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                                {{ $product->category }}
                            </span>

                            <h3
                                class="text-base font-bold text-gray-900 leading-snug mb-2 group-hover:text-brand-blue transition line-clamp-2">
                                {{ $product->name }}
                            </h3>

                            <div class="flex items-center gap-1 mb-4">
                                <div class="flex text-yellow-400 text-xs">
                                    @php
                                        $rating = $product->rating;
                                        $full = floor($rating);
                                        $half = ($rating - $full) >= 0.5;
                                    @endphp
                                    @for($i = 0; $i < $full; $i++) <i class="fa-solid fa-star"></i> @endfor
                                    @if($half) <i class="fa-solid fa-star-half-stroke"></i> @php $full++; @endphp @endif
                                    @for($i = $full; $i < 5; $i++) <i class="fa-regular fa-star text-gray-300"></i> @endfor
                                </div>
                                <span class="text-xs text-gray-500 ml-1">({{ $rating }})</span>
                            </div>

                            <div
                                class="mt-auto pt-3 border-t border-dashed border-gray-200 flex justify-between items-center">

                                <span class="text-lg font-bold text-gray-900">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>

                                <form action="{{ route('cart.store') }}" method="POST" onsubmit="event.stopPropagation()">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->_id }}">
                                    <input type="hidden" name="quantity" value="1">
                                </form>

                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-full py-16 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-300">
                        <i class="fa-solid fa-box-open text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-semibold text-gray-600">Belum ada produk</h3>
                        <p class="text-gray-400 text-sm">Silakan tambahkan data produk terlebih dahulu.</p>
                    </div>
                @endforelse

            </div>
        </div>
    </main>

    <x-footer></x-footer>
    @stack('scripts')
    <div id="wishlist-toast" class="fixed inset-0 z-[100] flex items-center justify-center hidden pointer-events-none">

        <div id="toast-overlay"
            class="absolute inset-0 bg-black/20 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>

        <div id="toast-content"
            class="relative bg-white rounded-2xl shadow-2xl p-6 flex items-center gap-4 transform scale-90 opacity-0 transition-all duration-300 pointer-events-auto min-w-[300px]">

            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-check text-xl text-green-600"></i>
            </div>

            <div>
                <h4 class="font-bold text-gray-900 text-lg">Berhasil!</h4>
                <p class="text-sm text-gray-500">Produk ditambahkan ke Wishlist.</p>
            </div>

            <button onclick="closeToast()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('mobile-menu-btn');
            const menu = document.getElementById('mobile-menu');

            if (btn && menu) {
                btn.addEventListener('click', () => {
                    menu.classList.toggle('hidden');
                });
            }
        });

        function toggleWishlist(button, productId, event) {
            // mencegah klik tembus ke link detail produk
            event.preventDefault();
            event.stopPropagation();

            const icon = button.querySelector('i');

            // AMBIL TOKEN DARI META TAG
            const tokenElement = document.querySelector('meta[name="csrf-token"]');
            if (!tokenElement) {
                console.error('Meta CSRF Token tidak ditemukan!');
                return;
            }
            const token = tokenElement.getAttribute('content');

            // UI OPTIMISTIC UPDATE 
            const isCurrentlyLiked = icon.classList.contains('fa-solid');

            if (!isCurrentlyLiked) {
                icon.classList.remove('fa-regular', 'text-gray-400');
                icon.classList.add('fa-solid', 'text-pink-500');
                icon.style.transform = "scale(1.2)";
                setTimeout(() => icon.style.transform = "scale(1)", 200);
            } else {
                icon.classList.remove('fa-solid', 'text-pink-500');
                icon.classList.add('fa-regular', 'text-gray-400');
            }

            // KIRIM REQUEST KE DATABASE
            fetch("{{ route('wishlist.toggle') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token
                },
                body: JSON.stringify({ product_id: productId })
            })
                .then(response => {
                    if (response.status === 401) {
                        // jika user belum login, batalkan perubahan visual dan redirect
                        if (!isCurrentlyLiked) {
                            icon.classList.add('fa-regular', 'text-gray-400');
                            icon.classList.remove('fa-solid', 'text-pink-500');
                        }
                        alert("Silakan login terlebih dahulu!");
                        window.location.href = "/login";
                        return null;
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data) return;

                    // sinkronisasi status akhir dari server (Penting!)
                    if (data.status === 'added') {
                        icon.classList.remove('fa-regular', 'text-gray-400');
                        icon.classList.add('fa-solid', 'text-pink-500');
                        showToast(data.message);
                    } else if (data.status === 'removed') {
                        icon.classList.remove('fa-solid', 'text-pink-500');
                        icon.classList.add('fa-regular', 'text-gray-400');
                        showToast(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (!isCurrentlyLiked) {
                        icon.classList.add('fa-regular', 'text-gray-400');
                        icon.classList.remove('fa-solid', 'text-pink-500');
                    } else {
                        icon.classList.add('fa-solid', 'text-pink-500');
                        icon.classList.remove('fa-regular', 'text-gray-400');
                    }
                });
        }

        // --- 3. LOGIKA TOAST ---
        let toastTimeout;

        function showToast(message) {
            const toast = document.getElementById('wishlist-toast');
            const overlay = document.getElementById('toast-overlay');
            const content = document.getElementById('toast-content');

            const messageElement = content.querySelector('p');
            if (messageElement) messageElement.innerText = message;

            clearTimeout(toastTimeout);
            toast.classList.remove('hidden');

            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                content.classList.remove('scale-90', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);

            toastTimeout = setTimeout(() => {
                closeToast();
            }, 3000);
        }

        function closeToast() {
            const toast = document.getElementById('wishlist-toast');
            const overlay = document.getElementById('toast-overlay');
            const content = document.getElementById('toast-content');

            overlay.classList.add('opacity-0');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-90', 'opacity-0');

            setTimeout(() => {
                toast.classList.add('hidden');
            }, 300);
        }
    </script>
</body>

</html>