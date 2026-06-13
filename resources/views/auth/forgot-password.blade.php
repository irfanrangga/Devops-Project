<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Designplus</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 w-full h-screen">

    <div class="w-full h-full flex">

        <div class="w-full md:w-1/2 flex items-center justify-center p-10 bg-white">

            <div class="w-full max-w-md">

                <h1 class="text-4xl font-extrabold text-blue-600 mb-6">Designplus.</h1>

                <h2 class="text-3xl font-bold mb-2">Lupa Password?</h2>
                <p class="text-gray-500 text-sm mb-7">
                    Masukkan email Anda. Kami akan mengirimkan tautan untuk mengatur ulang password Anda.
                </p>

                @if (session()->has('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf

                    <label class="text-sm font-semibold @error('email') text-red-500 @enderror">
                        Email
                    </label>
                    <input type="email" name="email" placeholder="Masukkan email Anda" required autofocus value="{{ old('email') }}"
                           class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring
                                  @error('email') border-red-500 focus:ring-red-300 @else focus:ring-blue-300 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1 mb-4">{{ $message }}</p>
                    @enderror

                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg mt-6">
                        Kirim Tautan Reset
                    </button>
                </form>

                <p class="text-sm mt-6 text-gray-500">
                    Ingat password Anda?
                    <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">Masuk di sini</a>
                </p>
            </div>

        </div>

        <div class="hidden md:block w-1/2 h-full">
            <img src="{{ asset('assets/login.jpg') }}" class="w-full h-full object-cover" alt="Login Illustration">
        </div>

    </div>

</body>

</html>
