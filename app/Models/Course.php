<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['avarage_rating', 'total_ratings', 'lessons_count'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('completed_at');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(CourseRating::class);
    }

    protected function avarageRating(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->ratings->avg('rating'),
        );
    }

    protected function totalRatings(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->ratings->count(),
        );
    }

    protected function lessonsCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->lessons->count(),
        );
    }
}