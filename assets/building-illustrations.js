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
 const villageHouse=Object.freeze({
  label:'MarketRealm Village House',footprint:Object.freeze([2,2]),aspect:178/192,
  views:Object.freeze(views('village-house-painted')),finish:'terracotta',
  tags:Object.freeze(['village','home','timber','two-storey']),artVersion:2
 });
 // VI.6D.6: Pattern Book entries describe future architecture without pretending
 // unpainted buildings have approved four-angle sprites.
 const patterns=Object.freeze({
  cottage:Object.freeze({label:'MarketRealm Cottage',status:'painted',prefab:'cottage',
   category:'homes',footprint:Object.freeze([2,2]),finish:'terracotta',
   tags:Object.freeze(['village','home','timber']),artKey:'cottage'}),
  villageHouse:Object.freeze({label:'MarketRealm Village House',status:'painted',prefab:'cottage',
   category:'homes',footprint:Object.freeze([2,2]),finish:'terracotta',
   tags:Object.freeze(['village','home','timber','two-storey']),artKey:'villageHouse'}),
  bakery:Object.freeze({label:'Village Bakery',status:'planned',prefab:null,
   category:'commerce',footprint:null,finish:'terracotta',
   tags:Object.freeze(['village','bakery','shop']),artKey:null}),
  blacksmith:Object.freeze({label:"Blacksmith's Forge",status:'planned',prefab:'blacksmith',
   category:'craft',footprint:null,finish:'slate',
   tags:Object.freeze(['village','forge','craft']),artKey:null}),
  marketStall:Object.freeze({label:'Market Stall',status:'planned',prefab:'market',
   category:'commerce',footprint:null,finish:'thatch',
   tags:Object.freeze(['market','trade']),artKey:null})
 });
 const categories=Object.freeze({
  homes:'Homes & cottages',commerce:'Shops & markets',craft:'Workshops & trades'
 });
 const patternBook=Object.freeze({
  version:1,patterns,categories,
  list(category){return Object.keys(patterns).filter(key=>!category||patterns[key].category===category)
   .map(key=>Object.freeze({key,...patterns[key]}));},
  get(key){return Object.prototype.hasOwnProperty.call(patterns,key)?patterns[key]:null;},
  // Only fully registered, painted assets can be rendered; planned entries
  // must never silently replace existing SVG/prefab illustrations.
  artwork(key){const p=this.get(key);return p&&p.status==='painted'&&p.artKey?
   (Object.prototype.hasOwnProperty.call(buildings,p.artKey)?buildings[p.artKey]:null):null;}
 });
 const buildings=Object.freeze({cottage,villageHouse});
 window.gmrcartBuildingArt=buildings;
 window.gmrcartArchitecturalPatternBook=patternBook;
 window.gmrcartBuildingPainter=Object.freeze({version:1,finishes,buildings,
  get(type){return Object.prototype.hasOwnProperty.call(buildings,type)?buildings[type]:null;},
  view(type,angle){const entry=this.get(type);if(!entry||!Number.isInteger(angle)||angle%90!==0)return null;return entry.views[((angle/90)%4+4)%4];}
 });
})();
