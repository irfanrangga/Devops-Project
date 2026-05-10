<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna | Designplus</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
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
            background-color: #f8f9fa;
        }

        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="text-gray-800 font-sans antialiased">

    <x-navbar></x-navbar>
    <x-chatbox></x-chatbox>
    @php
    $currentPage = request('page', 'user-info');
    $isActive = fn($page) => $currentPage === $page ? 'bg-brand-light text-brand-blue font-semibold' : 'text-gray-600 hover:bg-gray-50';
    @endphp

    <main class="w-full px-6 md:px-12 lg:px-16 mx-auto py-10 md:py-20">

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6" role="alert">
            <p class="font-bold">Sukses!</p>
            <p class="text-sm">{{ session('success') }}</p>
        </div>
        @endif

        {{-- Menampilkan error validasi umum --}}
        @if($errors->any() && !session('success'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6" role="alert">
            <p class="font-bold">Error!</p>
            <p class="text-sm">Silakan cek kembali input Anda.</p>
        </div>
        @endif

        <div class="mb-10">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Pengaturan Akun</h1>
            <p class="text-gray-500">Kelola informasi profil, kata sandi, dan preferensi Anda.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-10">

            <div class="lg:col-span-1 bg-white border border-gray-200 rounded-xl p-6 h-fit shadow-sm">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Menu Profile</h3>

                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('profile', ['page' => 'user-info']) }}" class="flex items-center gap-3 p-3 rounded-lg transition {{ $isActive('user-info') }}">
                            <i class="fa-solid fa-user text-lg"></i> User Info
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('profile', ['page' => 'wishlist']) }}" class="flex items-center gap-3 p-3 rounded-lg transition {{ $isActive('wishlist') }}">
                            <i class="fa-solid fa-heart text-lg"></i> Wishlist
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('profile', ['page' => 'riwayat-pesanan']) }}" class="flex items-center gap-3 p-3 rounded-lg transition {{ $isActive('riwayat-pesanan') }}">
                            <i class="fa-solid fa-clock-rotate-left text-lg"></i> Riwayat Pesanan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('profile', ['page' => 'pengaturan']) }}" class="flex items-center gap-3 p-3 rounded-lg transition {{ $isActive('pengaturan') }}">
                            <i class="fa-solid fa-gear text-lg"></i> Pengaturan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('profile', ['page' => 'notifikasi']) }}" class="flex items-center gap-3 p-3 rounded-lg transition {{ $isActive('notifikasi') }}">
                            <i class="fa-solid fa-bell text-lg"></i> Notifications
                        </a>
                    </li>

                    <hr class="my-4 border-gray-100">

                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 p-3 rounded-lg transition text-red-600 hover:bg-red-50 w-full justify-start">
                                <i class="fa-solid fa-right-from-bracket text-lg"></i> Log out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

            <div class="lg:col-span-3 bg-white border border-gray-200 rounded-xl p-8 shadow-sm">

                @switch($currentPage)

                {{-- ========================================================== --}}
                {{-- KONTEN 1: USER INFO --}}
                {{-- ========================================================== --}}
                @case('user-info')
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b border-gray-100 pb-3">Informasi Akun</h2>

                    <div class="flex items-center gap-6 mb-8">
                        <img src="{{ asset('assets/icon/user.png') }}" alt="{{ Auth::user()->name }}" class="w-20 h-20 rounded-full object-cover border border-gray-100">
                        <div>
                            <span class="text-2xl font-semibold text-gray-900">{{ Auth::user()->name }}</span>
                            <p class="text-gray-500 text-sm">{{ Auth::user()->location ?? 'Lokasi Belum Diatur' }}</p>
                        </div>
                    </div>

                    {{-- FORM UPDATE USER INFO --}}
                    <form method="POST" action="{{ route('profile.update-info') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Depan</label>
                            <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name ?? '') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-blue focus:ring focus:ring-brand-blue/20 p-2.5 @error('name') border-red-500 @enderror">
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Belakang</label>
                            <input type="text" id="full_name" name="full_name" value="{{ old('full_name', Auth::user()->full_name ?? '') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-blue focus:ring focus:ring-brand-blue/20 p-2.5 @error('full_name') border-red-500 @enderror">
                            @error('full_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" id="email" value="{{ Auth::user()->email }}" disabled class="w-full border-gray-200 bg-gray-50 rounded-lg p-2.5 cursor-not-allowed">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-blue focus:ring focus:ring-brand-blue/20 p-2.5 @error('phone') border-red-500 @enderror">
                            @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-1">
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                            <input type="text" id="location" name="location" value="{{ old('location', Auth::user()->location ?? '') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-blue focus:ring focus:ring-brand-blue/20 p-2.5 @error('location') border-red-500 @enderror">
                            @error('location')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-1">
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', Auth::user()->postal_code ?? '') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-blue focus:ring focus:ring-brand-blue/20 p-2.5 @error('postal_code') border-red-500 @enderror">
                            @error('postal_code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2 mt-4">
                            <button type="submit" class="px-6 py-3 bg-brand-blue text-white font-semibold rounded-lg hover:bg-brand-dark transition shadow">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
                @break

                {{-- ========================================================== --}}
                {{-- KONTEN 2: PENGATURAN (Ubah Password) --}}
                {{-- ========================================================== --}}
                @case('pengaturan')
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b border-gray-100 pb-3">Ubah Kata Sandi</h2>

                    @if($errors->has('current_password'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm" role="alert">
                        {{ $errors->first('current_password') }}
                    </div>
                    @endif

                    {{-- FORM UPDATE PASSWORD --}}
                    <form method="POST" action="{{ route('profile.update-password') }}" class="max-w-md space-y-4 mb-10">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi Saat Ini</label>
                            <input type="password" id="current_password" name="current_password" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-blue focus:ring focus:ring-brand-blue/20 p-2.5 @error('current_password') border-red-500 @enderror">
                        </div>
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi Baru</label>
                            <input type="password" id="new_password" name="new_password" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-blue focus:ring focus:ring-brand-blue/20 p-2.5 @error('new_password') border-red-500 @enderror">
                            @error('new_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-brand-blue focus:ring focus:ring-brand-blue/20 p-2.5">
                        </div>

                        <button type="submit" class="px-6 py-3 bg-brand-blue text-white font-semibold rounded-lg hover:bg-brand-dark transition shadow">
                            Update Kata Sandi
                        </button>
                    </form>

                    {{-- Preferensi Notifikasi dan Zona Bahaya --}}
                    <h2 class="text-2xl font-bold text-gray-900 mt-10 mb-6 border-b border-gray-100 pb-3">Preferensi Notifikasi</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2">
                            <div>
                                <h4 class="font-medium text-gray-800">Promo dan Penawaran</h4>
                                <p class="text-sm text-gray-500">Dapatkan notifikasi diskon dan produk baru.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer"><input type="checkbox" value="" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-brand-blue/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-blue"></div>
                            </label>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <div>
                                <h4 class="font-medium text-gray-800">Update Status Pesanan</h4>
                                <p class="text-sm text-gray-500">Kirim email saat pesanan selesai diproses atau dikirim.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer"><input type="checkbox" value="" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-brand-blue/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-blue"></div>
                            </label>
                        </div>
                    </div>
                    <div class="bg-red-100 border border-red-400 text-red-700 p-4 rounded-xl mt-12" role="alert">
                        <h3 class="font-bold text-lg mb-2">Zona Bahaya</h3>
                        <p class="text-sm mb-4">Tindakan ini akan menghapus data Anda secara permanen.</p>
                        <button type="button" class="px-5 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">Hapus Akun Saya</button>
                    </div>
                </div>
                @break

                {{-- KONTEN 3: WISHLIST --}}
                @case('wishlist')
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b border-gray-100 pb-3">Daftar Wishlist</h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @forelse($wishlists as $item)
                                <div class="group relative bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-all duration-300">
                                    
                                    <a href="{{ url('/product/' . $item->product->id) }}" class="absolute inset-0 z-10"></a>

                                    <div class="h-48 overflow-hidden bg-gray-50 relative">
                                        <img src="{{ asset($item->product->file) }}" 
                                            alt="{{ $item->product->nama }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    </div>

                                    <div class="p-4">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                                    {{ $item->product->kategori ?? 'Umum' }}
                                                </span>
                                                <h3 class="text-sm font-bold text-gray-900 line-clamp-2 group-hover:text-brand-blue">
                                                    {{ $item->product->nama }}
                                                </h3>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-dashed border-gray-100">
                                            <span class="font-bold text-brand-blue">
                                                Rp {{ number_format($item->product->harga, 0, ',', '.') }}
                                            </span>
                                            
                                            <button type="button" 
                                                    onclick="toggleWishlist(this, {{ $item->product->id }}, event)"
                                                    class="z-20 text-red-500 hover:text-red-700 text-sm flex items-center gap-1 cursor-pointer">
                                                <i class="fa-solid fa-trash"></i> <span class="text-xs">Hapus</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            @empty
                                <div class="col-span-full text-center py-10 border border-dashed border-gray-300 rounded-xl bg-gray-50">
                                    <i class="fa-solid fa-heart-crack text-5xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-600 font-medium">Anda belum menambahkan produk ke Wishlist.</p>
                                    <a href="{{ route('product.index') }}" class="text-brand-blue hover:underline text-sm mt-2 block">
                                        Mulai Jelajahi Produk
                                    </a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @break

                {{-- KONTEN BARU: RIWAYAT PESANAN --}}
                {{-- ========================================================== --}}
                @case('riwayat-pesanan')
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b border-gray-100 pb-3">Riwayat Pesanan</h2>

                    <div class="space-y-6">
                        @forelse($orders as $order)
                            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow duration-300">
                                {{-- Header Card: No Invoice & Status --}}
                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-4 md:p-6 border-b border-gray-200">
                                    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
                                        <div>
                                            <div class="flex items-center gap-3 mb-2">
                                                <span class="font-mono text-sm font-bold text-gray-600 bg-gray-200 px-3 py-1 rounded-full">#{{ $order->number }}</span>
                                                <span class="text-xs text-gray-500">{{ $order->created_at?->format('d M Y, H:i') }}</span>
                                            </div>
                                            
                                            {{-- Logic Status Badge --}}
                                            @if($order->payment_status == '2')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                    <i class="fa-solid fa-check-circle mr-1.5"></i> Lunas
                                                </span>
                                            @elseif($order->payment_status == '3')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                    <i class="fa-solid fa-circle-xmark mr-1.5"></i> Expired / Batal
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 animate-pulse">
                                                    <i class="fa-solid fa-clock mr-1.5"></i> Menunggu Pembayaran
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Tombol Aksi --}}
                                        <div class="flex gap-2">
                                            @if($order->payment_status == '1')
                                                <a href="{{ route('payment.show', $order->id) }}" 
                                                   class="inline-flex items-center gap-2 px-4 py-2 bg-brand-blue text-white text-sm font-bold rounded-lg hover:bg-brand-dark transition shadow-sm">
                                                    <i class="fa-solid fa-credit-card"></i> Bayar
                                                </a>
                                            @else
                                                <a href="{{ route('payment.show', $order->id) }}" 
                                                   class="inline-flex items-center gap-2 px-4 py-2 border border-brand-blue text-brand-blue text-sm font-semibold rounded-lg hover:bg-blue-50 transition">
                                                    <i class="fa-solid fa-eye"></i> Detail
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- List Barang & File Desain --}}
                                <div class="p-4 md:p-6 space-y-4">
                                    @foreach($order->items as $item)
                                        <div class="border border-gray-100 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                            <div class="flex items-start gap-4 mb-3">
                                                <div class="w-12 h-12 bg-gray-100 rounded-md overflow-hidden flex-shrink-0 border border-gray-200">
                                                    @if($item->product)
                                                        <img src="{{ asset($item->product->file) }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="flex items-center justify-center h-full text-gray-400"><i class="fa-solid fa-image text-sm"></i></div>
                                                    @endif
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="font-semibold text-gray-900 text-sm">{{ $item->product_name }}</h4>
                                                    <p class="text-xs text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->product_price, 0, ',', '.') }}</p>
                                                </div>
                                                <span class="text-sm font-bold text-brand-blue">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                            </div>

                                            {{-- Spesifikasi Produk --}}
                                            <div class="flex flex-wrap gap-2 mb-3">
                                                @if($item->bahan)
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                                        <i class="fa-solid fa-shirt text-gray-500 text-xs"></i> {{ $item->bahan }}
                                                    </span>
                                                @endif
                                                @if($item->warna)
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                                        <i class="fa-solid fa-palette text-gray-500 text-xs"></i> {{ $item->warna }}
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- File Desain Custom --}}
                                            @if(!empty($item->custom_file) && $item->custom_file !== 'null' && strtolower($item->custom_file) !== 'standard')
                                                <div class="p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200 flex items-center justify-between hover:shadow transition-all">
                                                    <div class="flex items-center gap-2.5 flex-1">
                                                        @php
                                                            $fileExtension = strtolower(pathinfo($item->custom_file, PATHINFO_EXTENSION));
                                                            $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                                            $isPdf = $fileExtension === 'pdf';
                                                        @endphp
                                                        
                                                        @if($isImage)
                                                            <i class="fa-solid fa-image text-lg text-blue-500"></i>
                                                        @elseif($isPdf)
                                                            <i class="fa-solid fa-file-pdf text-lg text-red-500"></i>
                                                        @else
                                                            <i class="fa-solid fa-file text-lg text-blue-500"></i>
                                                        @endif
                                                        <div>
                                                            <p class="text-xs font-bold text-blue-600 uppercase tracking-wide">File Desain Custom</p>
                                                            <p class="text-xs font-medium text-gray-700 truncate">{{ basename($item->custom_file) }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex gap-1.5">
                                                        @if($isImage)
                                                            <button onclick="viewDesignModal('{{ route('design.view', ['orderId' => $order->id, 'itemId' => $item->id]) }}')" 
                                                                    class="p-1.5 text-blue-600 hover:bg-blue-100 rounded transition" title="Lihat Desain">
                                                                <i class="fa-solid fa-eye text-sm"></i>
                                                            </button>
                                                        @endif
                                                        <a href="{{ route('design.download', ['orderId' => $order->id, 'itemId' => $item->id]) }}" 
                                                           download
                                                           class="p-1.5 text-blue-600 hover:bg-blue-100 rounded transition" 
                                                           title="Download file">
                                                            <i class="fa-solid fa-download text-sm"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Catatan --}}
                                            @if($item->note)
                                                <div class="mt-2 text-xs text-gray-600 bg-gray-50 p-2.5 rounded border border-dashed border-gray-300">
                                                    <p class="font-semibold text-gray-700"><i class="fa-regular fa-comment-dots mr-1.5"></i> Catatan:</p>
                                                    <p class="text-gray-600">{{ $item->note }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Footer: Total Harga --}}
                                <div class="bg-gray-50 px-4 md:px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                                    <span class="text-sm font-semibold text-gray-600">Total Tagihan</span>
                                    <span class="text-lg font-bold text-brand-blue">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                </div>
                            </div>

                        @empty
                            <div class="text-center py-12 border border-dashed border-gray-300 rounded-xl bg-gray-50">
                                <i class="fa-solid fa-clipboard-list text-5xl text-gray-400 mb-4"></i>
                                <p class="text-gray-600 font-medium mb-2">Belum ada riwayat pesanan.</p>
                                <p class="text-sm text-gray-500 mb-4">Mulai berbelanja dan setiap pesanan akan muncul di sini.</p>
                                <a href="{{ route('product.index') }}" class="inline-flex items-center gap-2 text-brand-blue hover:underline text-sm font-semibold">
                                    <i class="fa-solid fa-shopping-bag"></i> Belanja Sekarang
                                </a>
                            </div>
                        @endforelse
                    </div>

                    {{-- Modal untuk preview desain --}}
                    <div id="designModalProfile" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                        <div class="bg-white rounded-lg max-w-2xl w-full mx-4 overflow-hidden shadow-2xl">
                            <div class="bg-gray-900 p-4 flex justify-between items-center">
                                <h3 class="text-white font-bold text-lg">Preview Desain</h3>
                                <button onclick="closeDesignModalProfile()" class="text-white hover:text-gray-300 text-2xl font-light">×</button>
                            </div>
                            <div class="p-6 text-center bg-gray-50 max-h-96 overflow-auto flex items-center justify-center">
                                <img id="modalDesignImageProfile" src="" alt="Desain" class="max-w-full max-h-96 object-contain rounded">
                            </div>
                            <div class="bg-gray-100 p-4 text-right border-t">
                                <button onclick="closeDesignModalProfile()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition font-medium">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                @break

                {{-- KONTEN 4: NOTIFIKASI --}}
                @case('notifikasi')
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b border-gray-100 pb-3">Notifikasi Terbaru</h2>
                    <div class="text-center py-10 border border-dashed border-gray-300 rounded-xl bg-gray-50"><i class="fa-solid fa-bell-slash text-5xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600 font-medium">Tidak ada notifikasi baru untuk saat ini.</p>
                    </div>
                </div>
                @break

                @default
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b border-gray-100 pb-3">Informasi Akun</h2>
                    <p class="text-gray-500">Halaman tidak ditemukan. Kembali ke Informasi Akun.</p>
                </div>
                @endswitch

            </div>
        </div>
    </main>

    <x-footer></x-footer>

    {{-- SCRIPT MOBILE NAVBAR --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');

            menuBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');

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

        // Fungsi untuk menampilkan desain di modal (untuk profil)
        function viewDesignModal(imageUrl) {
            const modal = document.getElementById('designModalProfile');
            const modalImage = document.getElementById('modalDesignImageProfile');
            
            if (modal) {
                modalImage.src = imageUrl;
                modal.classList.remove('hidden');
            }
        }

        function closeDesignModalProfile() {
            const modal = document.getElementById('designModalProfile');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        // Close modal jika klik di luar area modal
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('designModalProfile');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeDesignModalProfile();
                    }
                });
            }
        });
    </script>
</body>

</html>
