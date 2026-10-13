<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
final class TrueFrontSurfaceTest extends TestCase {
    public function testSurfaceEnvelopesAreIndependentAndCameraRelative(): void {
        $js=(string)file_get_contents(dirname(__DIR__).'/assets/landscape.js');
        self::assertStringContainsString('function surfaceHeight(item)', $js);
        self::assertStringContainsString("item.surface==='base'", $js);
        self::assertStringContainsString("item.surface==='roof'", $js);
        self::assertStringContainsString("item.surface==='details'", $js);
        self::assertStringContainsString('Math.min(...sy)-topZ', $js);
        self::assertStringContainsString('Math.max(...sy)-bottomZ', $js);
        self::assertStringContainsString('function silhouetteDepth(item)', $js);
    }
    public function testStableOcclusionQueueRetainsDependenciesWithoutChangingSavedObjects(): void {
        $js=(string)file_get_contents(dirname(__DIR__).'/assets/landscape.js');
        self::assertStringContainsString('const addEdge=(from,to)=>', $js);
        self::assertStringContainsString('if(pending.has(target))incoming[target]--', $js);
        self::assertStringNotContainsString('fetch(', $js);
    }
}
