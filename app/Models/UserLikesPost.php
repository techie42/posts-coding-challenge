<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLikesPost extends Model
{
    use HasFactory;

    protected $table = 'users_like_posts';
    protected $fillable = [
        'user_id',
        'post_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    protected static function toggleLike(string $ident): bool
    {
        if (!auth()->user()) {
            return false;
        }

        $userId = auth()->user()->getAuthIdentifier();
        $query = self::where(['post_id' => $ident, 'user_id' => $userId]);
        $hit = $query->first();

        if ($hit) {
            $query->delete();
        } else {
            $like = self::create([
                'user_id' => $userId,
                'post_id' => $ident,
            ]);
            $like->save();
        }

        return true;
    }
}
