<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\EventRecommendationService;
use Illuminate\Console\Command;

class BuildUserPreferences extends Command
{
    protected $signature = 'recommendations:build-preferences
                           {--user= : Build preferences for a specific user ID only}';

    protected $description = 'Build or refresh AI preference profiles for all (or a specific) user based on registration history';

    public function handle(EventRecommendationService $service): int
    {
        $userId = $this->option('user');

        $query = User::query();
        if ($userId) {
            $query->where('id', $userId);
        }

        $users = $query->get();
        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        foreach ($users as $user) {
            $service->buildUserPreferences($user);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Preferences built for {$users->count()} user(s).");

        return Command::SUCCESS;
    }
}
