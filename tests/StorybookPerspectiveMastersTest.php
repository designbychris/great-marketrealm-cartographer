<?php
use PHPUnit\Framework\TestCase;

final class StorybookPerspectiveMastersTest extends TestCase
{
    public function testFourPaddedRgbaMastersAndUnassignedCameraDirections(): void
    {
        $root = dirname(__DIR__);
        $manifest = json_decode((string) file_get_contents($root . '/storybook-cottage-perspectives.json'), true);
        self::assertCount(4, $manifest['views']);
        foreach ($manifest['views'] as $view) {
            self::assertNull($view['camera_angle']);
            $path = $root . '/assets/sprites/buildings/' . $view['master_sprite'];
            self::assertFileExists($path);
            $size = getimagesize($path);
            self::assertSame(640, $size[0]);
            self::assertSame(560, $size[1]);
            self::assertSame(6, ord(file_get_contents($path)[25]));
        }
    }

    public function testLiveCottageIsNotChangedBeforeArtApproval(): void
    {
        $js = (string) file_get_contents(dirname(__DIR__) . '/assets/building-illustrations.js');
        self::assertStringContainsString("views('cottage-painted')", $js);
    }
}
