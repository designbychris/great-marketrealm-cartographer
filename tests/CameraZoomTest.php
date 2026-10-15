<?php
use PHPUnit\Framework\TestCase;
final class CameraZoomTest extends TestCase {
 public function testZoomIsViewOnlyAndBounded(): void {
  $source=file_get_contents(__DIR__.'/../assets/landscape.js');
  self::assertStringContainsString('MIN_ZOOM=.5,MAX_ZOOM=2', $source);
  self::assertStringContainsString("preview.setAttribute('viewBox'", $source);
  self::assertStringContainsString("frame.addEventListener('wheel'", $source);
  self::assertStringContainsString('passive:false', $source);
  self::assertStringContainsString('panning:!event.altKey', $source);
  self::assertStringContainsString('if(!cancelled&&dragging&&!panning', $source);
  self::assertStringContainsString('zoomControls.addEventListener', $source);
  self::assertStringNotContainsString('localStorage.setItem', $source);
 }
}
