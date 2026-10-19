/** VI.6D.8: presentation-only Pattern Book palette; no independent scene geometry. */
(()=>{'use strict';
 const root=document.querySelector('.gmrcart-canvas-panel');
 const book=window.gmrcartArchitecturalPatternBook;
 const painter=window.gmrcartBuildingPainter;
 const target=root?.querySelector('.gmrcart-pattern-cards');
 if(!root||!book||!painter||!target)return;
 const base=window.gmrcartScene?.atlasAssetBase||'';
 const status=root.querySelector('.gmrcart-pattern-status');
 for(const pattern of book.list()){
  const painted=pattern.status==='painted'&&!!book.artwork(pattern.key);
  const card=document.createElement('button');card.type='button';card.className='gmrcart-pattern-card';
  card.dataset.pattern=pattern.key;card.disabled=!painted;
  card.setAttribute('aria-pressed','false');
  const artwork=painted?painter.view(pattern.artKey,0):null;
  if(artwork){const img=document.createElement('img');img.src=base+artwork;img.alt='';img.loading='lazy';card.appendChild(img);}
  else {const placeholder=document.createElement('span');placeholder.className='gmrcart-pattern-placeholder';placeholder.textContent='✎';placeholder.setAttribute('aria-hidden','true');card.appendChild(placeholder);}
  const title=document.createElement('strong');title.textContent=pattern.label;card.appendChild(title);
  const meta=document.createElement('small');meta.textContent=painted?'Painted · '+pattern.footprint.join(' × '):'Coming soon · '+book.categories[pattern.category];card.appendChild(meta);
  if(painted)card.addEventListener('click',()=>{
   root.dispatchEvent(new CustomEvent('gmrcart:choose-pattern',{detail:{prefab:pattern.prefab,appearance:pattern.key}}));
   target.querySelectorAll('button').forEach(b=>b.setAttribute('aria-pressed',String(b===card)));
   status.textContent=pattern.label+' selected. Choose a free spot on the grid, or change the selected cottage.';
  });
  target.appendChild(card);
 }
})();
