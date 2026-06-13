<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="{{ asset('js/product.js') }}"></script>
    <title>Detail Produk</title>
</head>

<body>
    <x-navbar></x-navbar>

    <main class="container w-full px-6 md:px-12 lg:px-16 mx-auto mt-28">
        <div class="hidden gap-3 lg:flex">
            <a href="{{ route('home') }}" class="text-slate-400 hover:text-blue-500 text-sm">Home</a>
            <h2 class="text-slate-400">/</h2>
            <a href="{{ route('product.index') }}" class="text-slate-400 hover:text-blue-500 text-sm">Etalase Produk</a>
            <h2 class="text-slate-400">/</h2>
            <h2 class="text-blue-700 text-sm font-medium">{{ $product->nama }}</h2>
        </div>
        {{-- Product Detail --}}
        <section>
            <div class="container flex flex-col lg:flex-row gap-10 mt-4">
                {{-- Gambar Produk --}}
                <div class="w-full lg:w-1/2">
                    <div class="main-image-container mb-3">
                        <img id="main-product-image"
                            class="w-full h-96 md:h-[32rem] rounded-xl object-cover border border-gray-200"
                            src="{{ asset($product->file) }}" alt="{{ $product->nama }}">
                    </div>
                    
                    <div class="thumbnails-container flex gap-4 mt-4 w-full overflow-x-auto">
                        <img src="{{ asset($product->file) }}" alt="{{ $product->nama }}"
                            class="img-thumbnail w-32 h-32 object-cover border-2 border-blue-500 cursor-pointer rounded-md hover:opacity-100 transition"
                            onclick="changeMainImage(this)">
                        @foreach($product->productImages as $image)
                        <img src="{{ Storage::url($image->image_url) }}" alt=" {{ $image->image_url }}"
                            class="img-thumbnail w-32 h-32 shrink-0 object-cover border cursor-pointer rounded-md opacity-75 hover:opacity-100 transition"
                            onclick="changeMainImage(this)">
                        @endforeach
                    </div>
                    <div class="my-8 flex flex-col justify-center gap-1">
                        <h2 class="text-gray-300 text-md">*Harga belum termasuk ongkos kirim</h2>
                        <h2 class="text-gray-300 text-md">**Pembelian produk dikenakan PPN 12%</h2>
                    </div>
                </div>
                {{-- Deskripsi Produk --}}
                <div class="w-full lg:w-1/2">
                    <span class="border border-blue-500 bg-blue-100 text-sm text-blue-500 p-2 rounded-lg"><strong
                            class="text-blue-500">{{ $product->kategori }}</strong></span>
                    <h1 class="text-2xl font-bold mb-2 mt-4">{{ $product->nama }}</h1>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex gap-2">Terjual <p class="font-semibold text-gray-400">{{
                                number_format($product->sold ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex items-center text-yellow-400 text-sm">
                            @php
                            $rating = $product->rating ?? 0;
                            $full = floor($rating);
                            $half = ($rating - $full) >= 0.5;
                            @endphp
                            @for($i = 0; $i < $full; $i++) <i class="fa-solid fa-star"></i> @endfor
                                @if($half) <i class="fa-solid fa-star-half-stroke"></i> @php $full++; @endphp @endif
                                @for($i = $full; $i < 5; $i++) <i class="fa-regular fa-star text-gray-300"></i> @endfor
                                    <span class="text-sm text-gray-500 ml-2">({{ $rating }})</span>
                        </div>
                    </div>
                    <form id="productForm" action="{{ route('cart.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="flex flex-col my-2">
                            <div class="mb-5">
                                @php
                                // Ambil bahan dari field produk (bisa comma-separated, array, atau satu string)
                                $bahanList = [];
                                if (!empty($product->bahan)) {
                                if (is_array($product->bahan)) {
                                $bahanList = $product->bahan;
                                } else {
                                $bahanList = array_filter(array_map('trim', explode(',', $product->bahan)));
                                }
                                }
                                @endphp
                                <div class="material-group" data-category="product">
                                    <div class="flex justify-between mb-1 items-center">
                                        <label for="material" class="font-semibold text-gray-900 text-lg">Bahan</label>
                                        <p id="unitPrice" data-price="{{ $product->harga }}"
                                            class="text-lg font-semibold text-gray-400">Rp{{
                                            number_format($product->harga,
                                            0,
                                            ',', '.')
                                            }}</p>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($bahanList as $i => $material)
                                        @php
                                        $slug = \Illuminate\Support\Str::slug('product-' . $material);
                                        $checked = $i === 0;
                                        @endphp
                                        <input type="radio" name="material" id="material-{{ $slug }}"
                                            value="{{ $material }}" class="peer hidden" {{ $checked ? 'checked' : '' }}>
                                        <label for="material-{{ $slug }}"
                                            class="option-label border rounded-lg px-4 py-2 hover:cursor-pointer transition-colors bg-white text-gray-700">{{
                                            $material }}</label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="mb-5">
                                <label for="warna" class="text-lg font-semibold text-gray-900">Warna</label>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @php
                                    $colors = [];
                                    if(!empty($product->warna)) {
                                    $colors = array_map('trim', explode(',', $product->warna));
                                    }
                                    @endphp
                                    @if(count($colors) > 1)
                                    @foreach($colors as $color)
                                    @php $cslug = \Illuminate\Support\Str::slug($color); @endphp
                                    <input type="radio" name="warna" id="warna-{{ $cslug }}" value="{{ $color }}"
                                        class="peer hidden" {{ $loop->first ? 'checked' : '' }}>
                                    <label for="warna-{{ $cslug }}"
                                        class="option-label border rounded-lg px-4 py-2 hover:cursor-pointer transition-colors bg-white text-gray-700">{{
                                        $color }}</label>
                                    @endforeach
                                    @elseif(count($colors) === 1)
                                    <input type="hidden" name="warna" value="{{ $colors[0] }}">
                                    <div class="px-3 py-2 text-sm">{{ $colors[0] }}</div>
                                    @else
                                    <input type="text" name="warna" placeholder="Masukkan warna"
                                        class="border rounded px-3 py-2 w-full">
                                    @endif
                                </div>
                            </div>

                            <div class="mb-5">
                                <div class="flex justify-between mb-2 items-center">
                                    <label for="design_type" class="text-lg font-semibold text-gray-900">Desain</label>
                                    <p id="unitPrice" data-price="{{ $product->harga }}"
                                        class="text-lg font-semibold text-gray-400">
                                        <span id="designPrice">
                                            @php
                                            $designPrice = 0;
                                            @endphp
                                            Rp{{ number_format($designPrice, 0, ',', '.') }}
                                        </span>
                                    </p>
                                </div>
                                <div class="flex gap-6 items-center">
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="design_type" value="standard"
                                            class="design-radio appearance-none border border-gray-300 rounded-full w-3 h-3 checked:bg-blue-500 checked:border-blue-500 transition"
                                            checked>
                                        Standard
                                    </label>
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="design_type" value="custom"
                                            class="design-radio appearance-none border border-gray-300 rounded-full w-3 h-3 checked:bg-blue-500 checked:border-blue-500 transition">
                                        Custom
                                    </label>
                                </div>

                                <div class="col-span-full border border-dashed rounded-lg bg-gray-50 mt-3 hidden transition-colors duration-200"
                                    id="customDesignDiv">
                                    <div
                                        class="flex justify-center border-white/50 px-6 py-10 transition-colors duration-200">
                                        <div class="text-center w-full">
                                            <svg viewBox="0 0 24 24" fill="currentColor" data-slot="icon"
                                                aria-hidden="true" class="mx-auto size-12 text-gray-600">
                                                <path
                                                    d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z"
                                                    clip-rule="evenodd" fill-rule="evenodd" />
                                            </svg>
                                            <div class="mt-4 flex text-sm/6 text-gray-400 justify-center">
                                                <label for="file-upload"
                                                    class="relative cursor-pointer rounded-md bg-transparent font-semibold text-indigo-400 focus-within:outline-2 focus-within:outline-offset-2 focus-within:outline-indigo-500 hover:text-indigo-300">
                                                    <span>Upload a file</span>
                                                    <input id="file-upload" type="file" name="custom_file"
                                                        class="sr-only" accept=".png,.jpg,.jpeg,.pdf" />
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs/5 text-gray-400">PNG, JPG, JPEG, PDF up to 5MB</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-5">
                                <label for="quantity" class="text-lg font-semibold text-gray-900">Kuantitas</label>
                                <div class="flex gap-3 items-center mt-2">
                                    <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                        <button type="button" id="qtyMinus"
                                            class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition bg-white">
                                            -
                                        </button>

                                        <input type="text" name="quantity" id="quantityInput" value="1" min="1"
                                            data-max="{{ 9999 }}" onkeydown="return event.key !== 'Enter'"
                                            class="w-10 h-8 text-center text-sm border-none focus:ring-0 text-gray-900 bg-white">

                                        <button type="button" id="qtyPlus"
                                            class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition bg-white">
                                            +
                                        </button>
                                    </div>
                                    {{-- <div class="text-md text-gray-500 ml-4">Stok: {{ number_format($product->stok
                                        ?? 0, 0,
                                        ',', '.') }}</div> --}}
                                </div>
                            </div>

                            <div class="mb-5">
                                <label for="note" class="text-lg font-semibold text-gray-900">Catatan</label>
                                <textarea name="note" id="note" cols="30" rows="2"
                                    class="border rounded-md w-full p-3 mt-2"
                                    placeholder="Contoh: Warna dibuat agak terang"></textarea>
                            </div>

                        </div>
                        <div class="border my-5"></div>
                        <div class="flex justify-between">
                            <h3 class="text-2xl font-bold text-gray-900">Subtotal<span
                                    class="text-gray-300 font-light">*</span></h3>
                            <p id="totalPrice" class="text-2xl font-semibold text-blue-600">
                                <span id="subtotalText">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                            </p>
                        </div>
                        <div class="mt-6 lg:flex gap-4">
                            <button type="submit" id="addToCartBtn" formaction="{{ route('cart.store') }}"
                                class="bg-white text-blue-500 border border-blue-500 py-4 px-6 rounded-lg hover:bg-blue-50 w-full transition font-bold mb-3 lg:w-1/2">
                                <span class="mr-3"><i class="fa-solid fa-plus"></i></span>Keranjang
                            </button>
                            <button type="submit" id="buyNowBtn" formaction="{{ route('checkout.process') }}"
                                class="border font-bold border-blue-500 text-white py-4 px-6 rounded-lg bg-blue-500 hover:bg-blue-600 transition w-full mb-3 lg:w-1/2">Beli
                                Langsung</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        {{-- Ulasan Pengguna --}}
        <section>
            <div class="container w-full mt-16 sm:mt-32">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4">Ulasan Pengguna</h2>
                @if(session('success'))
                <div>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-3 rounded relative"
                        role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                </div>
                @endif

                @auth
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm mb-5">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Tulis Ulasan Anda</h3>

                    {{-- Arahkan ke route store yang ada di ReviewController --}}
                    <form action="{{ route('reviews.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        {{-- Pilihan Rating (Bintang) --}}
                        <div class="mb-4">
                            {{-- Pilihan Rating (Custom Dropdown Smooth) --}}
                            <div class="mb-4 relative" id="rating-dropdown-container">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Penilaian</label>

                                {{-- Input tersembunyi ini yang akan dikirim ke controller Laravel --}}
                                <input type="hidden" name="rating" id="rating-input" value="{{ old('rating') }}"
                                    required>

                                {{-- Tombol Pemicu Dropdown --}}
                                <button type="button" id="rating-button"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 flex justify-between items-center text-left transition-colors">
                                    <span id="rating-text" class="text-gray-500">Pilih Bintang...</span>
                                    <svg class="w-4 h-4 ml-2 text-gray-500 transition-transform duration-200"
                                        id="rating-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                {{-- Menu Opsi (Dengan Efek Transisi Tailwind) --}}
                                <div id="rating-menu"
                                    class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 scale-95 pointer-events-none transform origin-top transition-all duration-200 ease-out">
                                    <ul class="py-1 text-sm text-gray-700">
                                        <li><button type="button" data-value="5"
                                                class="rating-option w-full text-left px-4 py-2 hover:bg-blue-50 hover:text-blue-700 transition-colors">⭐⭐⭐⭐⭐
                                                - Sangat Bagus</button></li>
                                        <li><button type="button" data-value="4"
                                                class="rating-option w-full text-left px-4 py-2 hover:bg-blue-50 hover:text-blue-700 transition-colors">⭐⭐⭐⭐
                                                - Bagus</button></li>
                                        <li><button type="button" data-value="3"
                                                class="rating-option w-full text-left px-4 py-2 hover:bg-blue-50 hover:text-blue-700 transition-colors">⭐⭐⭐
                                                - Cukup</button></li>
                                        <li><button type="button" data-value="2"
                                                class="rating-option w-full text-left px-4 py-2 hover:bg-blue-50 hover:text-blue-700 transition-colors">⭐⭐
                                                - Kurang</button></li>
                                        <li><button type="button" data-value="1"
                                                class="rating-option w-full text-left px-4 py-2 hover:bg-blue-50 hover:text-blue-700 transition-colors">⭐
                                                - Sangat Kurang</button></li>
                                    </ul>
                                </div>
                                @error('rating')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Kolom Komentar --}}
                        <div class="mb-4">
                            <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Komentar
                                (Opsional)</label>
                            <textarea id="comment" name="comment" rows="4"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Bagaimana kualitas hasil cetak dan pelayanannya?">{{ old('comment') }}</textarea>
                            @error('comment')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center transition-colors">
                            Kirim Ulasan
                        </button>
                    </form>
                </div>
                @else
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center mt-6">
                    <p class="text-blue-800 mb-3">Anda harus masuk untuk dapat memberikan ulasan.</p>
                    <a href="{{ route('login') }}"
                        class="inline-block bg-blue-600 text-white font-medium px-4 py-2 rounded shadow hover:bg-blue-700 transition">Login
                        Sekarang</a>
                </div>
                @endauth

                <div class="space-y-4 mb-8">
                    @forelse($product->reviews as $review)
                    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                {{-- Avatar Pengguna (Menggunakan logika avatar sebelumnya) --}}
                                <img src="{{ $review->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) . '&background=random' }}"
                                    alt="{{ $review->user->name }}" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover">
                                <div>
                                    <h4 class="font-semibold text-gray-800">{{ $review->user->name }}</h4>
                                    <p class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            {{-- Bintang Rating --}}
                            <div class="flex text-yellow-400 text-sm">
                                @for($i = 1; $i <= 5; $i++) 
                                    @if($i <=$review->rating)
                                        <i class="fa-solid fa-star"></i>
                                    @else
                                        <i class="fa-regular fa-star text-gray-300"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        <p class="text-gray-600 mt-3 text-sm sm:text-base">{{ $review->comment }}</p>
                    </div>
                    @empty
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
                        <p class="text-gray-500">Belum ada ulasan untuk produk ini. Jadilah yang pertama memberikan
                            ulasan!</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- Tab Detail, Estimasi Harga, Panduan, Informasi Pengiriman --}}
        <section>
            <div class="container w-full">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mt-12">Detail Produk</h2>
                <div class="border border-gray-200 rounded-lg mt-4">
                    <div class="flex bg-gray-50 justify-center border-b border-gray-200">
                        <div class="tab-button hover:text-blue-500 hover:cursor-pointer border-b-2 border-blue-500 py-4 transition-colors duration-300 active" role="button" data-tab="detail">
                            <a class="text-sm text-center sm:text-lg px-3 sm:px-5 opacity-100 block cursor-pointer" data-tab="detail">Detail</a>
                        </div>
                        <div class="tab-button hover:text-blue-500 hover:cursor-pointer border-b-2 border-transparent py-4 transition-colors duration-300" role="button" data-tab="estimasi-harga">
                            <a class="text-sm text-center sm:text-lg px-3 sm:px-5 block cursor-pointer" data-tab="estimasi-harga">Harga</a>
                        </div>
                        <div class="tab-button hover:text-blue-500 hover:cursor-pointer border-b-2 border-transparent py-4 transition-colors duration-300" role="button" data-tab="panduan">
                            <a class="text-sm text-center sm:text-lg px-3 sm:px-5 block cursor-pointer" data-tab="panduan">Panduan</a>
                        </div>
                        <div class="tab-button hover:text-blue-500 hover:cursor-pointer border-b-2 border-transparent py-4 transition-colors duration-300" role="button" data-tab="informasi-pengiriman">
                            <a class="text-sm text-center sm:text-lg px-3 sm:px-5 block cursor-pointer" data-tab="informasi-pengiriman">Pengiriman</a>
                        </div>
                    </div>

                    {{-- Tab Content --}}
                    <div class="p-6">
                        {{-- Detail Tab --}}
                        <div id="detail" class="tab-content block">
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-md sm:text-lg font-semibold text-gray-800 mb-2">Deskripsi Produk</h3>
                                    <p class="text-sm sm:text-base text-gray-600">{{ $product->deskripsi ?? 'Deskripsi produk tidak tersedia' }}</p>
                                </div>
                                <div>
                                    <h3 class="text-md sm:text-lg font-semibold text-gray-800 mb-2">Kategori</h3>
                                    <p class="text-sm sm:text-base text-gray-600">{{ $product->kategori ?? '-' }}</p>
                                </div>
                                <div>
                                    <h3 class="text-md sm:text-lg font-semibold text-gray-800 mb-2">Bahan</h3>
                                    <p class="text-sm sm:text-base text-gray-600">{{ $product->bahan ?? '-' }}</p>
                                </div>
                                <div>
                                    <h3 class="text-md sm:text-lg font-semibold text-gray-800 mb-2">Warna Tersedia</h3>
                                    <p class="text-sm sm:text-base text-gray-600">{{ $product->warna ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Estimasi Harga Tab --}}
                        <div id="estimasi-harga" class="tab-content hidden">
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-md sm:text-lg font-semibold text-gray-800 mb-2">Harga Dasar Produk</h3>
                                    <p class="text-lg sm:text-2xl font-bold text-blue-600">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                                </div>
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <h3 class="text-md sm:text-lg font-semibold text-gray-800 mb-2">Catatan Harga</h3>
                                    <ul class="text-sm sm:text-base text-gray-600 space-y-2">
                                        <li>• Harga belum termasuk ongkos kirim</li>
                                        <li>• Pembelian produk dikenakan PPN 12%</li>
                                        <li>• Harga dapat berubah tergantung bahan dan warna yang dipilih</li>
                                        <li>• Desain custom mungkin dikenakan biaya tambahan</li>
                                    </ul>
                                </div>
                                <div>
                                    <table class="w-full border border-gray-200 rounded-lg text-left table-auto">
                                        <thead class="bg-gray-50">
                                            <tr class="">
                                                <th class="py-3 px-4 text-left text-gray-800 font-semibold">Bahan</th>
                                                <th class="py-3 px-4 text-left text-gray-800 font-semibold">Kuantitas</th>
                                                <th class="py-3 px-4 text-left text-gray-800 font-semibold">Harga Satuan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="border-t border-gray-200">
                                                <td class="py-3 px-4">Standard</td>
                                                <td class="py-3 px-4">Rp 0</td>
                                            </tr>
                                            <tr class="border-t border-gray-200">
                                                <td class="py-3 px-4">Custom</td>
                                                <td class="py-3 px-4">Rp 20.000 - Rp 100.000 (tergantung kompleksitas desain)</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Panduan Tab --}}
                        <div id="panduan" class="tab-content hidden">
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-md sm:text-lg font-semibold text-gray-800 mb-3">Panduan Pembelian</h3>
                                    <ol class="text-sm sm:text-base text-gray-600 space-y-2 list-decimal list-inside">
                                        <li>Pilih bahan dan warna yang diinginkan</li>
                                        <li>Tentukan jenis desain (standard atau custom)</li>
                                        <li>Jika custom, upload file desain Anda (PNG, JPG, PDF max 5MB)</li>
                                        <li>Masukkan kuantitas produk yang diinginkan</li>
                                        <li>Tambahkan catatan khusus jika diperlukan</li>
                                        <li>Klik "Beli Langsung" atau "Keranjang"</li>
                                        <li>Lanjutkan ke checkout dan pembayaran</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        {{-- Informasi Pengiriman Tab --}}
                        <div id="informasi-pengiriman" class="tab-content hidden">
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-md sm:text-lg font-semibold text-gray-800 mb-2">Estimasi Waktu Pengiriman</h3>
                                    <ul class="text-sm sm:text-base text-gray-600 space-y-2 list-disc list-inside">
                                        <li>Produk standard: 3-5 hari kerja</li>
                                        <li>Produk custom: 5-7 hari kerja</li>
                                        <li>Pengiriman dilakukan setelah pembayaran dikonfirmasi</li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-md sm:text-lg font-semibold text-gray-800 mb-2">Ongkos Kirim</h3>
                                    <p class="text-sm sm:text-base text-gray-600">Ongkos kirim dihitung berdasarkan lokasi pengiriman dan akan ditampilkan saat checkout</p>
                                </div>
                                <div>
                                    <h3 class="text-md sm:text-lg font-semibold text-gray-800 mb-2">Kebijakan Pengembalian</h3>
                                    <p class="text-sm sm:text-base text-gray-600">Produk dapat dikembalikan dalam kondisi baik dan kemasan asli dalam waktu 7 hari setelah penerimaan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <x-footer></x-footer>
    @stack('scripts')
</body>

</html>