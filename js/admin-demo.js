/*global $, dotclear */
'use strict';

/* User-defined JS script */
// Hide and Show menubar on collapser mouseover event
$(function () {
  // Main menu collapser
  const objMain = $('#wrapper');
  const hideMainMenu = 'hide_main_menu';

  // Sidebar separator
  $('#collapser').on('click', function (e) {
    e.preventDefault();
    if (objMain.hasClass('hide-mm')) {
      // Show sidebar
      objMain.removeClass('hide-mm');
      dotclear.dropLocalData(hideMainMenu);
      $('#main-menu input#qx').trigger('focus');
    } else {
      // Hide sidebar
      objMain.addClass('hide-mm');
      dotclear.storeLocalData(hideMainMenu, true);
      $('#content a.go_home').trigger('focus');
    }
  });
  // Cope with current stored state of collapser
  if (dotclear.readLocalData(hideMainMenu) === true) {
    objMain.addClass('hide-mm');
  } else {
    objMain.removeClass('hide-mm');
  }
});
