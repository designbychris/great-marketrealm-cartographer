(()=>{'use strict';
const root=document.querySelector('.gmrcart-canvas-panel');if(!root||!window.gmrcartLandscape)return;
const source=window.gmrcartLandscape,grid=root.querySelector('.gmrcart-grid'),preview=root.querySelector('.gmrcart-landscape'),frame=root.querySelector('.gmrcart-landscape-frame'),toggle=root.querySelector('.gmrcart-view-toggle');
const ns='http://www.w3.org/2000/svg',W=12,H=12,tw=48,th=24,origin=330;
let active=false,queued=false;
function node(tag,attrs,parent){const n=document.createElementNS(ns,tag);Object.entries(attrs||{}).forEach(([k,v])=>n.setAttribute(k,String(v)));(parent||preview).appendChild(n);return n;}
function point(x,y,z=0){return [origin+(x-y)*tw/2,30+(x+y)*th/2-z];}
function polygon(points,fill,stroke='#392c29',parent=preview){return node('polygon',{points:points.map(p=>p.join(',')).join(' '),fill,stroke,'stroke-width':.8,'stroke-linejoin':'round'},parent);}
function draw(){if(!active)return;preview.replaceChildren();preview.setAttribute('viewBox','0 0 660 385');preview.setAttribute('role','img');preview.setAttribute('aria-label','Isometric preview of the current construction scene');
const tiles=Array.from(grid.querySelectorAll('.gmrcart-cell'));
for(let sum=0;sum<W+H-1;sum++)for(let y=0;y<H;y++){const x=sum-y;if(x<0||x>=W)continue;const mat=tiles[y*W+x]?.dataset.material||'empty';const a=point(x,y),b=point(x+1,y),c=point(x+1,y+1),d=point(x,y+1),center=point(x+.5,y+.5);
const colors={floor:'#9d9169',empty:'#544a40',wall:'#9a8581',water:'#347d95',tree:'#6e8751'};polygon([a,b,c,d],colors[mat]||colors.empty);
if(mat==='water'){const adjacent=(dx,dy)=>{const xx=x+dx,yy=y+dy;return xx>=0&&xx<W&&yy>=0&&yy<H&&tiles[yy*W+xx]?.dataset.material==='water';};if(!adjacent(1,0))node('path',{d:`M ${b.join(' ')} L ${c.join(' ')}`,stroke:'#83c2cb','stroke-width':2,fill:'none'});node('path',{d:`M ${center[0]-8} ${center[1]+1} q 8 -5 16 -1`,stroke:'#9fd0d8','stroke-width':1.6,fill:'none',opacity:.7});}
if(mat==='wall'){const rise=13;polygon([point(x,y,rise),point(x+1,y,rise),point(x+1,y+1,rise),point(x,y+1,rise)],'#a99b95');polygon([b,c,point(x+1,y+1,rise),point(x+1,y,rise)],'#5f5354');polygon([d,c,point(x+1,y+1,rise),point(x,y+1,rise)],'#736565');}
if(mat==='tree'){node('ellipse',{cx:center[0]+3,cy:center[1]+6,rx:12,ry:5,fill:'#2c352c',opacity:.4});node('path',{d:`M${center[0]} ${center[1]+4}v-17`,stroke:'#5d3a29','stroke-width':4});node('circle',{cx:center[0]-5,cy:center[1]-15,r:10,fill:'#315b40'});node('circle',{cx:center[0]+5,cy:center[1]-18,r:11,fill:'#4e8555'});node('circle',{cx:center[0],cy:center[1]-24,r:9,fill:'#70a467'});}
}
const objects=window.gmrcartObjects?.()||[];
objects.sort((a,b)=>(a.x+a.y)-(b.x+b.y)).forEach(o=>{let [w,h]=(window.gmrcartScene?.prefabs?.[o.type]?.size)||[1,1];if(o.rotation%180)[w,h]=[h,w];const a=point(o.x,o.y,0),b=point(o.x+w,o.y,0),c=point(o.x+w,o.y+h,0),d=point(o.x,o.y+h,0),top=22;
polygon([a,b,c,d],['chamber','crypt','corridor'].includes(o.type)?'#8c8984':'#b29c77');
if(['chamber','crypt','corridor'].includes(o.type)){polygon([point(o.x,o.y,12),point(o.x+w,o.y,12),point(o.x+w,o.y+h,12),point(o.x,o.y+h,12)],'#b2ada4');polygon([b,c,point(o.x+w,o.y+h,12),point(o.x+w,o.y,12)],'#716a65');}
else{polygon([a,d,point(o.x,o.y+h,top),point(o.x,o.y,top)],'#cbb38a');polygon([d,c,point(o.x+w,o.y+h,top),point(o.x,o.y+h,top)],'#94775c');const ridge1=point(o.x+w/2,o.y,top+18),ridge2=point(o.x+w/2,o.y+h,top+18);polygon([point(o.x,o.y,top),ridge1,ridge2,point(o.x,o.y+h,top)],o.type==='tavern'?'#835044':'#955e48');polygon([ridge1,point(o.x+w,o.y,top),point(o.x+w,o.y+h,top),ridge2],o.type==='tavern'?'#a76a51':'#bd7959');}
});
}
function schedule(){if(queued)return;queued=true;requestAnimationFrame(()=>{queued=false;draw();});}
toggle.addEventListener('click',()=>{active=!active;preview.hidden=!active;frame.hidden=!active;frame.style.display=active?'block':'none';grid.hidden=active;toggle.setAttribute('aria-pressed',String(active));toggle.textContent=active?'Return to editable grid':'View illustrated landscape';schedule();});
root.addEventListener('gmrcart:objects-changed',schedule);grid.addEventListener('pointerdown',schedule);grid.addEventListener('pointermove',e=>{if(e.buttons)schedule();});grid.addEventListener('click',schedule);
})();
