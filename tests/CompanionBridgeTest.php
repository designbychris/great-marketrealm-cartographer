<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use GreatMarketrealmCartographer\CompanionBridge;
final class CompanionBridgeTest extends TestCase
{
    public function testMissingUserIsDenied(): void
    {
        self::assertFalse(CompanionBridge::keeperEntitled(false, 0));
        self::assertFalse(CompanionBridge::canEditCampaign(false, 0, 'test'));
        self::assertSame([], CompanionBridge::campaigns(0));
    }
    public function testBridgeUsesCanonicalCampaignTypeAndOwnership(): void
    {
        $source = file_get_contents(dirname(__DIR__) . '/src/CompanionBridge.php');
        self::assertStringContainsString('gmrc_campaign', $source);
        self::assertStringContainsString('_gmrc_campaign_id', $source);
        self::assertStringContainsString('\'author\' => $userId', $source);
        self::assertStringContainsString('gmrc_manage_campaigns', $source);
    }
    public function testWorkshopUsesCampaignSelectorAndDMCapability(): void
    {
        $source = file_get_contents(dirname(__DIR__) . '/src/Workshop.php');
        self::assertStringContainsString('CompanionBridge::campaigns(', $source);
        self::assertStringContainsString('name="campaign_id"', $source);
        self::assertStringContainsString("'gmrc_manage_campaigns'", $source);
    }
}
