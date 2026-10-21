/** VI.6D.10A: Storybook Foliage Standard. Presentation-only; no scene mutations. */
(()=>{'use strict';
 const varieties=Object.freeze({
  oak:Object.freeze({label:'MarketRealm Oak',collection:'marketrealm',silhouette:'broadleaf',size:Object.freeze([32,57]),views:Object.freeze([0,90,180,270].map(a=>'foliage/oak-'+a+'.png'))}),
  pine:Object.freeze({label:'Whispering Pine',collection:'marketrealm',silhouette:'evergreen',size:Object.freeze([32,57]),views:Object.freeze([0,90,180,270].map(a=>'foliage/pine-'+a+'.png'))}),
  orchard:Object.freeze({label:'Fructan Orchard Tree',collection:'marketrealm',silhouette:'fruit',size:Object.freeze([32,57]),views:Object.freeze([0,90,180,270].map(a=>'foliage/orchard-'+a+'.png'))}),
  goldenleaf:Object.freeze({label:'Goldenleaf',collection:'marketrealm',silhouette:'broadleaf',size:Object.freeze([32,57]),views:Object.freeze([0,90,180,270].map(a=>'foliage/oak-'+a+'.png'))}),
  willow:Object.freeze({label:'Riverside Willow',collection:'marketrealm',silhouette:'weeping',size:Object.freeze([32,57]),views:Object.freeze([0,90,180,270].map(a=>'foliage/willow-'+a+'.png'))})
 });
 const collections=Object.freeze({marketrealm:'Classic MarketRealm',mushroomWoods:'Mushroom Woods',frostreem:'Frostreem',witheredReach:'The Withered Reach',midnightMenu:'Midnight Menu'});
 const safePath=/^foliage\/[a-z0-9_/-]+\.(png|webp)$/;
 const valid=(entry)=>!!entry&&Array.isArray(entry.views)&&entry.views.length===4&&entry.views.every(v=>typeof v==='string'&&safePath.test(v))&&Array.isArray(entry.size)&&entry.size.length===2&&entry.size.every(n=>Number.isFinite(n)&&n>0);
 // Stable world-cell selection; independent of camera orientation, zoom and rerenders.
 const pick=(x,y)=>{const keys=Object.keys(varieties);const hash=(((x+1)*73856093)^((y+1)*19349663))>>>0;return keys[hash%keys.length];};
 const library=Object.freeze({version:1,collections,varieties,valid,pick,
  get(key){return Object.prototype.hasOwnProperty.call(varieties,key)?varieties[key]:null;},
  view(key,angle){const item=this.get(key);if(!valid(item)||!Number.isInteger(angle)||angle%90!==0)return null;return item.views[((angle/90)%4+4)%4];}
 });
 window.gmrcartFoliageLibrary=library;
})();
