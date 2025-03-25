<?php
require("model.php");

function calculateBoundingBox(array $glider): array
{
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

    return [$minRow, $maxRow, $minCol, $maxCol];
}

function printGlider(array $glider): void
{
    if (empty($glider)) {
        echo "No live cells";
        return;
    }
    $liveCellsMap = buildLiveMap($glider); // Instead of checking the glider array, we convert it to a hashmap for faster lookups.
    [$minRow, $maxRow, $minCol, $maxCol] = calculateBoundingBox($glider);

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
