<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Models\User;
use App\Notifications\GameStartReminder;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendGameReminders extends Command
{
    protected $signature = 'games:send-reminders';
    protected $description = 'Send game start reminders to users';

    public function handle()
    {
        $upcomingGames = Game::where('kickoff', '>', now())
            ->where('kickoff', '<=', now()->addHours(1))
            ->where('reminder_sent', false)
            ->get();

        foreach ($upcomingGames as $game) {
            $this->sendReminders($game);
            $game->update(['reminder_sent' => true]);
        }
    }

    private function sendReminders(Game $game)
    {
        $affectedTeams = [$game->home_team_id, $game->away_team_id];
        
        $usersWithPlayers = User::whereHas('entries.players', function ($query) use ($affectedTeams) {
            $query->whereIn('team_id', $affectedTeams);
        })->get();

        foreach ($usersWithPlayers as $user) {
            $playerEntries = $user->entries()->whereHas('players', function ($query) use ($affectedTeams) {
                $query->whereIn('team_id', $affectedTeams);
            })->get();

            $user->notify(new GameStartReminder($game, $playerEntries));
        }
    }
}