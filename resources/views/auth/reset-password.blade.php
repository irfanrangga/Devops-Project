<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Designplus</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 w-full h-screen">

    <div class="w-full h-full flex">

        <div class="w-full md:w-1/2 flex items-center justify-center p-10 bg-white">

            <div class="w-full max-w-md">

                <h1 class="text-4xl font-extrabold text-blue-600 mb-6">Designplus.</h1>

                <h2 class="text-3xl font-bold mb-2">Reset Password</h2>
                <p class="text-gray-500 text-sm mb-7">
                    Silakan masukkan password baru Anda.
                </p>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4" role="alert">
                        <ul class="list-disc pl-5 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <label class="text-sm font-semibold @error('email') text-red-500 @enderror">
                        Email
                    </label>
                    <input type="email" name="email" value="{{ old('email', $email) }}" required readonly
                           class="w-full mt-1 mb-4 px-4 py-2 border rounded-lg bg-gray-100 cursor-not-allowed focus:outline-none">

                    <label class="text-sm font-semibold @error('password') text-red-500 @enderror mt-2 block">
                        Password Baru
                    </label>
                    <input type="password" name="password" placeholder="Minimal 6 karakter" required autofocus
                           class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring
                                  @error('password') border-red-500 focus:ring-red-300 @else focus:ring-blue-300 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1 mb-4">{{ $message }}</p>
                    @enderror

                    <label class="text-sm font-semibold mt-4 block">
                        Konfirmasi Password Baru
                    </label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password baru" required
                           class="w-full mt-1 mb-4 px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">

                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg mt-4">
                        Reset Password
                    </button>
                </form>

            </div>

        </div>

        <div class="hidden md:block w-1/2 h-full">
            <img src="{{ asset('assets/login.jpg') }}" class="w-full h-full object-cover" alt="Login Illustration">
        </div>

    </div>

</body>

</html>
