<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
final class MarketRealmCottageArtTest extends TestCase {
 public function test_cottage_art_has_four_transparent_consistent_views(): void {
  $root=dirname(__DIR__).'/assets/sprites/buildings/';
  $hashes=[];
  foreach ([0,90,180,270] as $angle) {
   $file=$root."cottage-{$angle}.png";
   self::assertFileExists($file);
   self::assertSame([192,178],array_slice(getimagesize($file),0,2));
   $data=file_get_contents($file);
   self::assertSame(6,ord($data[25]),'RGBA PNG expected');
   $hashes[]=hash('sha256',$data);
  }
  self::assertCount(4,array_unique($hashes));
 }
}
