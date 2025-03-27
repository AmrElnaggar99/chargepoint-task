<?php

namespace App\View;

use App\Utils\CellKeyUtils;
use App\Grid;

class GridRenderer
{
    private const LIVE_CELL_SYMBOL = '⬛';
    private const DEAD_CELL_SYMBOL = '⬜';
    private Grid $grid;
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
    }
    /**
     * Renders the glider with a list of live cells as an HTML markup.
     * @param array $glider The glider to render.
     * The glider is an array of cells, where each cell is an array of two integers: [row, col].
     * @return void
     */
    public function renderGliderInfo(): void
    {
        if ($this->grid->isEmpty()) {
            echo "No live cells";
            return;
        }
        [$minRow, $maxRow, $minCol, $maxCol] = $this->grid->getBoundingBox();
        $currentGlider = $this->grid->getCurrentGlider();
        $gliderCellsMap = $this->grid->getGliderCellsMap();

        echo self::renderGrid($gliderCellsMap, $minRow, $maxRow, $minCol, $maxCol);
        echo "<strong>Live cells: </strong>";
        foreach ($currentGlider as [$row, $col]) {
            echo "[$row, $col]";
            echo PHP_EOL;
        }
    }

    /**
     * Returns the HTML markup for the grid.
     * @param array $gliderCellsMap The glider cells map.
     * The glider cells map is an associative array where the keys are cell keys "row,col" and the values are true.
     * @param int $minRow The minimum row index.
     * @param int $maxRow The maximum row index.
     * @param int $minCol The minimum column index.
     * @param int $maxCol The maximum column index.
     * @return string The HTML markup for the grid.
     */
    private function renderGrid(array $gliderCellsMap, int $minRow, int $maxRow, int $minCol, int $maxCol): string
    {
        $output = "<pre>";
        // Add column headers
        $output .= "\t";
        for ($col = $minCol; $col <= $maxCol; $col++) {
            $output .= $col . "\t";
        }
        $output .= PHP_EOL;
        // Add rows
        for ($row = $minRow; $row <= $maxRow; $row++) {
            $output .= $row . "\t";
            for ($col = $minCol; $col <= $maxCol; $col++) {
                $isLive = isset($gliderCellsMap[CellKeyUtils::toCellKey($row, $col)]);
                $output .= $isLive ? self::LIVE_CELL_SYMBOL : self::DEAD_CELL_SYMBOL;
                $output .= "\t";
            }
            $output .= PHP_EOL;
        }
        $output .= "</pre>";

        return $output;
    }
}
