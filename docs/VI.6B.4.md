# VI.6B.4 — Pippin Separates the Layers

- Workshop shortcode `[gmrcart_workshop]` now lists and creates drafts only.
- Canvas shortcode `[gmrcart_canvas]` opens a specific draft on `/cartographer/canvas/?draft_id=123&campaign_id=...`.
- Studio requires the existing Keeper entitlement and `Scene::canEdit` (draft author and campaign permission).
- If the canvas WordPress page is unpublished, links temporarily fall back to the Workshop URL; the old embedded canvas is deliberately removed from the Workshop, so publish the canvas page before installing.
- The opt-in depth guides show numbered draw order, anchor points, and projected footprint outlines in all four camera directions. They are SVG-only, not stored in scenes.
- This phase diagnoses painter-order limitations; it does not claim complete per-face occlusion correction.
- Elementor: create `/cartographer/canvas/`, use Full Width and `[gmrcart_canvas]` shortcode.
