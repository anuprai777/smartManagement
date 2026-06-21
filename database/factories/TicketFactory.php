<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Registration;
use App\Models\Ticket;
use App\Models\User;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Generate the QR code image after the ticket is created.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Ticket $ticket) {
            try {
                if (!$ticket->qr_code_data) {
                    return;
                }

                $result = (new Builder(
                    writer: new PngWriter(),
                    data: $ticket->qr_code_data,
                    encoding: new Encoding('UTF-8'),
                    errorCorrectionLevel: ErrorCorrectionLevel::High,
                    size: 300,
                    margin: 10,
                ))->build();

                $filename = 'qr-codes/' . $ticket->ticket_number . '.png';
                Storage::disk('public')->put($filename, $result->getString());

                $ticket->update(['qr_code_path' => $filename]);
            } catch (\Exception $e) {
                // Silently fail — the command `tickets:regenerate-qr` can fix later
            }
        });
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
