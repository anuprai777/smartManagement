<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/events/browse', [EventController::class, 'browse'])->name('events.browse');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Events management
    Route::resource('events', EventController::class)->except(['show']);
    Route::patch('/events/{event}/publish', [EventController::class, 'publish'])->name('events.publish');

    // Registrations
    Route::get('/my-registrations', [RegistrationController::class, 'myRegistrations'])->name('registrations.my');
    Route::post('/events/{event}/register', [RegistrationController::class, 'register'])->name('events.register');
    Route::get('/registrations/{registration}/ticket', [RegistrationController::class, 'showTicket'])->name('registrations.ticket');
    Route::delete('/registrations/{registration}/cancel', [RegistrationController::class, 'cancel'])->name('registrations.cancel');

    // Attendance / QR Scanning
    Route::get('/events/{event}/scan', [AttendanceController::class, 'scanPage'])->name('attendance.scan');
    Route::post('/events/{event}/verify', [AttendanceController::class, 'verifyTicket'])->name('attendance.verify');
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
});

// Authentication routes (login/register)
Route::middleware('guest')->group(function () {
    Route::get('/login', fn () => view('auth.login'))->name('login');
    Route::get('/register', fn () => view('auth.register'))->name('register');
});

require __DIR__ . '/auth.php';
