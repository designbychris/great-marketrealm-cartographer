<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class PersistentBuildingEntrancesTest extends TestCase {
    public function testEntranceDirectionComesFromSavedPrefabRotation(): void {
        $js = file_get_contents(__DIR__ . '/../assets/landscape.js');
        self::assertStringContainsString('function entranceSide(o)', $js);
        self::assertStringContainsString('Number(o.rotation)||0', $js);
        self::assertStringContainsString("const cardinal=['south','west','north','east']", $js);
        self::assertStringNotContainsString('function entranceSide(orientation)', $js);
    }
    public function testDoorOnlyAppearsOnCameraVisibleEntranceWall(): void {
        $js = file_get_contents(__DIR__ . '/../assets/landscape.js');
        self::assertStringContainsString('function visibleFacadeSide()', $js);
        self::assertStringContainsString('side===entranceSide(o)', $js);
        self::assertStringContainsString('const windowCenters=', $js);
        self::assertStringContainsString('function facadeOpening(', $js);
    }
}
