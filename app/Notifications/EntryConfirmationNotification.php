<?php

namespace App\Notifications;

use App\Models\Entry;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EntryConfirmationNotification extends Notification
{
    use Queueable;

    protected $entry;

    public function __construct(Entry $entry)
    {
        $this->entry = $entry;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('NFL Playoff Fantasy - Entry Confirmed')
            ->line('Your entry "' . $this->entry->name . '" has been confirmed!')
            ->line('Team Roster:')
            ->line($this->formatRoster())
            ->action('View Entry', url('/entries/' . $this->entry->id))
            ->line('Good luck in the playoffs!');
    }

    private function formatRoster()
    {
        return $this->entry->players
            ->map(function ($player) {
                return "- {$player->position}: {$player->name} ({$player->team->name})";
            })
            ->implode("\n");
    }
}