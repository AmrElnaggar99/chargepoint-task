<?php
require("utils.php");

const NORTH     = [-1,  0];
const SOUTH     = [1,  0];
const WEST      = [0, -1];
const EAST      = [0,  1];
const NORTHWEST = [-1, -1];
const NORTHEAST = [-1,  1];
const SOUTHWEST = [1, -1];
const SOUTHEAST = [1,  1];

$directions = [
    NORTH,
    SOUTH,
    WEST,
    EAST,
    NORTHWEST,
    NORTHEAST,
    SOUTHWEST,
    SOUTHEAST
];

function getNeighbors(array $directions, int $row, int $col): array
{
    $neighbors = [];
    foreach ($directions as [$dRow, $dCol]) {
        $neighbors[] = [$row + $dRow, $col + $dCol];
    }
    return $neighbors;
}

function countLiveNeighbors(array $directions, array $liveCellsMap, int $row, int $col): int
{
    $count = 0;
    $neighbors = getNeighbors($directions, $row, $col);
    foreach ($neighbors as [$nRow, $nCol]) {
        if (isset($liveCellsMap[toCellKey($nRow, $nCol)])) {
            $count++;
        }
    }
    return $count;
}

function createLiveCellMap(array $arr): array
{
    $liveCellsMap = [];
    foreach ($arr as [$row, $col]) {
        $liveCellsMap[toCellKey($row, $col)] = true;
    }
    return $liveCellsMap;
}

/**
 * Computes the next generation of live cells based on the previous state and the Game of Life rules.
 *
 * @param array $seed The current generation of live cells.
 * @param array $directions The possible directions for neighbors.
 * @return array The next generation of live cells.
 */
function computeNextGeneration(array $seed, array $directions): array
{
    $liveCellsMap = createLiveCellMap($seed); // Instead of checking the seed array, we convert it to a hashmap for faster lookups.
    $nextGeneration = [];
    $cellsToCheck = [];     // Since there are no sets in PHP, we use a hashmap to prevent checking the same cell twice.
    foreach ($seed as [$row, $col]) {
        $cellsToCheck[toCellKey($row, $col)] = true;
        $neighbors = getNeighbors($directions, $row, $col);
        foreach ($neighbors as [$nRow, $nCol]) {
            $cellsToCheck[toCellKey($nRow, $nCol)] = true;
        }
    }
    foreach ($cellsToCheck as $key => $_) {
        [$row, $col] = fromCellKey($key);
        $countOfLiveNeighbors = countLiveNeighbors($directions, $liveCellsMap, $row, $col);
        $rowsLive = isset($liveCellsMap[toCellKey($row, $col)]);
        if ($rowsLive) {
            // Any live cell with two or three live neighbors lives on to the next generation
            if ($countOfLiveNeighbors == 2 || $countOfLiveNeighbors == 3) {
                $nextGeneration[] = [$row, $col];
            }
        } else {
            // Any dead cell with exactly three live neighbors becomes a live cell
            if ($countOfLiveNeighbors == 3) {
                $nextGeneration[] = [$row, $col];
            }
        }
    }
    return $nextGeneration;
}
