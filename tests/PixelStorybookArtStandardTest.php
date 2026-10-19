<?php
use PHPUnit\Framework\TestCase;

final class PixelStorybookArtStandardTest extends TestCase
{
    private function standard(): array
    {
        $raw = file_get_contents(__DIR__ . '/../assets/art-standards/pixel-storybook-v1.json');
        self::assertNotFalse($raw);
        return json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
    }

    public function testFourIndependentCameraViewsAndGroundContract(): void
    {
        $standard = $this->standard();
        self::assertSame([0, 90, 180, 270], $standard['camera']['angles']);
        self::assertTrue($standard['camera']['independentViews']);
        self::assertFalse($standard['camera']['allowCssRotationOrMirroring']);
        self::assertSame('transparent alpha', $standard['assetContract']['background']);
        self::assertStringContainsString('ground plane', $standard['assetContract']['groundAnchor']);
    }

    public function testRolloutPreservesExistingArchitectureAndFallbacks(): void
    {
        $standard = $this->standard();
        foreach ($standard['compatibility'] as $change) {
            self::assertFalse($change);
        }
        self::assertSame(4, $standard['acceptance']['viewsRequired']);
        self::assertStringContainsString('existing painted assets', $standard['rollout']['fallback']);
        self::assertSame('storybook cottage proof of concept', $standard['rollout']['first']);
    }
}
