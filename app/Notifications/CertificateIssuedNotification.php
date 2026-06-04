<?php

namespace App\Notifications;

use App\Models\Certificate;
use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CertificateIssuedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Event $event,
        public Certificate $certificate
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Certificate Issued',
            'message' => "Your certificate for \"{$this->event->title}\" is now available for download.",
            'event_id' => $this->event->id,
            'action_url' => route('certificates.index'),
            'icon' => 'award',
        ];
    }
}
