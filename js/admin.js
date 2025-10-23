/*global dotclear */
'use strict';

dotclear.ready(() => {
  const headercolor = document.querySelector('#user_ui_userheadercolor');
  if (headercolor) {
    const applyheadercolor = (checked) => {
      const lightcolor = document.querySelector('#user_ui_headercolor');
      const darkcolor = document.querySelector('#user_ui_headercolor_dark');
      lightcolor.disabled = !checked;
      darkcolor.disabled = !checked;
    };
    headercolor.addEventListener('change', (event) => {
      // State has changed
      applyheadercolor(event.target.checked);
    });
    // 1st pass
    applyheadercolor(headercolor.checked);
  }

  const dock = document.querySelector('#user_ui_dock');
  if (dock) {
    const applydock = (checked) => {
      const dockitemactive = document.querySelector('#user_ui_dockactive');
      const dockautohide = document.querySelector('#user_ui_dockautohide');
      dockitemactive.disabled = !checked;
      dockautohide.disabled = !checked;
    };
    dock.addEventListener('change', (event) => {
      // State has changed
      applydock(event.target.checked);
    });
    // 1st pass
    applydock(dock.checked);
  }
});
