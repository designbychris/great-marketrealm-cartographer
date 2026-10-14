<?php
use PHPUnit\Framework\TestCase;

final class ArchitecturalFacadesTest extends TestCase {
    public function testFacadeDetailsAreRenderedWithoutChangingSceneStorage(): void {
        $renderer = file_get_contents(__DIR__ . '/../assets/landscape.js');
        self::assertStringContainsString('function architecturalRoofInk(', $renderer);
        self::assertStringContainsString('function architecturalWallInk(', $renderer);
        self::assertStringContainsString('architecturalRoofInk(o,w,h,top,false)', $renderer);
        self::assertStringContainsString('architecturalWallInk(o,w,h,top)', $renderer);
        self::assertStringContainsString("o.type==='blacksmith'", $renderer);
        self::assertStringNotContainsString('localStorage', file_get_contents(__DIR__ . '/../assets/architectural-sketchbook.js'));
    }
    public function testFacadeInkUsesCameraProjectionAndNotSceneMutation(): void {
        $renderer = file_get_contents(__DIR__ . '/../assets/landscape.js');
        self::assertStringContainsString('point(o.x+w/2,o.y,z)', $renderer);
        self::assertStringContainsString('function visibleFacade(o,w,h)', $renderer);
        self::assertStringContainsString('for(const side of visibleFacadeSides())', $renderer);
        self::assertStringContainsString('const face=facadeForSide(o,w,h,side)', $renderer);
        self::assertStringContainsString('const isEntrance=side===entranceSide(o)', $renderer);
        self::assertStringContainsString('facadeOpening(face,center,half*2,10,7', $renderer);
        self::assertStringNotContainsString('point(x,frontY,wallTop)', $renderer);
        self::assertStringContainsString("if(item.surface==='details'&&!stone)", $renderer);
    }
}
