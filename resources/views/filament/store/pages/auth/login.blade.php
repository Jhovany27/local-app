<div class="bg-[#edf3e3] min-h-screen flex items-center justify-center p-4 relative">

    {{-- BOTÓN PORTAL --}}
    <a href="{{ route('portal') }}"
       class="fixed top-5 left-5 inline-flex items-center gap-2 bg-white/80 backdrop-blur-sm
              text-gray-600 text-sm font-semibold px-4 py-2 rounded-full shadow-sm
              border border-gray-200 hover:bg-white hover:text-gray-900 transition-all duration-200 z-50">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             stroke-width="2" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        Portal
    </a>

    <div class="w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden grid md:grid-cols-2">

        {{-- FORM --}}
        <div class="p-6 md:p-12 flex flex-col justify-center">

            <h2 class="text-2xl md:text-3xl font-extrabold text-center mb-6 md:mb-8">
                Iniciar Sesión
            </h2>

            <form method="POST" action="{{ route('login') }}" class="space-y-5 md:space-y-6">
                @csrf

                {{-- EMAIL --}}
                <div>
                    <label class="block md:text-center mb-2 font-medium">Correo</label>
                    <input type="email" name="email"
                        class="w-full border-2 border-gray-800 rounded-lg px-4 py-2 md:text-center focus:outline-none"
                        required>
                </div>

                {{-- PASSWORD --}}
                <div class="relative">
                    <label class="block md:text-center mb-2 font-medium">Contraseña</label>
                    <input id="password" type="password" name="password"
                        class="w-full border-2 border-gray-800 rounded-lg px-4 py-2 pr-10 md:text-center focus:outline-none"
                        required>
                    <button type="button" onclick="togglePassword()"
                        class="absolute right-3 top-10 text-gray-600">
                        <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 hidden">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 3l18 18M10.584 10.587A3 3 0 0012 15a3 3 0 002.414-4.413M9.88 5.458A9.77 9.77 0 0112 4.5c6 0 9.75 7.5 9.75 7.5a16.708 16.708 0 01-4.293 5.774M6.61 6.61A16.708 16.708 0 002.25 12s3.75 7.5 9.75 7.5a9.77 9.77 0 004.22-.958"/>
                        </svg>
                    </button>
                </div>

                {{-- OPTIONS --}}
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between text-sm gap-2">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember">
                        Recordarme
                    </label>
                    <a href="#" class="text-gray-600 hover:underline">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>

                {{-- BOTÓN --}}
                <button type="submit"
                    class="w-full bg-[#a8df11] hover:bg-[#95c510] text-white font-bold py-3 rounded-lg transition">
                    Entrar
                </button>

                {{-- ERROR --}}
                @error('email')
                <p class="text-red-600 text-sm text-center">
                    @if($message === 'verificar_correo')
                        Debes <a href="{{ route('verificar-correo') }}" class="font-bold underline">verificar tu correo</a> antes de iniciar sesión.
                    @else
                        {{ $message }}
                    @endif
                </p>
                @enderror

            </form>

            {{-- REGISTRO --}}
            <p class="mt-6 text-center text-sm">
                ¿No tienes cuenta?
                <a href="{{ route('registro.create') }}" class="text-[#d41b11] font-bold hover:underline">
                    Regístrate
                </a>
            </p>

        </div>

        {{-- IMAGEN --}}
        <div class="hidden md:flex bg-[#a8df11] items-center justify-center relative">
            <img src="{{ asset('images/Logo_local_app.png') }}" class="max-w-md w-full z-10">
            <div class="absolute inset-0 bg-gradient-to-br from-[#a8df11] to-[#7cc10a] opacity-90"></div>
        </div>

    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');
            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>

</div>