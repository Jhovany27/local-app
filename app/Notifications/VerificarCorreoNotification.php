<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerificarCorreoNotification extends VerifyEmail
{
    public function toMail(mixed $notifiable): MailMessage
    {
        $url    = $this->verificationUrl($notifiable);
        $nombre = $notifiable->persona?->per_nombre ?? $notifiable->name;

        return (new MailMessage)
            ->subject('Confirma tu correo — LocalApp')
            ->greeting("¡Hola {$nombre}! 👋")
            ->line('Estás a un paso de unirte a LocalApp.')
            ->line('Solo necesitamos confirmar que este correo te pertenece.')
            ->action('Verificar mi correo', $url)
            ->line('Este enlace expira en 60 minutos.')
            ->line('Si no creaste una cuenta, puedes ignorar este mensaje.')
            ->salutation('Con cariño, el equipo de LocalApp 💚');
    }
}
