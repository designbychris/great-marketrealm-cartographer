<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
final class ConstructionStudioRegressionTest extends TestCase
{
    public function testDedicatedShortcodeAndPrivateDraftGate(): void
    {
        $source = file_get_contents(__DIR__ . '/../src/Workshop.php');
        self::assertStringContainsString("add_shortcode('gmrcart_canvas'", $source);
        self::assertStringContainsString('Scene::canEdit($draftId, get_current_user_id())', $source);
        self::assertStringContainsString("get_page_by_path('cartographer/canvas')", $source);
        self::assertStringContainsString('self::canvasUrl((int) $draft->ID, $draftCampaign)', $source);
        self::assertStringContainsString('self::renderEditor($campaign);', $source);
    }
    public function testDebugIsOptInAndPresentationOnly(): void
    {
        $php = file_get_contents(__DIR__ . '/../src/Workshop.php');
        $js = file_get_contents(__DIR__ . '/../assets/landscape.js');
        self::assertStringContainsString('gmrcart-depth-toggle', $php);
        self::assertStringContainsString('depthDebug=false', $js);
        self::assertStringContainsString('data-gmrcart-depth-debug', $js);
        self::assertStringContainsString('debugEntries.forEach', $js);
        self::assertStringNotContainsString('depthDebug', file_get_contents(__DIR__ . '/../src/Scene.php'));
    }
}
