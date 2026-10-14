<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
final class ArchitecturalSurfaceAwarenessTest extends TestCase {
    public function testOpeningsUseWorldSpaceFacades(): void {
        $js = file_get_contents(__DIR__ . '/../assets/landscape.js');
        self::assertStringContainsString('function visibleFacade(o,w,h)', $js);
        self::assertStringContainsString('function facadeOpening(face,center,width,bottom,height,fill)', $js);
        self::assertStringContainsString("['south','east','north','west'][orientation]", $js);
        self::assertStringNotContainsString('door[1]-12', $js);
    }
    public function testChimneyIsAnchoredToRoofSlope(): void {
        $js = file_get_contents(__DIR__ . '/../assets/landscape.js');
        self::assertStringContainsString('const roofZ=top+18*', $js);
        self::assertStringContainsString('roofZ+15', $js);
        self::assertStringContainsString('architecturalWallInk(o,w,h,top);buildingDetails(o,w,h,top);', $js);
    }
}
