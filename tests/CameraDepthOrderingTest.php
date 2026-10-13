<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class CameraDepthOrderingTest extends TestCase
{
    public function testRaisedSceneryAndPrefabsShareCameraAwareDrawQueue(): void
    {
        $script = (string) file_get_contents(dirname(__DIR__) . '/assets/landscape.js');
        self::assertStringContainsString('function depth(x,y)', $script);
        self::assertStringContainsString('const [u,v]=orient(x,y)', $script);
        self::assertStringContainsString("kind:mat,x,y,depth:depth(x+.5,y+.5)", $script);
        self::assertStringContainsString("kind:'prefab',object:o,depth:front", $script);
        self::assertStringContainsString('Math.max(...corners.map(([x,y])=>depth(x,y)))', $script);
        self::assertStringContainsString('const judgement=judgeOcclusion(raised)', $script);
        self::assertStringContainsString('raised.splice(0,raised.length,...judgement.ordered)', $script);
        self::assertStringContainsString('function painterCompare(a,b)', $script);
        self::assertStringNotContainsString('raised.sort((a,b)=>a.depth-b.depth', $script);
        self::assertStringContainsString('raised.forEach(item=>{', $script);
        self::assertStringNotContainsString('objects.sort(', $script);
    }

    public function testDepthOrderingDoesNotChangeScenePersistence(): void
    {
        $script = (string) file_get_contents(dirname(__DIR__) . '/assets/landscape.js');
        self::assertStringContainsString("root.addEventListener('gmrcart:objects-changed',schedule)", $script);
        self::assertStringContainsString("if(o.rotation%180)[w,h]=[h,w]", $script);
        self::assertStringNotContainsString('fetch(', $script);
        self::assertStringNotContainsString('localStorage', $script);
    }
}
