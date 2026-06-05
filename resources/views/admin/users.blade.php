@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Manage Users</h1>
        <p class="text-gray-500 mt-1">View and manage all registered users.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-4 font-semibold text-gray-600">User</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Joined</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Events</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Registrations</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Role</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="w-9 h-9 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-semibold text-sm">
                                    {{ substr($user->name, 0, 2) }}
                                </span>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $user->events_count }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $user->registrations_count }}</td>
                        <td class="px-6 py-4">
                            @if($user->is_admin)
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800">
                                Admin
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-600">
                                User
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('admin.users.toggleAdmin', $user) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-3 py-1.5 text-sm font-medium rounded-lg transition
                                        @if($user->is_admin) text-red-600 hover:bg-red-50
                                        @else text-indigo-600 hover:bg-indigo-50 @endif">
                                        @if($user->is_admin) Demote @else Promote @endif
                                    </button>
                                </form>

                                @if(auth()->id() !== $user->id)
                                <form method="POST" action="{{ route('admin.users.delete', $user) }}"
                                    onsubmit="return confirm('Delete user {{ $user->name }}? This also removes their events and registrations.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition">
                                        Delete
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection
