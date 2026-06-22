<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPreference extends Model
{
    protected $fillable = [
        'user_id',
        'preferred_keywords',
        'preferred_organizer_ids',
        'manual_keywords',
    ];

    protected function casts(): array
    {
        return [
            'preferred_keywords' => 'array',
            'preferred_organizer_ids' => 'array',
            'manual_keywords' => 'array',
        ];
    }

    /**
     * Get the merged list of all keywords (auto-extracted + manual).
     */
    public function getAllKeywords(): array
    {
        return array_values(array_unique(array_merge(
            $this->preferred_keywords ?? [],
            $this->manual_keywords ?? [],
        )));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
