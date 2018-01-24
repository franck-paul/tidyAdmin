(function($) {
  $.modalText = function(txt, w, h) {
    div = $('<div class="readme">' + txt + '</div>').css({
      width: w,
      height: h,
    });
    $.magnificPopup.open({
      items: {
        src: div,
        type: 'inline'
      }
    });
  };
  $.fn.modalText = function(w, h) {
    this.click(function() {
      if ($(this).data('readme') != undefined) {
        $.modalText($(this).data('readme'), w, h);
      }
      return false;
    });
  };
})(jQuery);
$(function() {
  // Iconset information
  $('a.iconset-readme').modalText($(window).width() / 2 - 40, $(window).height() / 2 - 40);
  // Iconset delete confirmation
  $('table.iconset_list form input[type=submit][name=delete]').click(function() {
    var p_name = $('input[name=iconset_id]', $(this).parent()).val();
    return window.confirm(dotclear.msg.confirm_delete_iconset.replace('%s', p_name));
  });
});
