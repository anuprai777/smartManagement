<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Certificate>
 */
class CertificateFactory extends Factory
{
    public function definition(): array
    {
        $registration = Registration::inRandomOrder()->first() ?? Registration::factory();
        $certNumber = 'CERT-' . strtoupper(Str::random(10));

        return [
            'event_id' => $registration instanceof Registration ? $registration->event_id : Event::factory(),
            'user_id' => $registration instanceof Registration ? $registration->user_id : User::factory(),
            'registration_id' => $registration,
            'certificate_number' => $certNumber,
            'certificate_path' => 'certificates/' . $certNumber . '.pdf',
            'issued_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
            //
        ];
    }
}
