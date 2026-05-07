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

    <main class="w-full px-6 md:px-12 lg:px-16 mx-auto mt-28">
        <div class="hidden gap-3 lg:flex">
            <a href="{{ route('home') }}" class="text-slate-400 hover:text-blue-500">Home</a>
            <h2 class="text-slate-400">/</h2>
            <a href="{{ route('product.index') }}" class="text-slate-400 hover:text-blue-500">Etalase Produk</a>
            <h2 class="text-slate-400">/</h2>
            <h2 class="text-blue-700">{{ $product->name }}</h2>
        </div>
        {{-- Product Detail --}}
        <section>
            <div class="flex flex-col lg:flex-row gap-10 mt-5">
                {{-- Gambar Produk --}}
                <div class="w-full lg:w-1/3">
                    <img class="w-full h-96 md:h-[32rem] rounded-xl object-cover border border-gray-200"
                        src="{{ asset($product->product_image) }}" alt="{{ $product->name }}">
                </div>
                {{-- Deskripsi Produk --}}
                <div class="w-full lg:w-2/3">
                    <span class="border border-blue-500 bg-blue-100 text-sm text-blue-500 p-2 rounded-lg"><strong
                            class="text-blue-500">{{ $product->category }}</strong></span>
                    <h1 class="text-3xl font-bold mb-2 mt-4">{{ $product->name }}</h1>
                    <div class="flex items-center gap-4 mb-4">
                        {{-- <div class="flex gap-2">Stok <p class="font-semibold text-gray-400">{{ number_format($product->stok
                                ?? 0, 0, ',', '.') }}</p>
                        </div> --}}
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
                    <form id="productForm" action="{{ route('cart.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->_id }}">
                        <div class="flex flex-col my-5">
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
                                    <div class="flex justify-between mb-2 items-center">
                                        <label for="material" class="text-lg font-semibold text-gray-900">Bahan</label>
                                        <p id="unitPrice" data-price="{{ $product->price }}"
                                            class="text-lg font-semibold text-gray-400">Rp{{ number_format($product->price,
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
                                    if(!empty($product->color)) {
                                    $colors = array_map('trim', explode(',', $product->color));
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
                                    <p id="unitPrice" data-price="{{ $product->price }}"
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
                                    <label class="inline-flex items-center gap-2">
                                        <input type="radio" name="design_type" value="standard" class="design-radio"
                                            checked>
                                        Standard
                                    </label>
                                    <label class="inline-flex items-center gap-2">
                                        <input type="radio" name="design_type" value="custom" class="design-radio">
                                        Custom
                                    </label>
                                </div>
    
                                <div class="col-span-full border border-dashed rounded-lg bg-gray-50 mt-3 hidden transition-colors duration-200" id="customDesignDiv">
                                    <div class="mt-2 flex justify-center border-white/50 px-6 py-10 transition-colors duration-200">
                                        <div class="text-center w-full">
                                            <svg viewBox="0 0 24 24" fill="currentColor" data-slot="icon" aria-hidden="true"
                                                class="mx-auto size-12 text-gray-600">
                                                <path
                                                    d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z"
                                                    clip-rule="evenodd" fill-rule="evenodd" />
                                            </svg>
                                            <div class="mt-4 flex text-sm/6 text-gray-400 justify-center">
                                                <label for="file-upload"
                                                    class="relative cursor-pointer rounded-md bg-transparent font-semibold text-indigo-400 focus-within:outline-2 focus-within:outline-offset-2 focus-within:outline-indigo-500 hover:text-indigo-300">
                                                    <span>Upload a file</span>
                                                    <input id="file-upload" type="file" name="custom_file" class="sr-only" accept=".png,.jpg,.jpeg,.pdf"/>
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
                                    {{-- <div class="text-md text-gray-500 ml-4">Stok: {{ number_format($product->stok ?? 0, 0,
                                        ',', '.') }}</div> --}}
                                </div>
                            </div>
    
                            <div class="mb-5">
                                <label for="note" class="text-lg font-semibold text-gray-900">Catatan</label>
                                <textarea name="note" id="note" cols="30" rows="2" class="border rounded-md w-full p-3 mt-2"
                                    placeholder="Contoh: Warna dibuat agak terang"></textarea>
                            </div>
    
                        </div>
                        <div class="border my-5"></div>
                        <div class="flex justify-between">
                            <h3 class="text-2xl font-bold text-gray-900">Subtotal</h3>
                            <p id="totalPrice" class="text-2xl font-semibold text-blue-600">
                                <span id="subtotalText">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
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
    </main>

    <x-footer></x-footer>
    @stack('scripts')
</body>

</html>