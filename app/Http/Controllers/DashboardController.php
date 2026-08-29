<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Services\EventRecommendationService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(EventRecommendationService $recommendationService)
    {
        $user = auth()->user();

        // Organizer stats
        $myEventsCount = Event::where('user_id', $user->id)->count();
        $upcomingEventsCount = Event::where('user_id', $user->id)
            ->where('event_date', '>=', now())
            ->where('status', 'published')
            ->count();
        $totalRegistrations = Registration::whereIn('event_id', function ($q) use ($user) {
            $q->select('id')->from('events')->where('user_id', $user->id);
        })->count();
        $totalAttendees = Registration::whereIn('event_id', function ($q) use ($user) {
            $q->select('id')->from('events')->where('user_id', $user->id);
        })->where('status', 'attended')->count();

        // My registrations as attendee
        $myRegistrations = Registration::where('user_id', $user->id)
            ->with('event')
            ->latest()
            ->take(5)
            ->get();

        // Recent events by this organizer
        $recentEvents = Event::where('user_id', $user->id)
            ->withCount('registrations')
            ->latest()
            ->take(5)
            ->get();

        // AI-powered event recommendations
        $recommendedEvents = $recommendationService->getRecommendations($user, 6);

        return view('dashboard.index', compact(
            'myEventsCount',
            'upcomingEventsCount',
            'totalRegistrations',
            'totalAttendees',
            'myRegistrations',
            'recentEvents',
            'recommendedEvents'
        ));
    }
}
