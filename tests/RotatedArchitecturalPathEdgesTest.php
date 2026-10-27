<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
final class RotatedArchitecturalPathEdgesTest extends TestCase {
 public function test_structure_precedes_same_material_join(): void {
  $s=file_get_contents(dirname(__DIR__).'/assets/landscape.js');
  $body=substr($s,strpos($s,'function pathEdge('),strpos($s,'function drawPathEdge(')-strpos($s,'function pathEdge('));
  self::assertLessThan(strpos($body,"if(next===mat)return 'joined'"),strpos($body,"if(neighbours.hasStructure(nx,ny)"));
 }
 public function test_footprints_use_world_not_camera_coordinates(): void {
  $s=file_get_contents(dirname(__DIR__).'/assets/landscape.js');
  $body=substr($s,strpos($s,'function neighbourRegister('),strpos($s,'function pathEdge(')-strpos($s,'function neighbourRegister('));
  self::assertStringContainsString('o.rotation',$body);
  self::assertStringContainsString('occupied.add(yy*W+xx)',$body);
  self::assertStringNotContainsString('orientation',$body);
 }
}
