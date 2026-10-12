# Great MarketRealm Cartographer — Cartographer’s Workshop

Optional WordPress plugin for Dungeon Master construction tools.

## Phase VI.1 — The Cartographer Opens His Workshop

Foundation only: activation-safe plugin bootstrap, a permission-gated admin Workshop page, optional `[gmrcart_workshop]` shortcode, explicit capability negotiation hook, gettext text domain and regression test scaffolding.

**No scene editor, account entitlement, scene publishing, or expansion-pack installation is implemented yet.** The current `edit_posts` permission is a temporary conservative WordPress capability gate, **not** proof that a user is a campaign Dungeon Master. The production DM-account check must use a negotiated Companion integration contract in a later phase.

### Architectural boundaries

- **Companion** owns canonical campaigns, Locations, DM permissions and character records.
- **Cartographer** owns construction drafts, geometry, prefabs, level/storey representation, roofs and editor UX (future phases).
- **Tabletop** owns playable scenes, fog, LOS, walls, tokens and live game state.
- **Expansions** owns published Almanac content and activation; themed construction asset packs will be negotiated through an explicit future capability, not assumed to be supported by the existing rules-content API.
- No cross-plugin direct database access. No modification to other plugins in VI.1.

### Installation

Copy the files into `wp-content/plugins/great-marketrealm-cartographer/`, activate through WordPress, and open **Cartographer** in the WordPress admin menu as an authorised user. This is a foundation screen, not yet a player-facing DM Desk integration.

### Tests

Run `composer install` then `php vendor/bin/phpunit --display-warnings` from the plugin directory. PHP 8.1+.

### Next

VI.2: define campaign-scoped construction draft model, optional per-DM entitlement bridge, and integration contract tests before editing scenes.
