<?php
use PHPUnit\Framework\TestCase;
final class ConstructionLibraryTest extends TestCase {
 public function testLibraryKeepsPrefabsSeparateFromTerrain(): void {
  $scene=file_get_contents(__DIR__.'/../src/Scene.php');
  $workshop=file_get_contents(__DIR__.'/../src/Workshop.php');
  $canvas=file_get_contents(__DIR__.'/../assets/canvas.js');
  $landscape=file_get_contents(__DIR__.'/../assets/landscape.js');
  self::assertStringContainsString('public const PREFABS', $scene);
  self::assertStringContainsString("'objects'=>", $scene);
  self::assertStringContainsString('count($objects) > 32', $scene);
  self::assertStringContainsString('Construction Library', $workshop);
  self::assertStringContainsString('gmrcart-object-rotate', $workshop);
  self::assertStringContainsString('objects:objects.map', $canvas);
  self::assertStringContainsString('window.gmrcartObjects', $landscape);
 }
}
