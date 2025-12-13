<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SorteioRealizado extends Notification
{
    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }
    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Sorteio Realizado!')
            ->icon('/pwa-192x192.png')
            ->body('Venha ver quem é seu Amigo Secreto!')
            ->action('Ver agora', 'view_app')
            ->data(['url' => '/dashboard']); // Url para abrir ao clicar
    }
}
