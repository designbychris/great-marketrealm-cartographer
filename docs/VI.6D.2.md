# VI.6D.2 — Pippin Paints His First Cottage

Adds the first four transparent, pixel-inspired cottage illustrations under `assets/sprites/buildings/`, with a reusable view-only `assets/building-illustrations.js` catalogue. The catalogue is enqueued before `landscape.js`. The illustration adapter keeps prefab footprint, rotation, depth ordering, and saved scene untouched; Classic Illustration and unsupported building types retain the original SVG renderer. Cottage images are intentionally first-pass procedural pixel illustrations, not final commissioned concept art.

Browser checks: in Pixel-inspired mode, rotate through all four views and compare cottage alignment, then switch to Classic Illustration and verify the SVG cottage returns. Check 100%/150%, depth guides and reload. PHPUnit regression tests cover asset availability and enqueue dependency. Current baseline before this patch: 58 tests / 298 assertions.
