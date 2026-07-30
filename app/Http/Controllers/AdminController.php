<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Event;
use App\Models\Registration;
use App\Models\Setting;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ─── Dashboard ──────────────────────────────────────────────────────

    public function dashboard()
    {
        $totalUsers = User::count();
        $totalEvents = Event::count();

        $activeRegistrations = Registration::where('status', 'registered')->count();
        $eventsByStatus = Event::selectRaw('status, count(*) as total')
            ->groupBy('status')->pluck('total', 'status');
        $newUsersThisMonth = User::where('created_at', '>=', now()->subDays(30))->count();
        $upcomingWeekEvents = Event::published()
            ->whereBetween('event_date', [now(), now()->addDays(7)])->count();

        $topOrganizers = User::withCount('events')
            ->whereHas('events')->orderByDesc('events_count')->take(5)->get();

        $recentUsers = User::latest()->take(6)->get();
        $recentRegistrations = Registration::with(['user', 'event'])
            ->latest()->take(8)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalEvents',
            'activeRegistrations', 'eventsByStatus', 'newUsersThisMonth',
            'upcomingWeekEvents', 'topOrganizers',
            'recentUsers', 'recentRegistrations'
        ));
    }

    // ─── Users Management ───────────────────────────────────────────────

    public function users(Request $request)
    {
        $query = User::withCount(['events', 'registrations']);

        if ($search = $request->get('search')) {
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"));
        }

        [$sort, $dir] = $this->applySorting($request, 'users', ['name', 'email', 'created_at', 'events_count', 'registrations_count']);
        $users = $query->orderBy($sort, $dir)->paginate(15)->withQueryString();

        return view('admin.users', compact('users', 'sort', 'dir'));
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        $name = $user->name;
        $user->delete();
        return back()->with('success', "{$name} has been deleted permanently.");
    }

    // ─── Events Management ──────────────────────────────────────────────

    public function events(Request $request)
    {
        $query = Event::with(['organizer'])
            ->withCount(['registrations' => fn ($q) => $q->where('status', 'registered')]);

        if ($search = $request->get('search')) {
            $query->where(fn ($q) => $q->where('title', 'like', "%{$search}%")
                ->orWhere('venue', 'like', "%{$search}%"));
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        [$sort, $dir] = $this->applySorting($request, 'events', ['title', 'event_date', 'status', 'visibility', 'venue', 'registrations_count']);
        $events = $query->orderBy($sort, $dir)->paginate(15)->withQueryString();
        $statusCounts = Event::selectRaw('status, count(*) as total')
            ->groupBy('status')->pluck('total', 'status');

        return view('admin.events', compact('events', 'statusCounts', 'sort', 'dir'));
    }

    public function deleteEvent(Event $event)
    {
        $title = $event->title;
        $event->delete();
        return back()->with('success', "Event \"{$title}\" has been deleted.");
    }

    // ─── Registrations Management ───────────────────────────────────────

    public function registrations(Request $request)
    {
        $period = $request->get('period', 'all');
        $eventId = $request->get('event_id');

        // Date range
        $range = match ($period) {
            'today'       => [now()->startOfDay(), now()->endOfDay()],
            'last_7'      => [now()->subDays(7)->startOfDay(), now()->endOfDay()],
            'last_15'     => [now()->subDays(15)->startOfDay(), now()->endOfDay()],
            'last_30'     => [now()->subDays(30)->startOfDay(), now()->endOfDay()],
            'last_90'     => [now()->subDays(90)->startOfDay(), now()->endOfDay()],
            default       => [null, null],
        };

        // Scope helper
        $scopeDate = fn ($q) => $range[0] && $range[1] ? $q->whereBetween('created_at', [$range[0], $range[1]]) : $q;

        // ─── LEVEL 1: Event list (when no event selected) ───────────────
        if (! $eventId) {
            $events = Event::withCount([
                'registrations as total_count',
                'registrations as attended_count' => fn ($q) => $q->where('status', 'attended')->when($range[0] && $range[1], fn ($q) => $q->whereBetween('created_at', [$range[0], $range[1]])),
                'registrations as cancelled_count' => fn ($q) => $q->where('status', 'cancelled')->when($range[0] && $range[1], fn ($q) => $q->whereBetween('created_at', [$range[0], $range[1]])),
            ])
            ->whereHas('registrations', fn ($q) => $scopeDate($q))
            ->orderBy('total_count', 'desc')
            ->paginate(15)
            ->withQueryString();

            $totalRegistrations = Registration::query()->when($range[0] && $range[1], fn ($q) => $q->whereBetween('created_at', [$range[0], $range[1]]))->count();

            return view('admin.registrations', compact('events', 'period', 'totalRegistrations', 'eventId'));
        }

        // ─── LEVEL 2: Registrations for a specific event ────────────────
        $event = Event::findOrFail($eventId);

        $query = Registration::with(['user', 'ticket'])
            ->where('event_id', $eventId);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $scopeDate($query);

        [$sort, $dir] = $this->applySorting($request, 'registrations', ['status', 'created_at', 'checked_in_at', 'ticket_number']);
        $registrations = $query->orderBy($sort, $dir)->paginate(20)->withQueryString();

        $statusCounts = (clone $query)->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        // Also count other events' registrations for the sidebar context
        $otherEvents = Event::where('id', '!=', $eventId)
            ->withCount(['registrations as total_count' => fn ($q) => $scopeDate($q)])
            ->whereHas('registrations', fn ($q) => $scopeDate($q))
            ->orderByDesc('total_count')
            ->take(5)
            ->get();

        return view('admin.registrations', compact(
            'registrations', 'statusCounts', 'sort', 'dir',
            'period', 'event', 'eventId', 'otherEvents'
        ));
    }

    public function deleteRegistration(Registration $registration)
    {
        $registration->delete();
        return back()->with('success', 'Registration has been deleted.');
    }

    // ─── Tickets Management ─────────────────────────────────────────────

    public function tickets(Request $request)
    {
        $query = Ticket::with(['user', 'event', 'registration']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('event', fn ($e) => $e->where('title', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        [$sort, $dir] = $this->applySorting($request, 'tickets', ['ticket_number', 'status', 'scanned_at', 'created_at']);
        $tickets = $query->orderBy($sort, $dir)->paginate(15)->withQueryString();
        $statusCounts = Ticket::selectRaw('status, count(*) as total')
            ->groupBy('status')->pluck('total', 'status');

        return view('admin.tickets', compact('tickets', 'statusCounts', 'sort', 'dir'));
    }

    public function deleteTicket(Ticket $ticket)
    {
        $ticket->delete();
        return back()->with('success', 'Ticket has been deleted.');
    }

    // ─── Certificates Management ────────────────────────────────────────

    public function certificates(Request $request)
    {
        $query = Certificate::with(['user', 'event', 'registration']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('certificate_number', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('event', fn ($e) => $e->where('title', 'like', "%{$search}%"));
            });
        }

        [$sort, $dir] = $this->applySorting($request, 'certificates', ['certificate_number', 'issued_at', 'created_at']);
        $certificates = $query->orderBy($sort, $dir)->paginate(15)->withQueryString();
        $total = Certificate::count();

        return view('admin.certificates', compact('certificates', 'total', 'sort', 'dir'));
    }

    public function deleteCertificate(Certificate $certificate)
    {
        $certificate->delete();
        return back()->with('success', 'Certificate has been deleted.');
    }

    // ─── Earnings ───────────────────────────────────────────────────────

    public function earnings(Request $request)
    {
        $period = $request->get('period', 'this_month');
        $organizerId = $request->get('organizer');
        $commissionRate = Setting::commissionRate();

        // Define date range
        $range = match ($period) {
            'this_month'  => [now()->startOfMonth(), now()->endOfMonth()],
            'last_month'  => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
            'this_year'   => [now()->startOfYear(), now()->endOfYear()],
            'last_year'   => [now()->subYear()->startOfYear(), now()->subYear()->endOfYear()],
            'all'         => [null, null],
            default       => [now()->startOfMonth(), now()->endOfMonth()],
        };

        // Build base query
        $baseQuery = Registration::query()
            ->whereIn('registrations.status', ['registered', 'attended'])
            ->join('events', 'registrations.event_id', '=', 'events.id');

        if ($range[0] && $range[1]) {
            $baseQuery->whereBetween('registrations.created_at', [$range[0], $range[1]]);
        }

        // ─── Platform-wide totals ───────────────────────────────────────
        $totalRevenue   = (clone $baseQuery)->sum(DB::raw('COALESCE(events.price, 0)'));
        $totalRegistrations = (clone $baseQuery)->count('registrations.id');
        $totalCommission = round($totalRevenue * $commissionRate, 2);
        $totalPayout     = round($totalRevenue - $totalCommission, 2);
        $totalEvents     = (clone $baseQuery)->distinct()->count('events.id');
        $totalOrganizers = (clone $baseQuery)->distinct()->count('events.user_id');

        // ─── Organizer list (always shown) ──────────────────────────────
        $byOrganizer = (clone $baseQuery)
            ->select([
                'events.user_id',
                DB::raw('COUNT(DISTINCT events.id) as event_count'),
                DB::raw('COUNT(*) as reg_count'),
                DB::raw('SUM(COALESCE(events.price, 0)) as revenue'),
            ])
            ->groupBy('events.user_id')
            ->orderByDesc('revenue')
            ->get()
            ->map(function ($item) use ($commissionRate) {
                $organizer = User::find($item->user_id);
                $item->organizer_name = $organizer->name ?? 'Deleted User';
                $item->organizer_email = $organizer->email ?? '';
                $item->commission = round($item->revenue * $commissionRate, 2);
                $item->payout     = round($item->revenue - $item->commission, 2);
                return $item;
            });

        // ─── Detail views (only when an organizer is selected) ──────────
        $selectedOrganizer = null;
        $byEvent = collect();
        $recentEarnings = collect();
        $monthlyTrend = collect();

        if ($organizerId) {
            $selectedOrganizer = User::find($organizerId);

            // Build filtered query for this organizer
            $detailQuery = clone $baseQuery;
            $detailQuery->where('events.user_id', $organizerId);

            // Per-event breakdown
            $byEvent = (clone $detailQuery)
                ->select([
                    'events.id', 'events.title', 'events.price',
                    DB::raw('COUNT(*) as reg_count'),
                    DB::raw('SUM(COALESCE(events.price, 0)) as revenue'),
                ])
                ->groupBy('events.id', 'events.title', 'events.price')
                ->orderByDesc('revenue')
                ->get()
                ->map(function ($item) use ($commissionRate) {
                    $item->commission = round($item->revenue * $commissionRate, 2);
                    $item->payout     = round($item->revenue - $item->commission, 2);
                    return $item;
                });

            // Recent transactions for this organizer
            $recentEarnings = (clone $detailQuery)
                ->select([
                    'registrations.*',
                    'events.title as event_title',
                    'events.price',
                    DB::raw('COALESCE(events.price, 0) as amount'),
                ])
                ->orderByDesc('registrations.created_at')
                ->limit(15)
                ->get()
                ->map(function ($reg) use ($commissionRate) {
                    $reg->user = User::find($reg->user_id);
                    $reg->commission = round($reg->amount * $commissionRate, 2);
                    $reg->payout     = round($reg->amount - $reg->commission, 2);
                    return $reg;
                });

            // Monthly trend for this organizer
            $monthlyTrend = Registration::whereIn('registrations.status', ['registered', 'attended'])
                ->join('events', 'registrations.event_id', '=', 'events.id')
                ->where('events.user_id', $organizerId)
                ->where('registrations.created_at', '>=', now()->subMonths(12))
                ->selectRaw("DATE_FORMAT(registrations.created_at, '%Y-%m') as month")
                ->selectRaw('SUM(COALESCE(events.price, 0)) as revenue')
                ->selectRaw('COUNT(*) as registrations')
                ->groupBy(DB::raw("DATE_FORMAT(registrations.created_at, '%Y-%m')"))
                ->orderBy('month')
                ->get()
                ->map(function ($m) use ($commissionRate) {
                    $m->commission = round($m->revenue * $commissionRate, 2);
                    $m->payout     = round($m->revenue - $m->commission, 2);
                    return $m;
                });
        }

        return view('admin.earnings', compact(
            'period', 'organizerId', 'selectedOrganizer',
            'totalRevenue', 'totalCommission', 'totalPayout',
            'totalRegistrations', 'totalEvents', 'totalOrganizers',
            'byOrganizer', 'byEvent', 'recentEarnings', 'monthlyTrend',
            'commissionRate'
        ));
    }

    // ─── Settings ───────────────────────────────────────────────────────

    public function settings()
    {
        $commissionRate = Setting::get('commission_rate', 10);
        return view('admin.settings', compact('commissionRate'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        Setting::set('commission_rate', $validated['commission_rate']);

        return redirect()->route('admin.settings')
            ->with('success', 'Settings updated successfully. Commission rate is now ' . $validated['commission_rate'] . '%.');
    }

    // ─── Sorting Helper ─────────────────────────────────────────────────

    /**
     * Parse and validate sort/direction from the request.
     * Returns [column, direction] — safe defaults if invalid.
     */
    private function applySorting(Request $request, string $table, array $allowedColumns): array
    {
        $sort = $request->get('sort', 'created_at');
        $dir  = $request->get('direction', 'desc');

        // Validate column
        if (!in_array($sort, $allowedColumns)) {
            $sort = 'created_at';
        }
        // Validate direction
        if (!in_array($dir, ['asc', 'desc'])) {
            $dir = 'desc';
        }

        return [$sort, $dir];
    }
}
