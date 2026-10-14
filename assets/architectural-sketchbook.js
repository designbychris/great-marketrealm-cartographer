/** VI.6C.1: versioned, presentation-only architectural sketches.
 * These never change Scene::PREFABS, object geometry or persisted construction data.
 */
(()=>{'use strict';
 const sketches=Object.freeze({
  cottage:Object.freeze({label:'Cottage',roof:'thatch',door:'oak',windows:2,chimney:true}),
  tavern:Object.freeze({label:'Tavern',roof:'terracotta',door:'oak',windows:2,chimney:true}),
  blacksmith:Object.freeze({label:'Blacksmith',roof:'slate',door:'iron',windows:1,chimney:true}),
  tower:Object.freeze({label:'Watchtower',roof:'slate',door:'iron',windows:1,chimney:false}),
  market:Object.freeze({label:'Market stalls',roof:'canvas',door:'none',windows:0,chimney:false}),
  chamber:Object.freeze({label:'Dungeon chamber',roof:'stone',door:'none',windows:0,chimney:false}),
  corridor:Object.freeze({label:'Stone corridor',roof:'stone',door:'none',windows:0,chimney:false}),
  crypt:Object.freeze({label:'Crypt',roof:'stone',door:'none',windows:0,chimney:false})
 });
 const roofs=Object.freeze({
  thatch:Object.freeze({light:'#c6a66e',dark:'#96784c'}),
  terracotta:Object.freeze({light:'#b77859',dark:'#89533f'}),
  slate:Object.freeze({light:'#777e8c',dark:'#515967'}),
  canvas:Object.freeze({light:'#d6b87c',dark:'#9c7751'}),
  stone:Object.freeze({light:'#b2ada4',dark:'#85817e'})
 });
 window.gmrcartArchitecturalSketchbook=Object.freeze({version:1,sketches,roofs,resolve(type,appearance){const chosen=String(appearance||'default');return sketches[chosen==='default'?String(type):chosen]||sketches[String(type)]||null;}});
})();
