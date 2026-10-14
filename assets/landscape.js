(()=>{'use strict';
const root=document.querySelector('.gmrcart-canvas-panel');if(!root||!window.gmrcartLandscape)return;
const source=window.gmrcartLandscape,grid=root.querySelector('.gmrcart-grid'),preview=root.querySelector('.gmrcart-landscape'),frame=root.querySelector('.gmrcart-landscape-frame'),toggle=root.querySelector('.gmrcart-view-toggle');
const ns='http://www.w3.org/2000/svg',W=12,H=12,tw=48,th=24,origin=330;
let active=false,queued=false,orientation=0,illustrationStyle='classic',depthDebug=false;
// VI.6B.9: view-only camera state; never written to the construction document.
let zoom=1,panX=0,panY=0;const MIN_ZOOM=.5,MAX_ZOOM=2,BASE_WIDTH=660,BASE_HEIGHT=385;
const styleControls=root.querySelector('.gmrcart-atlas-style');
const illustrationToolbar=root.querySelector('.gmrcart-illustration-toolbar');
const assetLibrary=window.gmrcartAtlasAssets;
const sketchbook=window.gmrcartArchitecturalSketchbook;
function sketch(type,appearance){return sketchbook?.resolve(type,appearance)||null;}
function roofFinish(type,appearance){const name=sketch(type,appearance)?.roof;return sketchbook?.roofs?.[name]||null;}
const assetBase=window.gmrcartScene?.atlasAssetBase||'';
function sprite(name,x,y,width,height){if(illustrationStyle!=='pixel'||!assetLibrary||!assetBase)return false;const path=assetLibrary.resolve('marketrealm',name);if(!path)return false;node('image',{href:assetBase.replace(/\/$/,'')+'/'+path,x,y,width,height,preserveAspectRatio:'xMidYMax meet',style:'image-rendering:pixelated'});return true;}
styleControls?.querySelectorAll('[data-gmrcart-style]').forEach(button=>button.addEventListener('click',()=>{illustrationStyle=button.dataset.gmrcartStyle;styleControls.querySelectorAll('button').forEach(b=>b.setAttribute('aria-pressed',String(b===button)));schedule();}));
const debugButton=root.querySelector('.gmrcart-depth-toggle');
debugButton?.addEventListener('click',()=>{depthDebug=!depthDebug;debugButton.setAttribute('aria-pressed',String(depthDebug));debugButton.textContent=depthDebug?'Hide depth guides':'Show depth guides';schedule();});
const angleLabel=root.querySelector('.gmrcart-camera-angle');
const cameraButtons=root.querySelectorAll('[data-gmrcart-camera]');
const cameraNames=['South-east','South-west','North-west','North-east'];
// Zoom controls are generated inside the existing illustration toolbar; no theme markup changes.
const zoomControls=document.createElement('div');zoomControls.className='gmrcart-zoom-controls';
zoomControls.setAttribute('role','group');zoomControls.setAttribute('aria-label','Illustrated landscape zoom');
zoomControls.innerHTML='<button type="button" data-gmrcart-zoom="out" aria-label="Zoom out">−</button><output class="gmrcart-zoom-label" aria-live="polite">100%</output><button type="button" data-gmrcart-zoom="in" aria-label="Zoom in">+</button><button type="button" data-gmrcart-zoom="reset">Reset view</button>';
illustrationToolbar?.appendChild(zoomControls);
const zoomLabel=zoomControls.querySelector('.gmrcart-zoom-label');
function clamp(value,min,max){return Math.max(min,Math.min(max,value));}
function applyViewport(){const width=BASE_WIDTH/zoom,height=BASE_HEIGHT/zoom;
 panX=clamp(panX,-(BASE_WIDTH-width)/2,(BASE_WIDTH-width)/2);
 panY=clamp(panY,-(BASE_HEIGHT-height)/2,(BASE_HEIGHT-height)/2);
 preview.setAttribute('viewBox',`${(BASE_WIDTH-width)/2+panX} ${(BASE_HEIGHT-height)/2+panY} ${width} ${height}`);
 if(zoomLabel)zoomLabel.textContent=`${Math.round(zoom*100)}%`;
}
function setZoom(next,anchorX=.5,anchorY=.5){
 const oldZoom=zoom;zoom=clamp(Math.round(next*100)/100,MIN_ZOOM,MAX_ZOOM);
 // Keep the world point beneath the cursor in approximately the same screen position.
 panX+=(anchorX-.5)*BASE_WIDTH*(1/oldZoom-1/zoom);
 panY+=(anchorY-.5)*BASE_HEIGHT*(1/oldZoom-1/zoom);
 applyViewport();
}
zoomControls.addEventListener('click',event=>{const action=event.target.closest('button')?.dataset.gmrcartZoom;
 if(action==='in')setZoom(zoom+.25);else if(action==='out')setZoom(zoom-.25);
 else if(action==='reset'){zoom=1;panX=0;panY=0;applyViewport();}
});

const atlas=window.gmrcartAtlasPalettes?.marketrealm||{floor:'#9d9169',empty:'#544a40',wall:'#9a8581',water:'#347d95',tree:'#6e8751',roof:'#bd7959',roofShade:'#955e48',stone:'#b2ada4'};
function orient(x,y){switch(orientation){case 1:return [W-y,x];case 2:return [W-x,H-y];case 3:return [y,H-x];default:return [x,y];}}
function node(tag,attrs,parent){const n=document.createElementNS(ns,tag);Object.entries(attrs||{}).forEach(([k,v])=>n.setAttribute(k,String(v)));(parent||preview).appendChild(n);return n;}
function point(x,y,z=0){const [u,v]=orient(x,y);return [origin+(u-v)*tw/2,30+(u+v)*th/2-z];}
function polygon(points,fill,stroke='#392c29',parent=preview){return node('polygon',{points:points.map(p=>p.join(',')).join(' '),fill,stroke,'stroke-width':.8,'stroke-linejoin':'round'},parent);}
// VI.6A: deterministic illustration details, derived only from the scene document.
function shade(hex,delta){const value=hex.replace('#','');if(!/^[0-9a-f]{6}$/i.test(value))return hex;return '#'+[0,2,4].map(i=>Math.max(0,Math.min(255,parseInt(value.slice(i,i+2),16)+delta)).toString(16).padStart(2,'0')).join('');}
function line(a,b,color,width=1,opacity=1){return node('path',{d:`M${a[0]} ${a[1]}L${b[0]} ${b[1]}`,stroke:color,'stroke-width':width,opacity,fill:'none','stroke-linecap':'round'});}
function diamond(x,y,fill,stroke='#392c29',z=0){return polygon([point(x,y,z),point(x+1,y,z),point(x+1,y+1,z),point(x,y+1,z)],fill,stroke);}
function terrainDetail(x,y,mat){const c=point(x+.5,y+.5);const seed=(x*73+y*149)%11;
 if(mat==='floor'){if(seed<4){node('ellipse',{cx:c[0]+seed-5,cy:c[1]+2,rx:3.2,ry:1.3,fill:'#617c49',opacity:.8});line([c[0]+seed-5,c[1]+2],[c[0]+seed-4,c[1]-2],'#789755',.8);}if(seed===8){node('circle',{cx:c[0]-3,cy:c[1],r:1.1,fill:'#e3c98b'});node('circle',{cx:c[0]+1,cy:c[1]-1,r:1,fill:'#e3c98b'});}}
 if(mat==='water'){node('path',{d:`M ${c[0]-9} ${c[1]+2} q 5 -3 10 0 t 9 0`,stroke:'#b5e4e3','stroke-width':1.3,fill:'none',opacity:.7});node('path',{d:`M ${c[0]-4} ${c[1]-3} q 4 -2 8 0`,stroke:'#174e73','stroke-width':1.1,fill:'none',opacity:.55});}
}
// VI.6C.2: Architectural ink is generated from world-space surfaces, not saved objects.
// Projecting every stroke through point() keeps details attached at all four camera angles.
function architecturalRoofInk(o,w,h,top,stone){
 const design=sketch(o.type,o.appearance);if(!design||stone)return;
 const roof=design.roof;
 const z=top+18;
 // Both roof slopes use the same interpolation as the existing roof polygons.
 const slope=(side,t,r)=>{
  const x=o.x+w*(side===0?t*.5:.5+t*.5);
  return point(x,o.y+h*r,top+18*(side===0?t:1-t));
 };
 for(let side=0;side<2;side++){
  if(roof==='thatch'||roof==='terracotta'||roof==='slate'){
   const count=roof==='thatch'?5:4;
   for(let k=1;k<count;k++){
    const t=k/count;
    const start=slope(side,t,.08),end=slope(side,t,.92);
    line(start,end,roof==='thatch'?'#e7cb91':roof==='terracotta'?'#d99b73':'#a4afbd',roof==='thatch'?1.15:.75,.7);
   }
   for(let row=1;row<4;row++){
    const r=row/4;
    for(let col=0;col<3;col++){
     const t=(col+.35+(row%2)*.25)/3;
     const a=slope(side,t,r),b=slope(side,Math.min(.96,t+.12),r);
     line(a,b,roof==='thatch'?'#927347':roof==='terracotta'?'#774b3c':'#414b5b',.8,.6);
    }
   }
  }else if(roof==='canvas'){
   for(let k=1;k<5;k++)line(slope(side,k/5,0),slope(side,k/5,1),'#f1d7a0',1,.75);
  }
 }
 // Ridge cap follows the roof's world-space ridge, never the screen's horizontal axis.
 line(point(o.x+w/2,o.y,z),point(o.x+w/2,o.y+h,z),roof==='slate'?'#414b59':'#80563e',1.5,.8);
}
function architecturalWallInk(o,w,h,top){
 const design=sketch(o.type,o.appearance);
 if(!design||design.roof==='stone'||design.roof==='canvas')return;
 const face=visibleFacade(o,w,h);
 if(design.roof==='thatch'||design.roof==='terracotta'){
  for(const t of [.025,.975])line(face(t,1),face(t,20),'#63452f',2.2,.85);
  line(face(.025,7),face(.975,7),'#6c4b34',1.4,.8);
  if(design.roof==='terracotta')for(const t of [.25,.5,.75])line(face(t,9),face(t,19),'#7b543b',1.1,.7);
 }else if(design.roof==='slate'){
  for(const z of [6,12,18])line(face(.025,z),face(.975,z),'#6f6a68',.9,.6);
  for(let row=0;row<2;row++)for(let k=1;k<4;k++){
   const t=(k+(row%2)*.35)/4;
   line(face(t,6+row*6),face(t,12+row*6),'#6f6a68',.8,.55);
  }
 }
}
// VI.6C.4: A facade is a world-space vertical plane. Never place openings
// by shifting a projected screen point: that lets them float over the roof.
// VI.6C.5: Entrances are anchored to the prefab's own rotation, never
// reassigned when the camera turns. Existing prefabs face south at 0 degrees.
const cardinal=['south','west','north','east'];
function entranceSide(o){
 const degrees=Number(o.rotation)||0;
 return cardinal[((Math.round(degrees/90)%4)+4)%4];
}
function visibleFacadeSide(){return ['south','east','north','west'][orientation];}
function visibleFacade(o,w,h){
 const side=visibleFacadeSide();
 return (t,z)=>{
  if(side==='south')return point(o.x+w*t,o.y+h,z);
  if(side==='north')return point(o.x+w*(1-t),o.y,z);
  if(side==='east')return point(o.x+w,o.y+h*(1-t),z);
  return point(o.x,o.y+h*t,z);
 };
}
function facadeOpening(face,center,width,bottom,height,fill){
 const l=center-width/2,r=center+width/2;
 return polygon([face(l,bottom),face(r,bottom),face(r,bottom+height),face(l,bottom+height)],fill,'#4a362d');
}
function buildingDetails(o,w,h,top){
 const design=sketch(o.type,o.appearance);
 if(!design)return;
 const isMarket=o.type==='market';
 const face=visibleFacade(o,w,h);
 // All elevations are measured upward from the ground, below the wall eaves.
 if(!isMarket&&design.door!=='none'&&visibleFacadeSide()===entranceSide(o)){
  facadeOpening(face,.5,.17,0,14,design.door==='iron'?'#52555e':'#51382c');
  line(face(.415,14),face(.585,14),'#aa8256',1.2);
  const knob=face(.555,6);node('circle',{cx:knob[0],cy:knob[1],r:1,fill:'#f5ce78'});
 }
 const windowCenters=design.windows===0?[]:design.windows===1?[.25]:[.2,.8];
 for(const center of windowCenters){
  const half=.065;
  facadeOpening(face,center,half*2,10,7,'#f6c875');
  line(face(center,10),face(center,17),'#65483a',1);
  line(face(center-half,13.5),face(center+half,13.5),'#65483a',.9);
  line(face(center-half-.01,9.5),face(center+half+.01,9.5),'#b18a60',1.5);
 }
 // Roof ridge runs along the Y axis. The chimney rises from an actual
 // roof-slope point, and is projected as a world-space vertical prism.
 if(design.chimney){
  const x=o.x+w*.62,y=o.y+h*.38;
  const roofZ=top+18*(1-Math.abs((x-o.x)/w-.5)*2);
  const foot=point(x,y,roofZ),high=point(x,y,roofZ+15);
  const left=point(x-.055,y,roofZ),right=point(x+.055,y,roofZ);
  const upperLeft=point(x-.055,y,roofZ+15),upperRight=point(x+.055,y,roofZ+15);
  polygon([left,right,upperRight,upperLeft],'#89736c','#453a3a');
  line(upperLeft,upperRight,'#39353c',2);
  if(o.type==='blacksmith'){
   line(point(x-.055,y,roofZ+9),point(x+.055,y,roofZ+9),'#c0a69a',1.2);
   node('path',{d:`M${high[0]} ${high[1]-2}q-4 -5 1 -9`,stroke:'#b7b4b0','stroke-width':1.3,fill:'none',opacity:.65});
  }
 }
}

function draw(){if(!active)return;preview.replaceChildren();applyViewport();preview.setAttribute('role','img');preview.setAttribute('aria-label',`Isometric preview of the current construction scene, ${cameraNames[orientation]} view`);
const tiles=Array.from(grid.querySelectorAll('.gmrcart-cell'));
const order=[];for(let y=0;y<H;y++)for(let x=0;x<W;x++){const [u,v]=orient(x+.5,y+.5);order.push({x,y,depth:u+v});}order.sort((a,b)=>a.depth-b.depth);for(const {x,y} of order){const mat=tiles[y*W+x]?.dataset.material||'empty';const a=point(x,y),b=point(x+1,y),c=point(x+1,y+1),d=point(x,y+1),center=point(x+.5,y+.5);
const colors=atlas;polygon([a,b,c,d],mat==='floor'?shade(colors.floor,(x*17+y*11)%19-9):colors[mat]||colors.empty);terrainDetail(x,y,mat);
if(mat==='water'){const adjacent=(dx,dy)=>{const xx=x+dx,yy=y+dy;return xx>=0&&xx<W&&yy>=0&&yy<H&&tiles[yy*W+xx]?.dataset.material==='water';};if(!adjacent(1,0))node('path',{d:`M ${b.join(' ')} L ${c.join(' ')}`,stroke:'#83c2cb','stroke-width':2,fill:'none'});node('path',{d:`M ${center[0]-8} ${center[1]+1} q 8 -5 16 -1`,stroke:'#9fd0d8','stroke-width':1.6,fill:'none',opacity:.7});}

}
// VI.6B.3: One camera-depth queue for every raised item. Terrain stays underneath.
// The nearest footprint corner determines when a multi-cell structure is painted.
// This is a painter's-order approximation; it does not mutate saved scene objects.
const raised=[];
function depth(x,y){const [u,v]=orient(x,y);return u+v;}
for(let y=0;y<H;y++)for(let x=0;x<W;x++){
 const mat=tiles[y*W+x]?.dataset.material||'empty';
 if(mat==='wall'||mat==='tree')raised.push({kind:mat,x,y,depth:depth(x+.5,y+.5),tie:0});
}
const objects=window.gmrcartObjects?.()||[];
objects.forEach((o,index)=>{
 let [w,h]=(window.gmrcartScene?.prefabs?.[o.type]?.size)||[1,1];
 if(o.rotation%180)[w,h]=[h,w];
 const corners=[[o.x,o.y],[o.x+w,o.y],[o.x+w,o.y+h],[o.x,o.y+h]];
 const front=Math.max(...corners.map(([x,y])=>depth(x,y)));
 // VI.6B.6: Break illustrated structures into paintable silhouette surfaces.
 // Each surface retains the original prefab footprint and immutable scene object.
 // Local order is base -> roof -> details, while unrelated raised items can
 // interleave when their camera depth demands it.
 const base={kind:'prefab-surface',surface:'base',object:o,depth:front-.6,tie:1,index};
 const roof={...base,surface:'roof',depth:front-.3,tie:2};
 const details={...base,surface:'details',depth:front,tie:3};
 raised.push(base,roof,details);
});
// VI.6B.5: Occlusion judgement is camera-relative, derived from ground footprints.
// A strict dependency exists only when one footprint is entirely behind another
// on either oriented ground axis. Ambiguous/intersecting footprints retain a
// deterministic painter-order fallback; never invent a dependency cycle.
function footprint(item){
 const o=item.object,x=o?o.x:item.x,y=o?o.y:item.y;
 let w=1,h=1;
 if(o){[w,h]=window.gmrcartScene?.prefabs?.[o.type]?.size||[1,1];if(o.rotation%180)[w,h]=[h,w];}
 const corners=[[x,y],[x+w,y],[x+w,y+h],[x,y+h]].map(([px,py])=>orient(px,py));
 return {u0:Math.min(...corners.map(c=>c[0])),u1:Math.max(...corners.map(c=>c[0])),v0:Math.min(...corners.map(c=>c[1])),v1:Math.max(...corners.map(c=>c[1]))};
}
// VI.6B.7: Compare the actual silhouette envelope of each surface, not the
// full building height for every surface. Ground and roof are separate planes.
function surfaceHeight(item){
 if(item.kind==='tree')return [0,49];
 if(item.kind==='wall')return [0,13];
 if(item.kind==='prefab-surface'){
  const stone=['chamber','crypt','corridor'].includes(item.object.type);
  if(item.surface==='base')return [0,stone?12:22];
  if(item.surface==='roof')return [stone?12:22,stone?12:40];
  return [22,stone?22:42];
 }
 return [0,0];
}
function projectedBounds(item){
 const f=footprint(item),corners=[[f.u0,f.v0],[f.u1,f.v0],[f.u1,f.v1],[f.u0,f.v1]];
 const sx=corners.map(([u,v])=>origin+(u-v)*tw/2);
 const sy=corners.map(([u,v])=>30+(u+v)*th/2);
 const [bottomZ,topZ]=surfaceHeight(item);
 return {left:Math.min(...sx),right:Math.max(...sx),top:Math.min(...sy)-topZ,bottom:Math.max(...sy)-bottomZ};
}
function overlap(a,b){return a.left<b.right&&b.left<a.right&&a.top<b.bottom&&b.top<a.bottom;}
function strictBehind(a,b){
 return (a.u1<=b.u0||a.v1<=b.v0)&&!(b.u1<=a.u0||b.v1<=a.v0);
}
// If projected silhouettes overlap but footprints do not establish a strict
// ordering, a raised object's ground position is the stable camera-relative
// fallback. Never use the global object insertion order as the depth key.
function silhouetteDepth(item){
 const f=footprint(item),[low,high]=surfaceHeight(item);
 return (f.u1+f.v1)*12-(low+high)*.001;
}
function painterCompare(a,b){return silhouetteDepth(a)-silhouetteDepth(b)||a.tie-b.tie||(a.y??0)-(b.y??0)||(a.x??0)-(b.x??0)||(a.index??0)-(b.index??0);}
function judgeOcclusion(items){
 const footprints=items.map(footprint),bounds=items.map(projectedBounds);
 const edges=items.map(()=>[]),incoming=items.map(()=>0),conflicts=[];
 // Architectural constraints are authoritative: base -> roof -> details.
 // Camera ordering is a separate, weaker relationship between objects.
 const architecturalEdges=[];
 const occlusionAudit={architectural:0,ordered:0,ambiguous:0,cycles:0};
 const addEdge=(from,to)=>{if(from===to||edges[from].includes(to))return;edges[from].push(to);incoming[to]++;};
 for(let i=0;i<items.length;i++)for(let j=i+1;j<items.length;j++){
  if(items[i].object&&items[i].object===items[j].object)continue;
  if(!overlap(bounds[i],bounds[j]))continue;
  const ij=strictBehind(footprints[i],footprints[j]),ji=strictBehind(footprints[j],footprints[i]);
  if(ij&&!ji){addEdge(i,j);occlusionAudit.ordered++;}
  else if(ji&&!ij){addEdge(j,i);occlusionAudit.ordered++;}
  else {conflicts.push([i,j]);occlusionAudit.ambiguous++;}
 }
 // Preserve the construction order of each building's own surfaces.
 // These dependencies are acyclic and do not alter other scene objects.
 for(let i=0;i<items.length;i++){
  const a=items[i];if(a.kind!=='prefab-surface')continue;
  for(let j=0;j<items.length;j++){
   const b=items[j];if(i===j||b.kind!=='prefab-surface'||a.object!==b.object)continue;
   if((a.surface==='base'&&b.surface==='roof')||(a.surface==='roof'&&b.surface==='details')){
    architecturalEdges.push([i,j]);occlusionAudit.architectural++;
   }
  }
 }
 // Architectural order must never be overridden by a camera-only dependency.
 // Remove opposing camera edges before inserting mandatory local constraints.
 for(const [from,to] of architecturalEdges){
  const reverse=edges[to].indexOf(from);
  if(reverse!==-1){edges[to].splice(reverse,1);incoming[from]--;}
  addEdge(from,to);
 }
 const pending=new Set(items.map((_,i)=>i)),result=[];
 while(pending.size){
  const ready=Array.from(pending).filter(i=>incoming[i]===0).sort((i,j)=>painterCompare(items[i],items[j])||i-j);
  // Cyclic or genuinely intersecting geometry is intentionally conservative.
  // In an occlusion cycle, release the lowest-depth surface deterministically.
  // Only unresolved edges are considered; never corrupt an already painted item.
  if(!ready.length)occlusionAudit.cycles++;
  const next=ready.length?ready[0]:Array.from(pending).sort((i,j)=>painterCompare(items[i],items[j])||i-j)[0];
  pending.delete(next);result.push(next);
  for(const target of edges[next])if(pending.has(target))incoming[target]--;
 }
 return {ordered:result.map(i=>items[i]),conflicts,footprints,bounds,audit:occlusionAudit};
}
const judgement=judgeOcclusion(raised);
raised.splice(0,raised.length,...judgement.ordered);
// Diagnostic overlay is drawn last and never enters scene persistence.
const debugEntries=raised.map((item,index)=>({item,index}));
raised.forEach(item=>{
 if(item.kind==='wall'||item.kind==='tree'){
  const {x,y}=item,mat=item.kind;
  const a=point(x,y),b=point(x+1,y),c=point(x+1,y+1),d=point(x,y+1),center=point(x+.5,y+.5);
if(mat==='wall'){const rise=13;polygon([point(x,y,rise),point(x+1,y,rise),point(x+1,y+1,rise),point(x,y+1,rise)],'#a99b95');polygon([b,c,point(x+1,y+1,rise),point(x+1,y,rise)],'#5f5354');polygon([d,c,point(x+1,y+1,rise),point(x,y+1,rise)],'#736565');line(point(x,y,rise),point(x+1,y+1,rise),'#d2c3b5',.8,.7);}
if(mat==='tree'&&!sprite('tree',center[0]-16,center[1]-49,32,57)){node('ellipse',{cx:center[0]+3,cy:center[1]+6,rx:12,ry:5,fill:'#2c352c',opacity:.4});node('path',{d:`M${center[0]} ${center[1]+4}v-17`,stroke:'#5d3a29','stroke-width':4});node('circle',{cx:center[0]-5,cy:center[1]-15,r:10,fill:'#315b40'});node('circle',{cx:center[0]+5,cy:center[1]-18,r:11,fill:'#4e8555'});node('circle',{cx:center[0],cy:center[1]-24,r:9,fill:'#70a467'});node('circle',{cx:center[0]-4,cy:center[1]-27,r:3.5,fill:'#a0c27b',opacity:.65});}
  return;
 }
 const o=item.object;
let [w,h]=(window.gmrcartScene?.prefabs?.[o.type]?.size)||[1,1];if(o.rotation%180)[w,h]=[h,w];
const a=point(o.x,o.y,0),b=point(o.x+w,o.y,0),c=point(o.x+w,o.y+h,0),d=point(o.x,o.y+h,0),top=22;
const stone=['chamber','crypt','corridor'].includes(o.type);
if(item.surface==='base'){
 polygon([a,b,c,d],stone?'#8c8984':'#b29c77');
 if(stone){polygon([b,c,point(o.x+w,o.y+h,12),point(o.x+w,o.y,12)],'#716a65');}
 else{polygon([a,d,point(o.x,o.y+h,top),point(o.x,o.y,top)],'#cbb38a');polygon([d,c,point(o.x+w,o.y+h,top),point(o.x,o.y+h,top)],'#94775c');}
}
if(item.surface==='roof'){
 if(stone){polygon([point(o.x,o.y,12),point(o.x+w,o.y,12),point(o.x+w,o.y+h,12),point(o.x,o.y+h,12)],atlas.stone);}
 else{const ridge1=point(o.x+w/2,o.y,top+18),ridge2=point(o.x+w/2,o.y+h,top+18);const finish=roofFinish(o.type,o.appearance);polygon([point(o.x,o.y,top),ridge1,ridge2,point(o.x,o.y+h,top)],finish?.dark||atlas.roofShade);polygon([ridge1,point(o.x+w,o.y,top),point(o.x+w,o.y+h,top),ridge2],finish?.light||atlas.roof);architecturalRoofInk(o,w,h,top,false);}
}
if(item.surface==='details'&&!stone){architecturalWallInk(o,w,h,top);buildingDetails(o,w,h,top);}

});
if(depthDebug){
 const overlay=node('g',{'data-gmrcart-depth-debug':'true','pointer-events':'none'});
 // Show ambiguous projected overlaps without changing any saved data.
 const status=node('text',{x:12,y:18,fill:'#fff1bb','font-size':10,stroke:'#241b23','stroke-width':2,'paint-order':'stroke'},overlay);
 status.textContent=`Occlusion audit: ${judgement.audit.ambiguous} ambiguous · ${judgement.audit.ordered} ordered · ${judgement.audit.architectural} architectural · ${judgement.audit.cycles} cycles`;

 debugEntries.forEach(({item,index})=>{
  if(item.kind==='prefab-surface'&&item.surface!=='roof')return;
  const o=item.object, x=o?o.x:item.x, y=o?o.y:item.y;
  let w=1,h=1;
  if(o){[w,h]=(window.gmrcartScene?.prefabs?.[o.type]?.size)||[1,1];if(o.rotation%180)[w,h]=[h,w];}
  const corners=[point(x,y),point(x+w,y),point(x+w,y+h),point(x,y+h)];
  polygon(corners,'none',o?'#ffd274':item.kind==='tree'?'#85f4b5':'#ff9eae',overlay);
  const anchor=point(x+w/2,y+h/2);
  node('circle',{cx:anchor[0],cy:anchor[1],r:3,fill:'#fff0a6',stroke:'#271b25','stroke-width':1},overlay);
  const label=node('text',{x:anchor[0]+5,y:anchor[1]-5,fill:'#fff5ce','font-size':8,stroke:'#241b23','stroke-width':2,'paint-order':'stroke'},overlay);
  label.textContent=`${index+1} ${o?o.type+'/'+item.surface:item.kind} (${Math.round(item.depth*10)/10})`;
 });
}
}
function schedule(){if(queued)return;queued=true;requestAnimationFrame(()=>{queued=false;draw();});}
// VI.6B.8: a gesture changes only the camera; construction coordinates stay untouched.
// The scene has four authoritative isometric views, so drag previews use a
// crossfade between rendered views rather than claiming arbitrary 3D angles.
const reducedMotion=window.matchMedia?.('(prefers-reduced-motion: reduce)');
let gesture=null,turnToken=0;
frame.style.touchAction='pan-y';
frame.style.cursor='grab';
frame.setAttribute('aria-label','Illustrated landscape. Drag horizontally to rotate; Shift and drag to pan; scroll to zoom.');
frame.addEventListener('wheel',event=>{if(!active||!event.cancelable)return;event.preventDefault();const bounds=frame.getBoundingClientRect();if(!bounds.width||!bounds.height)return;setZoom(zoom+(event.deltaY<0?.1:-.1),(event.clientX-bounds.left)/bounds.width,(event.clientY-bounds.top)/bounds.height);},{passive:false});
function cameraLabel(){angleLabel.textContent=`${cameraNames[orientation]} · ${orientation*90}°`;}
function clearTurnLayer(){frame.querySelectorAll('[data-gmrcart-turn-layer]').forEach(layer=>layer.remove());preview.style.opacity='';}
function turn(delta,animate=true){
 if(!active||!delta)return;
 const token=++turnToken;
 clearTurnLayer();
 const oldView=animate&&!reducedMotion?.matches?preview.cloneNode(true):null;
 if(oldView){
  oldView.removeAttribute('id');oldView.setAttribute('data-gmrcart-turn-layer','');
  oldView.setAttribute('aria-hidden','true');
  Object.assign(oldView.style,{position:'absolute',inset:'0',width:'100%',height:'100%',pointerEvents:'none',opacity:'1',transition:'opacity 220ms ease-out'});
  frame.style.position='relative';frame.appendChild(oldView);
 }
 orientation=(orientation+delta%4+4)%4;
 cameraLabel();
 // Rendering the new camera immediately avoids modifying scene geometry.
 draw();
 if(oldView){
  preview.style.opacity='0';
  requestAnimationFrame(()=>{
   if(token!==turnToken)return;
   preview.style.transition='opacity 220ms ease-out';
   preview.style.opacity='1';oldView.style.opacity='0';
  });
  oldView.addEventListener('transitionend',()=>{if(token===turnToken){oldView.remove();preview.style.transition='';preview.style.opacity='';}},{once:true});
  // A transitionend can be omitted if a tab becomes hidden; next turn cleans up.
 }
}
cameraButtons.forEach(button=>button.addEventListener('click',()=>turn(button.dataset.gmrcartCamera==='left'?-1:1)));
frame.addEventListener('pointerdown',event=>{
 if(!active||event.button!==0||gesture)return;
 gesture={id:event.pointerId,x:event.clientX,y:event.clientY,dragging:false,panning:event.shiftKey&&zoom>1,startPanX:panX,startPanY:panY};
 if(gesture.panning){gesture.dragging=true;frame.style.cursor='grabbing';try{frame.setPointerCapture(event.pointerId);}catch(_error){}}
});
frame.addEventListener('pointermove',event=>{
 if(!gesture||event.pointerId!==gesture.id)return;
 const dx=event.clientX-gesture.x,dy=event.clientY-gesture.y;
 if(gesture.panning){const rect=frame.getBoundingClientRect();if(rect.width&&rect.height){panX=gesture.startPanX-dx*BASE_WIDTH/(rect.width*zoom);panY=gesture.startPanY-dy*BASE_HEIGHT/(rect.height*zoom);applyViewport();}return;}
 if(!gesture.dragging&&Math.abs(dx)>12&&Math.abs(dx)>Math.abs(dy)*1.2){
  gesture.dragging=true;frame.style.cursor='grabbing';
  try{frame.setPointerCapture(event.pointerId);}catch(_error){}
 }
});
function finishGesture(event,cancelled){
 if(!gesture||event.pointerId!==gesture.id)return;
 const dx=event.clientX-gesture.x,dragging=gesture.dragging,panning=gesture.panning;
 gesture=null;frame.style.cursor='grab';
 if(frame.hasPointerCapture?.(event.pointerId))frame.releasePointerCapture(event.pointerId);
 if(!cancelled&&dragging&&!panning&&Math.abs(dx)>=36){
  // Dragging right turns the world left, matching a hand turning a model.
  const steps=Math.min(3,Math.max(1,Math.round(Math.abs(dx)/120)));
  turn((dx>0?-1:1)*steps);
 }
}
frame.addEventListener('pointerup',event=>finishGesture(event,false));
frame.addEventListener('pointercancel',event=>finishGesture(event,true));
frame.addEventListener('lostpointercapture',()=>{gesture=null;frame.style.cursor='grab';});
toggle.addEventListener('click',()=>{if(active){++turnToken;clearTurnLayer();gesture=null;}active=!active;preview.hidden=!active;frame.hidden=!active;frame.style.display=active?'block':'none';grid.hidden=active;toggle.setAttribute('aria-pressed',String(active));toggle.textContent=active?'Return to editable grid':'View illustrated landscape';root.querySelector('.gmrcart-camera-controls').hidden=!active;if(illustrationToolbar)illustrationToolbar.hidden=!active;zoomControls.hidden=!active;if(styleControls)styleControls.hidden=!active;if(debugButton)debugButton.hidden=!active;cameraButtons.forEach(b=>b.disabled=!active);schedule();});
root.addEventListener('gmrcart:objects-changed',schedule);grid.addEventListener('pointerdown',schedule);grid.addEventListener('pointermove',e=>{if(e.buttons)schedule();});grid.addEventListener('click',schedule);
})();
