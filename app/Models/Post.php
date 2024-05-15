<?php

namespace App\Models;

use App\Observers\PostObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ObservedBy(PostObserver::class)]
class Post extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'title',
        'slug',
    ];
    protected $appends = [
        'formatted_published_at',
        'is_my_like',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_like_posts', 'post_id', 'user_id');
    }

    public function likedPost(): bool
    {
        if (!auth()->user()) {
            return false;
        }

        return UserLikesPost::where(['post_id' => $this->id, 'user_id' => auth()->user()->id])->count() > 0;
    }

    protected function isMyLike(): Attribute
    {
        return new Attribute(
            get: fn() => $this->likedPost(),
        );
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(UserLikesPost::class, 'users_like_posts')
            ->as('likedUsers')
            ->withTimestamps()
            ->withCount('users');
    }

    public function getTimezoneAdjustedPublishedAtAttribute(?string $format = null): Carbon | string
    {
        // TODO: Assume published_at is saved / treated as UTC at time of creation
        $originalTime = (new Carbon($this->published_at, 'UTC'));
        $adjustedTime = new Carbon($originalTime->tz('Europe/London'));

        // TODO: Assume viewer is in the UK, for simplicity ^_^
        if ($format === null) {
            return $adjustedTime;
        }

        return $adjustedTime->format($format);
    }

    protected function formattedPublishedAt(): Attribute
    {
        return new Attribute(
            get: fn () => $this->getTimezoneAdjustedPublishedAtAttribute('D jS M Y, g.ha'),
        );
    }

    protected function likesTally(): Attribute
    {
        return new Attribute(
            get: fn () => $this->withCount('userLikesPost'),
        );
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where(
                'published_at',
                '<=',
                Carbon::now(),
            );
    }

    public function scopeMostRecentlyPublishedFirst(Builder $query): Builder
    {
        return $query
            ->orderByDesc('published_at');
    }

    public function scopeFilterResults(Builder $query, $searchTerm): Builder
    {
        return $query
            ->whereAny([
                'title',
                'slug'
            ],
                'LIKE',
                sprintf('%%%s%%', $searchTerm),
            );
    }
}
