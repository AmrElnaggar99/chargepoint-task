<?php

namespace Tests\View;

use PHPUnit\Framework\TestCase;
use App\View\GridRenderer;
use App\Grid;

class GridRendererTest extends TestCase
{
    /**
     * Test renderGliderInfo with a non-empty grid.
     */
    public function testRenderGliderInfoWithNonEmptyGrid(): void
    {
        $glider = [[1, 2], [3, 4], [5, 6]];

        $grid = new Grid($glider);
        $renderer = new GridRenderer($grid);

        ob_start();
        $renderer->renderGliderWithInfo();
        $output = ob_get_clean();

        $reflection = new \ReflectionClass(GridRenderer::class);
        $LIVE_CELL_SYMBOL = $reflection->getConstant("LIVE_CELL_SYMBOL");
        $DEAD_CELL_SYMBOL = $reflection->getConstant("DEAD_CELL_SYMBOL");

        $this->assertIsString($LIVE_CELL_SYMBOL);
        $this->assertIsString($DEAD_CELL_SYMBOL);
        $this->assertStringContainsString($LIVE_CELL_SYMBOL, $output);
        $this->assertStringContainsString($DEAD_CELL_SYMBOL, $output);
        $this->assertStringContainsString("[1, 2]", $output);
        $this->assertStringContainsString("[3, 4]", $output);
        $this->assertStringContainsString("[5, 6]", $output);
        $this->assertStringContainsString("1", $output, "The output should contain minRow");
        $this->assertStringContainsString("5", $output, "The output should contain maxRow");
        $this->assertStringContainsString("2", $output, "The output should contain minCol");
        $this->assertStringContainsString("6", $output, "The output should contain maxCol");
    }

    /**
     * Test renderGliderInfo with an empty grid.
     */
    public function testRenderGliderInfoWithEmptyGrid(): void
    {
        $glider = [];

        $grid = new Grid($glider);
        $renderer = new GridRenderer($grid);

        ob_start();
        $renderer->renderGliderWithInfo();
        $output = ob_get_clean();

        $this->assertStringContainsString("No live cells", $output);
    }
}
