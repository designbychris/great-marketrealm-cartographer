# VI.2 — The Keeper Claims a Drafting Table

Private WordPress custom post type drafts; user-owned and linked to an opaque Companion campaign ID. No changes to Companion or Tabletop tables.

Security boundaries: `gmrcart_keeper_entitled` and `gmrcart_can_edit_campaign` both default to deny. A trusted integration adapter must implement both filters before draft creation is available. This phase intentionally does not infer DM status from WordPress `edit_posts` alone. The draft creation action checks authentication, entitlement, nonce and campaign permission.

Not yet included: Companion integration adapter, account activation UI, asset placement, levels, roofs, scene publishing. These require subsequent phases.
