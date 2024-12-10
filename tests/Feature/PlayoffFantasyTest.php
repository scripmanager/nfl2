<?php

namespace Tests\Feature;

use App\Models\Entry;
use App\Models\Game;
use App\Models\Player;
use App\Models\User;
use App\Models\Transaction;
use App\Models\PlayerStats;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlayoffFantasyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(); // Assuming we have seeded data for teams and base player data
    }

    /** @test */
    public function user_cannot_create_more_than_four_entries()
    {
        $user = User::factory()->create();
        
        // Create 4 entries
        Entry::factory()->count(4)->create(['user_id' => $user->id]);
        
        $response = $this->actingAs($user)->post('/entries', [
            'entry_name' => 'Fifth Entry'
        ]);
        
        $response->assertStatus(422);
        $this->assertEquals(4, Entry::where('user_id', $user->id)->count());
    }

    /** @test */
    public function entry_cannot_have_more_than_two_players_from_same_team()
    {
        $user = User::factory()->create([
            'email_verified_at' => now()
        ]);
        $this->actingAs($user);
            
        $entry = Entry::factory()->create(['user_id' => $user->id]);
        
        // Ensure we have a playoff team
        \App\Models\Team::where('id', 1)->update(['is_playoff_team' => true]);
        
        // Create players with proper position for WR slots
        $player1 = Player::factory()->create([
            'team_id' => 1,
            'position' => 'WR',
            'is_active' => true
        ]);
        $player2 = Player::factory()->create([
            'team_id' => 1,
            'position' => 'WR',
            'is_active' => true
        ]);
        $player3 = Player::factory()->create([
            'team_id' => 1,
            'position' => 'WR',
            'is_active' => true
        ]);
        
        // Add two players successfully
        $entry->players()->attach($player1->id, ['roster_position' => 'WR1']); 
        $entry->players()->attach($player2->id, ['roster_position' => 'WR2']);
        
        // Try to add third player from same team
        $response = $this->postJson("/entries/{$entry->id}/add-player", [
            'player_id' => $player3->id,
            'roster_position' => 'WR3'
        ]);
        
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['player_id']);
            
        $this->assertEquals(2, $entry->players()->where('players.team_id', 1)->count());
    }

    /** @test */
    public function entry_cannot_make_more_than_two_changes()
    {
        $user = User::factory()->create([
            'email_verified_at' => now()
        ]);
        $this->actingAs($user);
        
        $entry = Entry::factory()->create([
            'user_id' => $user->id,
            'changes_remaining' => 0
        ]);
        
        $oldPlayer = Player::factory()->create([
            'position' => 'WR',
            'is_active' => true
        ]);
        $newPlayer = Player::factory()->create([
            'position' => 'WR',
            'is_active' => true
        ]);
        
        $entry->players()->attach($oldPlayer->id, ['roster_position' => 'WR1']);
        
        $response = $this->postJson("/entries/{$entry->id}/transactions", [
            'dropped_player_id' => $oldPlayer->id,
            'added_player_id' => $newPlayer->id,
            'position' => 'WR1'
        ]);
        
        $response->assertStatus(422);
        $this->assertTrue($entry->players()->where('player_id', $oldPlayer->id)->exists());
    }

    /** @test */
    public function cannot_drop_player_after_game_starts()
    {
        $user = User::factory()->create([
            'email_verified_at' => now()
        ]);
        $entry = Entry::factory()->create(['user_id' => $user->id]);
        
        $player = Player::factory()->create([
            'is_active' => true
        ]);
        $newPlayer = Player::factory()->create([
            'is_active' => true
        ]);
        
        // Create a game that has started
        $game = Game::factory()->create([
            'home_team_id' => $player->team_id,
            'kickoff' => Carbon::now()->subHour()
        ]);
        
        $entry->players()->attach($player->id, ['roster_position' => 'WR1']);
        
        $response = $this->actingAs($user)->postJson("/entries/{$entry->id}/transactions", [
            'dropped_player_id' => $player->id,
            'added_player_id' => $newPlayer->id,
            'position' => 'WR1'
        ]);
        
        $response->assertStatus(422);
        $this->assertTrue($entry->players()->where('player_id', $player->id)->exists());
    }

    /** @test */
    public function calculate_player_scoring_correctly()
    {
        $player = Player::factory()->create([
            'is_active' => true
        ]);
        
        // Create stats for the player
        $stats = [
            'passing_yards' => 325, // 13 points + 4 bonus
            'passing_tds' => 2,     // 12 points
            'interceptions' => 1,    // -2 points
            'rushing_yards' => 45,   // 4 points
            'rushing_tds' => 1,      // 6 points
            'receptions' => 3,       // 1.5 points
            'receiving_yards' => 25, // 2 points
            'receiving_tds' => 0,    // 0 points
            'fumbles_lost' => 1,     // -2 points
            'two_point_conversions' => 1 // 2 points
        ];
        
        $playerStats = PlayerStats::create([
            'player_id' => $player->id,
            'game_id' => Game::factory()->create()->id,
            ...$stats
        ]);
        
        $expectedScore = 40.5; // Total of all points based on scoring rules
        
        $this->assertEquals($expectedScore, $player->calculateWeeklyScore($playerStats->game_id));
    }

    /** @test */
    public function entry_must_maintain_valid_roster_composition()
    {
        $user = User::factory()->create([
            'email_verified_at' => now()
        ]);
        $entry = Entry::factory()->create(['user_id' => $user->id]);
        
        $qb = Player::factory()->create([
            'position' => 'QB',
            'is_active' => true
        ]);
        $entry->players()->attach($qb->id, ['roster_position' => 'QB']);
        
        // Try to drop QB without adding new QB
        $wr = Player::factory()->create([
            'position' => 'WR',
            'is_active' => true
        ]);
        
        $response = $this->actingAs($user)->postJson("/entries/{$entry->id}/transactions", [
            'dropped_player_id' => $qb->id,
            'added_player_id' => $wr->id,
            'position' => 'WR1'
        ]);
        
        $response->assertStatus(422);
        $this->assertTrue($entry->players()->where('player_id', $qb->id)->exists());
    }
}