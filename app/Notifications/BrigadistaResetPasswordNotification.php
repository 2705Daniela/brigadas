<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BrigadistaResetPasswordNotification extends Notification
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
    $url = url(route('brigadistas.password.reset', [
        'token' => $this->token,
        'email' => $notifiable->correo,
    ], false));

    return (new MailMessage)
                ->subject('Recuperación de Contraseña')
                ->markdown('brigadistas.passwords.reset_password', [
                    'url' => $url,
                    'notifiable' => $notifiable
                ]);
}

}
