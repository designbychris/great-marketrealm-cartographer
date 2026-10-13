# VI.6B.7A — Pippin Establishes the Architectural Order

A small, deliberately conservative architectural-order patch.

- Keep the existing separate building base, roof, and detail surfaces.
- Establish **mandatory** local base → roof → details dependencies separately from camera depth comparisons.
- Prevent a reversed camera edge from overriding a mandatory local architectural dependency.
- Expose independent debug counters for ambiguous projected overlaps, ordered comparisons, architectural constraints and cycle fallbacks.
- Preserve the four orientations, saved scene format, construction editor and shortcode routes.

The ambiguous overlap count is **not** a visual quality score; it can remain unchanged while painting order improves. A cycle count above zero indicates that the camera ordering still needs further investigation.

## Verify

`php vendor/bin/phpunit --display-warnings`

Then check each camera direction with and without depth guides. In particular, inspect the walls/roofs and trees around the blacksmith and the stone chamber. This phase does **not** claim to eliminate every visual overlap.
