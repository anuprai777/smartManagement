<?php

namespace App\Http\Controllers;

use App\Models\UserPreference;
use App\Services\EventRecommendationService;
use Illuminate\Http\Request;

class PreferencesController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $preference = $user->preference;

        // Get auto-detected interests
        $autoKeywords = $preference?->preferred_keywords ?? [];
        $manualKeywords = $preference?->manual_keywords ?? [];

        // Also collect the events that influenced auto keywords
        $influencingEvents = $user->registrations()
            ->with('event')
            ->whereIn('status', ['registered', 'attended'])
            ->latest()
            ->get()
            ->pluck('event')
            ->filter();

        return view('preferences.edit', compact(
            'autoKeywords',
            'manualKeywords',
            'influencingEvents'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'keywords' => 'nullable|string|max:500',
        ]);

        $user = auth()->user();

        // Parse comma or space-separated keywords into a clean array
        $raw = $validated['keywords'] ?? '';
        $keywords = collect(preg_split('/[,\n]+/', $raw))
            ->map(fn ($k) => trim(mb_strtolower($k)))
            ->filter(fn ($k) => strlen($k) > 1)
            ->unique()
            ->values()
            ->toArray();

        // Upsert — preserve auto-extracted keywords, only update manual ones
        UserPreference::updateOrCreate(
            ['user_id' => $user->id],
            ['manual_keywords' => $keywords]
        );

        return redirect()->route('preferences.edit')
            ->with('success', 'Your interests have been updated! Recommendations will now reflect your preferences.');
    }
}
