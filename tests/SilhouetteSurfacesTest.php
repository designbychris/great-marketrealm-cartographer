<?php
use PHPUnit\Framework\TestCase;
final class SilhouetteSurfacesTest extends TestCase {
    public function testPrefabSilhouettesHaveIndependentOrderedSurfaces(): void {
        $source = file_get_contents(dirname(__DIR__) . '/assets/landscape.js');
        $this->assertStringContainsString("surface:'base'", $source);
        $this->assertStringContainsString("surface:'roof'", $source);
        $this->assertStringContainsString("surface:'details'", $source);
        $this->assertStringContainsString("a.surface==='base'&&b.surface==='roof'", $source);
        $this->assertStringContainsString("a.surface==='roof'&&b.surface==='details'", $source);
        $this->assertStringContainsString("if(item.surface==='roof')", $source);
        $this->assertStringContainsString("if(item.surface==='details'&&!stone)", $source);
    }
}
