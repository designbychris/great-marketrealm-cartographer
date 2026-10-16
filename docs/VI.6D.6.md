# VI.6D.6 — Pippin Opens the Architectural Pattern Book

## Scope
Adds a read-only, versioned architectural pattern catalogue to the existing
illustrated-building registration script. It does **not** add a placement UI,
change persisted scene objects, or claim to supply artwork that is not painted.

## Patterns
- MarketRealm Cottage: painted; retains its four approved PNG orientations.
- Village House: planned.
- Village Bakery: planned.
- Blacksmith's Forge: planned; references existing blacksmith prefab concept only.
- Market Stall: planned; references existing market prefab concept only.

`window.gmrcartArchitecturalPatternBook` exposes `get(key)`, `list(category)`,
`categories`, and `artwork(key)`. `artwork` returns null for planned entries.
Only the cottage remains registered in `window.gmrcartBuildingArt`, preserving
the landscape renderer's existing fallback behavior.

## Certification
Run `php vendor/bin/phpunit --display-warnings`. Check the cottage at all
four orientations in pixel-inspired mode, then switch to classic illustration.
All non-cottage buildings should remain unchanged. Pan, zoom and depth guides
should remain functional.

## Later phases
Paint and approve four-view sprites for each new building; separately add
the building palette and scene-placement interactions after the registry is
stable. Terrain elevation and bridges remain separate roadmap work.
