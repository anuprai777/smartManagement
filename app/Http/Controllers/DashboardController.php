<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\Certificate;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
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

        // Monthly registration trend (last 6 months)
        $monthlyTrend = Registration::whereIn('event_id', function ($q) use ($user) {
            $q->select('id')->from('events')->where('user_id', $user->id);
        })
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        return view('dashboard.index', compact(
            'myEventsCount',
            'upcomingEventsCount',
            'totalRegistrations',
            'totalAttendees',
            'myRegistrations',
            'recentEvents',
            'monthlyTrend'
        ));
    }
}
