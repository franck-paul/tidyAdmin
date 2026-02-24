/*global dotclear */
'use strict';

dotclear.ready(() => {
  const headercolor = document.querySelector('#ui_userheadercolor');
  if (!headercolor) {
    return;
  }
  const applyheadercolor = (checked) => {
    const lightcolor = document.querySelector('#ui_headercolor');
    const darkcolor = document.querySelector('#ui_headercolor_dark');
    lightcolor.readOnly = !checked;
    darkcolor.readOnly = !checked;
  };
  headercolor.addEventListener('change', (event) => {
    // State has changed
    applyheadercolor(event.target.checked);
  });
  // 1st pass
  applyheadercolor(headercolor.checked);
});
