<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
final class WorkshopFrontendAuthorizationRegressionTest extends TestCase
{
    public function testFrontendCallsExistingDraftAuthorizationMethod(): void
    {
        $source = file_get_contents(dirname(__DIR__) . '/src/Workshop.php');
        self::assertStringContainsString('Drafts::allowed(get_current_user_id(), $campaign)', $source);
        self::assertStringNotContainsString('Drafts::campaignAllowed(', $source);
        self::assertTrue(method_exists(\GreatMarketrealmCartographer\Drafts::class, 'allowed'));
    }
}
