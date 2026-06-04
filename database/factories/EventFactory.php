<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->randomElement([
            'Annual Tech Summit 2026',
            'Web Development Bootcamp',
            'AI & Machine Learning Conference',
            'Digital Marketing Workshop',
            'Startup Pitch Night',
            'Cloud Computing Expo',
            'Cybersecurity Awareness Seminar',
            'Data Science Hackathon',
            'UX Design Masterclass',
            'Blockchain & Crypto Forum',
            'Photography Exhibition',
            'Music Production Workshop',
            'Entrepreneurship Meetup',
            'Mobile App Development Sprint',
            'DevOps & CI/CD Conference',
            'Open Source Contribution Day',
            'Game Development Jam',
            'IoT & Smart Devices Expo',
            'E-commerce Strategies Workshop',
            'Leadership & Management Summit',
        ]);

        $startDate = fake()->dateTimeBetween('-2 months', '+4 months');
        $deadline = (clone $startDate)->modify('-7 days');

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'description' => fake()->paragraphs(3, true),
            'venue' => fake()->randomElement([
                'Kathmandu Convention Center',
                'Hotel Yak & Yeti, Kathmandu',
                'Soaltee Hotel, Kathmandu',
                'Online (Zoom)',
                'Baneshwor Hall, Kathmandu',
                'Pulchowk Engineering Campus',
                'Tribhuvan University Auditorium',
                'Bouddha Community Center',
                'Lakeside Resort, Pokhara',
                'Hyatt Regency, Kathmandu',
            ]),
            'event_date' => $startDate,
            'registration_deadline' => $deadline,
            'capacity' => fake()->randomElement([50, 100, 150, 200, 300, 500]),
            'status' => fake()->randomElement(['draft', 'published', 'published', 'published', 'completed']),
            'banner_image' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'event_date' => fake()->dateTimeBetween('-3 months', '-1 day'),
        ]);
    }

    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'event_date' => fake()->dateTimeBetween('+1 day', '+3 months'),
        ]);
    }
}
