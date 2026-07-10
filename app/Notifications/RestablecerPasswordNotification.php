<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class RestablecerPasswordNotification extends ResetPassword
{
    protected function resetUrl(mixed $notifiable): string
    {
        return route('cliente.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $url    = $this->resetUrl($notifiable);
        $nombre = $notifiable->persona?->per_nombre ?? $notifiable->name;

        return (new MailMessage)
            ->subject('Restablece tu contraseña — LocalApp')
            ->greeting("Hola {$nombre} 👋")
            ->line('Recibimos una solicitud para restablecer la contraseña de tu cuenta.')
            ->line('Haz clic en el botón para crear una nueva contraseña.')
            ->action('Crear nueva contraseña', $url)
            ->line('Este enlace expira en 60 minutos.')
            ->line('Si no solicitaste esto, puedes ignorar este mensaje con tranquilidad.')
            ->salutation('El equipo de LocalApp');
    }
}
