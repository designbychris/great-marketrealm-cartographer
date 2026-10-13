# VI.4B — The Workshop Joins the Reading Room

- Install the `cartographer/` overlay into the Cartographer plugin and the `expansions/` overlay into the Expansions plugin.
- A Keeper tool card is injected into the Reading Room using `gmrexp_reading_room_tools` when Cartographer is active and the user has `gmrc_manage_campaigns`.
- The Cartographer uses its own per-user flag `_gmrcart_workshop_enabled`. New users are opted out until they enable the tool. No Almanac activation state is touched.
- The toggle uses the authenticated `admin_post_gmrcart_toggle_workshop` endpoint, nonce, user capability, and updates only the current user's meta.
- Disabling never deletes construction drafts. Campaign ownership is still checked separately.
- Test: enable via Reading Room, open Companion campaign, create draft, disable and confirm link is hidden, re-enable and confirm draft persists.
- Note: the Cartographer plugin must be active to show the tool card. Reading Room and Cartographer should be on the same WordPress installation.
