<?php
use PHPUnit\Framework\TestCase;
final class IllustratedMasterpieceTest extends TestCase {
    public function testIllustrationIsDerivedFromExistingSceneWithoutNewPersistence(): void {
        $renderer = file_get_contents(__DIR__ . '/../assets/landscape.js');
        self::assertStringContainsString('function terrainDetail(', $renderer);
        self::assertStringContainsString('function buildingDetails(', $renderer);
        self::assertStringContainsString('window.gmrcartObjects?.()', $renderer);
        self::assertStringContainsString('cameraNames[orientation]', $renderer);
        self::assertStringNotContainsString('fetch(', $renderer);
        self::assertStringNotContainsString('localStorage', $renderer);
    }
}
