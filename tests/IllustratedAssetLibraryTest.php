<?php
use PHPUnit\Framework\TestCase;
final class IllustratedAssetLibraryTest extends TestCase {
 public function testIllustratedSpritesArePresentationOnlyAndOptional(): void {
  $workshop=file_get_contents(__DIR__.'/../src/Workshop.php');
  $renderer=file_get_contents(__DIR__.'/../assets/landscape.js');
  $manifest=file_get_contents(__DIR__.'/../assets/atlas-assets.js');
  self::assertStringContainsString('gmrcart-atlas-assets', $workshop);
  self::assertStringContainsString('atlasAssetBase', $workshop);
  self::assertStringContainsString('data-gmrcart-style', $workshop);
  self::assertStringContainsString("illustrationStyle='classic'", $renderer);
  self::assertStringContainsString("sprite('tree'", $renderer);
  self::assertStringContainsString('packs.marketrealm[id]', $manifest);
  self::assertFileExists(__DIR__.'/../assets/sprites/marketrealm/tree.png');
  self::assertStringNotContainsString('fetch(', $renderer);
 }
}
