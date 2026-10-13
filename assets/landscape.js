(()=>{'use strict';
const root=document.querySelector('.gmrcart-canvas-panel');if(!root||!window.gmrcartLandscape)return;
const source=window.gmrcartLandscape,grid=root.querySelector('.gmrcart-grid'),preview=root.querySelector('.gmrcart-landscape'),frame=root.querySelector('.gmrcart-landscape-frame'),toggle=root.querySelector('.gmrcart-view-toggle');
const ns='http://www.w3.org/2000/svg',W=12,H=12,tw=48,th=24,origin=330;
let active=false,queued=false,orientation=0;
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
 if(o.type==='cottage'||o.type==='tavern'||o.type==='blacksmith'){const chimney=point(o.x+w*.28,o.y+h*.26,top+25);polygon([[chimney[0]-4,chimney[1]+3],[chimney[0]+1,chimney[1]+5],[chimney[0]+1,chimney[1]-10],[chimney[0]-4,chimney[1]-12]],'#89736c','#453a3a');node('ellipse',{cx:chimney[0]-2,cy:chimney[1]-12,rx:3,ry:1.6,fill:'#39353c'});}
}
function draw(){if(!active)return;preview.replaceChildren();preview.setAttribute('viewBox','0 0 660 385');preview.setAttribute('role','img');preview.setAttribute('aria-label',`Isometric preview of the current construction scene, ${cameraNames[orientation]} view`);
const tiles=Array.from(grid.querySelectorAll('.gmrcart-cell'));
const order=[];for(let y=0;y<H;y++)for(let x=0;x<W;x++){const [u,v]=orient(x+.5,y+.5);order.push({x,y,depth:u+v});}order.sort((a,b)=>a.depth-b.depth);for(const {x,y} of order){const mat=tiles[y*W+x]?.dataset.material||'empty';const a=point(x,y),b=point(x+1,y),c=point(x+1,y+1),d=point(x,y+1),center=point(x+.5,y+.5);
const colors=atlas;polygon([a,b,c,d],mat==='floor'?shade(colors.floor,(x*17+y*11)%19-9):colors[mat]||colors.empty);terrainDetail(x,y,mat);
if(mat==='water'){const adjacent=(dx,dy)=>{const xx=x+dx,yy=y+dy;return xx>=0&&xx<W&&yy>=0&&yy<H&&tiles[yy*W+xx]?.dataset.material==='water';};if(!adjacent(1,0))node('path',{d:`M ${b.join(' ')} L ${c.join(' ')}`,stroke:'#83c2cb','stroke-width':2,fill:'none'});node('path',{d:`M ${center[0]-8} ${center[1]+1} q 8 -5 16 -1`,stroke:'#9fd0d8','stroke-width':1.6,fill:'none',opacity:.7});}
if(mat==='wall'){const rise=13;polygon([point(x,y,rise),point(x+1,y,rise),point(x+1,y+1,rise),point(x,y+1,rise)],'#a99b95');polygon([b,c,point(x+1,y+1,rise),point(x+1,y,rise)],'#5f5354');polygon([d,c,point(x+1,y+1,rise),point(x,y+1,rise)],'#736565');line(point(x,y,rise),point(x+1,y+1,rise),'#d2c3b5',.8,.7);}
if(mat==='tree'){node('ellipse',{cx:center[0]+3,cy:center[1]+6,rx:12,ry:5,fill:'#2c352c',opacity:.4});node('path',{d:`M${center[0]} ${center[1]+4}v-17`,stroke:'#5d3a29','stroke-width':4});node('circle',{cx:center[0]-5,cy:center[1]-15,r:10,fill:'#315b40'});node('circle',{cx:center[0]+5,cy:center[1]-18,r:11,fill:'#4e8555'});node('circle',{cx:center[0],cy:center[1]-24,r:9,fill:'#70a467'});node('circle',{cx:center[0]-4,cy:center[1]-27,r:3.5,fill:'#a0c27b',opacity:.65});}
}
const objects=window.gmrcartObjects?.()||[];
objects.sort((a,b)=>{const [aw,ah]=(window.gmrcartScene?.prefabs?.[a.type]?.size)||[1,1],[bw,bh]=(window.gmrcartScene?.prefabs?.[b.type]?.size)||[1,1];const [au,av]=orient(a.x+aw/2,a.y+ah/2),[bu,bv]=orient(b.x+bw/2,b.y+bh/2);return (au+av)-(bu+bv);}).forEach(o=>{let [w,h]=(window.gmrcartScene?.prefabs?.[o.type]?.size)||[1,1];if(o.rotation%180)[w,h]=[h,w];const a=point(o.x,o.y,0),b=point(o.x+w,o.y,0),c=point(o.x+w,o.y+h,0),d=point(o.x,o.y+h,0),top=22;
polygon([a,b,c,d],['chamber','crypt','corridor'].includes(o.type)?'#8c8984':'#b29c77');
if(['chamber','crypt','corridor'].includes(o.type)){polygon([point(o.x,o.y,12),point(o.x+w,o.y,12),point(o.x+w,o.y+h,12),point(o.x,o.y+h,12)],atlas.stone);polygon([b,c,point(o.x+w,o.y+h,12),point(o.x+w,o.y,12)],'#716a65');}
else{polygon([a,d,point(o.x,o.y+h,top),point(o.x,o.y,top)],'#cbb38a');polygon([d,c,point(o.x+w,o.y+h,top),point(o.x,o.y+h,top)],'#94775c');const ridge1=point(o.x+w/2,o.y,top+18),ridge2=point(o.x+w/2,o.y+h,top+18);polygon([point(o.x,o.y,top),ridge1,ridge2,point(o.x,o.y+h,top)],o.type==='tavern'?'#835044':atlas.roofShade);polygon([ridge1,point(o.x+w,o.y,top),point(o.x+w,o.y+h,top),ridge2],o.type==='tavern'?'#a76a51':atlas.roof);buildingDetails(o,w,h,top);}
});
}
function schedule(){if(queued)return;queued=true;requestAnimationFrame(()=>{queued=false;draw();});}
cameraButtons.forEach(button=>button.addEventListener('click',()=>{orientation=(orientation+(button.dataset.gmrcartCamera==='left'?3:1))%4;angleLabel.textContent=`${cameraNames[orientation]} · ${orientation*90}°`;cameraButtons.forEach(b=>b.disabled=!active);schedule();}));
toggle.addEventListener('click',()=>{active=!active;preview.hidden=!active;frame.hidden=!active;frame.style.display=active?'block':'none';grid.hidden=active;toggle.setAttribute('aria-pressed',String(active));toggle.textContent=active?'Return to editable grid':'View illustrated landscape';root.querySelector('.gmrcart-camera-controls').hidden=!active;cameraButtons.forEach(b=>b.disabled=!active);schedule();});
root.addEventListener('gmrcart:objects-changed',schedule);grid.addEventListener('pointerdown',schedule);grid.addEventListener('pointermove',e=>{if(e.buttons)schedule();});grid.addEventListener('click',schedule);
})();
