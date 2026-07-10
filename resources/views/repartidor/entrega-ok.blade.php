<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Entrega confirmada</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif;
            background: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
            padding: 2rem;
            width: 100%;
            max-width: 380px;
        }

        .circulo {
            width: 90px;
            height: 90px;
            position: relative;
        }

        .spinner {
            width: 90px;
            height: 90px;
            animation: girar 1.2s linear infinite;
            position: absolute;
            inset: 0;
        }

        .check {
            width: 90px;
            height: 90px;
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 0.3s;
        }

        @keyframes girar { to { transform: rotate(360deg); } }

        .check-path {
            stroke-dasharray: 40;
            stroke-dashoffset: 40;
            transition: stroke-dashoffset 0.5s ease 0.1s;
        }

        .barra-wrap {
            width: 200px;
            height: 4px;
            background: #e8f5d0;
            border-radius: 999px;
            overflow: hidden;
        }

        .barra {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #a8df11, #7cc10a);
            border-radius: 999px;
            transition: width 0.4s ease;
        }

        .txt-estado {
            font-size: 0.95rem;
            font-weight: 700;
            color: #555;
            text-align: center;
        }

        .txt-sub {
            font-size: 0.78rem;
            color: #aaa;
            text-align: center;
            margin-top: -0.75rem;
        }
    </style>
</head>
<body>
    <div class="wrap" id="wrap">

        <div class="circulo">
            <svg class="spinner" id="spinner" viewBox="0 0 90 90" fill="none">
                <circle cx="45" cy="45" r="38" stroke="#e8f5d0" stroke-width="6" />
                <path d="M45 7 A38 38 0 0 1 83 45" stroke="#a8df11" stroke-width="6" stroke-linecap="round" />
            </svg>
            <svg class="check" id="check" viewBox="0 0 90 90" fill="none">
                <circle cx="45" cy="45" r="38" stroke="#a8df11" stroke-width="6" />
                <path class="check-path" id="check-path" d="M28 46 L40 58 L63 34"
                    stroke="#a8df11" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>

        <div class="barra-wrap">
            <div class="barra" id="barra"></div>
        </div>

        <p class="txt-estado" id="txt-estado">Confirmando entrega...</p>
        <p class="txt-sub" id="txt-sub">Por favor espera</p>

    </div>

    <script>
        const barra    = document.getElementById('barra');
        const spinner  = document.getElementById('spinner');
        const check    = document.getElementById('check');
        const checkPath = document.getElementById('check-path');
        const txtEstado = document.getElementById('txt-estado');
        const txtSub    = document.getElementById('txt-sub');

        // Barra de progreso rápida (~1.2 s hasta 90%)
        let pct = 0;
        const iv = setInterval(() => {
            pct = Math.min(pct + Math.random() * 18, 90);
            barra.style.width = pct + '%';
        }, 200);

        // Al llegar a ~1.3 s: mostrar check y completar
        setTimeout(() => {
            clearInterval(iv);
            barra.style.width = '100%';

            setTimeout(() => {
                spinner.style.display = 'none';
                check.style.opacity   = '1';
                checkPath.style.strokeDashoffset = '0';
                txtEstado.textContent = '¡Pedido entregado!';
                txtSub.textContent    = 'Excelente trabajo 🚀';
            }, 400);

            // Redirigir al inicio tras la animación
            setTimeout(() => {
                window.location.href = '{{ route('repartidor.index') }}';
            }, 2400);

        }, 1300);
    </script>
</body>
</html>
