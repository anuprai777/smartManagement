<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    public function definition(): array
    {
        $registration = Registration::inRandomOrder()->first() ?? Registration::factory();
        $registrationId = $registration instanceof Registration ? $registration->id : null;
        $ticketNumber = $registration instanceof Registration ? $registration->ticket_number : strtoupper(fake()->bothify('???-########-?'));

        return [
            'registration_id' => $registration,
            'event_id' => $registration instanceof Registration ? $registration->event_id : Event::factory(),
            'user_id' => $registration instanceof Registration ? $registration->user_id : User::factory(),
            'ticket_number' => $ticketNumber,
            'qr_code_data' => json_encode([
                'ticket' => $ticketNumber,
                'event' => $registration instanceof Registration ? $registration->event_id : 1,
                'user' => $registration instanceof Registration ? $registration->user_id : 1,
                'email' => fake()->email(),
            ]),
            'qr_code_path' => null,
            'status' => 'active',
            'scanned_at' => null,
        ];
    }

    public function used(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'used',
            'scanned_at' => fake()->dateTimeBetween('-1 day', 'now'),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }
}
            //
        ];
    }
}
