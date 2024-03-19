/*global dotclear */
'use strict';

dotclear.ready(() => {
  // Main menu collapser
  const objMain = document.getElementById('wrapper');
  const hideMainMenu = 'hide_main_menu';

  // Sidebar separator
  document.getElementById('collapser')?.addEventListener('mouseover', (event) => {
    const t = setTimeout(() => {
      event.preventDefault();
      if (objMain.classList.contains('hide-mm')) {
        // Show sidebar
        objMain.classList.remove('hide-mm');
        dotclear.dropLocalData(hideMainMenu);
        document.querySelector('input#qx')?.focus();
        return;
      }
      // Hide sidebar
      objMain.classList.add('hide-mm');
      dotclear.storeLocalData(hideMainMenu, true);
      document.querySelector('#content a.go_home')?.focus();
    }, 500);
    event.target.addEventListener('mouseleave', () => {
      clearTimeout(t);
    });
  });
});
