<?php
declare(strict_types=1);
namespace GreatMarketrealmCartographer;
defined('ABSPATH') || exit;
/** Private, versioned scene documents belonging to Cartographer drafts. */
final class Scene
{
    public const META = '_gmrcart_scene';
    public const WIDTH = 12;
    public const HEIGHT = 12;
    public const MATERIALS = ['empty', 'floor', 'wall', 'water', 'tree'];

    public static function canEdit(int $id, int $user): bool
    {
        $post = $id > 0 ? get_post($id) : null;
        return $user > 0 && Workshop::canAccess() && $post instanceof \WP_Post
            && $post->post_type === Drafts::TYPE && $post->post_status === 'private'
            && (int)$post->post_author === $user
            && Drafts::allowed($user, Drafts::campaignId($id));
    }

    public static function blank(): array
    {
        return ['version'=>1, 'width'=>self::WIDTH, 'height'=>self::HEIGHT,
            'cells'=>array_fill(0, self::WIDTH * self::HEIGHT, 'empty')];
    }

    /** Reject invalid payloads rather than silently corrupting a scene. */
    public static function validate($value): ?array
    {
        if (!is_array($value) || ($value['version'] ?? null) !== 1
            || ($value['width'] ?? null) !== self::WIDTH || ($value['height'] ?? null) !== self::HEIGHT
            || !isset($value['cells']) || !is_array($value['cells'])
            || array_keys($value['cells']) !== range(0, self::WIDTH * self::HEIGHT - 1)) return null;
        foreach ($value['cells'] as $cell) {
            if (!is_string($cell) || !in_array($cell, self::MATERIALS, true)) return null;
        }
        return ['version'=>1, 'width'=>self::WIDTH, 'height'=>self::HEIGHT, 'cells'=>$value['cells']];
    }

    public static function read(int $id): array
    {
        $scene = get_post_meta($id, self::META, true);
        return self::validate($scene) ?? self::blank();
    }

    public static function save(): void
    {
        $id = absint($_POST['draft_id'] ?? 0);
        if (!self::canEdit($id, get_current_user_id())) wp_send_json_error(['message'=>'Access denied'], 403);
        if (!check_ajax_referer('gmrcart_scene_' . $id, 'nonce', false)) wp_send_json_error(['message'=>'Invalid nonce'], 403);
        $raw = isset($_POST['scene']) && is_string($_POST['scene']) ? wp_unslash($_POST['scene']) : '';
        if (strlen($raw) > 16000) wp_send_json_error(['message'=>'Scene too large'], 400);
        $scene = self::validate(json_decode($raw, true));
        if ($scene === null) wp_send_json_error(['message'=>'Invalid scene'], 400);
        update_post_meta($id, self::META, $scene);
        wp_send_json_success(['message'=>'Saved']);
    }
}
