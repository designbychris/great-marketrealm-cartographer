<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
final class ShortcodeWorkshopRegressionTest extends TestCase
{
    public function testShortcodeRendersFrontendRatherThanAdmin(): void
    {
        $source = file_get_contents(dirname(__DIR__) . '/src/Workshop.php');
        self::assertStringContainsString('return self::workshopMarkup($campaign);', $source);
        self::assertStringContainsString('Drafts::allowed(', $source);
        self::assertStringContainsString('wp_nonce_field(', $source);
    }
    public function testCampaignLinkPrefersPublishedPage(): void
    {
        $source = file_get_contents(dirname(__DIR__) . '/great-marketrealm-cartographer.php');
        self::assertStringContainsString("get_page_by_path('cartographer')", $source);
        self::assertStringContainsString("$page->post_status === 'publish'", $source);
        self::assertStringContainsString('get_permalink($page)', $source);
    }
}
