<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'venue',
        'event_date',
        'registration_deadline',
        'capacity',
        'status',
        'banner_image',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'datetime',
            'registration_deadline' => 'datetime',
        ];
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now());
    }

    public function registrationCount(): int
    {
        return $this->registrations()->where('status', 'registered')->count();
    }

    public function attendanceCount(): int
    {
        return $this->registrations()->where('status', 'attended')->count();
    }

    public function isFull(): bool
    {
        if ($this->capacity <= 0) {
            return false;
        }
        return $this->registrationCount() >= $this->capacity;
    }

    public function isRegistrationOpen(): bool
    {
        if ($this->status !== 'published') {
            return false;
        }
        if ($this->registration_deadline && $this->registration_deadline->isPast()) {
            return false;
        }
        return !$this->isFull();
    }
}
