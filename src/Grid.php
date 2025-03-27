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
    /**
     * Instead of checking the _currentGlider array, we convert it to a hashmap for faster lookups.
     */
    private array $gliderCellsMap;
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
     * Returns all the neighbors of a cell.
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
        $neighbors = self::computeNeighbors($row, $col);
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
