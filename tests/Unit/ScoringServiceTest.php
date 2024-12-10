<?php

namespace Tests\Unit;

use App\Services\ScoringService;
use PHPUnit\Framework\TestCase;

class ScoringServiceTest extends TestCase
{
    private $scoringService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->scoringService = new ScoringService();
    }

    public function test_passing_points_calculation()
    {
        // Test basic passing yards
        $this->assertEquals(10, $this->scoringService->calculatePassingPoints(250, 0, 0));
        
        // Test passing yards with bonuses
        $this->assertEquals(16, $this->scoringService->calculatePassingPoints(300, 0, 0));
        $this->assertEquals(24, $this->scoringService->calculatePassingPoints(400, 0, 0));
        
        // Test passing touchdowns
        $this->assertEquals(6, $this->scoringService->calculatePassingPoints(0, 1, 0));
        
        // Test interceptions
        $this->assertEquals(-2, $this->scoringService->calculatePassingPoints(0, 0, 1));
        
        // Test complete scenario
        $this->assertEquals(28, $this->scoringService->calculatePassingPoints(325, 2, 1));
    }

    public function test_rushing_points_calculation()
    {
        // Similar test cases for rushing
    }

    public function test_receiving_points_calculation()
    {
        // Similar test cases for receiving
    }

    public function test_misc_points_calculation()
    {
        // Similar test cases for miscellaneous points
    }
}