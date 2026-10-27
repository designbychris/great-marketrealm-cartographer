<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
final class NeighbourAwarePathsTest extends TestCase
{
    public function test_path_edges_use_placed_object_footprints_without_scene_mutation(): void
    {
        $script = file_get_contents(dirname(__DIR__) . '/assets/landscape.js');
        self::assertStringContainsString('function neighbourRegister(tiles,objects)', $script);
        self::assertStringContainsString('prefabs?.[o.type]?.size', $script);
        self::assertStringContainsString('occupied.add(yy*W+xx)', $script);
        self::assertStringContainsString("return 'architecture'", $script);
        self::assertStringContainsString("return 'water'", $script);
        self::assertStringContainsString("return 'nature'", $script);
        self::assertStringContainsString('const neighbours=neighbourRegister(tiles,sceneObjects)', $script);
        self::assertStringContainsString('function drawPathEdge(', $script);
        self::assertStringContainsString('function pathEdge(', $script);
    }
    public function test_all_path_surfaces_share_the_neighbour_aware_renderer(): void
    {
        $script = file_get_contents(dirname(__DIR__) . '/assets/landscape.js');
        self::assertStringContainsString('cobblestones(x,y,tiles,neighbours)', $script);
        self::assertStringContainsString('pathSurface(x,y,tiles,mat,neighbours)', $script);
        self::assertStringContainsString("kind==='architecture'", $script);
        self::assertStringContainsString("kind==='water'", $script);
        self::assertStringContainsString("kind==='path'", $script);
        self::assertStringContainsString("kind==='boundary'", $script);
    }
}
