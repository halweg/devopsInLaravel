<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class ExportCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private string $fileLink;

    public function __construct(string $fileLink)
    {
        $this->fileLink = $fileLink;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Your export is completed.')
            ->line('You can now download the Excel file containing every posts.')
            ->action('Download', $this->fileLink)
            ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Your export id completed',
            'body' => 'You can now download the Excel file containing every posts.',
            'url' => $this->fileLink,
        ];
    }
}
