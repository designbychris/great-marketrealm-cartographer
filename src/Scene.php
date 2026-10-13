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
    public const PREFABS = ['cottage'=>[2,2], 'tavern'=>[3,2], 'chamber'=>[3,3], 'blacksmith'=>[3,2], 'tower'=>[2,2], 'corridor'=>[1,3], 'crypt'=>[3,3], 'market'=>[3,2]];

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
            'cells'=>array_fill(0, self::WIDTH * self::HEIGHT, 'empty'), 'objects'=>[]];
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
        $objects = $value['objects'] ?? [];
        if (!is_array($objects) || count($objects) > 32 || array_keys($objects) !== ($objects ? range(0, count($objects)-1) : [])) return null;
        $ids = [];
        foreach ($objects as $object) {
            if (!is_array($object) || !isset($object['id'], $object['type'], $object['x'], $object['y'], $object['rotation'])
                || !is_string($object['id']) || !preg_match('/^[a-zA-Z0-9_-]{1,40}$/', $object['id'])
                || isset($ids[$object['id']]) || !is_string($object['type']) || !isset(self::PREFABS[$object['type']])
                || !is_int($object['x']) || !is_int($object['y']) || !is_int($object['rotation'])
                || !in_array($object['rotation'], [0, 90, 180, 270], true)) return null;
            $ids[$object['id']] = true;
            [$w,$h] = self::PREFABS[$object['type']];
            if ($object['rotation'] === 90 || $object['rotation'] === 270) [$w,$h] = [$h,$w];
            if ($object['x'] < 0 || $object['y'] < 0 || $object['x']+$w > self::WIDTH || $object['y']+$h > self::HEIGHT) return null;
        }
        for ($i=0; $i<count($objects); $i++) {
            $a=$objects[$i]; [$aw,$ah]=self::PREFABS[$a['type']];
            if ($a['rotation']%180) [$aw,$ah]=[$ah,$aw];
            for ($j=$i+1; $j<count($objects); $j++) {
                $b=$objects[$j]; [$bw,$bh]=self::PREFABS[$b['type']];
                if ($b['rotation']%180) [$bw,$bh]=[$bh,$bw];
                if ($a['x'] < $b['x']+$bw && $a['x']+$aw > $b['x'] && $a['y'] < $b['y']+$bh && $a['y']+$ah > $b['y']) return null;
            }
        }
        return ['version'=>1, 'width'=>self::WIDTH, 'height'=>self::HEIGHT, 'cells'=>$value['cells'], 'objects'=>$objects];
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
        if (strlen($raw) > 24000) wp_send_json_error(['message'=>'Scene too large'], 400);
        $scene = self::validate(json_decode($raw, true));
        if ($scene === null) wp_send_json_error(['message'=>'Invalid scene'], 400);
        update_post_meta($id, self::META, $scene);
        wp_send_json_success(['message'=>'Saved']);
    }
}
