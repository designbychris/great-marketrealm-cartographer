<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
final class VillageHousePatternTest extends TestCase {
 public function test_village_house_has_four_distinct_transparent_illustrations(): void {
  $root=dirname(__DIR__).'/assets/sprites/buildings/';$hashes=[];
  foreach ([0,90,180,270] as $angle) {
   $file=$root."village-house-painted-{$angle}.png";
   self::assertFileExists($file);
   self::assertSame([192,178],array_slice(getimagesize($file),0,2));
   self::assertSame(6,ord(file_get_contents($file)[25]));
   $hashes[]=hash_file('sha256',$file);
  }
  self::assertCount(4,array_unique($hashes));
  // Opposite cameras must have distinct artwork, not duplicated front elevations.
  self::assertNotSame($hashes[0],$hashes[2]);
  self::assertNotSame($hashes[1],$hashes[3]);
 }
 public function test_pattern_is_painted_and_reuses_cottage_geometry(): void {
  $art=file_get_contents(dirname(__DIR__).'/assets/building-illustrations.js');
  self::assertStringContainsString("views('village-house-painted')",$art);
  self::assertStringContainsString("villageHouse:Object.freeze({label:'MarketRealm Village House',status:'painted',prefab:'cottage'",$art);
  self::assertStringContainsString("const buildings=Object.freeze({cottage,villageHouse})",$art);
  $landscape=file_get_contents(dirname(__DIR__).'/assets/landscape.js');
  self::assertStringContainsString("o.type==='cottage'&&o.appearance === 'villageHouse'",$landscape);
  self::assertStringContainsString('buildingArt[artKey(o)]',$landscape);
  $scene=file_get_contents(dirname(__DIR__).'/src/Scene.php');
  self::assertStringContainsString("'villageHouse'], true)",$scene);
  $workshop=file_get_contents(dirname(__DIR__).'/src/Workshop.php');
  self::assertStringContainsString('value="villageHouse"',$workshop);
 }
}
