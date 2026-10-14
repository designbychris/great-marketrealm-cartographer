<?php
use PHPUnit\Framework\TestCase;
final class IllustratedEaselRegressionTest extends TestCase {
    public function testToolbarAndChimneysArePresentationOnly(): void {
        $base = dirname(__DIR__);
        $workshop = file_get_contents($base . '/src/Workshop.php');
        $landscape = file_get_contents($base . '/assets/landscape.js');
        $css = file_get_contents($base . '/assets/canvas.css');
        self::assertStringContainsString('gmrcart-illustration-toolbar', $workshop);
        self::assertStringContainsString('illustrationToolbar.hidden=!active', $landscape);
        self::assertStringContainsString('const roofZ=top+18*', $landscape);
        self::assertStringContainsString('const left=point(x-.055,y,roofZ)', $landscape);
        self::assertStringContainsString('const upperLeft=point(x-.055,y,roofZ+15)', $landscape);
        self::assertStringNotContainsString('const anchor=point(cx,cy,top+9)', $landscape);
        self::assertStringNotContainsString('top+25', $landscape);
        self::assertStringContainsString('.gmrcart-illustration-toolbar[hidden]', $css);
        self::assertStringContainsString('window.gmrcartObjects?.()', $landscape);
    }
}
