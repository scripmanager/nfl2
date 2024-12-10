<?php

namespace App\Notifications;

use App\Models\Game;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GameStartReminder extends Notification
{
    use Queueable;

    protected $game;
    protected $playerEntries;

    public function __construct(Game $game, $playerEntries)
    {
        $this->game = $game;
        $this->playerEntries = $playerEntries;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Game Starting Soon: ' . $this->game->homeTeam->name . ' vs ' . $this->game->awayTeam->name)
            ->line('A game featuring your players starts in 1 hour!')
            ->line($this->formatGameInfo())
            ->line('Your players in this game:')
            ->line($this->formatPlayerInfo())
            ->action('View Game Details', url('/games/' . $this->game->id));
    }

    private function formatGameInfo()
    {
        return sprintf(
            '%s vs %s - %s',
            $this->game->homeTeam->name,
            $this->game->awayTeam->name,
            $this->game->kickoff->format('l, F j - g:i A')
        );
    }

    private function formatPlayerInfo()
    {
        return $this->playerEntries->map(function ($entry) {
            $players = $entry->players()->whereIn('team_id', [
                $this->game->home_team_id,
                $this->game->away_team_id
            ])->get();

            return $players->map(function ($player) use ($entry) {
                return "- {$player->name} ({$player->team->name}) - {$entry->name}";
            })->implode("\n");
        })->implode("\n");
    }
}