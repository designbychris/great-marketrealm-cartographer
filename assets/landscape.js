(()=>{'use strict';
const root=document.querySelector('.gmrcart-canvas-panel');if(!root||!window.gmrcartLandscape)return;
const source=window.gmrcartLandscape,grid=root.querySelector('.gmrcart-grid'),preview=root.querySelector('.gmrcart-landscape'),frame=root.querySelector('.gmrcart-landscape-frame'),toggle=root.querySelector('.gmrcart-view-toggle');
const ns='http://www.w3.org/2000/svg',W=12,H=12,tw=48,th=24,origin=330;
let active=false,queued=false,orientation=0,illustrationStyle='classic',depthDebug=false;
const styleControls=root.querySelector('.gmrcart-atlas-style');
const illustrationToolbar=root.querySelector('.gmrcart-illustration-toolbar');
const assetLibrary=window.gmrcartAtlasAssets;
const assetBase=window.gmrcartScene?.atlasAssetBase||'';
function sprite(name,x,y,width,height){if(illustrationStyle!=='pixel'||!assetLibrary||!assetBase)return false;const path=assetLibrary.resolve('marketrealm',name);if(!path)return false;node('image',{href:assetBase.replace(/\/$/,'')+'/'+path,x,y,width,height,preserveAspectRatio:'xMidYMax meet',style:'image-rendering:pixelated'});return true;}
styleControls?.querySelectorAll('[data-gmrcart-style]').forEach(button=>button.addEventListener('click',()=>{illustrationStyle=button.dataset.gmrcartStyle;styleControls.querySelectorAll('button').forEach(b=>b.setAttribute('aria-pressed',String(b===button)));schedule();}));
const debugButton=root.querySelector('.gmrcart-depth-toggle');
debugButton?.addEventListener('click',()=>{depthDebug=!depthDebug;debugButton.setAttribute('aria-pressed',String(depthDebug));debugButton.textContent=depthDebug?'Hide depth guides':'Show depth guides';schedule();});
const angleLabel=root.querySelector('.gmrcart-camera-angle');
const cameraButtons=root.querySelectorAll('[data-gmrcart-camera]');
const cameraNames=['South-east','South-west','North-west','North-east'];
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
function buildingDetails(o,w,h,top){const cx=o.x+w/2,cy=o.y+h/2;const isTower=o.type==='tower';const isMarket=o.type==='market';
 const frontA=point(o.x,o.y+h,top-5),frontB=point(o.x+w,o.y+h,top-5);
 if(!isMarket){const door=point(cx,o.y+h,top-5);node('ellipse',{cx:door[0],cy:door[1]+4,rx:7,ry:3,fill:'#382c29',opacity:.4});polygon([[door[0]-4,door[1]],[door[0]+4,door[1]+2],[door[0]+4,door[1]-10],[door[0]-4,door[1]-12]],'#51382c','#35271e');node('circle',{cx:door[0]+2,cy:door[1]-5,r:.9,fill:'#f5ce78'});}
 for(const shift of [-.28,.28]){const p=point(cx+w*shift,o.y+h,top-7);polygon([[p[0]-4,p[1]-8],[p[0]+4,p[1]-6],[p[0]+4,p[1]+1],[p[0]-4,p[1]-1]],'#f6c875','#5c4030');line([p[0],p[1]-8],[p[0],p[1]],'#65483a',.9);}
 const roofCenter=point(cx,cy,top+19);if(!isMarket){line([roofCenter[0]-14,roofCenter[1]-2],[roofCenter[0]+10,roofCenter[1]+10],'#e4aa7e',1.3,.8);}
 if(o.type==='cottage'||o.type==='tavern'||o.type==='blacksmith'){
 // Keep the stack anchored to the visible roof plane, not to an elevated world point.
 // The previous excessive height offset made chimneys float above the roof at every camera angle.
 const anchor=point(cx,cy,top+9);
 polygon([[anchor[0]-3,anchor[1]+3],[anchor[0]+3,anchor[1]+5],[anchor[0]+3,anchor[1]-9],[anchor[0]-3,anchor[1]-11]],'#89736c','#453a3a');
 node('ellipse',{cx:anchor[0],cy:anchor[1]-10,rx:3.5,ry:1.5,fill:'#39353c'});
 }
}
function draw(){if(!active)return;preview.replaceChildren();preview.setAttribute('viewBox','0 0 660 385');preview.setAttribute('role','img');preview.setAttribute('aria-label',`Isometric preview of the current construction scene, ${cameraNames[orientation]} view`);
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
 raised.push({kind:'prefab',object:o,depth:front,tie:1,index});
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
function projectedBounds(item){
 const f=footprint(item), corners=[[f.u0,f.v0],[f.u1,f.v0],[f.u1,f.v1],[f.u0,f.v1]];
 const sx=corners.map(([u,v])=>origin+(u-v)*tw/2);
 const sy=corners.map(([u,v])=>30+(u+v)*th/2);
 const height=item.kind==='tree'?49:item.kind==='wall'?13:item.kind==='prefab'?40:0;
 return {left:Math.min(...sx),right:Math.max(...sx),top:Math.min(...sy)-height,bottom:Math.max(...sy)};
}
function overlap(a,b){return a.left<b.right&&b.left<a.right&&a.top<b.bottom&&b.top<a.bottom;}
function strictBehind(a,b){
 // On the isometric ground plane, either axis establishes an unambiguous
 // behind/in-front relationship when the intervals do not intersect.
 return (a.u1<=b.u0||a.v1<=b.v0)&&!(b.u1<=a.u0||b.v1<=a.v0);
}
function painterCompare(a,b){return a.depth-b.depth||a.tie-b.tie||(a.y??0)-(b.y??0)||(a.x??0)-(b.x??0)||(a.index??0)-(b.index??0);}
function judgeOcclusion(items){
 const footprints=items.map(footprint),bounds=items.map(projectedBounds);
 const edges=items.map(()=>[]),incoming=items.map(()=>0),conflicts=[];
 for(let i=0;i<items.length;i++)for(let j=i+1;j<items.length;j++){
  if(!overlap(bounds[i],bounds[j]))continue;
  const ij=strictBehind(footprints[i],footprints[j]),ji=strictBehind(footprints[j],footprints[i]);
  if(ij&&!ji){edges[i].push(j);incoming[j]++;}
  else if(ji&&!ij){edges[j].push(i);incoming[i]++;}
  else conflicts.push([i,j]);
 }
 const pending=new Set(items.map((_,i)=>i)),result=[];
 while(pending.size){
  const ready=Array.from(pending).filter(i=>incoming[i]===0).sort((i,j)=>painterCompare(items[i],items[j])||i-j);
  // Cyclic or genuinely intersecting geometry is intentionally conservative.
  const next=ready.length?ready[0]:Array.from(pending).sort((i,j)=>painterCompare(items[i],items[j])||i-j)[0];
  pending.delete(next);result.push(next);
  for(const target of edges[next])incoming[target]--;
 }
 return {ordered:result.map(i=>items[i]),conflicts,footprints,bounds};
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
let [w,h]=(window.gmrcartScene?.prefabs?.[o.type]?.size)||[1,1];if(o.rotation%180)[w,h]=[h,w];const a=point(o.x,o.y,0),b=point(o.x+w,o.y,0),c=point(o.x+w,o.y+h,0),d=point(o.x,o.y+h,0),top=22;
polygon([a,b,c,d],['chamber','crypt','corridor'].includes(o.type)?'#8c8984':'#b29c77');
if(['chamber','crypt','corridor'].includes(o.type)){polygon([point(o.x,o.y,12),point(o.x+w,o.y,12),point(o.x+w,o.y+h,12),point(o.x,o.y+h,12)],atlas.stone);polygon([b,c,point(o.x+w,o.y+h,12),point(o.x+w,o.y,12)],'#716a65');}
else{polygon([a,d,point(o.x,o.y+h,top),point(o.x,o.y,top)],'#cbb38a');polygon([d,c,point(o.x+w,o.y+h,top),point(o.x,o.y+h,top)],'#94775c');const ridge1=point(o.x+w/2,o.y,top+18),ridge2=point(o.x+w/2,o.y+h,top+18);polygon([point(o.x,o.y,top),ridge1,ridge2,point(o.x,o.y+h,top)],o.type==='tavern'?'#835044':atlas.roofShade);polygon([ridge1,point(o.x+w,o.y,top),point(o.x+w,o.y+h,top),ridge2],o.type==='tavern'?'#a76a51':atlas.roof);buildingDetails(o,w,h,top);}

});
if(depthDebug){
 const overlay=node('g',{'data-gmrcart-depth-debug':'true','pointer-events':'none'});
 // Show ambiguous projected overlaps without changing any saved data.
 const status=node('text',{x:12,y:18,fill:'#fff1bb','font-size':10,stroke:'#241b23','stroke-width':2,'paint-order':'stroke'},overlay);
 status.textContent=`Occlusion audit: ${judgement.conflicts.length} ambiguous overlap pairs`;

 debugEntries.forEach(({item,index})=>{
  const o=item.object, x=o?o.x:item.x, y=o?o.y:item.y;
  let w=1,h=1;
  if(o){[w,h]=(window.gmrcartScene?.prefabs?.[o.type]?.size)||[1,1];if(o.rotation%180)[w,h]=[h,w];}
  const corners=[point(x,y),point(x+w,y),point(x+w,y+h),point(x,y+h)];
  polygon(corners,'none',o?'#ffd274':item.kind==='tree'?'#85f4b5':'#ff9eae',overlay);
  const anchor=point(x+w/2,y+h/2);
  node('circle',{cx:anchor[0],cy:anchor[1],r:3,fill:'#fff0a6',stroke:'#271b25','stroke-width':1},overlay);
  const label=node('text',{x:anchor[0]+5,y:anchor[1]-5,fill:'#fff5ce','font-size':8,stroke:'#241b23','stroke-width':2,'paint-order':'stroke'},overlay);
  label.textContent=`${index+1} ${o?o.type:item.kind} (${Math.round(item.depth*10)/10})`;
 });
}
}
function schedule(){if(queued)return;queued=true;requestAnimationFrame(()=>{queued=false;draw();});}
cameraButtons.forEach(button=>button.addEventListener('click',()=>{orientation=(orientation+(button.dataset.gmrcartCamera==='left'?3:1))%4;angleLabel.textContent=`${cameraNames[orientation]} · ${orientation*90}°`;cameraButtons.forEach(b=>b.disabled=!active);schedule();}));
toggle.addEventListener('click',()=>{active=!active;preview.hidden=!active;frame.hidden=!active;frame.style.display=active?'block':'none';grid.hidden=active;toggle.setAttribute('aria-pressed',String(active));toggle.textContent=active?'Return to editable grid':'View illustrated landscape';root.querySelector('.gmrcart-camera-controls').hidden=!active;if(illustrationToolbar)illustrationToolbar.hidden=!active;if(styleControls)styleControls.hidden=!active;if(debugButton)debugButton.hidden=!active;cameraButtons.forEach(b=>b.disabled=!active);schedule();});
root.addEventListener('gmrcart:objects-changed',schedule);grid.addEventListener('pointerdown',schedule);grid.addEventListener('pointermove',e=>{if(e.buttons)schedule();});grid.addEventListener('click',schedule);
})();
