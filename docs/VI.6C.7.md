# Phase VI.6C.7 — Pippin Opens the Shutters

Presentation-only architectural detail enhancement. Window openings on both visible world-space elevations now include colour-coordinated shutters, frame edges, glazing, mullions, sill, and a small warm glint for non-slate styles. Slate windows use cool glass and muted shutters. Door placement remains anchored to the building's saved rotation. No saved scene schema, roof geometry, depth ordering, or prefab footprint changes.

## Verification

Run `php vendor/bin/phpunit --display-warnings`. Inspect all four camera orientations at 150% zoom with depth guides hidden, including the thatched tavern and slate blacksmith. Shutters should flank windows, not cover the glass; front doors must remain on their physical elevations.
