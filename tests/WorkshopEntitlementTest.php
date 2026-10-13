<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use GreatMarketrealmCartographer\WorkshopEntitlement;
final class WorkshopEntitlementTest extends TestCase
{
    public function testAccountFlagAndActionHaveSeparateNames(): void
    {
        self::assertSame('_gmrcart_workshop_enabled', WorkshopEntitlement::META_KEY);
        self::assertSame('gmrcart_toggle_workshop', WorkshopEntitlement::ACTION);
    }
    public function testMissingUserIsNeverEnabledOrEligible(): void
    {
        self::assertFalse(WorkshopEntitlement::enabled(0));
        self::assertFalse(WorkshopEntitlement::eligible(0));
    }
    public function testRegistrationAndMutationRequireNonceAndOwnerAccount(): void
    {
        $source = file_get_contents(dirname(__DIR__) . '/src/WorkshopEntitlement.php');
        self::assertStringContainsString('gmrexp_reading_room_tools', $source);
        self::assertStringContainsString('check_admin_referer(self::ACTION)', $source);
        self::assertStringContainsString('update_user_meta($userId', $source);
        self::assertStringNotContainsString('delete_post(', $source);
    }
}
