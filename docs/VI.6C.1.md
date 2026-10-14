# VI.6C.1 — Pippin Opens His Architectural Sketchbook

Introduce a versioned, presentation-only architectural sketchbook used by the illustrated landscape.

- Built-in sketches for cottage, tavern, blacksmith, watchtower, market stalls and stone interiors.
- Roof finishes: thatch, terracotta, slate, canvas and stone.
- Doors, window counts and chimneys derive from prefab type; no change to prefab footprint, scene storage or object placement.
- Renderer falls back to existing palette when a sketch is not defined.
- This phase establishes a registry, **not** per-object customization or an editable architectural palette. Those require explicit scene schema and controls in a later phase.

Verify the four camera directions, depth guides, zoom, rotation and saving/loading an existing construction.
