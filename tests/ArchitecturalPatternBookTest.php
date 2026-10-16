<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
final class ArchitecturalPatternBookTest extends TestCase {
 public function test_pattern_book_registers_five_distinct_architectural_patterns(): void {
  $js=file_get_contents(dirname(__DIR__).'/assets/building-illustrations.js');
  foreach (['cottage:Object.freeze','villageHouse:Object.freeze','bakery:Object.freeze',
            'blacksmith:Object.freeze','marketStall:Object.freeze'] as $pattern) {
   self::assertStringContainsString($pattern,$js);
  }
  self::assertStringContainsString('window.gmrcartArchitecturalPatternBook=patternBook',$js);
  self::assertStringContainsString("status:'planned'",$js);
  self::assertStringContainsString("status:'painted'",$js);
 }
 public function test_unpainted_patterns_cannot_replace_scene_artwork(): void {
  $js=file_get_contents(dirname(__DIR__).'/assets/building-illustrations.js');
  self::assertStringContainsString("p.status==='painted'&&p.artKey",$js);
  self::assertStringContainsString('window.gmrcartBuildingArt=buildings',$js);
  self::assertStringContainsString('footprint:Object.freeze([2,2])',$js);
  self::assertStringContainsString("views('cottage-painted')",$js);
 }
}
