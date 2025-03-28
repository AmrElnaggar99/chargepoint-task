<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Grid;
use App\Utils\CellKeyUtils;

class GridTest extends TestCase
{
    /**
     * Test the constructor with a valid glider.
     */
    public function testConstructorWithValidGlider(): void
    {
        $glider = [
            [1, 2],
            [3, 4],
            [5, 6],
        ];

        $grid = new Grid($glider);

        $this->assertEquals($glider, $grid->getCurrentGlider());
    }

    /**
     * Test the constructor with an invalid glider.
     */
    public function testConstructorWithInvalidGlider(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $invalidGlider = [
            [1, 2],
            [3],
        ];

        new Grid($invalidGlider);
    }

    /**
     * Test computeNeighbors method.
     */
    public function testComputeNeighbors(): void
    {
        $glider = [
            [1, 1],
        ];

        $grid = new Grid($glider);

        $neighbors = $grid->computeNeighbors(1, 1);

        $expectedNeighbors = [
            [0, 1],
            [2, 1],
            [1, 0],
            [1, 2],
            [0, 0],
            [0, 2],
            [2, 0],
            [2, 2],
        ];

        $this->assertEquals($expectedNeighbors, $neighbors);
    }

    /**
     * Test countLiveNeighbors method.
     */
    public function testCountLiveNeighbors(): void
    {
        $glider = [
            [1, 1],
            [1, 2],
            [2, 1],
        ];

        $grid = new Grid($glider);
        $neighbors = $grid->computeNeighbors(1, 1);
        $liveNeighbors = $grid->countLiveNeighbors($neighbors);

        $this->assertEquals(2, $liveNeighbors);
    }
}
