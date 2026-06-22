<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EventRecommendationService
{
    /**
     * Stop words to filter out during keyword extraction.
     */
    protected array $stopWords = [
        'the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for',
        'of', 'with', 'by', 'from', 'is', 'are', 'was', 'were', 'be', 'been',
        'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would',
        'could', 'should', 'may', 'might', 'shall', 'can', 'need', 'dare',
        'ought', 'used', 'this', 'that', 'these', 'those', 'i', 'me', 'my',
        'myself', 'we', 'our', 'ours', 'ourselves', 'you', 'your', 'yours',
        'yourself', 'he', 'him', 'his', 'himself', 'she', 'her', 'hers',
        'herself', 'it', 'its', 'itself', 'they', 'them', 'their', 'theirs',
        'themselves', 'what', 'which', 'who', 'whom', 'whose', 'when',
        'where', 'why', 'how', 'all', 'each', 'every', 'both', 'few', 'more',
        'most', 'other', 'some', 'such', 'no', 'nor', 'not', 'only', 'own',
        'same', 'so', 'than', 'too', 'very', 'just', 'because', 'as', 'until',
        'while', 'about', 'between', 'into', 'through', 'during', 'before',
        'after', 'above', 'below', 'up', 'down', 'out', 'off', 'over', 'under',
        'again', 'further', 'then', 'once', 'here', 'there', 'any', 'get',
        'via', 'new', 'free', 'one', 'two', 'three', 'also', 'back', 'well',
        'much', 'still', 'yet', 'since', 'next', 'ever', 'everyone', 'anyone',
        'something', 'everything', 'nothing', 'please', 'thank', 'dear',
    ];

    /**
     * Get AI-powered event recommendations for a user.
     * Merges auto-detected keywords with manually entered ones.
     */
    public function getRecommendations(User $user, int $limit = 6): Collection
    {
        // Build/refresh user preferences from behavior (preserves manual keywords)
        $this->buildUserPreferences($user);

        // Get merged keywords (auto-detected + manual)
        $preferences = $user->preference;
        $allKeywords = $preferences?->getAllKeywords() ?? [];

        if (empty($allKeywords)) {
            return $this->getPopularEvents($user, $limit);
        }

        // Events the user already registered for or owns
        $registeredEventIds = $user->registrations()
            ->whereIn('status', ['registered', 'attended'])
            ->pluck('event_id')
            ->toArray();

        // Candidate events: published, upcoming, public, not owned by user
        $candidates = Event::published()
            ->upcoming()
            ->public()
            ->where('user_id', '!=', $user->id)
            ->withCount(['registrations' => fn ($q) => $q->where('status', 'registered')])
            ->get();

        if ($candidates->isEmpty()) {
            return collect();
        }

        // Pre-compute IDF-like scores across all candidates
        $globalKeywordFrequency = $this->computeGlobalKeywordFrequency($candidates);

        // Score each candidate with enhanced multi-signal algorithm
        $scored = $candidates->map(function (Event $event) use ($user, $preferences, $allKeywords, $registeredEventIds, $globalKeywordFrequency) {
            if (in_array($event->id, $registeredEventIds)) {
                return null;
            }

            $score = 0.0;

            // 1. Content-based score with TF-IDF weighting — 40%
            $contentScore = $this->calculateContentScore($event, $allKeywords, $globalKeywordFrequency, $preferences);
            $score += $contentScore * 0.40;

            // 2. Collaborative filtering — 25%
            $collaborativeScore = $this->calculateCollaborativeScore($event, $user);
            $score += $collaborativeScore * 0.25;

            // 3. Organizer affinity — 15%
            $organizerScore = $this->calculateOrganizerScore($event, $preferences, $user);
            $score += $organizerScore * 0.15;

            // 4. Popularity — 10%
            $popularityScore = $this->calculatePopularityScore($event);
            $score += $popularityScore * 0.10;

            // 5. Venue/location familiarity — 10%
            $venueScore = $this->calculateVenueScore($event, $user);
            $score += $venueScore * 0.10;

            return (object) [
                'event' => $event,
                'score' => round($score, 4),
            ];
        });

        return collect($scored)
            ->filter()
            ->sortByDesc('score')
            ->take($limit)
            ->pluck('event');
    }

    /**
     * Build or refresh user preferences from registration + organizer history.
     * Uses recency weighting and own-event analysis.
     * Preserves any manually entered keywords.
     */
    public function buildUserPreferences(User $user): void
    {
        $keywordFrequency = [];
        $organizerIds = [];
        $venueKeywords = [];

        // ── Source 1: Events the user registered for ──
        $registeredEvents = Event::whereIn('id', function ($q) use ($user) {
            $q->select('event_id')
                ->from('registrations')
                ->where('user_id', $user->id)
                ->whereIn('status', ['registered', 'attended']);
        })->select('id', 'title', 'description', 'user_id', 'venue', 'event_date', 'created_at')->get();

        foreach ($registeredEvents as $event) {
            $recencyWeight = $this->recencyWeight($event->created_at);
            $text = $event->title . ' ' . ($event->description ?? '');
            $keywords = $this->extractKeywords($text);

            foreach ($keywords as $keyword) {
                $keywordFrequency[$keyword] = ($keywordFrequency[$keyword] ?? 0) + $recencyWeight;
            }

            if ($event->user_id) {
                $organizerIds[] = $event->user_id;
            }

            if ($event->venue) {
                foreach ($this->extractKeywords($event->venue) as $kw) {
                    $venueKeywords[$kw] = ($venueKeywords[$kw] ?? 0) + $recencyWeight;
                }
            }
        }

        // ── Source 2: Events the user organizes ──
        foreach (Event::where('user_id', $user->id)->select('title', 'description', 'venue')->get() as $event) {
            foreach ($this->extractKeywords($event->title . ' ' . ($event->description ?? '')) as $keyword) {
                $keywordFrequency[$keyword] = ($keywordFrequency[$keyword] ?? 0) + 0.5;
            }
            if ($event->venue) {
                foreach ($this->extractKeywords($event->venue) as $kw) {
                    $venueKeywords[$kw] = ($venueKeywords[$kw] ?? 0) + 0.5;
                }
            }
        }

        // Determine significant keywords (keep top 30)
        $minFrequency = max(1, $registeredEvents->count() > 1 ? 1.5 : 1.0);
        arsort($keywordFrequency);
        $significantKeywords = array_slice(
            array_keys(array_filter($keywordFrequency, fn ($w) => $w >= $minFrequency)),
            0, 30
        );

        // Preserve manual keywords!
        $existing = UserPreference::where('user_id', $user->id)->first();

        UserPreference::updateOrCreate(
            ['user_id' => $user->id],
            [
                'preferred_keywords' => $significantKeywords,
                'preferred_organizer_ids' => array_values(array_unique($organizerIds)),
                'manual_keywords' => $existing?->manual_keywords ?? [],
            ]
        );
    }

    /**
     * Extract meaningful keywords from text with improved stemming.
     */
    public function extractKeywords(string $text): array
    {
        $text = mb_strtolower(strip_tags($text));
        $text = preg_replace('/[^a-z0-9\s\-]/', ' ', $text);
        $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);

        $keywords = array_filter($words, function ($word) {
            $word = trim($word);
            return strlen($word) > 2
                && !in_array($word, $this->stopWords)
                && !is_numeric($word);
        });

        // Stemming approximation with extended suffixes
        $stemmed = array_map(function ($word) {
            if (strlen($word) > 5) {
                $word = preg_replace(
                    '/^(.*?)(ing|tion|ions|ment|ments|ness|less|able|ible|al|ial|ed|ly|ies|ied|er|est|ive|ous|ful|ship|dom|ity|tion|sion|ance|ence|ure|tive|ture|ling)$/',
                    '$1', $word
                );
            }
            return $word;
        }, $keywords);

        $stemmed = array_unique(array_filter($stemmed, fn ($w) => strlen($w) > 2));
        return array_values($stemmed);
    }

    // ─────────────────────────────────────────────
    //  Enhanced Scoring Methods
    // ─────────────────────────────────────────────

    /**
     * Enhanced content score with TF-IDF weighting and manual keyword boost.
     * - Manual keywords get 2x weight
     * - Title matches get 1.5x weight
     * - Rare (high-IDF) keywords get higher weight
     * - Combines TF-IDF (70%) + Jaccard (30%)
     */
    protected function calculateContentScore(
        Event $event,
        array $allKeywords,
        array $globalKeywordFrequency,
        ?UserPreference $preferences
    ): float {
        if (empty($allKeywords)) {
            return 0;
        }

        $eventText = $event->title . ' ' . ($event->description ?? '');
        $eventKeywords = $this->extractKeywords($eventText);

        if (empty($eventKeywords)) {
            return 0;
        }

        $manualKeywords = $preferences?->manual_keywords ?? [];
        $titleKeywords = $this->extractKeywords($event->title);

        // TF-IDF weighted matching
        $totalScore = 0.0;
        $totalWeight = 0.0;
        $totalDocs = max(1, count($globalKeywordFrequency));

        foreach ($allKeywords as $keyword) {
            $globalCount = $globalKeywordFrequency[$keyword] ?? 1;
            $idf = log(1 + ($totalDocs / $globalCount));
            $sourceBoost = in_array($keyword, $manualKeywords) ? 2.0 : 1.0;
            $titleBoost = in_array($keyword, $titleKeywords) ? 1.5 : 1.0;
            $weight = $idf * $sourceBoost * $titleBoost;

            if (in_array($keyword, $eventKeywords)) {
                $totalScore += $weight;
            }
            $totalWeight += $weight;
        }

        $tfidfScore = $totalWeight > 0 ? min(1.0, $totalScore / $totalWeight) : 0;

        // Jaccard similarity
        $intersection = array_intersect($allKeywords, $eventKeywords);
        $union = array_unique(array_merge($allKeywords, $eventKeywords));
        $jaccard = count($union) > 0 ? count($intersection) / count($union) : 0;

        return ($tfidfScore * 0.7) + ($jaccard * 0.3);
    }

    /**
     * Collaborative filtering: finds similar users and checks what they registered for.
     * Uses Jaccard-style ratio + logarithmic boost.
     */
    protected function calculateCollaborativeScore(Event $event, User $user): float
    {
        $similarUserIds = DB::table('registrations')
            ->whereIn('event_id', function ($q) use ($user) {
                $q->select('event_id')
                    ->from('registrations')
                    ->where('user_id', $user->id)
                    ->whereIn('status', ['registered', 'attended']);
            })
            ->where('user_id', '!=', $user->id)
            ->whereIn('status', ['registered', 'attended'])
            ->distinct()
            ->pluck('user_id');

        if ($similarUserIds->isEmpty()) {
            return 0;
        }

        $similarRegistrations = DB::table('registrations')
            ->where('event_id', $event->id)
            ->whereIn('user_id', $similarUserIds)
            ->whereIn('status', ['registered', 'attended'])
            ->count();

        if ($similarRegistrations === 0) {
            return 0;
        }

        $ratio = $similarRegistrations / max(1, $similarUserIds->count());
        $logBoost = log(1 + $similarRegistrations) / log(1 + max(1, $similarUserIds->count()));

        return min(1.0, ($ratio * 0.4 + $logBoost * 0.6));
    }

    /**
     * Organizer affinity: checks if user has registered with this organizer before,
     * plus mutual connections bonus.
     */
    protected function calculateOrganizerScore(Event $event, ?UserPreference $preferences, User $user): float
    {
        $organizerIds = $preferences?->preferred_organizer_ids ?? [];

        if (in_array($event->user_id, $organizerIds)) {
            return 1.0;
        }

        // Mutual connections: how many times has this user registered
        // for other events by the same organizer?
        $mutualCount = DB::table('registrations')
            ->join('events', 'registrations.event_id', '=', 'events.id')
            ->where('events.user_id', $event->user_id)
            ->where('registrations.user_id', $user->id)
            ->whereIn('registrations.status', ['registered', 'attended'])
            ->count();

        return $mutualCount > 0 ? min(0.5, 0.2 + $mutualCount * 0.1) : 0;
    }

    /**
     * Venue/location familiarity score.
     * Rewards events at venues the user has visited before.
     */
    protected function calculateVenueScore(Event $event, User $user): float
    {
        if (! $event->venue) {
            return 0;
        }

        $previousVisits = DB::table('registrations')
            ->join('events', 'registrations.event_id', '=', 'events.id')
            ->where('registrations.user_id', $user->id)
            ->where('events.venue', $event->venue)
            ->whereIn('registrations.status', ['registered', 'attended'])
            ->where('events.id', '!=', $event->id)
            ->count();

        return $previousVisits > 0 ? min(1.0, 0.3 + ($previousVisits * 0.1)) : 0;
    }

    /**
     * Popularity score based on registration fill ratio.
     */
    protected function calculatePopularityScore(Event $event): float
    {
        $count = $event->registrations_count
            ?? $event->registrations()->where('status', 'registered')->count();

        if ($count === 0) return 0;

        if ($event->capacity > 0) return min(1.0, $count / $event->capacity);

        return min(1.0, log(1 + $count) / log(100));
    }

    // ─────────────────────────────────────────────
    //  Helpers
    // ─────────────────────────────────────────────

    /**
     * Compute document frequency for each keyword across candidate events.
     */
    protected function computeGlobalKeywordFrequency(Collection $candidates): array
    {
        $freq = [];
        foreach ($candidates as $event) {
            $keywords = array_unique(
                $this->extractKeywords($event->title . ' ' . ($event->description ?? ''))
            );
            foreach ($keywords as $kw) {
                $freq[$kw] = ($freq[$kw] ?? 0) + 1;
            }
        }
        return $freq;
    }

    /**
     * Recency weight: 1.0 for this month, decaying to 0.5 at 6+ months.
     */
    protected function recencyWeight($date): float
    {
        if (! $date) return 0.7;
        return max(0.5, 1.0 - (now()->diffInMonths($date) * 0.08));
    }

    /**
     * Fallback: popular upcoming events when user has no preferences.
     */
    protected function getPopularEvents(User $user, int $limit): Collection
    {
        $registeredEventIds = $user->registrations()
            ->whereIn('status', ['registered', 'attended'])
            ->pluck('event_id')
            ->toArray();

        return Event::published()
            ->upcoming()
            ->public()
            ->where('user_id', '!=', $user->id)
            ->withCount(['registrations' => fn ($q) => $q->where('status', 'registered')])
            ->whereNotIn('id', $registeredEventIds)
            ->orderByDesc(
                DB::raw('(SELECT COUNT(*) FROM registrations WHERE event_id = events.id AND status = "registered")')
            )
            ->take($limit)
            ->get();
    }
}
