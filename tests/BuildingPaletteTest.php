<?php
use PHPUnit\Framework\TestCase;

final class BuildingPaletteTest extends TestCase
{
    public function testPaletteUsesPatternBookAndDisablesUnpaintedEntries(): void
    {
        $js = file_get_contents(__DIR__ . '/../assets/building-palette.js');
        self::assertStringContainsString('book.list()', $js);
        self::assertStringContainsString("pattern.status==='painted'", $js);
        self::assertStringContainsString('card.disabled=!painted', $js);
        self::assertStringContainsString('gmrcart:choose-pattern', $js);
    }

    public function testSelectionPreservesAppearanceWhenPlacing(): void
    {
        $js = file_get_contents(__DIR__ . '/../assets/canvas.js');
        self::assertStringContainsString("pendingAppearance=detail.appearance", $js);
        self::assertStringContainsString("appearance:pendingAppearance!=='default'", $js);
        self::assertStringContainsString("current.appearance=detail.appearance", $js);
    }

    public function testPaletteIsLoadedAfterPatternBook(): void
    {
        $php = file_get_contents(__DIR__ . '/../src/Workshop.php');
        self::assertStringContainsString('gmrcart-building-palette', $php);
        self::assertStringContainsString('gmrcart-pattern-cards', $php);
        self::assertStringContainsString("['gmrcart-canvas', 'gmrcart-building-illustrations']", $php);
    }
}
