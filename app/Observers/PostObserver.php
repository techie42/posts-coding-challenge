<?php

namespace App\Observers;

use App\Models\Post;
use Carbon\Carbon;

class PostObserver
{
    public function creating(Post $post): void
    {
        // TODO: Let's assume every post is published at the time of creation, for simplicity
        $post->published_at = Carbon::now();
    }
}
