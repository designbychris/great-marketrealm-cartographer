# VI.6B.7 — Pippin Finds the True Front

The illustrated canvas now uses independent projected height envelopes for each prefab's base, roof and details. Raised trees and walls have their own height ranges. Occlusion dependencies are deduplicated, and the stable fallback is derived from camera-oriented footprint geometry, not saved-object insertion order. Existing four camera directions, depth guides, scene data and saving are unchanged.

This is an incremental painter's-order improvement, not full polygon splitting or continuous 3D orbit. Check all four directions with and without depth guides, especially tree/roof intersections and stone corridors. The debug count can change when the bounds become more accurate; it is not itself a quality score.

Future: drag-to-rotate with snapping/transition between four supported directions. True arbitrary-angle orbit needs a 3D geometry renderer.
