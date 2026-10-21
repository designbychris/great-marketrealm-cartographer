<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
final class StorybookTavernTest extends TestCase {
 public function test_tavern_is_registered_as_a_painted_three_by_two_prefab(): void {
  $js=file_get_contents(__DIR__.'/../assets/building-illustrations.js');
  self::assertStringContainsString("const tavern=Object.freeze({", $js);
  self::assertStringContainsString("footprint:Object.freeze([3,2])", $js);
  self::assertStringContainsString("views('tavern-storybook')", $js);
  self::assertStringContainsString("const buildings=Object.freeze({cottage,villageHouse,tavern})", $js);
 }
 public function test_four_transparent_tavern_views_exist(): void {
  foreach ([0,90,180,270] as $angle) {
   $path=__DIR__.'/../assets/sprites/buildings/tavern-storybook-'.$angle.'.png';
   self::assertFileExists($path);
   $info=getimagesize($path);
   self::assertSame(IMAGETYPE_PNG,$info[2]);
  }
 }
}
