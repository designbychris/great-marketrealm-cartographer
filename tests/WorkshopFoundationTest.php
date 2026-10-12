<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use GreatMarketrealmCartographer\Workshop;
final class WorkshopFoundationTest extends TestCase
{
    public function testFoundationCapabilitiesAreExplicit(): void
    {
        $caps = Workshop::capabilities([]);
        self::assertSame('1.0.0', $caps['workshop.foundation']);
        self::assertFalse($caps['workshop.scene-publishing']);
        self::assertFalse($caps['workshop.asset-packs']);
    }
    public function testWorkshopHasNoCrossPluginDirectDatabaseDependencies(): void
    {
        $source = file_get_contents(dirname(__DIR__) . '/src/Workshop.php');
        self::assertStringNotContainsString('get_post_meta(', $source);
        self::assertStringNotContainsString('update_post_meta(', $source);
        self::assertStringNotContainsString('$wpdb', $source);
    }
}
