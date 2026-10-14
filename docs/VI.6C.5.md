# VI.6C.5 — Pippin Gives Every Building Its Own Front Door

Building doors now have a stable world-space elevation derived from the saved prefab rotation. An unrotated prefab has a south-facing entrance; quarter-turns rotate the entrance with the prefab. Camera orientation only decides whether that wall is visible. When a different wall is visible, its windows remain but the door is not transplanted there.

This is a deliberately small, backwards-compatible rendering change. No new saved fields, routes, object footprints, roof geometry, or camera controls are introduced. Existing structures use their current rotation. Future work may add an independently selectable entrance side, and render two simultaneously visible facades instead of the current single camera-facing elevation.

## Acceptance

1. Verify the existing scene renders at all four camera angles, with doors visible only on the entrance-facing view.
2. Rotate a building in the Construction Studio, save and reload; its entrance should follow the building's persisted rotation, not the camera.
3. Verify the selected architectural appearance survives save/reload and the roof/zoom/depth controls behave as before.
4. Run `php vendor/bin/phpunit --display-warnings`.
