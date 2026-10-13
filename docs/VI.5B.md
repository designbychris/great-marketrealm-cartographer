# VI.5B — Pippin Opens the Construction Library

Three versioned scene prefabs: cottage (2×2), tavern (3×2), dungeon chamber (3×3). Prefabs are stored as separate scene objects over terrain, with placement, selection, rotation, movement and removal. Overlap, out-of-bounds and duplicate identifiers are rejected server-side. Existing version 1 scenes with no `objects` remain valid. The illustrated SVG view renders initial structural representations; artwork, roofs, floors and full drag/drop follow later.
