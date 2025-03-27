<?php

namespace App\Utils;

class GridUtils
{
    private function __construct() {}
    /**
     * Calculates the bounding box of a grid.
     * @param array $currentGlider The grid to calculate the bounding box for.
     *  The currentGlider is an array of cells, where each cell is an array of two integers: [row, col].
     * @return array The bounding box as an array of four integers: [minRow, maxRow, minCol, maxCol].
     */
    public static function computeBoundingBox(array $currentGlider): array
    {
        $minRow = PHP_INT_MAX;
        $maxRow = -PHP_INT_MAX;
        $minCol = PHP_INT_MAX;
        $maxCol = -PHP_INT_MAX;

        foreach ($currentGlider as [$row, $col]) {
            $minRow = min($minRow, $row);
            $maxRow = max($maxRow, $row);
            $minCol = min($minCol, $col);
            $maxCol = max($maxCol, $col);
        }

        return [$minRow, $maxRow, $minCol, $maxCol];
    }
    /**
     * Creates a map of glider cells for faster lookups.
     * @param array $currentGlider The array of the glider cells to create the map for.
     * The currentGlider is an array of cells, where each cell is an array of two integers: [row, col].
     * @return array The map of glider cells as an associative array.
     */
    public static function createGliderCellMap(array $currentGlider): array
    {
        $gliderCellsMap = [];
        foreach ($currentGlider as [$row, $col]) {
            $gliderCellsMap[CellKeyUtils::toCellKey($row, $col)] = true;
        }
        return $gliderCellsMap;
    }
}
