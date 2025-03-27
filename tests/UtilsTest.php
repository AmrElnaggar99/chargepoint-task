<?php

use PHPUnit\Framework\TestCase;
use App\CellKeyUtils;

class UtilsTest extends TestCase
{

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
