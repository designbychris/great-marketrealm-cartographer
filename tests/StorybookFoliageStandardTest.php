<?php
use PHPUnit\Framework\TestCase;
final class StorybookFoliageStandardTest extends TestCase {
 public function test_foliage_registry_is_optional_and_scene_safe(): void {
  $registry=(string)file_get_contents(__DIR__.'/../assets/foliage-library.js');
  $renderer=(string)file_get_contents(__DIR__.'/../assets/landscape.js');
  $workshop=(string)file_get_contents(__DIR__.'/../src/Workshop.php');
  self::assertStringContainsString('gmrcartFoliageLibrary', $registry);
  foreach (['oak','pine','orchard','goldenleaf','willow'] as $key) self::assertStringContainsString($key.':Object.freeze(', $registry);
  foreach (['mushroomWoods','frostreem','witheredReach','midnightMenu'] as $key) self::assertStringContainsString($key.':', $registry);
  self::assertStringContainsString('foliageLibrary.pick(x,y)', $renderer);
  self::assertStringContainsString("!illustratedTree(x,y,center)&&!sprite('tree'", $renderer);
  self::assertStringContainsString("wp_enqueue_script('gmrcart-foliage-library'", $workshop);
  self::assertStringNotContainsString('Math.random(', $registry);
 }
}
