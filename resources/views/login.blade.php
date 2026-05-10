<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Designplus</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        #togglePassword {
            -webkit-tap-highlight-color: transparent;
        }
    </style>
</head>

<body class="bg-gray-100 w-full h-screen">

    <div class="w-full h-full flex">

        <div class="w-full md:w-1/2 flex items-center justify-center p-10 bg-white">

            <div class="w-full max-w-md">

                <h1 class="text-4xl font-extrabold text-blue-600 mb-6">Designplus.</h1>

                <h2 class="text-3xl font-bold mb-2">Masuk</h2>
                <p class="text-gray-500 text-sm mb-7">
                    Silahkan masuk untuk melanjutkan akses.
                </p>

                @if (session()->has('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session()->has('loginError'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4" role="alert">
                        {{ session('loginError') }}
                    </div>
                @endif

                <form action="{{ route('login.process') }}" method="POST">
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

                    <label class="text-sm font-semibold @error('password') text-red-500 @enderror mt-4 block">
                        Password
                    </label>
                    
                    <!-- Wrapper Input Password dengan Fitur Show/Hide -->
                    <div class="relative">
                        <input type="password" name="password" id="passwordField" placeholder="Masukkan password Anda" required
                               class="w-full mt-1 mb-4 px-4 py-2 border rounded-lg focus:ring pr-10
                                      @error('password') border-red-500 focus:ring-red-300 @else focus:ring-blue-300 @enderror">
                        
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center mb-4 text-gray-500 focus:outline-none select-none">
                            <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                            <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>

                    @error('password')
                        <p class="text-red-500 text-xs mt-1 mb-4">{{ $message }}</p>
                    @enderror

                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg mt-2">
                        Log in
                    </button>
                </form>

                <div class="flex items-center my-6">
                    <span class="flex-grow h-px bg-gray-300"></span>
                    <span class="mx-3 text-gray-400 text-sm">Atau</span>
                    <span class="flex-grow h-px bg-gray-300"></span>
                </div>

                <a href="{{ route('google.login') }}" class="w-full border py-3 flex items-center justify-center rounded-lg hover:bg-gray-50">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 mr-2" alt="Google">
                    <span class="text-sm font-medium">Masuk dengan Google</span>
                </a>

                <p class="text-sm mt-6 text-gray-500">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">Daftar di sini</a>
                </p>
            </div>

        </div>

        <div class="hidden md:block w-1/2 h-full">
            <img src="{{ asset('assets/login.jpg') }}" class="w-full h-full object-cover" alt="Login Illustration">
        </div>

    </div>

    <script>
        const passwordField = document.getElementById('passwordField');
        const toggleButton = document.getElementById('togglePassword');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');

        function showPassword() {
            passwordField.type = 'text';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }

        function hidePassword() {
            passwordField.type = 'password';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        }

        // Listener untuk Mouse (Desktop)
        toggleButton.addEventListener('mousedown', (e) => {
            e.preventDefault();
            showPassword();
        });
        toggleButton.addEventListener('mouseup', hidePassword);
        toggleButton.addEventListener('mouseleave', hidePassword);

        // Listener untuk Touch (Mobile)
        toggleButton.addEventListener('touchstart', (e) => {
            e.preventDefault();
            showPassword();
        });
        toggleButton.addEventListener('touchend', (e) => {
            e.preventDefault();
            hidePassword();
        });
    </script>

</body>

</html>