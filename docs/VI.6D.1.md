# VI.6D.1 — Pippin Opens the Illustrated Architect’s Atelier

First-stage, opt-in illustrated building adapter. The existing SVG construction remains the default, and the same world-space prefab footprint, placement, orientation, occlusion ordering and saved scene document are retained. No artwork is enabled without a complete approved four-view set.

Register a mapping before `assets/landscape.js` executes, e.g. `window.gmrcartBuildingArt = { cottage: { footprint: [2,2], aspect: 1.1, views: ['buildings/cottage-0.png','buildings/cottage-90.png','buildings/cottage-180.png','buildings/cottage-270.png'] } };` with the **actual** prefab key and dimensions from the scene catalogue. Place transparent artwork under `assets/sprites/buildings/` (same trusted asset origin). The `aspect` property is sprite height / projected bounding width. All four sprites should have consistent baselines and identical framing. Artwork is visible in Pixel-inspired mode only. If no complete valid entry exists, SVG architecture continues to render.

This is an integration foundation, **not** a delivered illustrated cottage asset pack. Art direction, individual illustrations, roof intersections and sprite visual quality remain for subsequent phases. Asset registry is not persisted in a campaign document.
