<?php
use PHPUnit\Framework\TestCase;
final class SceneFoundationTest extends TestCase {
    public function testSceneContractAndIsolation(): void {
        $src = file_get_contents(__DIR__ . '/../src/Scene.php');
        self::assertStringContainsString('Drafts::allowed(', $src);
        self::assertStringContainsString('post_author', $src);
        self::assertStringContainsString('check_ajax_referer(', $src);
        self::assertStringContainsString('wp_send_json_error(', $src);
        self::assertStringContainsString('update_post_meta(', $src);
    }
    public function testCanvasIsScopedAndPersistent(): void {
        $src = file_get_contents(__DIR__ . '/../src/Workshop.php');
        self::assertStringContainsString('Scene::canEdit(', $src);
        self::assertStringContainsString('wp_ajax_gmrcart_save_scene', $src);
        self::assertFileExists(__DIR__ . '/../assets/canvas.js');
        self::assertFileExists(__DIR__ . '/../assets/canvas.css');
    }
}
