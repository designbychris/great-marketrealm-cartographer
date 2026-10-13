<?php
use PHPUnit\Framework\TestCase;
final class OcclusionJudgementTest extends TestCase {
    public function testCameraRelativeOcclusionGraphIsPresentationOnly(): void {
        $js = file_get_contents(__DIR__ . '/../assets/landscape.js');
        self::assertStringContainsString('function judgeOcclusion(items)', $js);
        self::assertStringContainsString('function strictBehind(a,b)', $js);
        self::assertStringContainsString('function projectedBounds(item)', $js);
        self::assertStringContainsString('const judgement=judgeOcclusion(raised)', $js);
        self::assertStringContainsString('Occlusion audit:', $js);
        self::assertStringNotContainsString('gmrcartSaveScene(', $js);
    }
}
