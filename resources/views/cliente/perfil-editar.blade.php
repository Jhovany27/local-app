<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar perfil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/cliente/perfil.css')
</head>

<body>
    <div class="app">

        {{-- HEADER --}}
        <div class="header">
            <a href="{{ route('cliente.perfil') }}" class="btn-back">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </a>
            <div class="header-logo">
                <img src="{{ asset('images/Logo_local_app.png') }}" alt="LocalApp">
            </div>
            <div style="width:22px"></div>
        </div>

        {{-- HERO --}}
        <div class="perfil-hero">
            <div class="avatar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </div>
            <p class="perfil-nombre">Editar perfil</p>
            <p class="perfil-email">{{ $user->email }}</p>
        </div>

        {{-- FORM BODY --}}
        <div class="body">

            <form method="POST" action="{{ route('cliente.perfil.update') }}" novalidate>
                @csrf
                @method('PUT')

                {{-- DATOS PERSONALES --}}
                <div class="seccion">
                    <p class="seccion-titulo">Datos personales</p>
                    <div class="form-card">

                        <div class="field-group">
                            <label for="per_nombre">Nombre(s)</label>
                            <input
                                id="per_nombre"
                                type="text"
                                name="per_nombre"
                                value="{{ old('per_nombre', $persona?->per_nombre) }}"
                                placeholder="Tu nombre"
                                class="{{ $errors->has('per_nombre') ? 'error' : '' }}"
                                required
                            >
                            @error('per_nombre')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="field-group">
                            <label for="per_paterno">Apellido paterno</label>
                            <input
                                id="per_paterno"
                                type="text"
                                name="per_paterno"
                                value="{{ old('per_paterno', $persona?->per_paterno) }}"
                                placeholder="Apellido paterno"
                                class="{{ $errors->has('per_paterno') ? 'error' : '' }}"
                                required
                            >
                            @error('per_paterno')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="field-group">
                            <label for="per_materno">Apellido materno</label>
                            <input
                                id="per_materno"
                                type="text"
                                name="per_materno"
                                value="{{ old('per_materno', $persona?->per_materno) }}"
                                placeholder="Apellido materno (opcional)"
                                class="{{ $errors->has('per_materno') ? 'error' : '' }}"
                            >
                            @error('per_materno')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="field-group">
                            <label for="per_telefono">Teléfono</label>
                            <input
                                id="per_telefono"
                                type="tel"
                                name="per_telefono"
                                value="{{ old('per_telefono', $persona?->per_telefono) }}"
                                placeholder="10 dígitos"
                                class="{{ $errors->has('per_telefono') ? 'error' : '' }}"
                                required
                            >
                            @error('per_telefono')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- CUENTA --}}
                <div class="seccion">
                    <p class="seccion-titulo">Cuenta</p>
                    <div class="form-card">

                        <div class="field-group">
                            <label for="email">Correo electrónico</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                placeholder="tu@correo.com"
                                class="{{ $errors->has('email') ? 'error' : '' }}"
                                required
                            >
                            @error('email')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                            <span class="hint">Si cambias el correo deberás verificarlo de nuevo.</span>
                        </div>

                    </div>
                </div>

                {{-- CAMBIAR CONTRASEÑA --}}
                <div class="seccion">
                    <p class="seccion-titulo">Cambiar contraseña</p>
                    <div class="form-card">

                        <div class="field-group">
                            <label for="password_actual">Contraseña actual</label>
                            <input
                                id="password_actual"
                                type="password"
                                name="password_actual"
                                placeholder="Contraseña actual"
                                class="{{ $errors->has('password_actual') ? 'error' : '' }}"
                                autocomplete="current-password"
                            >
                            @error('password_actual')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="field-group">
                            <label for="nueva_password">Nueva contraseña</label>
                            <input
                                id="nueva_password"
                                type="password"
                                name="nueva_password"
                                placeholder="Mínimo 8 caracteres"
                                class="{{ $errors->has('nueva_password') ? 'error' : '' }}"
                                autocomplete="new-password"
                            >
                            @error('nueva_password')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="field-group">
                            <label for="nueva_password_confirmation">Confirmar contraseña</label>
                            <input
                                id="nueva_password_confirmation"
                                type="password"
                                name="nueva_password_confirmation"
                                placeholder="Repite la nueva contraseña"
                                autocomplete="new-password"
                            >
                        </div>

                        <span class="hint">Deja en blanco si no quieres cambiar tu contraseña.</span>

                    </div>
                </div>

                <button type="submit" class="btn-guardar">Guardar cambios</button>

            </form>

        </div>

        {{-- BOTTOM NAV --}}
        <nav class="bottom-nav">
            <a href="{{ route('cliente.index') }}" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
            </a>
            <a href="{{ route('carrito.index') }}" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
            </a>
            <a href="{{ route('cliente.pedidos') }}" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15" />
                </svg>
            </a>
            <a href="{{ route('cliente.perfil') }}" class="nav-item active">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </a>
        </nav>

    </div>
</body>

</html>
