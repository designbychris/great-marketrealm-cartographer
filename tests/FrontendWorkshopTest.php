<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class FrontendWorkshopTest extends TestCase
{
    public function testWorkshopHasAuthenticatedFrontendBoundary(): void
    {
        $source = file_get_contents(dirname(__DIR__) . '/src/Workshop.php');
        self::assertStringContainsString("'template_redirect'", $source);
        self::assertStringContainsString('auth_redirect()', $source);
        self::assertStringContainsString('Drafts::allowed(', $source);
        self::assertStringContainsString('get_header();', $source);
        self::assertStringContainsString('workshopMarkup($campaign)', $source);
        self::assertStringContainsString('get_footer();', $source);
        self::assertStringNotContainsString('<!doctype html>', $source);
        self::assertStringContainsString('wp_die(', $source);
    }

    public function testFrontendDraftFormRetainsNonceAndPostAction(): void
    {
        $source = file_get_contents(dirname(__DIR__) . '/src/Workshop.php');
        self::assertStringContainsString("wp_nonce_field('gmrcart_create_draft')", $source);
        self::assertStringContainsString('admin-post.php', $source);
        self::assertStringContainsString('renderFrontendContent($campaign)', $source);
    }
}
