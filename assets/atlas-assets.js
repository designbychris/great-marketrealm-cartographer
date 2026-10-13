/** VI.6B: presentation-only, extensible asset manifest. Paths are relative to plugin sprites/. */
(()=>{'use strict';
 const packs=Object.freeze({
  marketrealm:Object.freeze({tree:'marketrealm/tree.png'}),
  withered:Object.freeze({}),takeaway:Object.freeze({})
 });
 window.gmrcartAtlasAssets=Object.freeze({packs,resolve(pack,key){const name=String(pack||'');const id=String(key||'');if(!/^[a-z0-9_-]+$/.test(name)||!/^[a-z0-9_-]+$/.test(id))return null;return packs[name]?.[id]||packs.marketrealm[id]||null;}});
})();
