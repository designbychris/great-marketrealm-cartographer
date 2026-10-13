<?php
use PHPUnit\Framework\TestCase;
final class CameraRotationTest extends TestCase {
    public function testCameraControlsArePreviewOnlyAndHaveFourOrientations(): void {
        $workshop = file_get_contents(__DIR__ . '/../src/Workshop.php');
        $js = file_get_contents(__DIR__ . '/../assets/landscape.js');
        self::assertStringContainsString('gmrcart-camera-controls', $workshop);
        self::assertStringContainsString('data-gmrcart-camera', $workshop);
        self::assertStringContainsString('function orient(x,y)', $js);
        self::assertStringContainsString('case 1:', $js);
        self::assertStringContainsString('case 2:', $js);
        self::assertStringContainsString('case 3:', $js);
        self::assertStringContainsString('orientation=(orientation+', $js);
        self::assertStringContainsString('schedule();', $js);
        self::assertStringNotContainsString('fetch(', $js);
        self::assertStringNotContainsString('postMessage(', $js);
    }
}
