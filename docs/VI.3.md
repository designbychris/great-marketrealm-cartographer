# VI.3 — The Keeper Receives the Keys

The Cartographer reads GMRC campaign posts (`gmrc_campaign`, `_gmrc_campaign_id`) and checks ownership via post author and `gmrc_manage_campaigns`. No writes to GMRC or GMRT. Drafts remain private and owner-scoped.

Installing/activating the Cartographer is the optional feature gate in this phase; **per-account activation via Expansions is not implemented yet**. `gmrcart_keeper_entitled` remains the extension point for future entitlements. Do not claim that Reading Room Almanac activation grants the builder: the current Almanac contract is content-oriented.

Only owned campaigns are shown in the selection list. Server-side checks independently enforce ownership on POST. Future phases: per-account opt-in, front-end DM Desk integration, editing saved draft geometry.
