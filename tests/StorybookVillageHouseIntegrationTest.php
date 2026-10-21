<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
final class StorybookVillageHouseIntegrationTest extends TestCase {
 public function test_four_storybook_house_views_and_original_fallback_are_registered(): void {
  $root=dirname(__DIR__);
  $js=(string)file_get_contents($root.'/assets/building-illustrations.js');
  self::assertStringContainsString("views('village-house-storybook')",$js);
  self::assertStringContainsString("fallbackViews:Object.freeze(views('village-house-painted'))",$js);
  foreach ([0,90,180,270] as $angle) {
   $file=$root.'/assets/sprites/buildings/village-house-storybook-'.$angle.'.png';
   self::assertFileExists($file);
   self::assertSame([192,178],array_slice(getimagesize($file),0,2));
   self::assertSame(6,ord(file_get_contents($file)[25]));
  }
 }
}
