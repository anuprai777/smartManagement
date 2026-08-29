<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Database\Seeders\Helpers\EventBannerGenerator;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Rich, realistic event definitions grouped by category.
     * Each entry: [title, description_suffix, venue, capacity, category]
     */
    protected array $eventDefinitions = [
        // ─── Technology & Computing ─────────────────────────────────────
        'tech' => [
            ['Annual Tech Summit 2026', 'Featuring keynotes from industry leaders, breakout sessions on emerging technologies, and networking opportunities with 500+ tech professionals.', 'Kathmandu Convention Center', 500, 'tech'],
            ['Web Development Bootcamp', 'An intensive hands-on bootcamp covering modern web frameworks, APIs, responsive design, and deployment. Bring your laptop and build a full-stack app in 3 days.', 'Pulchowk Engineering Campus', 100, 'tech'],
            ['AI & Machine Learning Conference', 'Deep dive into neural networks, natural language processing, computer vision, and ML ops. Workshops from leading AI researchers and practitioners.', 'Hotel Yak & Yeti, Kathmandu', 300, 'tech'],
            ['Cloud Computing Expo', 'Explore AWS, Azure, and GCP with live demos, architecture reviews, and cost-optimization strategies for enterprises and startups alike.', 'Soaltee Hotel, Kathmandu', 200, 'tech'],
            ['Cybersecurity Awareness Seminar', 'Learn about ethical hacking, penetration testing, data protection laws, and how to build a security-first culture in your organization.', 'Baneshwor Hall, Kathmandu', 150, 'tech'],
            ['Data Science Hackathon', 'A 48-hour competition to solve real-world problems using data. Teams will work with datasets from partner organizations. Prizes for top 3 teams.', 'Tribhuvan University Auditorium', 200, 'tech'],
            ['DevOps & CI/CD Conference', 'Continuous integration, delivery, infrastructure as code, monitoring, and observability. Real-world case studies from companies that made the transition.', 'Kathmandu Convention Center', 250, 'tech'],
            ['Open Source Contribution Day', 'A full-day event dedicated to contributing to open-source projects. Mentors available for beginners. Projects in Python, JavaScript, Rust, and Go.', 'Pulchowk Engineering Campus', 100, 'tech'],
            ['Mobile App Development Sprint', 'Build a cross-platform mobile app from scratch using Flutter or React Native in this 2-day sprint. Includes UI/UX design and app store deployment.', 'Bouddha Community Center', 80, 'tech'],
            ['Game Development Jam', 'Create a playable game in 48 hours! Teams of 2-4 people. All skill levels welcome. Engines: Unity, Godot, or Phaser.', 'Lakeside Resort, Pokhara', 120, 'tech'],
            ['IoT & Smart Devices Expo', 'Showcasing the latest in Internet of Things — smart home, wearables, industrial IoT, and connected vehicles. Live demos and hands-on workshops.', 'Hyatt Regency, Kathmandu', 350, 'tech'],
            ['Women in Tech Leadership Forum', 'Empowering women in technology through mentorship, panel discussions, and skill-building workshops. Open to all genders.', 'Hotel Yak & Yeti, Kathmandu', 200, 'tech'],
            ['Rust Programming Workshop', 'A hands-on introduction to Rust — memory safety, concurrency, and systems programming. Perfect for experienced developers looking to expand their skills.', 'Baneshwor Hall, Kathmandu', 60, 'tech'],
            ['Blockchain & Crypto Forum', 'Understanding blockchain technology, cryptocurrencies, DeFi, NFTs, and smart contracts. Regulatory landscape and future trends.', 'Soaltee Hotel, Kathmandu', 250, 'tech'],
        ],

        // ─── Business & Entrepreneurship ────────────────────────────────
        'business' => [
            ['Startup Pitch Night', 'Watch 10 startups pitch to a panel of investors and industry experts. Networking reception afterwards. Bring your business cards!', 'Hotel Yak & Yeti, Kathmandu', 200, 'business'],
            ['E-commerce Strategies Workshop', 'Master online selling — from Shopify store setup to digital marketing, conversion optimization, and cross-border e-commerce.', 'Baneshwor Hall, Kathmandu', 100, 'business'],
            ['Leadership & Management Summit', 'Develop your leadership style, manage remote teams, drive organizational change, and build a culture of innovation.', 'Kathmandu Convention Center', 300, 'business'],
            ['Social Entrepreneurship Meetup', 'Connect with changemakers who are building businesses that make a difference. Impact measurement, funding, and scaling strategies.', 'Bouddha Community Center', 80, 'business'],
            ['Freelancing & Remote Work Conference', 'Navigate the world of freelancing — finding clients, pricing your work, managing finances, and maintaining work-life balance.', 'Online (Zoom)', 500, 'business'],
            ['Digital Marketing Masterclass', 'SEO, content marketing, social media advertising, email campaigns, and analytics. Real case studies from successful campaigns.', 'Pulchowk Engineering Campus', 150, 'business'],
            ['Investors & Founders Networking', 'An exclusive evening mixer for startup founders and angel investors. Speed networking followed by open discussions.', 'Hyatt Regency, Kathmandu', 100, 'business'],
            ['Business Plan Competition', 'Present your business plan to a panel of judges. Grand prize: $10,000 seed funding plus 3 months of mentorship.', 'Soaltee Hotel, Kathmandu', 150, 'business'],
            ['Financial Literacy Workshop', 'Learn personal and business finance — budgeting, investing, tax planning, and financial statement analysis for non-accountants.', 'Baneshwor Hall, Kathmandu', 120, 'business'],
            ['Supply Chain & Logistics Forum', 'Optimizing supply chains with technology, sustainable logistics, warehouse automation, and last-mile delivery innovations.', 'Kathmandu Convention Center', 200, 'business'],
        ],

        // ─── Creative Arts & Design ─────────────────────────────────────
        'design' => [
            ['UX Design Masterclass', 'User research, wireframing, prototyping, usability testing, and design systems. Hands-on Figma workshop included.', 'Lakeside Resort, Pokhara', 80, 'design'],
            ['Photography Exhibition', 'A curated exhibition featuring 50+ works from established and emerging photographers. Theme: "Faces of Nepal".', 'Bouddha Community Center', 300, 'design'],
            ['Graphic Design Workshop', 'Typography, color theory, branding, and layout design. Learn from award-winning designers in this intensive 2-day workshop.', 'Pulchowk Engineering Campus', 60, 'design'],
            ['Film Making Workshop', 'From script to screen — storytelling, cinematography, directing, editing, and distribution. Hands-on with professional equipment.', 'Tribhuvan University Auditorium', 100, 'design'],
            ['Fashion Design Showcase', 'Emerging designers present their collections. Includes a runway show, behind-the-scenes exhibition, and networking with industry insiders.', 'Hyatt Regency, Kathmandu', 400, 'design'],
            ['Interior Design Symposium', 'Residential and commercial interior design trends, sustainable materials, space planning, and client management.', 'Hotel Yak & Yeti, Kathmandu', 150, 'design'],
            ['Animation & Motion Graphics Workshop', '2D and 3D animation techniques using Blender and After Effects. Character design, rigging, and visual effects.', 'Baneshwor Hall, Kathmandu', 70, 'design'],
            ['Calligraphy & Lettering Exhibition', 'Traditional and modern calligraphy, hand lettering, and typography art. Live demonstrations and beginner workshops.', 'Bouddha Community Center', 100, 'design'],
        ],

        // ─── Music & Entertainment ──────────────────────────────────────
        'music' => [
            ['Music Production Workshop', 'Learn beat-making, mixing, mastering, and music production using Ableton Live and FL Studio. Studio time included.', 'Baneshwor Hall, Kathmandu', 50, 'music'],
            ['Jazz & Blues Night', 'An evening of live jazz and blues featuring renowned local and international artists. Dinner and drinks included.', 'Hyatt Regency, Kathmandu', 300, 'music'],
            ['Indie Music Festival', 'A 2-day outdoor festival featuring 20+ indie bands across multiple genres. Camping available. Food trucks and art installations.', 'Lakeside Resort, Pokhara', 1000, 'music'],
            ['Classical Music Concert', 'Orchestral performances of classical masterpieces by the National Symphony Orchestra. Formal attire recommended.', 'Tribhuvan University Auditorium', 500, 'music'],
            ['DJ & Electronic Music Workshop', 'Learn DJ techniques, electronic music production, and live performance skills. Equipment provided.', 'Soaltee Hotel, Kathmandu', 80, 'music'],
            ['Songwriting Retreat', 'A weekend retreat focused on songwriting, lyricism, and musical collaboration. Set in a serene mountain resort.', 'Lakeside Resort, Pokhara', 40, 'music'],
        ],

        // ─── Health & Wellness ──────────────────────────────────────────
        'health' => [
            ['Yoga & Meditation Retreat', 'A 3-day retreat focusing on mindfulness, yoga, meditation, and holistic wellness. Suitable for all levels.', 'Lakeside Resort, Pokhara', 60, 'health'],
            ['Mental Health Awareness Seminar', 'Understanding mental health, coping strategies, supporting colleagues, and reducing stigma. Expert speakers and support resources.', 'Kathmandu Convention Center', 200, 'health'],
            ['Nutrition & Healthy Living Expo', 'Interactive exhibits on nutrition, meal planning, superfoods, and healthy cooking demos by professional chefs.', 'Soaltee Hotel, Kathmandu', 300, 'health'],
            ['Fitness Bootcamp Challenge', 'A high-energy outdoor fitness challenge with professional trainers. Includes HIIT, strength training, and team competitions.', 'Pulchowk Engineering Campus', 100, 'health'],
            ['Ayurveda & Natural Healing Workshop', 'Traditional Ayurvedic wisdom for modern living. Herbal remedies, daily routines, seasonal detox, and mind-body balance.', 'Bouddha Community Center', 80, 'health'],
            ['First Aid & Emergency Response Training', 'Certified first aid training including CPR, wound care, emergency response, and disaster preparedness. Certificate upon completion.', 'Baneshwor Hall, Kathmandu', 50, 'health'],
        ],

        // ─── Education & Learning ───────────────────────────────────────
        'education' => [
            ['Public Speaking & Communication Workshop', 'Overcome stage fright, structure compelling presentations, and engage any audience. Video feedback and coaching included.', 'Hotel Yak & Yeti, Kathmandu', 100, 'education'],
            ['Career Development Fair', 'Connect with recruiters from top companies. Resume reviews, mock interviews, and career counseling sessions available.', 'Tribhuvan University Auditorium', 500, 'education'],
            ['Language Learning Intensive', 'Immersion-based language learning — choose from English, Japanese, Korean, or Spanish. 4-day intensive with native speakers.', 'Pulchowk Engineering Campus', 80, 'education'],
            ['Creative Writing Workshop', 'Fiction, poetry, creative non-fiction, and journaling. Nurture your voice with guidance from published authors.', 'Bouddha Community Center', 50, 'education'],
            ['STEM Education for Kids', 'Fun, hands-on science and technology activities for children aged 8-14. Robotics, coding, chemistry experiments, and more.', 'Tribhuvan University Auditorium', 150, 'education'],
            ['Study Abroad Information Session', 'Guidance on university applications, scholarships, visas, and cultural adaptation for students planning to study overseas.', 'Hotel Yak & Yeti, Kathmandu', 200, 'education'],
        ],

        // ─── Community & Social ─────────────────────────────────────────
        'social' => [
            ['Community Clean-Up Drive', 'Join us for a city-wide clean-up initiative. Gloves and bags provided. Refreshments for all volunteers. Family-friendly.', 'Kathmandu Convention Center', 200, 'social'],
            ['Cultural Heritage Walk', 'A guided walking tour through heritage sites with historians and local storytellers. Discover hidden gems and untold stories.', 'Bouddha Community Center', 60, 'social'],
            ['Volunteer Networking Mixer', 'Connect with NGOs and volunteer organizations looking for skilled professionals. Find your cause and make a difference.', 'Hotel Yak & Yeti, Kathmandu', 150, 'social'],
            ['Food Festival & Charity Gala', 'A celebration of local cuisine with food stalls from 30+ vendors. Proceeds go to supporting underprivileged children\'s education.', 'Soaltee Hotel, Kathmandu', 500, 'social'],
            ['Intergenerational Dialogue Forum', 'Bridging the gap between generations — meaningful conversations about technology, values, and the future of our community.', 'Baneshwor Hall, Kathmandu', 100, 'social'],
            ['Environmental Sustainability Summit', 'Climate action, renewable energy, waste management, and sustainable living. Policy discussions and community action planning.', 'Kathmandu Convention Center', 300, 'social'],
            ['Book Fair & Literary Festival', 'Book stalls from major publishers, author signings, panel discussions, and poetry readings. Special discounts for students.', 'Tribhuvan University Auditorium', 400, 'social'],
        ],
    ];

    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Creating sample users...');
            $users = User::factory(15)->create();
        }

        $totalEvents = 0;
        $totalBanners = 0;
        $bar = $this->command->getOutput()->createProgressBar(
            collect($this->eventDefinitions)->flatten(1)->count()
        );
        $bar->start();

        foreach ($this->eventDefinitions as $category => $events) {
            foreach ($events as $index => $def) {
                [$title, $descriptionSuffix, $venue, $capacity, $cat] = $def;

                // Distribute events across users
                $user = $users->get($index % $users->count());

                // Determine date: mix of past (completed), upcoming, and draft
                $dateOffset = match (true) {
                    $index % 5 === 0 => now()->subDays(rand(5, 60)),       // past (completed)
                    $index % 7 === 0 => now()->addDays(rand(120, 180)),    // far future (draft)
                    default          => now()->addDays(rand(3, 90)),       // upcoming (published)
                };

                $isPast = $dateOffset->isPast();
                $status = match (true) {
                    $isPast            => 'completed',
                    $index % 7 === 0   => 'draft',
                    default            => 'published',
                };

                $deadline = $isPast
                    ? $dateOffset->copy()->subDays(rand(3, 10))
                    : $dateOffset->copy()->subDays(rand(5, 20));

                // Generate a beautiful banner image
                $bannerPath = EventBannerGenerator::generate($title, $cat);

                Event::create([
                    'user_id' => $user->id,
                    'title' => $title,
                    'description' => "Join us for an unforgettable event: {$title}. {$descriptionSuffix}",
                    'venue' => $venue,
                    'venue_type' => str_contains(strtolower($venue), 'online') ? 'online' : 'offline',
                    'event_date' => $dateOffset,
                    'registration_deadline' => $deadline,
                    'capacity' => $capacity,
                    'status' => $status,
                    'visibility' => 'public',
                    'banner_image' => $bannerPath,
                ]);

                $totalEvents++;
                if ($bannerPath) {
                    $totalBanners++;
                }
                $bar->advance();
            }
        }

        $bar->finish();
        $this->command->newLine(2);
        $this->command->info("✅ Created {$totalEvents} events with {$totalBanners} generated banner images.");
    }
}
