<?php

namespace App\Listeners;

use App\Events\PostPublishedEvent;
use App\Models\User;
use App\Notifications\PostPublishedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPostPublishedNotifications implements ShouldQueue
{
    use InteractsWithQueue;

    public $queue = 'notifications';

    public function handle(PostPublishedEvent $event): void
    {
        User::where('id', '!=', $event->post->author->id)
            ->get()
            ->each
            ->notify(
                (new PostPublishedNotification($event->post))->onQueue('notifications')
            );
    }
}
