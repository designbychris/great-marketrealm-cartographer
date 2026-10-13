# VI.6 — Pippin’s Illustrated Atlas (foundation)

This is a **layout and presentation foundation**, not a finished asset renderer.

- Workshop internal shell can grow to 1760px. Elementor's parent container can still restrict it: set the Cartographer page template/container to Full Width and remove boxed/max-width constraints in Elementor. Avoid global theme CSS overrides.
- Construction grid scales to 980px and SVG preview to 1200px, while remaining responsive.
- `assets/atlas-palettes.js` registers named *visual-only* palettes. MarketRealm is active; Withered and Takeaway are placeholders for later expansion style packs, **not activated** or available as a user control.
- Terrain and object coordinates, ownership checks, camera rotation and persistence are unchanged.
- Later illustrated asset packs should register trusted assets with validated identifiers, image dimensions, view direction and layer ordering; do not inject untrusted SVG markup.
- Next milestone: actual high-quality authored/illustrated assets and renderer registration, plus a controlled visual theme selector.
