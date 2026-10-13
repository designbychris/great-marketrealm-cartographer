/**
 * VI.6 — Illustrated Atlas palette registry (visual presentation only).
 * New themes can register a palette here without changing saved scenes.
 * No remote assets, no network calls and no user-supplied SVG markup.
 */
(()=>{'use strict';
 const palettes=Object.freeze({
  marketrealm:Object.freeze({floor:'#9d9169',empty:'#544a40',wall:'#9a8581',water:'#347d95',tree:'#6e8751',roof:'#bd7959',roofShade:'#955e48',stone:'#b2ada4'}),
  withered:Object.freeze({floor:'#77717b',empty:'#39343f',wall:'#817c88',water:'#465f80',tree:'#4c615a',roof:'#5e455d',roofShade:'#453647',stone:'#aaa4ad'}),
  takeaway:Object.freeze({floor:'#66717c',empty:'#30323e',wall:'#818b9c',water:'#247c9d',tree:'#457f84',roof:'#9c5ba8',roofShade:'#6b437f',stone:'#a5b4bd'})
 });
 window.gmrcartAtlasPalettes=palettes;
})();
