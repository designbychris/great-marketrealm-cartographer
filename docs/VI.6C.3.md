# VI.6C.3 — Pippin Opens the Builder's Catalogue

Adds an optional `appearance` key to scene objects, with a strict allowlist and backwards-compatible absence for existing drafts. The construction catalogue presents a style selector. New structures inherit the chosen appearance; selecting an existing structure loads its current style and changing the selector updates that structure. Appearance changes only the illustrated roof/door/window palette, not prefab identity, footprint, or placement rules. Older objects without `appearance` retain their type-default appearance.

This is the first selectable catalogue; it does not yet include graphical thumbnails or freeform roof component editing. The visual decorations still use the existing projection and occlusion ordering.
