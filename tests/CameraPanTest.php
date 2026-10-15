<?php
use PHPUnit\Framework\TestCase;
final class CameraPanTest extends TestCase {
 public function testLandscapePanningIsViewOnlyAndWorksAtNormalZoom(): void {
  $source=file_get_contents(__DIR__.'/../assets/landscape.js');
  self::assertStringContainsString("frame.style.touchAction='none'",$source);
  self::assertStringContainsString('panning:!event.altKey',$source);
  self::assertStringContainsString('gesture.startPanX-dx*BASE_WIDTH/(rect.width*zoom)',$source);
  self::assertStringContainsString('gesture.startPanY-dy*BASE_HEIGHT/(rect.height*zoom)',$source);
  self::assertStringContainsString('const panLimitX=BASE_WIDTH*.7+width*.15',$source);
  self::assertStringContainsString('if(orientation!==0)turn(-orientation,false)',$source);
  self::assertStringContainsString("frame.addEventListener('pointercancel'",$source);
  self::assertStringNotContainsString('localStorage.setItem',$source);
 }
}
