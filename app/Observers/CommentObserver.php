<?php

namespace App\Observers;

use App\Models\Comment;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     */
    public function created(Comment $comment): void
    {
        if ($comment->sentiment === null) {
            $newSentiment = $comment->analyzeCommentSentiment();
            
            // Update only if sentiment has changed to prevent loop
            if ($comment->sentiment !== $newSentiment) {
                $comment->update(['sentiment' => $newSentiment]);
            }
        }
    }

    /**
     * Handle the Comment "updated" event.
     */
    public function updated(Comment $comment): void
    {
        if ($comment->sentiment === null) {
            $newSentiment = $comment->analyzeCommentSentiment();
            
            // Update only if sentiment has changed to prevent loop
            if ($comment->sentiment !== $newSentiment) {
                $comment->update(['sentiment' => $newSentiment]);
            }
        }
    }

    /**
     * Handle the Comment "deleted" event.
     */
    public function deleted(Comment $comment): void
    {
        //
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
