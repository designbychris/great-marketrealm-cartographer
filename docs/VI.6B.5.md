# VI.6B.5 — Pippin Judges the Overlap

Camera-relative, presentation-only occlusion dependency graph. Ground footprints are transformed by the active camera. Only projected pairs are examined; unambiguous behind/in-front relations create ordering dependencies. Ambiguous/intersecting geometry retains stable painter-order fallback, with a diagnostic pair count in the optional depth overlay. Scene storage and editing are unchanged.

## Limitations

This is an intermediate conservative visibility judgement, not per-polygon clipping or perfect transparency. Intersecting geometry and tall structures can still require surface splitting in a later phase. No diagnostic values are persisted.
