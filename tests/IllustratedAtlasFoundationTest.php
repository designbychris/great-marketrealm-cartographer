<?php
use PHPUnit\Framework\TestCase;
final class IllustratedAtlasFoundationTest extends TestCase {
    public function testAtlasRegistryIsPresentationOnlyAndEnqueuedBeforeRenderer(): void {
        $workshop = file_get_contents(__DIR__ . '/../src/Workshop.php');
        $atlas = file_get_contents(__DIR__ . '/../assets/atlas-palettes.js');
        $renderer = file_get_contents(__DIR__ . '/../assets/landscape.js');
        self::assertStringContainsString('1760px', $workshop);
        self::assertStringContainsString('gmrcart-atlas-palettes', $workshop);
        self::assertStringContainsString("['gmrcart-canvas', 'gmrcart-atlas-palettes', 'gmrcart-atlas-assets', 'gmrcart-architectural-sketchbook', 'gmrcart-building-illustrations']", $workshop);
        self::assertStringContainsString("wp_enqueue_script('gmrcart-atlas-assets'", $workshop);
        self::assertStringContainsString('marketrealm:', $atlas);
        self::assertStringContainsString('withered:', $atlas);
        self::assertStringContainsString('takeaway:', $atlas);
        self::assertStringContainsString('gmrcartAtlasPalettes', $renderer);
        self::assertStringNotContainsString('fetch(', $atlas);
    }
}
