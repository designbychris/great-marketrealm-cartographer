<?php
use PHPUnit\Framework\TestCase;

final class StorybookCottageArtTest extends TestCase
{
    public function testFourDistinctTransparentNativeSpritesExist(): void
    {
        $hashes = [];
        foreach ([0, 90, 180, 270] as $angle) {
            $path = __DIR__ . '/../assets/sprites/buildings/cottage-storybook-' . $angle . '.png';
            self::assertFileExists($path);
            $size = getimagesize($path);
            self::assertIsArray($size);
            self::assertSame(192, $size[0]);
            self::assertSame(178, $size[1]);
            self::assertSame(IMAGETYPE_PNG, $size[2]);
            $bytes = file_get_contents($path);
            self::assertNotFalse($bytes);
            self::assertSame(6, ord($bytes[25]), 'PNG must use RGBA colour type');
            $hashes[] = hash('sha256', $bytes);
        }
        self::assertCount(4, array_unique($hashes));
    }

    public function testLivePaintedCottageIsNotReplacedBeforeApproval(): void
    {
        $js = file_get_contents(__DIR__ . '/../assets/building-illustrations.js');
        self::assertNotFalse($js);
        self::assertStringContainsString("views('cottage-painted')", $js);
        self::assertStringContainsString("fallbackViews:Object.freeze(views('cottage'))", $js);
    }
}
