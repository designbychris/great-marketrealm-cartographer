<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
final class CottagePainterWorkshopTest extends TestCase {
 public function test_painted_cottage_has_four_distinct_rgba_views_and_originals(): void {
  $root=dirname(__DIR__).'/assets/sprites/buildings/';$hashes=[];
  foreach ([0,90,180,270] as $angle) {
   foreach (["cottage-{$angle}.png","cottage-painted-{$angle}.png"] as $name) {
    $file=$root.$name;
    self::assertFileExists($file);
    self::assertSame([192,178],array_slice(getimagesize($file),0,2));
    self::assertSame(6,ord(file_get_contents($file,false,null,25,1)),'RGBA PNG required');
   }
   $hashes[]=hash_file('sha256',$root."cottage-painted-{$angle}.png");
  }
  self::assertCount(4,array_unique($hashes));
 }
 public function test_painter_registry_remains_view_only_and_has_four_views(): void {
  $js=file_get_contents(dirname(__DIR__).'/assets/building-illustrations.js');
  self::assertStringContainsString("views('cottage-painted')",$js);
  self::assertStringContainsString("fallbackViews:Object.freeze(views('cottage'))",$js);
  self::assertStringContainsString('footprint:Object.freeze([2,2])',$js);
  self::assertStringContainsString('window.gmrcartBuildingPainter=Object.freeze',$js);
 }
}
