# VI.6B.3 — Pippin Learns What Stands Before What

The illustrated renderer now paints flat terrain first, then uses one camera-aware depth queue for raised walls, trees (including pixel-inspired sprites), and prefabricated structures. The depth order is recomputed when the camera changes, with the nearest projected corner of a rotated multi-cell prefab used as its sorting anchor. The scene document, object positions, prefab definitions, collision checks, and save endpoints are unchanged.

This is an initial painter's-order solution. A tall or very large object is still drawn as a single unit, so intricate intersecting structures may eventually require per-face or per-layer depth sorting. Verify all four camera angles with overlapping buildings, walls, and trees before proceeding to more detailed sprite art.
