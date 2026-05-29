<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css'])
</head>

<body class="bg-[#edf3e3] min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden grid md:grid-cols-2">

    <!-- FORMULARIO -->
    <div class="p-8 md:p-12 flex flex-col justify-center">

        <h2 class="text-3xl font-extrabold text-center mb-6">
            Crear cuenta
        </h2>

        <form method="POST" action="{{ route('registro.store') }}" class="space-y-5">
            @csrf

            <!-- USUARIO -->
            <div>
                <label class="block text-sm font-semibold mb-1">Usuario</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-[#a8df11]">
                @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Correo</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-[#a8df11]">
                @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Contraseña</label>
                <input type="password" name="password"
                    class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-[#a8df11]">
                @error('password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Confirmar contraseña</label>
                <input type="password" name="password_confirmation"
                    class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-[#a8df11]">
            </div>

            <!-- PERSONA -->
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Nombre</label>
                    <input type="text" name="per_nombre" value="{{ old('per_nombre') }}"
                        class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-[#a8df11]">
                    @error('per_nombre') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Teléfono</label>
                    <input type="text" name="per_telefono" value="{{ old('per_telefono') }}"
                        class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-[#a8df11]">
                    @error('per_telefono') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Apellido paterno</label>
                    <input type="text" name="per_paterno" value="{{ old('per_paterno') }}"
                        class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-[#a8df11]">
                    @error('per_paterno') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Apellido materno</label>
                    <input type="text" name="per_materno" value="{{ old('per_materno') }}"
                        class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-[#a8df11]">
                </div>
            </div>

            <!-- BOTONES -->
            <button type="submit"
                class="w-full bg-[#a8df11] hover:bg-[#95c510] text-white font-bold py-2 rounded-lg transition">
                Registrarme
            </button>

            <p class="text-center text-sm mt-4">
                ¿Ya tienes cuenta?
                <a href="/store/login" class="text-[#d41b11] font-bold hover:underline">
                    Inicia sesión
                </a>
            </p>

        </form>
    </div>

    <!-- LADO VISUAL -->
    <div class="hidden md:flex items-center justify-center bg-[#a8df11] relative">

        <img src="{{ asset('images/Logo_local_app.png') }}"
            class="max-w-xs w-full z-10">

        <!-- decoración -->
        <div class="absolute inset-0 bg-gradient-to-br from-[#a8df11] to-[#7cc10a] opacity-90"></div>
    </div>

</div>

</body>
</html>