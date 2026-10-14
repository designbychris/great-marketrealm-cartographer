<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
final class TwoVisibleElevationsTest extends TestCase {
    public function testEveryCameraQuadrantHasTwoDistinctPhysicalWalls(): void {
        $js = file_get_contents(__DIR__ . '/../assets/landscape.js');
        self::assertStringContainsString('function visibleFacadeSides()', $js);
        self::assertStringContainsString("[['south','east'],['east','north'],['north','west'],['west','south']][orientation]", $js);
        self::assertStringContainsString('function facadeForSide(o,w,h,side)', $js);
        self::assertStringContainsString('for(const side of visibleFacadeSides())', $js);
    }
    public function testDoorStaysOnItsPhysicalElevationAndOtherWallsKeepWindows(): void {
        $js = file_get_contents(__DIR__ . '/../assets/landscape.js');
        self::assertStringContainsString('const isEntrance=side===entranceSide(o)', $js);
        self::assertStringContainsString("if(!isMarket&&design.door!=='none'&&isEntrance)", $js);
        self::assertStringContainsString("side==='north'?[.3,.7]:[.25,.75]", $js);
        self::assertStringContainsString('const face=facadeForSide(o,w,h,side)', $js);
    }
}
