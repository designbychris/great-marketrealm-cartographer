<?php
use PHPUnit\Framework\TestCase;
final class PlacementPreviewTest extends TestCase {
 public function testGhostIsOnlyAVisualPreviewAndSceneFormatIsUnchanged(): void {
  $js=file_get_contents(__DIR__.'/../assets/canvas.js');
  $css=file_get_contents(__DIR__.'/../assets/canvas.css');
  $view=file_get_contents(__DIR__.'/../src/Workshop.php');
  self::assertStringContainsString('function ghost(i)', $js);
  self::assertStringContainsString('fits(candidate)', $js);
  self::assertStringContainsString('gmrcart-ghost-invalid', $css);
  self::assertStringContainsString('gmrcart-object-cancel', $view);
  self::assertStringContainsString('objects:objects.map', $js);
 }
}
