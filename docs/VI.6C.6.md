# Phase VI.6C.6 — Pippin Sees Both Sides of the Story

Each isometric camera quadrant now presents two distinct world-space wall elevations, with independently anchored decorative detailing. The entrance continues to be derived from the prefab rotation, and only appears when its physical wall is visible. Window arrangements distinguish entrance, rear and side walls without modifying scene persistence.

The two-elevation renderer retains the existing catalogue, roof planes, depth graph, viewport controls and saved scene schema. Existing one-facade helper remains for compatibility. This is an illustration-layer change, not a new 3D mesh or multi-storey system.

## Browser checks

Rotate a saved building through all four quadrants: the door must appear only in the two quadrants that expose its wall, while windows and facade ink appear on both visible walls. Repeat after saving/reloading a rotated prefab, changing its catalogue appearance and zooming to 150%. Check the tree and roof occlusion order.
