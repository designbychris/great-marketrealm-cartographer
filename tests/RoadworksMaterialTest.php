<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use GreatMarketrealmCartographer\Scene;

final class RoadworksMaterialTest extends TestCase
{
    public function test_road_material_is_accepted_and_persisted_in_scene_validation(): void
    {
        $scene = Scene::blank();
        $scene['cells'][13] = 'road';
        $result = Scene::validate($scene);
        self::assertNotNull($result);
        self::assertSame('road', $result['cells'][13]);
        self::assertSame(1, $result['version']);
    }

    public function test_unknown_road_variant_is_rejected_until_registered(): void
    {
        $scene = Scene::blank();
        $scene['cells'][13] = 'road_unregistered';
        self::assertNull(Scene::validate($scene));
    }

    public function test_road_tool_and_illustrated_material_are_registered(): void
    {
        $root = dirname(__DIR__);
        self::assertStringContainsString("'road'=>__('Road'", file_get_contents($root . '/src/Workshop.php'));
        self::assertStringContainsString("mat==='road'", file_get_contents($root . '/assets/landscape.js'));
        self::assertStringContainsString('data-material="road"', str_replace('\\"', '"', file_get_contents($root . '/assets/canvas.css')));
    }
}
