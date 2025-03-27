<?php

namespace App\Utils;

final class CellKeyUtils
{
    private function __construct() {}
    public static function toCellKey(int $row, int $col): string
    {
        return "$row,$col";
    }

    public static function fromCellKey(string $key): array
    {
        $parts = explode(',', $key);
        if (count($parts) !== 2 || !is_numeric($parts[0]) || !is_numeric($parts[1])) {
            throw new \InvalidArgumentException("Invalid cell key format: $key");
        }
        return [(int)$parts[0], (int)$parts[1]];
    }
}
