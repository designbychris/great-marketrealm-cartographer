# VI.6B.6 — Pippin Separates the Silhouettes

The existing illustrated renderer now emits separate base, roof, and architectural-detail paint entries for each prefab. The scene model, saved draft format, permissions, and existing art assets are unchanged.

Surface order within each prefab is preserved by explicit dependencies. Other trees and walls may interleave between surfaces according to the existing camera-aware occlusion judgement. The debug overlay labels the roof surface instead of repeating each building three times.

This is an incremental silhouette split, **not** full polygon-level depth clipping. Some crossings may remain ambiguous, particularly large roofs and dense tree clusters. Review all four orientations before promoting further changes.
