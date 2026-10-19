# VI.6D.9A — The MarketRealm Pixel Storybook Art Standard

**Status:** art direction and asset contract established; no sprites or runtime rendering changed in this phase.

The user-approved reference is a warm, softly sculpted miniature bakery. The goal is **Pixel Storybook**: preserve the miniature's rounded, inviting forms and tactile materials, with subtly pixel-defined edges. The 70/30 description is a qualitative art-direction target, **not** a filter strength or mathematical mix.

## Visual identity

- Cream plaster, chunky timber beams, softly rounded terracotta tiles, restrained muted-green awnings, stone footings.
- Friendly oversized roofs and legible doors/windows; shop details such as bread baskets and hanging signs must remain readable at the actual map scale.
- Soft, coherent upper-left daylight baked into artwork; no strong free-floating shadow patches or blurred outlines.
- Crisp silhouettes and selected pixel-step edges; avoid crunchy nearest-neighbour overprocessing, black outlines and photorealistic textures.

## Asset and camera contract

- Transparent PNG per building **per camera angle**: `buildings/{stem}-{0|90|180|270}.png`. No CSS rotation/mirroring as a shortcut.
- Four views share a consistent canvas size and ground anchor. Allow for chimneys/overhang but trim wasteful transparent padding.
- Preserve existing prefab footprint, ground placement, depth/occlusion semantics, scene persistence and Classic mode. Artwork is presentation only.
- Evaluate at 100% canvas zoom, all four angles, with trees and walls in front/behind; verify roof, doors and ground contact.
- New patterns remain Coming Soon until all four views and Pattern Book registration are complete.

## Implementation sequence

1. **VI.6D.9B:** Build and visually approve one new Storybook Cottage four-view set, retaining the existing painted cottage as fallback.
2. **VI.6D.9C:** Repaint the Village House using the approved art proportions and anchors.
3. **VI.6D.9D:** Paint the Bakery and promote it to a selectable Pattern Book entry only when all four views pass inspection.
4. Continue with Blacksmith, Market Stall and other architecture; environmental tiles/trees in later phases.

The machine-readable source of truth for these standards is `assets/art-standards/pixel-storybook-v1.json`. This phase deliberately does **not** introduce an unfinished style toggle or change any current rendering.
