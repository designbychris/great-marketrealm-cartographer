# VI.6D.9B — Pippin Paints the Storybook Cottage

## Status: art proof for visual approval (not enabled in production)

Four independent, transparent PNG sprites at 192 × 178, one per approved camera angle:
`cottage-storybook-{0,90,180,270}.png`.

First-pass pixel storybook cues: warm plaster, timber framing, individually marked terracotta tiles, stone/grass ground contact, warm windows, chimney, crisp sprite boundaries.

## Approval gate

Compare all four sprites against the supplied miniature reference. The artwork is a **candidate**, not an approved final style. Check warmth, rounded/simplified shapes, roof completeness, legibility at normal map zoom, and consistency between angles. Once approved, wire it as a separate selectable illustrated appearance with fallback to `cottage-painted-*`; do not silently overwrite the original.

No production JavaScript, existing assets, scene geometry, prefab dimensions, or camera handling is changed in this phase.
