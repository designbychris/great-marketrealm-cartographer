# VI.6C.8 — Pippin Raises the Timber Frames

Presentation-only refinement to the two-elevation procedural renderer.

- Thatched and terracotta appearances use stronger corner posts, upper/lower beams and short corner braces.
- Slate appearances use restrained stone courses and staggered masonry joints.
- All wall marks are projected through `facadeForSide`, for both visible elevations.
- Beams and masonry avoid the window band (z 10–17); low wall posts avoid the centre entrance.
- No changes to scene persistence, camera, entrance directions, prefab footprints or depth ordering.

## Certification

Starting point: 54 tests, 280 assertions. Two new regression tests added. Run `php vendor/bin/phpunit --display-warnings` on the deployment host, then inspect at 150% in all four views.

## Future illustrated assets

Retain structured building geometry and layering; a later asset adapter can display four-view transparent PNG/WebP building artwork inside the SVG scene without sacrificing editing or occlusion logic.
