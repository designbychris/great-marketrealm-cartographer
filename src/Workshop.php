<?php
declare(strict_types=1);
namespace GreatMarketrealmCartographer;
defined('ABSPATH') || exit;

/**
 * VI.1: Independent construction boundary. No direct reads/writes to GMRC or GMRT tables.
 * Feature entitlement and scene publishing will be negotiated in later phases.
 */
final class Workshop
{
    public static function boot(): void
    {
        add_action('admin_menu', [self::class, 'registerMenu']);
        add_shortcode('gmrcart_workshop', [self::class, 'shortcode']);
        add_filter('gmrcart_capabilities', [self::class, 'capabilities']);
    }

    public static function capabilities(array $capabilities): array
    {
        $capabilities['workshop.foundation'] = '1.0.0';
        $capabilities['workshop.scene-publishing'] = false;
        $capabilities['workshop.asset-packs'] = false;
        return $capabilities;
    }

    public static function canAccess(): bool
    {
        // Account-level entitlements will be added separately. Never assume a login is a DM.
        return is_user_logged_in() && current_user_can('edit_posts');
    }

    public static function registerMenu(): void
    {
        add_menu_page(
            __('Cartographer’s Workshop', 'great-marketrealm-cartographer'),
            __('Cartographer', 'great-marketrealm-cartographer'),
            'edit_posts', 'gmrcart-workshop', [self::class, 'render'], 'dashicons-location-alt', 59
        );
    }

    public static function shortcode(): string
    {
        if (!self::canAccess()) {
            return '<p>' . esc_html__('The Cartographer’s Workshop is reserved for authorised Keepers.', 'great-marketrealm-cartographer') . '</p>';
        }
        ob_start();
        self::render();
        return (string) ob_get_clean();
    }

    public static function render(): void
    {
        if (!self::canAccess()) {
            echo '<p>' . esc_html__('Access denied.', 'great-marketrealm-cartographer') . '</p>';
            return;
        }
        echo '<div class="wrap gmrcart-workshop">';
        echo '<h1>' . esc_html__('Cartographer’s Workshop', 'great-marketrealm-cartographer') . '</h1>';
        echo '<p>' . esc_html__('The drafting table is ready. Construction tools, asset libraries and campaign-scoped scenes will arrive in future releases.', 'great-marketrealm-cartographer') . '</p>';
        echo '<p><strong>' . esc_html__('Workshop foundation', 'great-marketrealm-cartographer') . '</strong> · ' . esc_html__('No campaign or Tabletop scenes have been changed.', 'great-marketrealm-cartographer') . '</p>';
        echo '</div>';
    }
}
