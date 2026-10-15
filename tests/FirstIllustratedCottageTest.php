<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
final class FirstIllustratedCottageTest extends TestCase {
 public function test_four_camera_views_are_shipped(): void {
  foreach ([0,90,180,270] as $angle) {
   $file=dirname(__DIR__)."/assets/sprites/buildings/cottage-{$angle}.png";
   self::assertFileExists($file);
   self::assertSame("image/png", mime_content_type($file));
  }
 }
 public function test_asset_catalogue_is_loaded_before_landscape(): void {
  $root=dirname(__DIR__);
  $catalog=file_get_contents($root.'/assets/building-illustrations.js');
  $workshop=file_get_contents($root.'/src/Workshop.php');
  self::assertStringContainsString('footprint:[2,2]', $catalog);
  self::assertStringContainsString('gmrcart-building-illustrations', $workshop);
  self::assertStringContainsString("'gmrcart-architectural-sketchbook', 'gmrcart-building-illustrations'", $workshop);
 }
 public function test_four_views_have_distinct_artwork_and_sprite_replaces_geometry(): void {
  $root=dirname(__DIR__);
  $hashes=[];
  foreach ([0,90,180,270] as $angle) {
   $path="$root/assets/sprites/buildings/cottage-{$angle}.png";
   self::assertSame([192,178], array_slice(getimagesize($path),0,2));
   $hashes[]=hash_file('sha256',$path);
  }
  self::assertCount(4,array_unique($hashes),'Every camera angle needs its own projected artwork.');
  $renderer=file_get_contents($root.'/assets/landscape.js');
  self::assertStringContainsString("if(item.surface==='base'&&hasBuildingIllustration(o))return;",$renderer);
  self::assertStringContainsString("if(item.surface==='roof'&&!stone&&illustratedBuilding(o,w,h))return;",$renderer);
 }

}
