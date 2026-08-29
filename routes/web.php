<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PreferencesController;
use App\Http\Controllers\RegistrationController;
use App\Models\Event;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    $upcomingEvents = Event::published()
        ->upcoming()
        ->public()
        ->withCount(['registrations' => fn ($q) => $q->where('status', 'registered')])
        ->latest('event_date')
        ->take(6)
        ->get();

    return view('landing', compact('upcomingEvents'));
});

Route::get('/events/browse', [EventController::class, 'browse'])->name('events.browse');

// Join a private event via registration QR / link (public so attendees can scan and register)
Route::get('/events/{event}/join', [RegistrationController::class, 'join'])->name('events.join');
Route::get('/events/{event}/join-qr', [RegistrationController::class, 'joinQr'])->name('events.join.qr');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Events management (except show — defined below so it doesn't catch /create etc.)
    Route::resource('events', EventController::class)->except(['show']);
    Route::patch('/events/{event}/publish', [EventController::class, 'publish'])->name('events.publish');

    // Registrations
    Route::get('/my-registrations', [RegistrationController::class, 'myRegistrations'])->name('registrations.my');
    Route::post('/events/{event}/register', [RegistrationController::class, 'register'])->name('events.register');
    Route::post('/events/{event}/tickets/generate', [RegistrationController::class, 'generateTicket'])->name('events.tickets.generate');
    Route::get('/registrations/{registration}/ticket', [RegistrationController::class, 'showTicket'])->name('registrations.ticket');
    Route::delete('/registrations/{registration}/cancel', [RegistrationController::class, 'cancel'])->name('registrations.cancel');

    // Attendance / QR Scanning
    Route::get('/scan', [AttendanceController::class, 'scanUniversal'])->name('attendance.scan');
    Route::post('/scan/verify', [AttendanceController::class, 'verifyUniversal'])->name('attendance.verify');
    Route::get('/events/{event}/scan', [AttendanceController::class, 'scanPage'])->name('attendance.scan.event');
    Route::post('/events/{event}/verify', [AttendanceController::class, 'verifyTicket'])->name('attendance.verify.event');
    Route::get('/events/{event}/attendees', [AttendanceController::class, 'attendees'])->name('attendance.attendees');

    // Certificates
    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::post('/events/{event}/certificates/generate', [CertificateController::class, 'generate'])->name('certificates.generate');
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');
    Route::get('/events/{event}/certificates', [CertificateController::class, 'eventCertificates'])->name('certificates.event');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');

    // Preferences / Interests
    Route::get('/preferences', [PreferencesController::class, 'edit'])->name('preferences.edit');
    Route::patch('/preferences', [PreferencesController::class, 'update'])->name('preferences.update');
});

// Public event detail (placed AFTER resource routes so /events/create matches the resource route first)
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Admin routes — full management suite
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');

    // Users
    Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('users');
    Route::delete('/users/{user}', [\App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.delete');

    // Events
    Route::get('/events', [\App\Http\Controllers\AdminController::class, 'events'])->name('events');
    Route::delete('/events/{event}', [\App\Http\Controllers\AdminController::class, 'deleteEvent'])->name('events.delete');

    // Registrations
    Route::get('/registrations', [\App\Http\Controllers\AdminController::class, 'registrations'])->name('registrations');
    Route::delete('/registrations/{registration}', [\App\Http\Controllers\AdminController::class, 'deleteRegistration'])->name('registrations.delete');

    // Tickets
    Route::get('/tickets', [\App\Http\Controllers\AdminController::class, 'tickets'])->name('tickets');
    Route::delete('/tickets/{ticket}', [\App\Http\Controllers\AdminController::class, 'deleteTicket'])->name('tickets.delete');

    // Certificates
    Route::get('/certificates', [\App\Http\Controllers\AdminController::class, 'certificates'])->name('certificates');
    Route::delete('/certificates/{certificate}', [\App\Http\Controllers\AdminController::class, 'deleteCertificate'])->name('certificates.delete');

    // Earnings
    Route::get('/earnings', [\App\Http\Controllers\AdminController::class, 'earnings'])->name('earnings');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\AdminController::class, 'settings'])->name('settings');
    Route::patch('/settings', [\App\Http\Controllers\AdminController::class, 'updateSettings'])->name('settings.update');
});

// Authentication routes (login/register)
Route::middleware('guest')->group(function () {
    Route::get('/login', fn () => view('auth.login'))->name('login');
    Route::get('/register', fn () => view('auth.register'))->name('register');
});

require __DIR__ . '/auth.php';
