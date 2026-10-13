<?php
use PHPUnit\Framework\TestCase;
final class IllustratedLandscapeTest extends TestCase {
    public function testIllustratedViewIsNonDestructiveAndOptional(): void {
        $workshop = file_get_contents(__DIR__ . '/../src/Workshop.php');
        $js = file_get_contents(__DIR__ . '/../assets/landscape.js');
        self::assertStringContainsString('gmrcart-view-toggle', $workshop);
        self::assertStringContainsString('gmrcart-landscape', $workshop);
        self::assertStringContainsString("['gmrcart-canvas']", $workshop);
        self::assertStringContainsString("grid.hidden=active", $js);
        self::assertStringContainsString("preview.hidden=!active", $js);
        self::assertStringNotContainsString('fetch(', $js);
        self::assertStringNotContainsString('postMessage(', $js);
    }
}
