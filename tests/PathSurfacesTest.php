<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use GreatMarketrealmCartographer\Scene;
final class PathSurfacesTest extends TestCase
{
    public function test_path_surfaces_are_persisted(): void
    {
        $scene=Scene::blank();
        foreach (['dirt_track','market_paving','woodland_path'] as $i=>$material) $scene['cells'][$i]=$material;
        $result=Scene::validate($scene);
        self::assertNotNull($result);
        self::assertSame(array_slice($scene['cells'],0,3),array_slice($result['cells'],0,3));
        self::assertSame(1,$result['version']);
    }
    public function test_path_tools_and_renderer_are_registered(): void
    {
        $root=dirname(__DIR__);
        $tools=file_get_contents($root.'/src/Workshop.php');
        $renderer=file_get_contents($root.'/assets/landscape.js');
        foreach (['dirt_track','market_paving','woodland_path'] as $material) {
            self::assertStringContainsString("'".$material."'=>__",$tools);
            self::assertStringContainsString("mat==='".$material."'",$renderer);
        }
    }
}
