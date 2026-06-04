<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Event;
use App\Models\Registration;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\EventRegistrationNotification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ─── Create users ───────────────────────────────────────────────
        $admin = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $users = User::factory(10)->create();

        $allUsers = collect([$admin, ...$users]);

        // ─── Events ─────────────────────────────────────────────────────
        // 5 events for the admin (various statuses)
        $adminEvents = Event::factory(5)
            ->sequence(
                ['title' => 'Annual Tech Summit 2026',   'status' => 'published', 'event_date' => now()->addDays(30),  'registration_deadline' => now()->addDays(25)],
                ['title' => 'Web Development Bootcamp',   'status' => 'published', 'event_date' => now()->addDays(60),  'registration_deadline' => now()->addDays(50)],
                ['title' => 'AI & Machine Learning Conf', 'status' => 'draft',     'event_date' => now()->addDays(90),  'registration_deadline' => now()->addDays(80)],
                ['title' => 'Startup Pitch Night',         'status' => 'published', 'event_date' => now()->addDays(15),  'registration_deadline' => now()->addDays(10)],
                ['title' => 'Digital Marketing Workshop',  'status' => 'completed','event_date' => now()->subDays(10),  'registration_deadline' => now()->subDays(17)],
            )
            ->create(['user_id' => $admin->id]);

        // 5 events from other users
        $otherEvents = Event::factory(5)
            ->sequence(fn ($seq) => [
                'user_id' => $allUsers->skip(($seq->index % 10) + 1)->first()->id,
                'status' => 'published',
            ])
            ->create();

        $allEvents = collect([...$adminEvents, ...$otherEvents]);

        // ─── Registrations & Tickets ────────────────────────────────────
        // Each published/completed event gets registrations (max available users)
        foreach ($allEvents->whereIn('status', ['published', 'completed']) as $event) {
            $maxCount = min($allUsers->count(), $event->capacity > 0 ? $event->capacity : $allUsers->count());
            $regCount = fake()->numberBetween(3, $maxCount);

            $registrants = $allUsers->random($regCount);

            foreach ($registrants as $registrant) {
                $ticketNumber = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $event->title), 0, 3))
                    . '-' . strtoupper(fake()->bothify('????????'))
                    . '-' . $event->id;

                $isAttended = $event->status === 'completed' && fake()->boolean(80);
                $isCancelled = fake()->boolean(10);

                $status = $isCancelled ? 'cancelled' : ($isAttended ? 'attended' : 'registered');
                $checkedIn = $isAttended ? fake()->dateTimeBetween($event->event_date, $event->event_date->format('Y-m-d') . ' 23:59:59') : null;

                $registration = Registration::create([
                    'event_id' => $event->id,
                    'user_id' => $registrant->id,
                    'ticket_number' => $isCancelled ? $ticketNumber : $ticketNumber,
                    'status' => $status,
                    'checked_in_at' => $checkedIn,
                ]);

                $qrData = json_encode([
                    'ticket' => $ticketNumber,
                    'event' => $event->id,
                    'user' => $registrant->id,
                    'email' => $registrant->email,
                ]);

                $ticketStatus = $isCancelled ? 'cancelled' : ($isAttended ? 'used' : 'active');

                Ticket::create([
                    'registration_id' => $registration->id,
                    'event_id' => $event->id,
                    'user_id' => $registrant->id,
                    'ticket_number' => $ticketNumber,
                    'qr_code_data' => $qrData,
                    'qr_code_path' => null,
                    'status' => $ticketStatus,
                    'scanned_at' => $checkedIn,
                ]);
            }
        }

        // ─── Certificates for attended registrations ───────────────────
        $attendedRegistrations = Registration::where('status', 'attended')->get();

        foreach ($attendedRegistrations as $reg) {
            $certNumber = 'CERT-' . strtoupper(fake()->bothify('??????????'));

            Certificate::create([
                'event_id' => $reg->event_id,
                'user_id' => $reg->user_id,
                'registration_id' => $reg->id,
                'certificate_number' => $certNumber,
                'certificate_path' => null, // No actual PDF generated for seed data
                'issued_at' => $reg->checked_in_at ?? now(),
            ]);
        }

        // ─── Notifications ──────────────────────────────────────────────
        // Notify admin about registrations
        $adminRegistrations = Registration::whereIn('event_id', $adminEvents->pluck('id'))
            ->where('user_id', '!=', $admin->id)
            ->latest()
            ->take(10)
            ->get();

        foreach ($adminRegistrations as $reg) {
            $admin->notify(new EventRegistrationNotification($reg->event, $reg->user, 'new_registration'));
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info("Users: " . User::count());
        $this->command->info("Events: " . Event::count());
        $this->command->info("Registrations: " . Registration::count());
        $this->command->info("Tickets: " . Ticket::count());
        $this->command->info("Certificates: " . Certificate::count());
    }
}
