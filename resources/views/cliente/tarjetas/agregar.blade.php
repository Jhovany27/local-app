<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Agregar tarjeta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/cliente/tarjetas.css')
    <script src="https://js.stripe.com/v3/"></script>
</head>

<body>
    <div class="app">

        <div class="header">
            <a href="{{ route('cliente.tarjetas') }}" class="header-back">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </a>
            <h1>Agregar tarjeta</h1>
            <div style="width:22px"></div>
        </div>

        <div class="body">

            <p style="font-size:0.82rem;color:#666;line-height:1.5;">
                Tu tarjeta se guarda de forma segura con Stripe. Nunca almacenamos los datos de tu tarjeta en nuestros servidores.
            </p>

            <div class="stripe-form-card">
                <label>Datos de tu tarjeta</label>
                <div id="card-element"></div>
                <p class="card-errors" id="card-errors"></p>
                <p class="info-tip">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" style="width:13px;height:13px;flex-shrink:0;">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                    Encriptación SSL — procesado por Stripe
                </p>
            </div>

            <p style="font-size:0.72rem;color:#aaa;text-align:center;">
                Tarjetas de prueba: <strong>4242 4242 4242 4242</strong> — cualquier fecha futura — cualquier CVC
            </p>

            <button type="button" id="btn-guardar" class="btn-guardar-tarjeta" onclick="guardarTarjeta()">
                Guardar tarjeta
            </button>

        </div>

    </div>

    {{-- FORM OCULTO --}}
    <form method="POST" action="{{ route('cliente.tarjetas.guardar') }}" id="form-guardar" style="display:none">
        @csrf
        <input type="hidden" name="payment_method_id" id="payment-method-id">
    </form>

    <script>
        const stripe   = Stripe('{{ config('services.stripe.key') }}');
        const elements = stripe.elements();

        const cardElement = elements.create('card', {
            style: {
                base: {
                    fontFamily: '"Instrument Sans", sans-serif',
                    fontSize: '16px',
                    color: '#111',
                    '::placeholder': { color: '#aaa' },
                },
                invalid: { color: '#d41b11' },
            },
        });

        cardElement.mount('#card-element');

        cardElement.on('change', function(event) {
            document.getElementById('card-errors').textContent = event.error ? event.error.message : '';
        });

        async function guardarTarjeta() {
            const btn = document.getElementById('btn-guardar');
            btn.disabled = true;
            btn.textContent = 'Guardando...';

            const clientSecret = '{{ $clientSecret }}';

            const { setupIntent, error } = await stripe.confirmCardSetup(clientSecret, {
                payment_method: { card: cardElement },
            });

            if (error) {
                document.getElementById('card-errors').textContent = error.message;
                btn.disabled = false;
                btn.textContent = 'Guardar tarjeta';
                return;
            }

            document.getElementById('payment-method-id').value = setupIntent.payment_method;
            document.getElementById('form-guardar').submit();
        }
    </script>
</body>

</html>
