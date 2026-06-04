<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Registration>
 */
class RegistrationFactory extends Factory
{
    public function definition(): array
    {
        $event = Event::inRandomOrder()->first() ?? Event::factory();
        $eventId = $event instanceof Event ? $event->id : null;

        return [
            'event_id' => $event,
            'user_id' => User::factory(),
            'ticket_number' => strtoupper(Str::random(3)) . '-' . strtoupper(Str::random(8)) . '-' . $eventId,
            'status' => 'registered',
            'checked_in_at' => null,
        ];
    }

    public function attended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'attended',
            'checked_in_at' => fake()->dateTimeBetween('-1 day', 'now'),
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
