# VI.5D — Pippin Catalogues His Kingdom

Adds a searchable, category-filtered, data-driven construction catalogue with eight built-in prefabs. The metadata is declared in `PrefabCatalog`; server-side footprint validation remains an explicit whitelist in `Scene::PREFABS`. Future expansion packs require a separately designed registration/validation contract and should not bypass that whitelist. Existing saved scene version 1 and existing prefab IDs remain compatible. Illustrated drag-and-drop and connector snapping are reserved for later phases.
