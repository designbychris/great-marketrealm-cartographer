/** VI.6D.5: illustrated architecture catalogue. Presentation only: scene geometry is authoritative. */
(()=>{'use strict';
 const views=(stem)=>[0,90,180,270].map(a=>`buildings/${stem}-${a}.png`);
 const finishes=Object.freeze({
  terracotta:Object.freeze({label:'Warm terracotta',roof:'#c87952',trim:'#67452e'}),
  slate:Object.freeze({label:'Weathered slate',roof:'#667486',trim:'#494c56'}),
  thatch:Object.freeze({label:'Golden thatch',roof:'#c9a56c',trim:'#735939'})
 });
 const cottage=Object.freeze({
  label:'MarketRealm Cottage',footprint:Object.freeze([2,2]),aspect:178/192,
  // The painter's four registered views are not rotated or mirrored in CSS.
  views:Object.freeze(views('cottage-painted')),
  fallbackViews:Object.freeze(views('cottage')),
  finish:'terracotta',tags:Object.freeze(['village','home','timber']),artVersion:2
 });
 const buildings=Object.freeze({cottage});
 window.gmrcartBuildingArt=buildings;
 window.gmrcartBuildingPainter=Object.freeze({version:1,finishes,buildings,
  get(type){return Object.prototype.hasOwnProperty.call(buildings,type)?buildings[type]:null;},
  view(type,angle){const entry=this.get(type);if(!entry||!Number.isInteger(angle)||angle%90!==0)return null;return entry.views[((angle/90)%4+4)%4];}
 });
})();
