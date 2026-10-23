<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class LeatherboundAtlasTest extends TestCase
{
    public function test_leatherbound_atlas_is_a_presentation_only_background(): void
    {
        $css = file_get_contents(__DIR__ . '/../assets/canvas.css');
        self::assertStringContainsString("VI.6D.14", $css);
        self::assertStringContainsString("--gmrcart-atlas-leather-base", $css);
        self::assertStringContainsString("repeating-linear-gradient", $css);
        self::assertStringContainsString("svg.gmrcart-landscape", $css);
        self::assertStringContainsString("background:transparent!important", $css);
        self::assertStringContainsString("@media(forced-colors:active)", $css);
    }

    public function test_future_realm_palette_tokens_are_scoped_to_the_frame(): void
    {
        $css = file_get_contents(__DIR__ . '/../assets/canvas.css');
        foreach (['midnight', 'withered', 'frostreem'] as $theme) {
            self::assertStringContainsString('data-gmrcart-atlas-theme="' . $theme . '"', $css);
        }
    }
}
