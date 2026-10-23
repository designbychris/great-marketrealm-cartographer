# Phase VI.6D.14 — Pippin Opens the Leatherbound Atlas

Adds a dark forest-green, smoky leather atlas treatment to the illustrated landscape
frame. Fine CSS gradient grain, a soft vignette and restrained brass edging echo a
handcrafted Keeper's desk without adding image downloads or motion.

The visual treatment is CSS-only. It does not touch scene serialization, map
coordinates, SVG rendering, terrain, water, buildings, foliage, camera or input.
The editable construction grid remains unchanged.

CSS token hooks for Midnight Menu, Withered Reach and Frostreem are reserved
for later Almanac-driven theme selection. They are not yet exposed as selectable
themes. The default leather appearance needs no configuration.

Test with: php vendor/bin/phpunit --display-warnings
Visual check: pixel and classic views at 0° and 90°, zoom/pan, responsive widths.
