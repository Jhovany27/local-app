<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verifica tu correo — Tienda</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/filament/store/theme.css')
</head>
<body class="bg-[#edf3e3] min-h-screen flex items-center justify-center p-4">

    {{-- BOTÓN PORTAL --}}
    <a href="{{ route('portal') }}"
        class="fixed top-5 left-5 inline-flex items-center gap-2 bg-white/80 backdrop-blur-sm
               text-gray-600 text-sm font-semibold px-4 py-2 rounded-full shadow-sm
               border border-gray-200 hover:bg-white hover:text-gray-900 transition-all z-50">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
        </svg>
        Portal
    </a>

    <div class="w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden grid md:grid-cols-2">

        {{-- CONTENIDO --}}
        <div class="p-6 md:p-12 flex flex-col justify-center">

            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-[#f0fde0] border-2 border-[#d4f0a0] rounded-2xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#4a8a06" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                </div>
            </div>

            <h2 class="text-2xl md:text-3xl font-extrabold text-center mb-2">
                Verifica tu correo
            </h2>
            <p class="text-gray-500 text-sm text-center mb-6">
                Antes de continuar, confirma tu dirección de correo.
            </p>

            <p class="text-sm text-gray-600 text-center leading-relaxed mb-6">
                Te enviamos un enlace de verificación a<br>
                <strong class="text-gray-900">{{ Auth::user()->email }}</strong>.<br>
                Revisa tu bandeja de entrada o spam.
            </p>

            @if (session('message'))
                <div class="bg-[#f0fde0] border border-[#c6f0a0] text-[#3a7a05] text-sm font-semibold px-4 py-3 rounded-xl mb-4 text-center">
                    {{ session('message') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
                @csrf
                <button type="submit"
                    class="w-full bg-[#a8df11] hover:bg-[#95c510] text-gray-900 font-bold py-3 rounded-xl transition text-sm">
                    Reenviar correo de verificación
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-center text-sm text-gray-400 hover:text-red-500 transition py-2">
                    Cerrar sesión
                </button>
            </form>

        </div>

        {{-- IMAGEN --}}
        <div class="hidden md:flex bg-[#a8df11] items-center justify-center relative overflow-hidden">
            <img src="{{ asset('images/Logo_local_app.png') }}" class="max-w-md w-full z-10">
            <div class="absolute inset-0 bg-gradient-to-br from-[#a8df11] to-[#7cc10a] opacity-90"></div>
        </div>

    </div>

</body>
</html>
