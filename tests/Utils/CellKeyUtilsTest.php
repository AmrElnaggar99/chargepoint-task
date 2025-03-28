<?php

use PHPUnit\Framework\TestCase;
use App\Utils\CellKeyUtils;

class CellKeyUtilsTest extends TestCase
{
    /**
     * Test that CellKeyUtils cannot be instantiated.
     */
    public function testCannotInstantiateCellKeyUtils(): void
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Call to private App\Utils\CellKeyUtils::__construct()');

        new CellKeyUtils();
    }
    /**
     * The output of toCellKey can be used as the input to fromCellKey
     */
    public function testTheOutputOfToCellKeyCanBeUsedAsInputToFromCellKey()
    {
        $row = 5;
        $col = 10;
        $cellKey = CellKeyUtils::toCellKey($row, $col);
        $result = CellKeyUtils::fromCellKey($cellKey);

        $this->assertEquals([$row, $col], $result);
    }
}
