<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\GameOfLife;
use App\Grid;

class GameOfLifeTest extends TestCase
{
    /**
     * Test computeNextGlider with a simple glider pattern.
     * This ensures the Game of Life rules are applied.
     */
    public function testComputeNextGlider(): void
    {
        $initialGlider = [
            [0, 1],
            [1, 2],
            [2, 0],
            [2, 1],
            [2, 2]
        ];

        $expectedNextGlider = [
            [1, 0],
            [1, 2],
            [2, 2],
            [2, 1],
            [3, 1]
        ];

        $grid = new Grid($initialGlider);
        $gameOfLife = new GameOfLife($grid);

        $nextGlider = $gameOfLife->computeNextGlider();

        $this->assertEqualsCanonicalizing($expectedNextGlider, $nextGlider);
    }

    /**
     * Test computeNextGlider with an empty grid.
     */
    public function testComputeNextGliderWithEmptyGrid(): void
    {
        $initialGlider = [];

        $grid = new Grid($initialGlider);
        $gameOfLife = new GameOfLife($grid);

        $nextGlider = $gameOfLife->computeNextGlider();

        $this->assertEmpty($nextGlider);
    }

    /**
     * Test computeNextGlider with a static glider.
     * The static glider should generate an exactly similar glider.
     */
    public function testComputeNextGliderWithStaticPattern(): void
    {
        $initialGlider = [
            [1, 1],
            [1, 2],
            [2, 1],
            [2, 2],
        ];

        $expectedNextGlider = $initialGlider;

        $grid = new Grid($initialGlider);
        $gameOfLife = new GameOfLife($grid);

        $nextGlider = $gameOfLife->computeNextGlider();

        $this->assertEqualsCanonicalizing($expectedNextGlider, $nextGlider);
    }

    /**
     * Test computeNextGlider with a glider that will generate an empty generation.
     * The next glider should be empty.
     */
    public function testComputeNextGliderThatIsEmpty(): void
    {
        $initialGlider = [
            [2, 1],
            [2, 2]
        ];
        $expectedNextGlider = [];
        $grid = new Grid($initialGlider);
        $gameOfLife = new GameOfLife($grid);

        $nextGlider = $gameOfLife->computeNextGlider();
        $this->assertEqualsCanonicalizing($expectedNextGlider, $nextGlider);
    }
}
