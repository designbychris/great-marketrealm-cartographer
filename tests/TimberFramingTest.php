<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
final class TimberFramingTest extends TestCase {
    public function testTimberAndMasonryStayOnTwoWorldSpaceElevations(): void {
        $js = file_get_contents(__DIR__ . '/../assets/landscape.js');
        self::assertStringContainsString('function architecturalWallInk(o,w,h,top)', $js);
        self::assertStringContainsString('for(const side of visibleFacadeSides())', $js);
        self::assertStringContainsString('const face=facadeForSide(o,w,h,side)', $js);
        self::assertStringContainsString('for(const z of [3,19])', $js);
        self::assertStringContainsString('for(const z of [3,7,19])', $js);
    }
    public function testWallInkDoesNotRepositionDoorsOrCoverTheWindowBand(): void {
        $js = file_get_contents(__DIR__ . '/../assets/landscape.js');
        self::assertStringContainsString('for(const t of [.22,.78])line(face(t,3),face(t,8)', $js);
        self::assertStringContainsString('line(face(.025,19),face(.14,17.7)', $js);
        self::assertStringContainsString('const isEntrance=side===entranceSide(o)', $js);
        self::assertStringContainsString('architecturalWindow(face,center,design);', $js);
        self::assertStringNotContainsString('o.appearance=', $js);
    }
}
