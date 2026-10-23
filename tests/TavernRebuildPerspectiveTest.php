<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
final class TavernRebuildPerspectiveTest extends TestCase {
 public function test_four_tavern_perspectives_are_distinct_png_assets(): void {
  $hashes=[];
  foreach ([0,90,180,270] as $angle) {
   $path=__DIR__.'/../assets/sprites/buildings/tavern-storybook-'.$angle.'.png';
   self::assertFileExists($path);
   $info=getimagesize($path);
   self::assertSame([480,435],array_slice($info,0,2));
   self::assertSame(IMAGETYPE_PNG,$info[2]);
   $hashes[]=hash_file('sha256',$path);
  }
  self::assertCount(4,array_unique($hashes));
 }
 public function test_tavern_keeps_the_certified_footprint(): void {
  $js=file_get_contents(__DIR__.'/../assets/building-illustrations.js');
  self::assertStringContainsString('footprint:Object.freeze([3,2])',$js);
  self::assertStringContainsString("views('tavern-storybook')",$js);
 }
}
