<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Restablecimiento de contraseña')
            ->line('Estás recibiendo este correo porque se solicitó un restablecimiento de contraseña para tu cuenta.')
            ->action('Restablecer Contraseña', url(route('password.reset', $this->token, false)))
            ->line('Si no solicitaste el cambio, no se requiere ninguna acción adicional.');
    }
}
