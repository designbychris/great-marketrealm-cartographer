<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
final class SoftenedLandscapeTest extends TestCase {
 public function test_pixel_terrain_keeps_world_grid_and_adds_view_only_toggle(): void {
  $source=file_get_contents(__DIR__.'/../assets/landscape.js');
  self::assertStringContainsString("const seamless=illustrationStyle==='pixel'", $source);
  self::assertStringContainsString("neighbour===mat", $source);
  self::assertStringContainsString("Show terrain grid", $source);
  self::assertStringContainsString("aria-pressed", $source);
  self::assertStringContainsString("const tiles=Array.from(grid.querySelectorAll", $source);
  self::assertStringContainsString("if(depthDebug)", $source);
 }
}
