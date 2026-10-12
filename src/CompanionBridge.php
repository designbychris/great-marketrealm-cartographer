<?php
declare(strict_types=1);
namespace GreatMarketrealmCartographer;
defined('ABSPATH') || exit;
/** Read-only bridge to the Companion campaign register; no duplicated campaign records. */
final class CompanionBridge
{
    private const CAPABILITY = 'gmrc_manage_campaigns';
    private const CAMPAIGN_TYPE = 'gmrc_campaign';
    private const CAMPAIGN_ID = '_gmrc_campaign_id';
    public static function register(): void
    {
        add_filter('gmrcart_keeper_entitled', [self::class, 'keeperEntitled'], 10, 2);
        add_filter('gmrcart_can_edit_campaign', [self::class, 'canEditCampaign'], 10, 3);
    }
    public static function keeperEntitled(bool $granted, int $userId): bool
    {
        if ($userId < 1 || !get_userdata($userId)) return false;
        // Plugin activation is the first optional feature gate. Future account-level
        // Expansions entitlements may restrict this further via this explicit filter.
        return user_can($userId, self::CAPABILITY);
    }
    public static function canEditCampaign(bool $granted, int $userId, string $campaignId): bool
    {
        if (!self::keeperEntitled(false, $userId) || $campaignId === '') return false;
        $matches = get_posts([
            'post_type' => self::CAMPAIGN_TYPE, 'post_status' => 'publish',
            'author' => $userId, 'meta_key' => self::CAMPAIGN_ID,
            'meta_value' => $campaignId, 'posts_per_page' => 2,
            'fields' => 'ids', 'suppress_filters' => true,
        ]);
        return count($matches) === 1;
    }
    public static function campaigns(int $userId): array
    {
        if (!self::keeperEntitled(false, $userId)) return [];
        $posts = get_posts([
            'post_type' => self::CAMPAIGN_TYPE, 'post_status' => 'publish',
            'author' => $userId, 'posts_per_page' => 200, 'orderby' => 'title',
            'order' => 'ASC', 'suppress_filters' => true,
        ]);
        $result = [];
        foreach ($posts as $post) {
            $id = (string) get_post_meta((int) $post->ID, self::CAMPAIGN_ID, true);
            if ($id === '' || isset($result[$id])) continue;
            $result[$id] = (string) $post->post_title;
        }
        return $result;
    }
}
