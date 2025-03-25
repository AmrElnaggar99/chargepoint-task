<?php

function toCellKey(int $row, int $col): string
{
    return "$row,$col";
}

function fromCellKey(string $key): array
{
    return explode(',', $key);
}
