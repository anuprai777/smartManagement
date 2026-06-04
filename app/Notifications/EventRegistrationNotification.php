<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EventRegistrationNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Event $event,
        public User $attendee,
        public string $type = 'new_registration'
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        if ($this->type === 'new_registration') {
            return [
                'title' => 'New Registration',
                'message' => "{$this->attendee->name} has registered for \"{$this->event->title}\".",
                'event_id' => $this->event->id,
                'action_url' => route('events.show', $this->event),
                'icon' => 'user-plus',
            ];
        }

        return [
            'title' => 'Registration Confirmed',
            'message' => "You have successfully registered for \"{$this->event->title}\".",
            'event_id' => $this->event->id,
            'action_url' => route('registrations.my'),
            'icon' => 'check-circle',
        ];
    }
}
