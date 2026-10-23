<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
final class WiderKeeperCanvasTest extends TestCase {
 public function test_canvas_and_toolbar_use_available_width(): void {
  $css=file_get_contents(__DIR__.'/../assets/canvas.css');
  self::assertStringContainsString('VI.6D.13A.3W', $css);
  self::assertStringContainsString('.gmrcart-studio .gmrcart-canvas-panel .gmrcart-landscape-frame', $css);
  self::assertStringContainsString('.gmrcart-studio .gmrcart-canvas-panel .gmrcart-illustration-toolbar', $css);
  self::assertStringContainsString('width:100%;max-width:none', $css);
 }
}
