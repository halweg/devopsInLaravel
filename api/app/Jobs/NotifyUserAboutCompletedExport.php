<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\ExportCompletedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyUserAboutCompletedExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;

    private string $fileLink;

    public function __construct(User $user, string $fileLink)
    {
        $this->user = $user;

        $this->fileLink = $fileLink;
    }

    public function handle()
    {
        $this->user->notify(new ExportCompletedNotification($this->fileLink));
    }
}
