(function($) {
	$.modalText = function(txt,w,h) {
		div = $('<div class="readme">'+txt+'</div>').css({
			border: 'none',
			width: w,
			height: h,
		});
		return new $.modal(div);
	};

	$.fn.modalText = function(w,h) {
		this.click(function() {
			if ($(this).data('readme') != undefined) {
				$.modalText($(this).data('readme'),w,h);
			}
			return false;
		});
	};
})(jQuery);

$(function() {

	// Iconset information
	$('a.iconset-readme').modalText($(window).width()/2-40,$(window).height()/2-40);

});