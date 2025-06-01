<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\File;

class NuevaSolicitudUsuario extends Notification
{
    use Queueable;

    protected $nuevoUsuario;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $nuevoUsuario)
    {
        $this->nuevoUsuario = $nuevoUsuario;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $enlaceSolicitudes = url(route('users.solicitudes'));

        return (new MailMessage)
            ->subject('Nueva solicitud de acceso - CyberStock WMS')
            ->view('emails.nueva-solicitud', [
                'notifiable' => $notifiable,
                'user' => $this->nuevoUsuario,
                'enlaceSolicitudes' => $enlaceSolicitudes
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
