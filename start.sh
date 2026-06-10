#!/bin/bash

cd ~/proyectos/local_app

alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
export PATH="$HOME/.local/bin:$PATH"

echo " Levantando contenedores..."
sh vendor/bin/sail up -d

echo " Esperando que los servicios inicien..."
sleep 3

echo " Iniciando Vite..."
sh vendor/bin/sail npm run dev &

echo " Iniciando Queue Worker..."
sh vendor/bin/sail artisan queue:work &

echo " Iniciando Reverb..."
sh vendor/bin/sail artisan reverb:start &

echo " Iniciando Stripe webhook..."
stripe listen --forward-to localhost/stripe/webhook &

echo ""
echo " Todo corriendo. Presiona Ctrl+C para detener procesos en background."
wait
