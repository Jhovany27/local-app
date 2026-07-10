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
                <div style="position:relative">
                    <input type="password" id="tienda-pwd" name="password"
                        class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-[#a8df11]"
                        style="padding-right:3rem">
                    <button type="button" onclick="togglePwd('tienda-pwd','eyeT1','eyeT1c')"
                        style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9ca3af;display:flex;align-items:center;">
                        <svg id="eyeT1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <svg id="eyeT1c" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18" style="display:none">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.584 10.587A3 3 0 0012 15a3 3 0 002.414-4.413M9.88 5.458A9.77 9.77 0 0112 4.5c6 0 9.75 7.5 9.75 7.5a16.708 16.708 0 01-4.293 5.774M6.61 6.61A16.708 16.708 0 002.25 12s3.75 7.5 9.75 7.5a9.77 9.77 0 004.22-.958"/>
                        </svg>
                    </button>
                </div>
                <div id="tienda-pwd-reqs" style="display:none;margin-top:0.4rem;padding:0.5rem 0.75rem;background:#f9fafb;border-radius:0.5rem;border:1.5px solid #e5e7eb;">
                    <div id="t-req-len" style="font-size:0.72rem;color:#dc2626;font-weight:600;line-height:1.8;"><span>✗</span> Mínimo 8 caracteres</div>
                    <div id="t-req-upper" style="font-size:0.72rem;color:#dc2626;font-weight:600;line-height:1.8;"><span>✗</span> Al menos una mayúscula</div>
                    <div id="t-req-num" style="font-size:0.72rem;color:#dc2626;font-weight:600;line-height:1.8;"><span>✗</span> Al menos un número</div>
                </div>
                @error('password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Confirmar contraseña</label>
                <div style="position:relative">
                    <input type="password" id="tienda-pwd-conf" name="password_confirmation"
                        class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-[#a8df11]"
                        style="padding-right:3rem">
                    <button type="button" onclick="togglePwd('tienda-pwd-conf','eyeT2','eyeT2c')"
                        style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9ca3af;display:flex;align-items:center;">
                        <svg id="eyeT2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <svg id="eyeT2c" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18" style="display:none">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.584 10.587A3 3 0 0012 15a3 3 0 002.414-4.413M9.88 5.458A9.77 9.77 0 0112 4.5c6 0 9.75 7.5 9.75 7.5a16.708 16.708 0 01-4.293 5.774M6.61 6.61A16.708 16.708 0 002.25 12s3.75 7.5 9.75 7.5a9.77 9.77 0 004.22-.958"/>
                        </svg>
                    </button>
                </div>
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

<script>
function togglePwd(inputId, openId, closedId) {
    const input  = document.getElementById(inputId);
    const open   = document.getElementById(openId);
    const closed = document.getElementById(closedId);
    if (input.type === 'password') {
        input.type = 'text';
        open.style.display   = 'none';
        closed.style.display = 'block';
    } else {
        input.type = 'password';
        open.style.display   = 'block';
        closed.style.display = 'none';
    }
}
const tiendaPwd  = document.getElementById('tienda-pwd');
const tiendaReqs = document.getElementById('tienda-pwd-reqs');
if (tiendaPwd && tiendaReqs) {
    tiendaPwd.addEventListener('focus', () => tiendaReqs.style.display = 'block');
    tiendaPwd.addEventListener('input', () => {
        const v = tiendaPwd.value;
        [['t-req-len', v.length >= 8], ['t-req-upper', /[A-Z]/.test(v)], ['t-req-num', /[0-9]/.test(v)]].forEach(([id, ok]) => {
            const el = document.getElementById(id);
            el.style.color = ok ? '#15803d' : '#dc2626';
            el.querySelector('span').textContent = ok ? '✓' : '✗';
        });
    });
}
</script>
</body>
</html>