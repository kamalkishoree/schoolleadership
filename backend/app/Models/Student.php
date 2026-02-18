<?php

namespace App\Models;

use Database\Factories\StudentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected static function newFactory()
    {
        return StudentFactory::new();
    }

    protected $fillable = [
        'school_id',
        'class_id',
        'name',
        'email_or_mobile',
        'password',
        'total_xp',
        'current_streak',
        'last_active_date',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'last_active_date' => 'date',
        'total_xp' => 'integer',
        'current_streak' => 'integer',
        'password' => 'hashed',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function addXp(int $amount): void
    {
        $this->increment('total_xp', $amount);
    }

    public function updateStreak(): void
    {
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        if ($this->last_active_date === $yesterday) {
            $this->increment('current_streak');
        } elseif ($this->last_active_date !== $today) {
            $this->current_streak = 1;
        }

        $this->last_active_date = $today;
        $this->save();
    }
}

