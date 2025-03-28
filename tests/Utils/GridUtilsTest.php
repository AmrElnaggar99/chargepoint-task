<?php

namespace Tests\Utils;

use PHPUnit\Framework\TestCase;
use App\Utils\GridUtils;
use App\Utils\CellKeyUtils;

class GridUtilsTest extends TestCase
{
    /**
     * Test that GridUtils cannot be instantiated.
     */
    public function testCannotInstantiateGridUtils(): void
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Call to private App\Utils\GridUtils::__construct()');

        new GridUtils();
    }
    /**
     * Test the computeBoundingBox method with a non-empty grid.
     */
    public function testComputeBoundingBoxWithNonEmptyGrid()
    {
        $currentGlider = [[1, 2], [3, 4], [5, 6]];
        $expectedBoundingBox = [1, 5, 2, 6];
        $result = GridUtils::computeBoundingBox($currentGlider);
        $this->assertEquals($expectedBoundingBox, $result);
    }

    /**
     * Test the computeBoundingBox method with an empty grid.
     */
    public function testComputeBoundingBoxWithEmptyGrid()
    {
        $currentGlider = [];
        $expectedBoundingBox = [PHP_INT_MAX, -PHP_INT_MAX, PHP_INT_MAX, -PHP_INT_MAX];
        $result = GridUtils::computeBoundingBox($currentGlider);
        $this->assertEquals($expectedBoundingBox, $result);
    }

    /**
     * Test createGliderCellMap with a valid glider.
     */
    public function testCreateGliderCellMap(): void
    {
        $glider = [
            [1, 2],
            [3, 4],
            [5, 6],
        ];

        $gliderCellsMap = GridUtils::createGliderCellMap($glider);

        $expectedMap = [
            CellKeyUtils::toCellKey(1, 2) => true,
            CellKeyUtils::toCellKey(3, 4) => true,
            CellKeyUtils::toCellKey(5, 6) => true,
        ];

        $this->assertEquals($expectedMap, $gliderCellsMap);
    }
    /**
     * Test createGliderCellMap with an empty glider.
     */
    /**
     * Test createGliderCellMap with an empty glider.
     */
    public function testCreateGliderCellMapWithEmptyGlider(): void
    {
        $glider = [];

        $gliderCellsMap = GridUtils::createGliderCellMap($glider);

        $this->assertEmpty($gliderCellsMap);
    }
}
