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
            'is_admin' => true,
        ]);

        $admin2 = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);

        // Create variety of users with different names for realism
        $regularUsers = User::factory()->createMany([
            ['name' => 'Alice Sharma',    'email' => 'alice@example.com'],
            ['name' => 'Bob Thapa',       'email' => 'bob@example.com'],
            ['name' => 'Carina Gurung',   'email' => 'carina@example.com'],
            ['name' => 'Dev Bahadur',     'email' => 'dev@example.com'],
            ['name' => 'Esha Rana',       'email' => 'esha@example.com'],
            ['name' => 'Frank Limbu',     'email' => 'frank@example.com'],
            ['name' => 'Gita Poudel',     'email' => 'gita@example.com'],
            ['name' => 'Hari Adhikari',   'email' => 'hari@example.com'],
            ['name' => 'Isha Koirala',    'email' => 'isha@example.com'],
            ['name' => 'Jack Tamang',     'email' => 'jack@example.com'],
            ['name' => 'Kiran Neupane',   'email' => 'kiran@example.com'],
            ['name' => 'Laxmi Joshi',     'email' => 'laxmi@example.com'],
            ['name' => 'Mohan Rai',       'email' => 'mohan@example.com'],
            ['name' => 'Nisha Basnet',    'email' => 'nisha@example.com'],
            ['name' => 'Om Singh',        'email' => 'om@example.com'],
        ]);

        $allUsers = collect([$admin, $admin2, ...$regularUsers]);

        // ─── Events (50+ with generated banner images) ──────────────────
        $this->call(EventSeeder::class);

        $allEvents = Event::all();

        // ─── Registrations & Tickets ────────────────────────────────────
        $publishedEvents = $allEvents->whereIn('status', ['published', 'completed']);
        $registeredUserIds = [];

        foreach ($publishedEvents as $event) {
            $maxCount = min($allUsers->count(), $event->capacity > 0 ? min($event->capacity, 20) : $allUsers->count());
            $regCount = fake()->numberBetween(3, max(3, $maxCount));
            $registrants = $allUsers->random($regCount);

            foreach ($registrants as $registrant) {
                $ticketNumber = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $event->title), 0, 3))
                    . '-' . strtoupper(fake()->bothify('????????'))
                    . '-' . $event->id;

                $isAttended = $event->status === 'completed' && fake()->boolean(80);
                $isCancelled = fake()->boolean(10);

                $status = $isCancelled ? 'cancelled' : ($isAttended ? 'attended' : 'registered');
                $checkedIn = $isAttended
                    ? fake()->dateTimeBetween($event->event_date, $event->event_date->format('Y-m-d') . ' 23:59:59')
                    : null;

                $registration = Registration::create([
                    'event_id' => $event->id,
                    'user_id' => $registrant->id,
                    'ticket_number' => $ticketNumber,
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

                $registeredUserIds[$registrant->id] = true;
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
                'certificate_path' => null,
                'issued_at' => $reg->checked_in_at ?? now(),
            ]);
        }

        // ─── Notifications ──────────────────────────────────────────────
        $adminRegistrations = Registration::whereIn('event_id', $allEvents->where('user_id', $admin->id)->pluck('id'))
            ->where('user_id', '!=', $admin->id)
            ->latest()
            ->take(10)
            ->get();

        foreach ($adminRegistrations as $reg) {
            $admin->notify(new EventRegistrationNotification($reg->event, $reg->user, 'new_registration'));
        }

        // ─── Summary ────────────────────────────────────────────────────
        $this->command->info('────────────────────────────────────────');
        $this->command->info('  ✅ Database seeded successfully!');
        $this->command->info('────────────────────────────────────────');
        $this->command->info("  Users:          " . User::count());
        $this->command->info("  Events:         " . Event::count());
        $this->command->info("  Registrations:  " . Registration::count());
        $this->command->info("  Tickets:        " . Ticket::count());
        $this->command->info("  Certificates:   " . Certificate::count());
        $this->command->info('────────────────────────────────────────');
    }
}
