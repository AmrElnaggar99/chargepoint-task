<?php

namespace App;

use App\Utils\CellKeyUtils;

class GameOfLife
{
    private Grid $grid;
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
    }

    /**
     * Computes the next glider of live cells based on the previous state and the Game of Life rules.
     * @return array The next glider of live cells.
     */
    public function computeNextGlider(): array
    {
        $cellsToCheck = $this->grid->computeCellsToCheck();
        $gliderCellsMap = $this->grid->getGliderCellsMap();
        return $this->determineShapeOfNextGlider($cellsToCheck, $gliderCellsMap);
    }

    private function determineShapeOfNextGlider(array $cellsToCheck, array $gliderCellsMap): array
    {
        $nextGlider = [];
        foreach ($cellsToCheck as $key => $_) {
            [$row, $col] = CellKeyUtils::fromCellKey($key);
            $countOfLiveNeighbors = $this->grid->countLiveNeighbors($row, $col);
            $isLive = isset($gliderCellsMap[CellKeyUtils::toCellKey($row, $col)]);
            if ($isLive) {
                // Any live cell with two or three live neighbors lives on to the next generation.
                if ($countOfLiveNeighbors == 2 || $countOfLiveNeighbors == 3) {
                    $nextGlider[] = [$row, $col];
                }
            } elseif ($countOfLiveNeighbors == 3) {
                // Any dead cell with exactly three live neighbors becomes a live cell, as if by reproduction
                $nextGlider[] = [$row, $col];
            }
        }
        return $nextGlider;
    }
}
