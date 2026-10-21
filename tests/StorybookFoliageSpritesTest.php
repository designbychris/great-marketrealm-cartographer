<?php
use PHPUnit\Framework\TestCase;
final class StorybookFoliageSpritesTest extends TestCase {
    public function test_five_varieties_have_four_real_png_views(): void {
        $base = dirname(__DIR__);
        $registry = file_get_contents($base . '/assets/foliage-library.js');
        foreach (['oak','pine','orchard','goldenleaf','willow'] as $name) {
            $this->assertStringContainsString("foliage/" . $name . "-'", $registry);
            foreach ([0,90,180,270] as $angle) {
                $path = $base . "/assets/sprites/foliage/{$name}-{$angle}.png";
                $this->assertFileExists($path);
                $size = getimagesize($path);
                $this->assertSame('image/png', $size['mime']);
                $this->assertSame(192, $size[0]);
                $this->assertSame(340, $size[1]);
            }
        }
    }
    public function test_foliage_registry_is_a_landscape_dependency(): void {
        $workshop = file_get_contents(dirname(__DIR__) . '/src/Workshop.php');
        $this->assertStringContainsString("'gmrcart-building-illustrations', 'gmrcart-foliage-library'", $workshop);
    }
}
