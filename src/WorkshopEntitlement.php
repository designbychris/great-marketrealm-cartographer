<?php
declare(strict_types=1);
namespace GreatMarketrealmCartographer;
defined('ABSPATH') || exit;

/** Account-scoped opt-in for the construction tool, independent of Almanac activation. */
final class WorkshopEntitlement
{
    public const META_KEY = '_gmrcart_workshop_enabled';
    public const ACTION = 'gmrcart_toggle_workshop';

    public static function enabled(int $userId): bool
    {
        return $userId > 0 && (string) get_user_meta($userId, self::META_KEY, true) === '1';
    }

    public static function eligible(int $userId): bool
    {
        return $userId > 0 && (bool) get_userdata($userId) && user_can($userId, 'gmrc_manage_campaigns');
    }

    public static function register(): void
    {
        add_action('gmrexp_reading_room_tools', [self::class, 'renderCard']);
        add_action('admin_post_' . self::ACTION, [self::class, 'handleToggle']);
    }

    public static function renderCard(): void
    {
        $userId = get_current_user_id();
        if (!self::eligible($userId)) return;
        $enabled = self::enabled($userId);
        echo '<section class="gmrexp-reading-room__section gmrcart-tool-card" aria-labelledby="gmrcart-tool-title">';
        echo '<div class="gmrexp-reading-room__section-heading"><div><p class="gmrexp-reading-room__kicker">Keeper tools</p><h2 id="gmrcart-tool-title">Cartographer’s Workshop</h2></div></div>';
        echo '<p>Build private drafting tables for your campaigns. This optional tool is separate from Almanacs.</p>';
        echo '<p><strong>' . esc_html($enabled ? 'Enabled for your account' : 'Not enabled') . '</strong></p>';
        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
        echo '<input type="hidden" name="action" value="' . esc_attr(self::ACTION) . '">';
        wp_nonce_field(self::ACTION);
        echo '<button class="gmrexp-reading-room__button" type="submit" name="enabled" value="' . ($enabled ? '0' : '1') . '">' . esc_html($enabled ? 'Disable Workshop' : 'Enable Workshop') . '</button>';
        echo '</form>';
        if ($enabled && function_exists('gmrcart_workshop_url')) {
            echo '<p><a class="gmrexp-reading-room__text-link" href="' . esc_url(gmrcart_workshop_url('')) . '">Open Cartographer →</a></p>';
        }
        echo '</section>';
    }

    public static function handleToggle(): void
    {
        $userId = get_current_user_id();
        if (!self::eligible($userId)) {
            wp_die(esc_html__('Access denied.', 'great-marketrealm-cartographer'), '', ['response' => 403]);
        }
        check_admin_referer(self::ACTION);
        $enabled = isset($_POST['enabled']) && (string) wp_unslash($_POST['enabled']) === '1';
        update_user_meta($userId, self::META_KEY, $enabled ? '1' : '0');
        // Do not delete drafts, campaigns or any Almanac activation records.
        $return = wp_get_referer();
        wp_safe_redirect($return ? $return : home_url('/'), 303);
        exit;
    }
}
