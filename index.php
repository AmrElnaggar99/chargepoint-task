<?php

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

function toCellKey(int $row, int $col): string
{
    return "$row,$col";
}

function fromCellKey(string $key): array
{
    return explode(',', $key);
}

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

function buildLiveMap(array $arr): array
{
    $liveCellsMap = [];
    foreach ($arr as [$row, $col]) {
        $liveCellsMap[toCellKey($row, $col)] = true;
    }
    return $liveCellsMap;
}

function computeNextGeneration(array $seed, array $directions): array
{
    $liveCellsMap = buildLiveMap($seed); // Instead of checking the seed array, we convert it to a hashmap for faster lookups.
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

function printGlider(array $glider): void
{
    if (empty($glider)) {
        echo "No live cells";
        return;
    }
    $liveCellsMap = buildLiveMap($glider); // Instead of checking the glider array, we convert it to a hashmap for faster lookups.
    // Determine bounding box coordinates
    $minRow = PHP_INT_MAX;
    $maxRow = -PHP_INT_MAX;
    $minCol = PHP_INT_MAX;
    $maxCol = -PHP_INT_MAX;

    foreach ($glider as [$row, $col]) {
        $minRow = min($minRow, $row);
        $maxRow = max($maxRow, $row);
        $minCol = min($minCol, $col);
        $maxCol = max($maxCol, $col);
    }
    echo "<pre>";
    // Print head
    echo "\t";
    for ($col = $minCol; $col <= $maxCol; $col++) {
        echo $col . "\t";
    }
    echo PHP_EOL;
    // Print each row
    for ($row = $minRow; $row <= $maxRow; $row++) {
        echo $row . "\t";
        for ($col = $minCol; $col <= $maxCol; $col++) {
            $rowsAlive = isset($liveCellsMap[toCellKey($row, $col)]);
            echo $rowsAlive ? '⬛' : '⬜';
            echo "\t";
        }
        echo PHP_EOL;
    }
    echo "</pre>";
    // Print the list of live cells
    echo "<pre>";
    foreach ($glider as [$row, $col]) {
        echo "[$row, $col]" . PHP_EOL;
    }
    echo "</pre>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChargePoint Task - Amr Elnaggar</title>
    <style>
        pre {
            font-size: 18px;
            tab-size: 4;
        }
    </style>
</head>

<body>
    <?php
    const NUM_OF_GENERATIONS = 8;
    $seed = [
        [0, 1],
        [1, 2],
        [2, 0],
        [2, 1],
        [2, 2]
    ];
    for ($row = 0; $row < NUM_OF_GENERATIONS; $row++) {
        echo "<h2>Generation " . ($row + 1) . "</h2>";
        printGlider($seed);
        $seed = computeNextGeneration($seed, $directions);
    }

    ?>
</body>

</html>