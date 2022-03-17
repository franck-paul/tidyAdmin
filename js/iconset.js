/*global $, jQuery, dotclear */
'use strict';

(() => {
  const $ = jQuery;
  $.modalText = (txt, w, h) => {
    const div = $(`<div class="readme">${txt}</div>`).css({
      width: w,
      height: h,
    });
    $.magnificPopup.open({
      items: {
        src: div,
        type: 'inline',
      },
    });
  };
  $.fn.modalText = function (w, h) {
    this.on('click', function () {
      if ($(this).data('readme') != undefined) {
        $.modalText($(this).data('readme'), w, h);
      }
      return false;
    });
  };
})();
$(() => {
  dotclear.mergeDeep(dotclear, dotclear.getData('tidy_admin'));
  // Iconset information
  $('a.iconset-readme').modalText($(window).width() / 2 - 40, $(window).height() / 2 - 40);
  // Iconset delete confirmation
  $('table.iconset_list form input[type=submit][name=delete]').on('click', function () {
    const p_name = $('input[name=iconset_id]', $(this).parent()).val();
    return window.confirm(dotclear.msg.confirm_delete_iconset.replace('%s', p_name));
  });
});
