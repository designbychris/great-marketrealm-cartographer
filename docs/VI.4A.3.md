# VI.4A.3 — The Workshop Steps Into the Realm

The Companion bridge now points to `/?gmrcart_workshop=1&campaign_id=...` rather than wp-admin.

The Cartographer intercepts only this dedicated query parameter at `template_redirect`, authenticates the user, checks Workshop entitlement and campaign ownership, then renders a responsive dark-fantasy frontend Workshop. No Companion router registration or rewrite flushing is required.

Drafts remain private WordPress posts. The form posts to the existing nonce-protected `admin_post_gmrcart_create_draft` action, then redirects back to the frontend with the same campaign ID. The WordPress admin menu remains a fallback.

No Tabletop publishing or actual scene editor is included. Expansions per-account entitlement is still pending.

Install into **Cartographer only**. Run `php vendor/bin/phpunit --display-warnings` and test from a Companion campaign card.
