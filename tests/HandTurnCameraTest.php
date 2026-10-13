<?php
use PHPUnit\Framework\TestCase;

final class HandTurnCameraTest extends TestCase
{
    public function testPointerRotationUsesFourViewsWithoutChangingSceneData(): void
    {
        $source = file_get_contents(__DIR__ . '/../assets/landscape.js');
        self::assertStringContainsString("frame.addEventListener('pointerdown'", $source);
        self::assertStringContainsString("frame.addEventListener('pointermove'", $source);
        self::assertStringContainsString("frame.addEventListener('pointerup'", $source);
        self::assertStringContainsString("frame.addEventListener('pointercancel'", $source);
        self::assertStringContainsString('setPointerCapture', $source);
        self::assertStringContainsString('touchAction', $source);
        self::assertStringContainsString('Math.abs(dx)>=36', $source);
        self::assertStringContainsString('orientation=(orientation+delta%4+4)%4', $source);
    }

    public function testCameraTransitionPreservesReducedMotionAndExistingButtons(): void
    {
        $source = file_get_contents(__DIR__ . '/../assets/landscape.js');
        self::assertStringContainsString('prefers-reduced-motion: reduce', $source);
        self::assertStringContainsString("data-gmrcart-turn-layer", $source);
        self::assertStringContainsString('cloneNode(true)', $source);
        self::assertStringContainsString('cameraButtons.forEach(button=>button.addEventListener', $source);
        self::assertStringContainsString('clearTurnLayer()', $source);
    }
}
