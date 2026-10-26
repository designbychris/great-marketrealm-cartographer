Phase VI.6D.13A.4 — Pippin Raises the Tavern’s Roof

Overlay this ZIP onto the current installed Cartographer (including VI.6D.13A.3 and its corrective test fix).

Four new 480x435 transparent, separately illustrated Tavern camera views replace the previous low-profile tavern art. The 3x2 gameplay footprint, artwork paths, registry and save data are unchanged. Includes the current VI.6D.13A.3 landscape painter and corrected Village House regression test so the overlay can be applied to the last supplied repo if needed.

Visual inspection: confirm 0/90/180/270 orientations, no clipping, palette preview, roof height, ground anchor, Classic illustration, and pan/zoom. Concept artwork can vary in minor ornament placement between angles; review before final artistic sign-off.

Run: php vendor/bin/phpunit --display-warnings
