/*global dotclear */
'use strict';

dotclear.ready(() => {
  const headercolor = document.querySelector('#user_ui_userheadercolor');
  if (headercolor) {
    const applyheadercolor = (checked) => {
      const lightcolor = document.querySelector('#user_ui_headercolor');
      const darkcolor = document.querySelector('#user_ui_headercolor_dark');
      lightcolor.readOnly = !checked;
      darkcolor.readOnly = !checked;
    };
    headercolor.addEventListener('change', (event) => {
      // State has changed
      applyheadercolor(event.target.checked);
    });
    // 1st pass
    applyheadercolor(headercolor.checked);
  }
});
