<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends Notification
{
    use Queueable;

    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $appUrl = config('app.url') ?: url('/');
        $resetUrl = rtrim($appUrl, '/') . '/reset-password?token=' . $this->token . '&email=' . urlencode($notifiable->email);

        return (new MailMessage)
            ->subject('Redefinição de senha')
            ->greeting('Olá ' . ($notifiable->name ?? ''))
            ->line('Recebemos uma solicitação para redefinir sua senha. Use o botão abaixo para continuar.')
            ->action('Redefinir senha', $resetUrl)
            ->line('Se preferir, utilize este token diretamente:')
            ->line($this->token)
            ->line('Se você não solicitou a redefinição, ignore esta mensagem.');
    }
}
