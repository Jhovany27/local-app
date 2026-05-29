<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Validando tu tienda</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/filament/store/theme.css')
</head>
<body class="bg-[#edf3e3] min-h-screen flex items-center justify-center p-4">

{{-- BOTÓN PORTAL --}}
<a href="{{ route('portal') }}"
   class="fixed top-5 left-5 inline-flex items-center gap-2 bg-white/80 backdrop-blur-sm
          text-gray-600 text-sm font-semibold px-4 py-2 rounded-full shadow-sm
          border border-gray-200 hover:bg-white hover:text-gray-900 transition-all duration-200 z-50">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
    </svg>
    Portal
</a>

{{-- BOTÓN LOGOUT --}}
<form method="POST" action="{{ route('logout') }}" class="fixed top-5 right-5 z-50">
    @csrf
    <button type="submit"
            class="inline-flex items-center gap-2 bg-white/80 backdrop-blur-sm
                   text-gray-600 text-sm font-semibold px-4 py-2 rounded-full shadow-sm
                   border border-gray-200 hover:bg-white hover:text-red-600 transition-all duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
        </svg>
        Salir
    </button>
</form>

<div class="w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden grid md:grid-cols-2">

    {{-- CONTENIDO --}}
    <div class="p-6 md:p-12 flex flex-col justify-center">

        <h2 class="text-2xl md:text-3xl font-extrabold text-center mb-6">
            Tienda en revisión
        </h2>

        <div class="space-y-4 mb-8">

            {{-- Paso 1 --}}
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-[#a8df11] flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-500 line-through">Registro de cuenta completado</span>
            </div>

            {{-- Paso 2 --}}
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-[#a8df11] flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-500 line-through">Documentos enviados</span>
            </div>

            {{-- Paso 3 --}}
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-amber-50 border-2 border-amber-400 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#f59e0b" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-900">Revisión por el administrador</span>
            </div>

            {{-- Paso 4 --}}
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center flex-shrink-0 text-gray-400 text-xs font-bold">
                    4
                </div>
                <span class="text-sm text-gray-400">Activación de tu panel de tienda</span>
            </div>

        </div>

        <p class="text-gray-500 text-sm text-center mb-6">
            Un administrador revisará tus documentos y activará tu cuenta lo antes posible.
        </p>

        <a href="{{ route('portal') }}"
           class="w-full block text-center bg-[#a8df11] hover:bg-[#95c510] text-white font-bold py-3 rounded-lg transition">
            Volver al portal
        </a>

    </div>

    {{-- IMAGEN --}}
    <div class="hidden md:flex bg-[#a8df11] items-center justify-center relative overflow-hidden">
        <img src="{{ asset('images/Logo_local_app.png') }}" class="max-w-md w-full z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-[#a8df11] to-[#7cc10a] opacity-90"></div>
    </div>

</div>

</body>
</html>