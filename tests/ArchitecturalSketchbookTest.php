<?php
use PHPUnit\Framework\TestCase;
final class ArchitecturalSketchbookTest extends TestCase {
 public function testSketchbookIsLoadedBeforeLandscape(): void {
  $workshop=file_get_contents(__DIR__.'/../src/Workshop.php');
  self::assertStringContainsString("'gmrcart-architectural-sketchbook'",$workshop);
  self::assertStringContainsString('assets/architectural-sketchbook.js',$workshop);
 }
 public function testIllustratedSketchesRemainPresentationOnly(): void {
  $manifest=file_get_contents(__DIR__.'/../assets/architectural-sketchbook.js');
  $renderer=file_get_contents(__DIR__.'/../assets/landscape.js');
  self::assertStringContainsString('gmrcartArchitecturalSketchbook',$manifest);
  self::assertStringContainsString("roof:'thatch'",$manifest);
  self::assertStringContainsString("roof:'slate'",$manifest);
  self::assertStringContainsString('roofFinish(o.type)',$renderer);
  self::assertStringNotContainsString('fetch(',$manifest);
  self::assertStringNotContainsString('localStorage',$manifest);
 }
}
