<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
final class LivingWaterPresentationTest extends TestCase {
 public function test_ripples_are_sparse_view_only_and_accessible(): void {
  $js=file_get_contents(__DIR__.'/../assets/landscape.js');
  $css=file_get_contents(__DIR__.'/../assets/canvas.css');
  self::assertStringContainsString('if(hash%7===0)', $js);
  self::assertStringContainsString('data-gmrcart-water-cell', $js);
  self::assertStringContainsString('gmrcart-water-ripple', $js);
  self::assertStringContainsString("if(mat==='water'&&illustrationStyle!=='pixel')", $js);
  self::assertStringContainsString('prefers-reduced-motion:reduce', $css);
  self::assertStringContainsString('@keyframes gmrcart-water-breathe', $css);
  self::assertStringContainsString('.gmrcart-terrain-grid-toggle', $css);
 }
}
