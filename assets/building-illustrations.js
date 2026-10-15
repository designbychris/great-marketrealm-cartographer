/** VI.6D.3: reusable, view-only building illustration catalogue. */
(()=>{'use strict';
 const buildings={
  cottage:{footprint:[2,2],aspect:178/192,views:[
   'buildings/cottage-0.png','buildings/cottage-90.png',
   'buildings/cottage-180.png','buildings/cottage-270.png']}
 };
 window.gmrcartBuildingArt=Object.freeze(buildings);
})();
