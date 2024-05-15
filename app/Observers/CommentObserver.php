<?php

namespace App\Observers;

use App\Models\Comment;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     */
    public function created(Comment $comment): void
    {
       
        // Log::info('Comment Created: ', ['comment' => $comment]);

        $comment->user->notify('Comment Created: ', ['comment' => $comment]);

    }

    /**
     * Handle the Comment "updated" event.
     */
    public function updated(Comment $comment): void
    {


        $comment->user->notify('Comment Updated: ', ['comment' => $comment]);

        // Log::info('Comment Updated: ', ['comment' => $comment]);
    }

    /**
     * Handle the Comment "deleted" event.
     */
    public function deleted(Comment $comment): void
    {


        $comment->user->notify('Comment Deleted: ', ['comment' => $comment]);

        // Log::info('Comment Deleted: ', ['comment' => $comment]);
    }

    /**
     * Handle the Comment "restored" event.
     */
    public function restored(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "force deleted" event.
     */
    public function forceDeleted(Comment $comment): void
    {
        //
    }
}
