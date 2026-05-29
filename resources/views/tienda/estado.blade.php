<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado de tu solicitud</title>
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
                   border border-gray-200 hover:bg-white hover:text-red-600 transition-all">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
        </svg>
        Salir
    </button>
</form>

<div class="w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden grid md:grid-cols-2">

    {{-- CONTENIDO --}}
    <div class="p-6 md:p-12 flex flex-col justify-center">

        <h2 class="text-2xl md:text-3xl font-extrabold text-center mb-2">
            Estado de tu solicitud
        </h2>
        <p class="text-gray-500 text-sm text-center mb-8">
            Aquí puedes ver el estado de todas tus tiendas registradas
        </p>

        <div class="space-y-4">
            @foreach($tiendas as $tienda)

            {{-- CARD TIENDA --}}
            <div class="border-2 rounded-xl p-4 {{ $tienda->tie_estado == 0 ? 'border-amber-300 bg-amber-50' : 'border-red-300 bg-red-50' }}">

                {{-- Nombre + badge --}}
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-bold text-gray-900 text-sm">{{ $tienda->tie_nombre }}</h3>
                    @if($tienda->tie_estado == 0)
                        <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-700 border border-amber-300 text-xs font-bold px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                            Pendiente
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 border border-red-300 text-xs font-bold px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                            Rechazada
                        </span>
                    @endif
                </div>

                {{-- Contenido según estado --}}
                @if($tienda->tie_estado == 0)
                    {{-- PENDIENTE --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-xs text-gray-600">
                            <div class="w-5 h-5 rounded-full bg-[#a8df11] flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="white" class="w-3 h-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                </svg>
                            </div>
                            Registro completado
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-600">
                            <div class="w-5 h-5 rounded-full bg-[#a8df11] flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="white" class="w-3 h-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                </svg>
                            </div>
                            Documentos enviados
                        </div>
                        <div class="flex items-center gap-2 text-xs text-amber-700 font-semibold">
                            <div class="w-5 h-5 rounded-full bg-amber-100 border-2 border-amber-400 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#f59e0b" class="w-3 h-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"/>
                                </svg>
                            </div>
                            En revisión por el administrador
                        </div>
                    </div>

                @else
                    {{-- RECHAZADA --}}
                    <div class="mb-3">
                        <p class="text-xs font-bold text-red-600 uppercase tracking-wide mb-1">Motivo del rechazo</p>
                        <p class="text-sm text-gray-700 bg-white border border-red-200 rounded-lg px-3 py-2 leading-relaxed">
                            {{ $tienda->tie_motivo_rechazo ?? 'No se especificó un motivo.' }}
                        </p>
                    </div>
                    <a href="{{ route('registro.tienda') }}"
                       class="block w-full text-center bg-[#a8df11] hover:bg-[#95c510] text-white font-bold py-2 rounded-lg transition text-sm">
                        Registrar nueva tienda
                    </a>
                @endif

            </div>

            @endforeach
        </div>

    </div>

    {{-- IMAGEN --}}
    <div class="hidden md:flex bg-[#a8df11] items-center justify-center relative overflow-hidden">
        <img src="{{ asset('images/Logo_local_app.png') }}" class="max-w-md w-full z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-[#a8df11] to-[#7cc10a] opacity-90"></div>
    </div>

</div>

</body>
</html>