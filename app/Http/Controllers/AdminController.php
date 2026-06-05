<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Event;
use App\Models\Registration;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_events' => Event::count(),
            'total_registrations' => Registration::count(),
            'total_tickets' => Ticket::count(),
            'total_certificates' => Certificate::count(),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_events' => Event::withCount('registrations')->latest()->take(5)->get(),
            'events_by_status' => Event::selectRaw('status, count(*) as total')
                ->groupBy('status')->pluck('total', 'status'),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function users()
    {
        $users = User::withCount(['events', 'registrations'])->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function toggleAdmin(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot remove your own admin status.');
        }

        $user->update(['is_admin' => !$user->is_admin]);

        $status = $user->is_admin ? 'promoted to admin' : 'demoted from admin';
        return back()->with('success', "User {$user->name} {$status}.");
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $name = $user->name;
        $user->delete();

        return back()->with('success', "User {$name} deleted successfully.");
    }
}
