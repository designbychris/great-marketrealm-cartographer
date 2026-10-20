<?php
use PHPUnit\Framework\TestCase;

final class StorybookCottageIntegrationTest extends TestCase
{
    public function testFourStorybookCameraSpritesAreCompleteRgbaImages(): void
    {
        foreach ([0, 90, 180, 270] as $angle) {
            $path = __DIR__ . '/../assets/sprites/buildings/cottage-storybook-' . $angle . '.png';
            self::assertFileExists($path);
            $size = getimagesize($path);
            self::assertSame(192, $size[0]);
            self::assertSame(178, $size[1]);
            self::assertSame(IMAGETYPE_PNG, $size[2]);
            $image = imagecreatefrompng($path);
            if ($image !== false) {
                self::assertTrue(imagecolortransparent($image) >= -1);
                imagedestroy($image);
            }
        }
    }

    public function testStorybookIsActiveAndPreviousPaintedSpriteRemainsFallback(): void
    {
        $js = file_get_contents(__DIR__ . '/../assets/building-illustrations.js');
        self::assertStringContainsString("views('cottage-storybook')", $js);
        self::assertStringContainsString("fallbackViews:Object.freeze(views('cottage-painted'))", $js);
    }
}
