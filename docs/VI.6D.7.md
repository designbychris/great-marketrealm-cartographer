# VI.6D.7 — Pippin Paints the Village House

Adds a second hand-painted, four-angle transparent PNG building to the Pattern Book.
The Village House is a two-storey timber-framed home with terracotta roof, stone
foundation, chimney, warm windows, and a distinct silhouette.

## Using it
On the construction canvas, select or place a **Cottage** (2×2 prefab), choose
**Painted Village House** in Architectural appearance, then save. Switch to
**Pixel-inspired art** to see the registered four-angle illustrations. The
underlying prefab stays `cottage` and retains the existing 2×2 footprint.
The Classic illustration retains its existing geometry-based presentation.

## Invariants
- The server explicitly validates the new appearance; no new persisted prefab.
- Only cottage objects with `appearance=villageHouse` resolve this art key.
- Four directional assets are selected by camera and object orientation.
- No changes to occlusion order, pan/zoom, terrain, other prefab art or geometry.
- Future work can introduce a dedicated Village House prefab and palette safely.

## Certification
Run `php vendor/bin/phpunit --display-warnings`. Check the Village House
at 0°, 90°, 180°, 270° in Pixel-inspired art; check Classic illustration,
selection, save/reload, pan/zoom, depth guides and the original cottage.
