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

function toCellKey($i, $j)
{
    return "$i,$j";
}

function fromCellKey($key)
{
    return explode(',', $key);
}

function getNeighbors($directions, $i, $j)
{
    $neighbors = [];
    foreach ($directions as [$di, $dj]) {
        $neighbors[] = [$i + $di, $j + $dj];
    }
    return $neighbors;
}

function countLiveNeighbors($directions, $seed, $i, $j)
{
    $count = 0;
    $neighbors = getNeighbors($directions, $i, $j);
    foreach ($neighbors as [$ni, $nj]) {
        if (in_array([$ni, $nj], $seed)) {
            $count++;
        }
    }
    return $count;
}

function computeNextGeneration($seed, $directions)
{
    $nextGeneration = [];
    $cellsToCheck = [];     // Since there are no sets in PHP, we use a hashmap to prevent checking the same cell twice.
    foreach ($seed as [$i, $j]) {
        $cellsToCheck[toCellKey($i, $j)] = true;
        $neighbors = getNeighbors($directions, $i, $j);
        foreach ($neighbors as [$ni, $nj]) {
            $cellsToCheck[toCellKey($ni, $nj)] = true;
        }
    }

    foreach ($cellsToCheck as $key => $_) {
        [$i, $j] = fromCellKey($key);
        $countOfLiveNeighbors = countLiveNeighbors($directions, $seed, $i, $j);
        $isLive = in_array([$i, $j], $seed);
        if ($isLive) {
            // Any live cell with two or three live neighbors lives on to the next generation
            if ($countOfLiveNeighbors == 2 || $countOfLiveNeighbors == 3) {
                $nextGeneration[] = [$i, $j];
            }
        } else {
            // Any dead cell with exactly three live neighbors becomes a live cell
            if ($countOfLiveNeighbors == 3) {
                $nextGeneration[] = [$i, $j];
            }
        }
    }
    return $nextGeneration;
}

function printGlider($glider)
{
    // Determine bounding box coordinates (3×3 for a glider)
    $minI = PHP_INT_MAX;
    $maxI = -PHP_INT_MAX;
    $minJ = PHP_INT_MAX;
    $maxJ = -PHP_INT_MAX;

    foreach ($glider as [$i, $j]) {
        $minI = min($minI, $i);
        $maxI = max($maxI, $i);
        $minJ = min($minJ, $j);
        $maxJ = max($maxJ, $j);
    }
    echo "<pre>";
    // Print head
    for ($j = $minJ; $j <= $maxJ; $j++) {
        echo str_pad($j, 3, ' ', STR_PAD_LEFT) . " ";
    }
    echo PHP_EOL;
    // Print each row
    for ($i = $minI; $i <= $maxI; $i++) {
        echo $i . " ";
        for ($j = $minJ; $j <= $maxJ; $j++) {
            $isAlive = in_array([$i, $j], $glider);
            echo $isAlive ? '⬛' : '⬜';
            echo "  ";
        }
        echo PHP_EOL;
    }
    echo "</pre>";
    // Print the list of live cells
    echo "<pre>";
    foreach ($glider as [$i, $j]) {
        echo "[$i, $j]" . PHP_EOL;
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
    for ($i = 0; $i < NUM_OF_GENERATIONS; $i++) {
        echo "<h2>Generation " . ($i + 1) . "</h2>";
        printGlider($seed);
        $seed = computeNextGeneration($seed, $directions);
    }

    ?>
</body>

</html>