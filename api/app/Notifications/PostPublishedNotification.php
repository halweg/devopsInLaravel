<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostPublishedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $tries = 5;

    public $backoff = [30, 60, 120];

    private Post $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('A new post for you!')
            ->line("There is a new post for you: {$this->post->title}")
            ->action('Read more', route('posts.show', ['post' => $this->post]));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'A new post for you!',
            'body' => "There is a new post for you: {$this->post->title}",
            'link' => route('posts.show', ['post' => $this->post]),
        ];
    }
}
