# VI.6B — Illustrated Asset Library

The classic SVG renderer remains the default and is the fallback. In the illustrated view, select **Pixel-inspired art** to use the first bundled transparent PNG sprite (MarketRealm tree). The other terrain and buildings continue to use procedural shapes; this is a pipeline proof, not a complete art pack. The asset manifest in `assets/atlas-assets.js` contains theme slots for future packs. The SVG scene projection and saved draft format remain unchanged. No external asset URLs are accepted and no remote fetching is introduced.

Future work: illustrated buildings and terrain tiles, four-direction asset variants, consistent light/shadow, depth sorting, and sprite atlases for performance.
