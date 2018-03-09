/*global $ */
'use strict';

/* User-defined JS script */
// Hide and Show menubar on collapser mouseover event
$(function() {
  var objMain = $('#wrapper');

  function showSidebar() {
    // Show sidebar
    objMain.removeClass('hide-mm');
    $.cookie('sidebar-pref', null, {
      expires: 30
    });
  }

  function hideSidebar() {
    // Hide sidebar
    objMain.addClass('hide-mm');
    $.cookie('sidebar-pref', 'hide-mm', {
      expires: 30
    });
  }
  // Sidebar separator
  var objSeparator = $('#collapser');
  objSeparator.mouseover(function(e) {
    e.preventDefault();
    if (objMain.hasClass('hide-mm')) {
      showSidebar();
      $('#main-menu input#qx').focus();
    } else {
      hideSidebar();
      $('#content a.go_home').focus();
    }
  });
});
