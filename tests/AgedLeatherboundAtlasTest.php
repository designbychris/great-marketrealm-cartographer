<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
final class AgedLeatherboundAtlasTest extends TestCase {
 public function test_aged_leather_is_scoped_to_the_illustrated_frame(): void {
  $css=file_get_contents(__DIR__.'/../assets/canvas.css');
  self::assertStringContainsString('VI.6D.14A', $css);
  self::assertStringContainsString("textures/keeper-leather-grain.png", $css);
  self::assertStringContainsString('.gmrcart-landscape-frame::before', $css);
  self::assertStringContainsString('.gmrcart-landscape-frame::after', $css);
  self::assertStringContainsString('pointer-events:none', $css);
 }
 public function test_local_texture_is_valid_transparent_png(): void {
  $file=__DIR__.'/../assets/textures/keeper-leather-grain.png';
  self::assertFileExists($file);
  $info=getimagesize($file);
  self::assertSame(IMAGETYPE_PNG,$info[2]);
  self::assertSame(384,$info[0]);
  self::assertSame(384,$info[1]);
 }
}
