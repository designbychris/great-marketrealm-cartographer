<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
final class ArchitecturalOrderTest extends TestCase {
    public function testArchitecturalEdgesTakePrecedenceOverCameraOrdering(): void {
        $js=(string)file_get_contents(dirname(__DIR__).'/assets/landscape.js');
        self::assertStringContainsString('const architecturalEdges=[]', $js);
        self::assertStringContainsString('architecturalEdges.push([i,j])', $js);
        self::assertStringContainsString('for(const [from,to] of architecturalEdges)', $js);
        self::assertStringContainsString('edges[to].splice(reverse,1)', $js);
        self::assertStringContainsString('addEdge(from,to)', $js);
    }
    public function testAuditSeparatesArchitecturalEdgesAndCameraAmbiguities(): void {
        $js=(string)file_get_contents(dirname(__DIR__).'/assets/landscape.js');
        self::assertStringContainsString('occlusionAudit.architectural++', $js);
        self::assertStringContainsString('occlusionAudit.ambiguous++', $js);
        self::assertStringContainsString('occlusionAudit.cycles++', $js);
        self::assertStringContainsString('judgement.audit.ambiguous', $js);
    }
}
