<?php
use PHPUnit\Framework\TestCase;
final class PrefabCatalogTest extends TestCase {
 public function testCatalogueAndSceneValidationShareExplicitIds(): void {
  $catalog=file_get_contents(__DIR__.'/../src/PrefabCatalog.php');
  $scene=file_get_contents(__DIR__.'/../src/Scene.php');
  $workshop=file_get_contents(__DIR__.'/../src/Workshop.php');
  $canvas=file_get_contents(__DIR__.'/../assets/canvas.js');
  $landscape=file_get_contents(__DIR__.'/../assets/landscape.js');
  self::assertStringContainsString('final class PrefabCatalog', $catalog);
  foreach (['blacksmith','tower','corridor','crypt','market'] as $id) {
   self::assertStringContainsString("'".$id."'=>", $catalog);
   self::assertStringContainsString("'".$id."'=>", $scene);
  }
  self::assertStringContainsString('PrefabCatalog::items()', $workshop);
  self::assertStringContainsString('gmrcart-library-search', $workshop);
  self::assertStringContainsString('filterLibrary()', $canvas);
  self::assertStringContainsString('cfg.prefabs', $canvas);
  self::assertStringContainsString('gmrcartScene?.prefabs', $landscape);
 }
}
