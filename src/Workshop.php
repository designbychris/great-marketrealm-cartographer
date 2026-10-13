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
        require_once __DIR__ . '/CompanionBridge.php';
        require_once __DIR__ . '/Scene.php';
        require_once __DIR__ . '/WorkshopEntitlement.php';
        WorkshopEntitlement::register();
        CompanionBridge::register();
        add_action('init', [Drafts::class, 'register']);
        add_action('admin_menu', [self::class, 'registerMenu']);
        add_action('template_redirect', [self::class, 'frontend']);
        add_action('admin_post_gmrcart_create_draft', [self::class, 'createDraft']);
        add_action('wp_ajax_gmrcart_save_scene', [Scene::class, 'save']);
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
        return is_user_logged_in() && (bool) apply_filters('gmrcart_keeper_entitled', false, get_current_user_id());
    }

    public static function registerMenu(): void
    {
        add_menu_page(
            __('Cartographer’s Workshop', 'great-marketrealm-cartographer'),
            __('Cartographer', 'great-marketrealm-cartographer'),
            'gmrc_manage_campaigns', 'gmrcart-workshop', [self::class, 'render'], 'dashicons-location-alt', 59
        );
    }

    public static function shortcode(): string
    {
        $campaign = sanitize_text_field((string) wp_unslash($_GET['campaign_id'] ?? ''));
        return self::workshopMarkup($campaign);
    }

    public static function createDraft(): void
    {
        if (!self::canAccess()) wp_die('Access denied', '', ['response'=>403]);
        check_admin_referer('gmrcart_create_draft');
        $campaign=sanitize_text_field((string)wp_unslash($_POST['campaign_id']??''));
        $name=sanitize_text_field((string)wp_unslash($_POST['draft_title']??''));
        if (!Drafts::create(get_current_user_id(),$campaign,$name)) wp_die('Campaign not authorised', '', ['response'=>403]);
        $destination = function_exists('gmrcart_workshop_url') ? gmrcart_workshop_url($campaign) : home_url('/');
        $destination = add_query_arg('created', '1', $destination);
        wp_safe_redirect($destination);
        exit;
    }


    /** Legacy URL support: redirect only to an existing published Workshop page. */
    public static function frontend(): void
    {
        if (!isset($_GET['gmrcart_workshop']) || (string) $_GET['gmrcart_workshop'] !== '1') return;
        if (!is_user_logged_in()) { auth_redirect(); exit; }
        if (!self::canAccess()) wp_die(esc_html__('Workshop access denied.', 'great-marketrealm-cartographer'), '', ['response'=>403]);
        $campaign = sanitize_text_field((string) wp_unslash($_GET['campaign_id'] ?? ''));
        if ($campaign !== '' && !Drafts::allowed(get_current_user_id(), $campaign)) {
            wp_die(esc_html__('Campaign access denied.', 'great-marketrealm-cartographer'), '', ['response'=>403]);
        }
        $page = get_page_by_path('cartographer');
        if ($page instanceof \WP_Post && $page->post_status === 'publish') {
            $url = add_query_arg('campaign_id', $campaign, get_permalink($page));
            wp_safe_redirect($url); exit;
        }
        // Retain a working legacy route until the shortcode page is published.
        nocache_headers();
        get_header();
        echo self::workshopMarkup($campaign);
        get_footer();
        exit;
    }

    /** Elementor-compatible: renders markup only; WordPress owns the page shell. */
    public static function workshopMarkup(string $campaign): string
    {
        if (!is_user_logged_in()) return '<p>' . esc_html__('Please sign in to open the Workshop.', 'great-marketrealm-cartographer') . '</p>';
        if (!self::canAccess()) return '<p>' . esc_html__('Workshop access denied.', 'great-marketrealm-cartographer') . '</p>';
        if ($campaign !== '' && !Drafts::allowed(get_current_user_id(), $campaign)) {
            return '<p>' . esc_html__('Campaign access denied.', 'great-marketrealm-cartographer') . '</p>';
        }
        $name = CompanionBridge::campaigns(get_current_user_id())[$campaign] ?? '';
        $returnUrl = $campaign !== ''
            ? add_query_arg('gmrc_route', 'dungeon-master/campaigns/' . rawurlencode($campaign), home_url('/companion/'))
            : home_url('/companion/');
        ob_start();
        ?>
<style>
.gmrcart-realm{background:#100d13;color:#f7ead3;font-family:system-ui,-apple-system,Segoe UI,sans-serif;min-height:70vh}
.gmrcart-realm *{box-sizing:border-box}.gmrcart-shell{width:min(100%,1760px);max-width:none;margin:0 auto;padding:32px clamp(16px,2.5vw,48px) 80px}
.gmrcart-top{display:flex;justify-content:space-between;align-items:center;gap:18px;flex-wrap:wrap}
.gmrcart-top a{color:#f4c980;text-decoration:none}.gmrcart-hero{margin:24px 0 28px;padding:42px 36px;border:1px solid #7b5a34;border-radius:18px;background:linear-gradient(115deg,#2c1b25,#24191c 58%,#48311f);box-shadow:0 20px 60px #0007}
.gmrcart-kicker{letter-spacing:.16em;text-transform:uppercase;color:#e6b967;font-size:.75rem;font-weight:800}
.gmrcart-hero h1{font-family:Georgia,serif;font-size:clamp(2rem,5vw,3.6rem);margin:12px 0}
.gmrcart-hero p{max-width:680px;line-height:1.65;color:#e5d4be}
.gmrcart-layout{display:grid;grid-template-columns:1.2fr 1fr;gap:22px}
.gmrcart-panel{background:#251b23;border:1px solid #715034;border-radius:14px;padding:26px;box-shadow:0 12px 30px #0005}
.gmrcart-panel h2{font-family:Georgia,serif;color:#f9db9f;margin:0 0 20px}
.gmrcart-panel label{display:block;margin:0 0 14px;font-weight:700}
.gmrcart-panel input,.gmrcart-panel select{width:100%;margin-top:7px;padding:12px;background:#150f15;color:#fff;border:1px solid #98724a;border-radius:8px}
.gmrcart-button{background:#d6a44f;color:#24151b;border:0;border-radius:8px;padding:12px 20px;font-weight:800;cursor:pointer}
.gmrcart-list{list-style:none;padding:0;margin:0}.gmrcart-list li{border:1px solid #61472e;background:#34252a;padding:14px;border-radius:9px;margin:0 0 10px}
.gmrcart-muted{color:#dac5ac;line-height:1.6}.gmrcart-notice{padding:12px;border:1px solid #c79b52;border-radius:8px;margin-bottom:18px}
@media(max-width:760px){.gmrcart-layout{grid-template-columns:1fr}.gmrcart-hero{padding:28px 22px}}
/* VI.6: the workshop occupies available space without leaking into other Elementor pages. */
.gmrcart-realm{width:100%;max-width:none}
.gmrcart-realm .gmrcart-shell{width:min(100%,1760px);max-width:none}
.gmrcart-realm .gmrcart-canvas-panel{min-width:0}
@media(min-width:1100px){.gmrcart-realm .gmrcart-layout{grid-template-columns:minmax(0,1.25fr) minmax(300px,.75fr)}}
@media(max-width:760px){.gmrcart-realm .gmrcart-shell{padding:20px 12px 48px}}
</style>
<main class="gmrcart-realm"><div class="gmrcart-shell">
<nav class="gmrcart-top" aria-label="<?php echo esc_attr__('Workshop navigation', 'great-marketrealm-cartographer'); ?>">
<strong>✦ <?php echo esc_html__('The Great MarketRealm · Cartographer', 'great-marketrealm-cartographer'); ?></strong>
<a href="<?php echo esc_url($returnUrl); ?>">← <?php echo esc_html__('Return to Dungeon Master’s Desk', 'great-marketrealm-cartographer'); ?></a>
</nav>
<section class="gmrcart-hero">
<div class="gmrcart-kicker"><?php echo esc_html__('The Keeper’s drafting room', 'great-marketrealm-cartographer'); ?></div>
<h1><?php echo esc_html__('Cartographer’s Workshop', 'great-marketrealm-cartographer'); ?></h1>
<p><?php echo esc_html($name !== '' ? $name : __('Choose a campaign and begin a private construction draft.', 'great-marketrealm-cartographer')); ?></p>
<p><?php echo esc_html__('Shape the foundations of future adventures. Scene building and Tabletop publishing will arrive in later phases.', 'great-marketrealm-cartographer'); ?></p>
</section>
<?php self::renderFrontendContent($campaign); ?>
<?php self::renderEditor($campaign); ?>
</div></main>
<?php
        return (string) ob_get_clean();
    }

    private static function renderFrontendContent(string $campaign): void
    {
        $campaigns = CompanionBridge::campaigns(get_current_user_id());
        $drafts = Drafts::owned(get_current_user_id());
        echo '<div class="gmrcart-layout"><section class="gmrcart-panel"><h2>' . esc_html__('My drafting tables', 'great-marketrealm-cartographer') . '</h2>';
        if (isset($_GET['created'])) echo '<p class="gmrcart-notice" role="status">' . esc_html__('Draft created.', 'great-marketrealm-cartographer') . '</p>';
        echo '<ul class="gmrcart-list">';
        $shown = 0;
        foreach ($drafts as $draft) {
            $draftCampaign = Drafts::campaignId((int) $draft->ID);
            if ($campaign !== '' && $draftCampaign !== $campaign) continue;
            ++$shown;
            echo '<li><strong>' . esc_html($draft->post_title) . '</strong><div class="gmrcart-muted">' . esc_html($campaigns[$draftCampaign] ?? $draftCampaign) . '</div><a style="color:#f4c980" href="' . esc_url(add_query_arg(['draft_id'=>(int)$draft->ID, 'campaign_id'=>$draftCampaign], remove_query_arg(['created','draft_id']))) . '">' . esc_html__('Open construction canvas', 'great-marketrealm-cartographer') . '</a></li>';
        }
        if ($shown === 0) echo '<li class="gmrcart-muted">' . esc_html__('No drafting tables yet. Create the first one!', 'great-marketrealm-cartographer') . '</li>';
        echo '</ul></section><section class="gmrcart-panel"><h2>' . esc_html__('New drafting table', 'great-marketrealm-cartographer') . '</h2>';
        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
        echo '<input type="hidden" name="action" value="gmrcart_create_draft">';
        wp_nonce_field('gmrcart_create_draft');
        echo '<label>' . esc_html__('Campaign', 'great-marketrealm-cartographer') . '<select name="campaign_id" required>';
        foreach ($campaigns as $id => $label) {
            echo '<option value="' . esc_attr((string) $id) . '"' . selected($campaign, (string) $id, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select></label><label>' . esc_html__('Draft name', 'great-marketrealm-cartographer') . '<input name="draft_title" maxlength="160" required></label>';
        echo '<button type="submit" class="gmrcart-button">' . esc_html__('Create drafting table', 'great-marketrealm-cartographer') . '</button></form></section></div>';
    }


    /** Render only the owner's campaign-authorised draft. */
    private static function renderEditor(string $campaign): void
    {
        $draftId = absint($_GET['draft_id'] ?? 0);
        if (!$draftId) return;
        if (!Scene::canEdit($draftId, get_current_user_id())) {
            echo '<p class="gmrcart-notice">' . esc_html__('This construction draft is unavailable.', 'great-marketrealm-cartographer') . '</p>';
            return;
        }
        if ($campaign !== '' && Drafts::campaignId($draftId) !== $campaign) return;
        $scene = Scene::read($draftId);
        $config = [
            'ajax' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('gmrcart_scene_' . $draftId),
            'draftId' => $draftId,
            'scene' => $scene,
            'labels' => ['saved'=>__('Scene saved.', 'great-marketrealm-cartographer'), 'error'=>__('Could not save scene.', 'great-marketrealm-cartographer')],
        ];
        require_once __DIR__ . '/PrefabCatalog.php';
        $config['prefabs'] = PrefabCatalog::items();
        $config['atlasAssetBase'] = plugins_url('assets/sprites/', dirname(__FILE__));
        wp_enqueue_style('gmrcart-canvas', plugins_url('assets/canvas.css', dirname(__FILE__)), [], GMRCART_VERSION);
        wp_enqueue_script('gmrcart-canvas', plugins_url('assets/canvas.js', dirname(__FILE__)), [], GMRCART_VERSION, true);
        wp_enqueue_script('gmrcart-atlas-palettes', plugins_url('assets/atlas-palettes.js', dirname(__FILE__)), [], GMRCART_VERSION, true);
        wp_enqueue_script('gmrcart-atlas-assets', plugins_url('assets/atlas-assets.js', dirname(__FILE__)), [], GMRCART_VERSION, true);
        wp_enqueue_script('gmrcart-landscape', plugins_url('assets/landscape.js', dirname(__FILE__)), ['gmrcart-canvas', 'gmrcart-atlas-palettes', 'gmrcart-atlas-assets'], GMRCART_VERSION, true);
        wp_add_inline_script('gmrcart-landscape', 'window.gmrcartLandscape = true;', 'before');
        wp_add_inline_script('gmrcart-canvas', 'window.gmrcartScene = ' . wp_json_encode($config) . ';', 'before');
        echo '<section class="gmrcart-panel gmrcart-canvas-panel"><h2>' . esc_html__('Pippin’s construction canvas', 'great-marketrealm-cartographer') . '</h2>';
        echo '<p class="gmrcart-muted">' . esc_html__('Choose a material, then click or drag across the grid. Save to keep your work.', 'great-marketrealm-cartographer') . '</p>';
        echo '<div class="gmrcart-tools" role="toolbar" aria-label="Construction materials">';
        foreach (['floor'=>__('Ground', 'great-marketrealm-cartographer'), 'wall'=>__('Wall', 'great-marketrealm-cartographer'), 'water'=>__('Water', 'great-marketrealm-cartographer'), 'tree'=>__('Tree', 'great-marketrealm-cartographer'), 'erase'=>__('Erase', 'great-marketrealm-cartographer')] as $key=>$label) {
            echo '<button type="button" class="gmrcart-tool" data-material="' . esc_attr($key) . '" aria-pressed="' . ($key === 'floor' ? 'true' : 'false') . '">' . esc_html($label) . '</button>';
        }
        echo '</div><div class="gmrcart-library" aria-label="Construction Library"><h3>' . esc_html__('Construction Library', 'great-marketrealm-cartographer') . '</h3><p class="gmrcart-muted">' . esc_html__('Browse reusable structures. Choose one, then click the grid to place it.', 'great-marketrealm-cartographer') . '</p>';
        echo '<div class="gmrcart-library-filters"><label for="gmrcart-library-search">' . esc_html__('Find a structure', 'great-marketrealm-cartographer') . '</label><input id="gmrcart-library-search" class="gmrcart-library-search" type="search" placeholder="Search the library" autocomplete="off" />';
        echo '<label for="gmrcart-library-category">' . esc_html__('Category', 'great-marketrealm-cartographer') . '</label><select id="gmrcart-library-category" class="gmrcart-library-category"><option value="all">All structures</option><option value="buildings">Buildings</option><option value="interiors">Interiors</option><option value="settlements">Settlements</option></select></div>';
        echo '<div class="gmrcart-prefabs" role="group" aria-label="Prefabs">';
        foreach (PrefabCatalog::items() as $key => $item) {
            $size = $item['size'];
            echo '<button type="button" class="gmrcart-prefab" data-prefab="' . esc_attr($key) . '" data-category="' . esc_attr($item['category']) . '" data-search="' . esc_attr(strtolower($item['name'] . ' ' . $item['category'])) . '" aria-pressed="false"><span class="gmrcart-prefab-name">' . esc_html__($item['name'], 'great-marketrealm-cartographer') . '</span><small>' . esc_html($size[0] . ' × ' . $size[1]) . '</small></button>';
        }
        echo '</div><div class="gmrcart-placement-hint" role="status" aria-live="polite">Choose a building to preview its footprint on the grid.</div><div class="gmrcart-object-actions"><span class="gmrcart-object-status" role="status" aria-live="polite">' . esc_html__('No structure selected', 'great-marketrealm-cartographer') . '</span><button type="button" class="gmrcart-object-cancel" hidden>Cancel placement</button><button type="button" class="gmrcart-object-rotate" disabled>' . esc_html__('Rotate', 'great-marketrealm-cartographer') . '</button><button type="button" class="gmrcart-object-move" disabled>' . esc_html__('Move', 'great-marketrealm-cartographer') . '</button><button type="button" class="gmrcart-object-remove" disabled>' . esc_html__('Remove', 'great-marketrealm-cartographer') . '</button></div></div><button type="button" class="gmrcart-view-toggle" aria-pressed="false">' . esc_html__('View illustrated landscape', 'great-marketrealm-cartographer') . '</button>';
        echo '<div class="gmrcart-illustration-toolbar" hidden><div class="gmrcart-camera-controls" role="group" aria-label="Illustrated landscape camera" hidden><button type="button" data-gmrcart-camera="left" disabled>↶ Rotate left</button><output class="gmrcart-camera-angle" aria-live="polite">South-east · 0°</output><button type="button" data-gmrcart-camera="right" disabled>Rotate right ↷</button></div>';
        
        echo '<div class="gmrcart-atlas-style" role="group" aria-label="Illustration style" hidden><button type="button" data-gmrcart-style="classic" aria-pressed="true">Classic illustration</button><button type="button" data-gmrcart-style="pixel" aria-pressed="false">Pixel-inspired art</button></div></div>';
        echo '<div class="gmrcart-grid" role="grid" aria-label="Editable construction grid"></div><div class="gmrcart-landscape-frame" hidden><svg class="gmrcart-landscape" xmlns="http://www.w3.org/2000/svg" width="660" height="385" viewBox="0 0 660 385" role="img" aria-label="Isometric construction landscape"></svg></div>';
        echo '<div class="gmrcart-canvas-actions"><button type="button" class="gmrcart-button gmrcart-save">' . esc_html__('Save construction', 'great-marketrealm-cartographer') . '</button><span class="gmrcart-save-status" role="status" aria-live="polite"></span></div></section>';
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
        echo '<p><label>' . esc_html__('Campaign', 'great-marketrealm-cartographer') . ' <select name="campaign_id" required>';
        echo '<option value="">' . esc_html__('Select a campaign', 'great-marketrealm-cartographer') . '</option>';
        $selectedCampaign = sanitize_text_field((string) wp_unslash($_GET['campaign_id'] ?? ''));
        foreach (CompanionBridge::campaigns(get_current_user_id()) as $campaignId => $campaignName) {
            echo '<option value="' . esc_attr($campaignId) . '"' . selected($selectedCampaign, (string) $campaignId, false) . '>' . esc_html($campaignName) . '</option>';
        }
        echo '</select></label></p>';
        echo '<p><label>Draft name <input name="draft_title" required></label></p>';
        submit_button(__('Create drafting table', 'great-marketrealm-cartographer'));
        echo '</form></div>';
    }
}
