<?php

namespace App;

use App\Utils\GridUtils;
use App\Utils\CellKeyUtils;

class Grid
{
    private const DIRECTIONS = [
        [-1,  0], // NORTH
        [1,  0],  // SOUTH
        [0, -1],  // WEST
        [0,  1],  // EAST
        [-1, -1], // NORTHWEST
        [-1,  1], // NORTHEAST
        [1, -1],  // SOUTHWEST
        [1,  1]   // SOUTHEAST
    ];
    private array $gliderCellsMap;  // Because checking for existence in a map is faster than in an array.
    private array $boundingBox;
    private bool $isEmpty;
    private array $currentGlider;
    public function __construct(array $_currentGlider)
    {
        $this->validateCurrentGlider($_currentGlider);
        $this->currentGlider = $_currentGlider;
        $this->gliderCellsMap = GridUtils::createGliderCellMap($_currentGlider);
        $this->boundingBox = GridUtils::computeBoundingBox($_currentGlider);
        $this->isEmpty = empty($_currentGlider);
    }

    /**
     * Ensure the currentGlider is in the expected format.
     */
    private function validateCurrentGlider(array $currentGlider): void
    {
        foreach ($currentGlider as $cell) {
            if (!is_array($cell) || count($cell) !== 2 || !is_int($cell[0]) || !is_int($cell[1])) {
                throw new \InvalidArgumentException('Invalid glider format. Each cell must be an array of two integers: [row, col].');
            }
        }
    }
    /**
     * Computes all the possible cells needed to be checked.
     * This includes the cells of the current glider and their 8 neighbors without duplicates.
     * @return array The cells to check as an associative array where the keys are cell keys "row,col" and the values are true.
     * The associative array is used to prevent duplicates because PHP does not have a Set.
     */
    public function computeCellsToCheck(): array
    {
        $cellsToCheck = [];
        foreach ($this->currentGlider as [$row, $col]) {
            $cellsToCheck[CellKeyUtils::toCellKey($row, $col)] = true;
            $neighbors = $this->computeNeighbors($row, $col);
            foreach ($neighbors as [$nRow, $nCol]) {
                $cellsToCheck[CellKeyUtils::toCellKey($nRow, $nCol)] = true;
            }
        }

        return $cellsToCheck;
    }

    /**
     * Returns all the possible 8 neighbors of a cell in the infinite grid.
     * @param int $row The row of the cell.
     * @param int $col The column of the cell.
     * @return array The neighbors of the cell.
     */
    public function computeNeighbors(int $row, int $col): array
    {
        $neighbors = [];
        foreach (self::DIRECTIONS as [$dRow, $dCol]) {
            $neighbors[] = [$row + $dRow, $col + $dCol];
        }
        return $neighbors;
    }

    public function countLiveNeighbors(int $row, int $col): int
    {
        $count = 0;
        $neighbors = $this->computeNeighbors($row, $col);
        foreach ($neighbors as [$nRow, $nCol]) {
            if (isset($this->gliderCellsMap[CellKeyUtils::toCellKey($nRow, $nCol)])) {
                $count++;
            }
        }
        return $count;
    }

    public function getGliderCellsMap(): array
    {
        return $this->gliderCellsMap;
    }

    public function getBoundingBox(): array
    {
        return $this->boundingBox;
    }

    public function isEmpty(): bool
    {
        return $this->isEmpty;
    }

    public function getCurrentGlider(): array
    {
        return $this->currentGlider;
    }
}
