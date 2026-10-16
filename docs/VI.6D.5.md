# VI.6D.5 — Pippin Opens the Cottage Painter's Workshop

The painter's workshop registers an illustrated building by **type**, not by an arbitrary sprite attached to a tile. The current `cottage` entry carries a label, native 2×2 footprint, image aspect ratio, four distinct angle-specific transparent PNGs, an original-art fallback set, a finish identifier, and a version number.

The `gmrcartBuildingPainter` read-only catalogue exposes finish swatches and `view(type,angle)` for future workshop tooling. This does not grant new editing permissions or change persisted prefab/scene geometry. Existing landscape placement, rotation, pan/zoom, and depth-ordering remain authoritative. The illustrated sprite is rendered only in pixel-inspired mode and only when the registered footprint matches the prefab.

## Painter's first coat

`cottage-painted-{0,90,180,270}.png` is a richer four-view variant of the approved cottage silhouette. The originals remain available as fallback and regression reference. Each view retains 192×178 RGBA dimensions, the original silhouette, and its own camera-specific details. Painted roof tile accents and warm window glints are deliberately subtle at normal zoom.

## Adding a future building

Provide four independent, consistently registered transparent PNG/WebP views; register a new type and its native footprint in `assets/building-illustrations.js`. Never rotate a single flat picture to fabricate the other perspectives. Use the existing SVG/geometry fallback for any unregistered type. Do not modify the prefab footprint to fit artwork.

## Browser certification

In pixel-inspired mode, inspect the cottage at 0°, 90°, 180°, and 270°; confirm roof fill, walls, window placement, chimney, ground contact and tree occlusion. Switch repeatedly to classic mode, check depth guides (no new cycles), pan and zoom, then reload. Existing non-cottage buildings must remain unchanged.
