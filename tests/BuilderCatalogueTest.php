<?php
use PHPUnit\Framework\TestCase;
final class BuilderCatalogueTest extends TestCase {
 public function testAppearanceIsOptionalAndStrictlyValidated(): void {
  $scene=file_get_contents(__DIR__.'/../src/Scene.php');
  self::assertStringContainsString("array_key_exists('appearance', \$object)",$scene);
  self::assertStringContainsString("'default', 'cottage', 'tavern', 'blacksmith', 'tower', 'market'",$scene);
  self::assertStringContainsString("'objects'=>\$objects",$scene);
 }
 public function testAppearanceIsIndependentOfPrefabGeometry(): void {
  $editor=file_get_contents(__DIR__.'/../assets/canvas.js');
  $renderer=file_get_contents(__DIR__.'/../assets/landscape.js');
  $workshop=file_get_contents(__DIR__.'/../src/Workshop.php');
  self::assertStringContainsString('gmrcart-appearance-picker',$workshop);
  self::assertStringContainsString("appearance:pendingAppearance!=='default'?pendingAppearance:(appearancePicker?.value||'default')",$editor);
  self::assertStringContainsString('pendingAppearance=detail.appearance',$editor);
  self::assertStringContainsString('current.appearance=detail.appearance',$editor);
  self::assertStringContainsString('roofFinish(o.type,o.appearance)',$renderer);
  self::assertStringContainsString('sketch(o.type,o.appearance)',$renderer);
 }
}
