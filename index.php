<?php

require __DIR__ . '/vendor/autoload.php';

use App\GameOfLife;
use App\Grid;
use App\View\GliderRenderer;
use App\View\GridRenderer;

const NUM_OF_GENERATIONS = 8;
$currentGlider = [
    [0, 1],
    [1, 2],
    [2, 0],
    [2, 1],
    [2, 2]
];
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
    for ($row = 0; $row < NUM_OF_GENERATIONS; $row++) {
        echo "<h2>Generation " . ($row + 1) . "</h2>";
        $grid = new Grid($currentGlider);
        $gridRenderer = new GridRenderer($grid);
        $gridRenderer->renderGliderInfo();
        $game = new GameOfLife($grid);
        $currentGlider = $game->computeNextGlider();
    }

    ?>
</body>

</html>