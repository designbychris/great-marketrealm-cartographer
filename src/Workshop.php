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
        require_once __DIR__ . '/Drafts.php';
        add_action('init', [Drafts::class, 'register']);
        add_action('admin_menu', [self::class, 'registerMenu']);
        add_action('admin_post_gmrcart_create_draft', [self::class, 'createDraft']);
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
        return is_user_logged_in() && current_user_can('edit_posts') && (bool) apply_filters('gmrcart_keeper_entitled', false, get_current_user_id());
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

    public static function createDraft(): void
    {
        if (!self::canAccess()) wp_die('Access denied', '', ['response'=>403]);
        check_admin_referer('gmrcart_create_draft');
        $campaign=sanitize_text_field((string)wp_unslash($_POST['campaign_id']??''));
        $name=sanitize_text_field((string)wp_unslash($_POST['draft_title']??''));
        if (!Drafts::create(get_current_user_id(),$campaign,$name)) wp_die('Campaign not authorised', '', ['response'=>403]);
        wp_safe_redirect(admin_url('admin.php?page=gmrcart-workshop&created=1'));
        exit;
    }

    public static function render(): void
    {
        if (!self::canAccess()) {
            echo '<p>' . esc_html__('Access denied.', 'great-marketrealm-cartographer') . '</p>';
            return;
        }
        echo '<div class="wrap gmrcart-workshop">';
        echo '<h1>' . esc_html__('Cartographer’s Workshop', 'great-marketrealm-cartographer') . '</h1>';
        echo '<p>' . esc_html__('Private construction drafts. Publishing to Tabletop is not yet available.', 'great-marketrealm-cartographer') . '</p>';
        if (isset($_GET['created'])) echo '<p>' . esc_html__('Draft created.', 'great-marketrealm-cartographer') . '</p>';
        echo '<h2>' . esc_html__('My drafting tables', 'great-marketrealm-cartographer') . '</h2><ul>';
        foreach (Drafts::owned(get_current_user_id()) as $draft) echo '<li>' . esc_html($draft->post_title) . '</li>';
        echo '</ul><h2>' . esc_html__('New drafting table', 'great-marketrealm-cartographer') . '</h2>';
        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
        echo '<input type="hidden" name="action" value="gmrcart_create_draft">';
        wp_nonce_field('gmrcart_create_draft');
        echo '<p><label>Campaign ID <input name="campaign_id" required></label></p>';
        echo '<p><label>Draft name <input name="draft_title" required></label></p>';
        submit_button(__('Create drafting table', 'great-marketrealm-cartographer'));
        echo '</form></div>';
    }
}
