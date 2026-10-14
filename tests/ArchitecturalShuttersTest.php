<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
final class ArchitecturalShuttersTest extends TestCase {
    public function testShuttersUseTheExistingWorldSpaceFacade(): void {
        $js = file_get_contents(__DIR__ . '/../assets/landscape.js');
        self::assertStringContainsString('function architecturalWindow(face,center,design)', $js);
        self::assertStringContainsString('architecturalWindow(face,center,design);', $js);
        self::assertStringContainsString('for(const side of visibleFacadeSides())', $js);
        self::assertStringContainsString('const face=facadeForSide(o,w,h,side)', $js);
        self::assertStringContainsString('polygon([face(inner,10),face(outer,10),face(outer,17),face(inner,17)]', $js);
    }
    public function testShuttersAreDecorativeAndEntranceDirectionIsUnchanged(): void {
        $js = file_get_contents(__DIR__ . '/../assets/landscape.js');
        self::assertStringContainsString('const isEntrance=side===entranceSide(o)', $js);
        self::assertStringContainsString("if(!isMarket&&design.door!=='none'&&isEntrance)", $js);
        self::assertStringContainsString("design.roof==='slate'?'#626c79'", $js);
        self::assertStringContainsString('facadeOpening(face,center,half*2,10,7,glass)', $js);
        self::assertStringNotContainsString('o.appearance=', $js);
    }
}
