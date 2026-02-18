<?php

namespace App\Models;

use Database\Factories\ChallengeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Challenge extends Model
{
    use HasFactory, SoftDeletes;

    protected static function newFactory()
    {
        return ChallengeFactory::new();
    }

    protected $fillable = [
        'title',
        'description',
        'type',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option',
        'xp_reward',
        'active_date',
        'is_active',
    ];

    protected $casts = [
        'active_date' => 'date',
        'is_active' => 'boolean',
        'xp_reward' => 'integer',
    ];

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function isCorrect(string $selectedOption): bool
    {
        return $this->correct_option === strtolower($selectedOption);
    }

    public static function getTodayChallenge(): ?self
    {
        return self::where('active_date', today())
            ->where('is_active', true)
            ->first();
    }
}

