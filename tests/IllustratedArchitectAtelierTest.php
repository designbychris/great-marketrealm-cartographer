<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
final class IllustratedArchitectAtelierTest extends TestCase {
 public function testArtworkIsOptInAndRequiresFourViews(): void {
  $js=file_get_contents(__DIR__.'/../assets/landscape.js');
  self::assertStringContainsString('window.gmrcartBuildingArt||{}', $js);
  self::assertStringContainsString('entry.views.length===4', $js);
  self::assertStringContainsString('entry.footprint[0]===native[0]', $js);
  self::assertStringContainsString("illustrationStyle==='pixel'", $js);
 }
 public function testFallbackAndArchitecturalDepthRemain(): void {
  $js=file_get_contents(__DIR__.'/../assets/landscape.js');
  self::assertStringContainsString("if(item.surface==='details'&&!stone&&illustratedBuilding(o,w,h))return;",$js);
  self::assertStringContainsString('if(!hasBuildingIllustration(o)) {architecturalWallInk', $js);
  self::assertStringContainsString("'data-gmrcart-building-art':artKey(o)",$js);
  self::assertStringContainsString("function artKey(o)",$js);
  self::assertStringContainsString('const judgement=', $js);
 }
}
