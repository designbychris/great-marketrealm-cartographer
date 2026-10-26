<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
final class RaisedTavernRoofTest extends TestCase {
 public function test_four_storybook_views_are_transparent_and_have_identical_canvases(): void {
  $hashes=[];
  foreach ([0,90,180,270] as $angle) {
   $file=__DIR__.'/../assets/sprites/buildings/tavern-storybook-'.$angle.'.png';
   self::assertFileExists($file);
   $info=getimagesize($file);
   self::assertSame(IMAGETYPE_PNG,$info[2]);
   self::assertSame(480,$info[0]);
   self::assertSame(435,$info[1]);
   $header=file_get_contents($file,false,null,0,26);
   self::assertSame(6,ord($header[25]),'PNG must use RGBA colour type');
   $hashes[]=hash_file('sha256',$file);
  }
  self::assertCount(4,array_unique($hashes));
 }
 public function test_existing_tavern_art_registry_preserves_footprint(): void {
  $book=file_get_contents(__DIR__.'/../assets/building-illustrations.js');
  self::assertStringContainsString("label:'MarketRealm Village Tavern',footprint:Object.freeze([3,2])",$book);
  self::assertStringContainsString("views:Object.freeze(views('tavern-storybook'))",$book);
 }
}
