/* This part of code is by Flo from shopencart.com */
(function($) {

 function superdropdownalign() {
  var supermenu_width = $('#supermenu').outerWidth(false);
  if (supermenu_width > 767 || typeof runnedonce == 'undefined' || $('#supermenu').hasClass('superbig')) {
	runnedonce = true;
	$('#supermenu').removeClass('respmedium');
	$('#supermenu').removeClass('respsmall');
	$('#supermenu').addClass('superbig');
	if (supermenu_width < 900 && supermenu_width >= 768) {
		$('#supermenu').addClass('respmedium');
	} else if (supermenu_width < 768) {
		$('#supermenu').removeClass('respmedium');
		$('#supermenu').removeClass('superbig');
		$('#supermenu').addClass('respsmall');
	}
	$('#supermenu ul > li > a + div').each(function(index, element) {	
		if (!$(this).hasClass('withflyout')) {
			if($(this).data("width")) {
				$(this).css('width', $(this).data("width")+'px');
			} else {
				$(this).css('width', '1200px');
			}
		}
		$(this).css('margin-left', '0px');
		if ($(this).outerWidth(false) > (supermenu_width)) {
			$(this).css('width', (Math.round(supermenu_width)) + 'px');
		}
		var supermenu = $('#supermenu').offset();
		var ddown = $(this).parent();
		
		var i = (ddown.offset().left + $(this).outerWidth(false)) - (supermenu.left + supermenu_width);
		if (i > 0) {
			$(this).css('margin-left', '-' + (i) + 'px');
		}
		if ($(this).hasClass('flytoleft')) {
			var offset_right = ($(window).width() - (ddown.offset().left + ddown.outerWidth(false)));
			var supermenu_right = ($(window).width() - (supermenu.left + supermenu_width));
			var y = offset_right - supermenu_right;
			var z = supermenu_width - (200 + y);
			if($(this).find('.inflyouttoright').outerWidth(false) > z ) {
				$(this).find('.inflyouttoright').css('width', (z - 13) + 'px');
			}
		} else {
			var y = ddown.offset().left - supermenu.left;
			var z = supermenu_width - (200 + y);
			if($(this).find('.inflyouttoright').outerWidth(false) > z ) {
				$(this).find('.inflyouttoright').css('width', (z) + 'px');
			}
		}
	});
  }
 }
 var timpderesize;
 $(window).resize(function() {
    clearTimeout(timpderesize);
    timpderesize = setTimeout(superdropdownalign, 500);
 });
 if (window.addEventListener) {	
	window.addEventListener("orientationchange", superdropdownalign, false);
 }
 
$(document).ready(function() {
    superdropdownalign();
	setTimeout(function(){ superdropdownalign(); }, 800);
	setTimeout(function(){ superdropdownalign(); }, 1600);

	$( '#supermenu.superbig > ul > li.mkids > a' ).doubleTapToGo();

	$( '#supermenu.superbig .hasflyout > a' ).doubleTapToGo();

	var isdown = $('#supermenu > ul').hasClass('exped');

	$('#supermenu a.mobile-trigger').on('click', function() {
		$('#supermenu > ul').toggleClass('exped');
	});
	
	$('#supermenu a.superdropper').on('click', function(event) {
		event.preventDefault();
		$(this).parent().toggleClass('exped');
	});
});
})(jQuery);

/*  The below code by:
	By Osvaldas Valutis, www.osvaldas.info
	Available for use under the MIT License
*/
;(function( $, window, document, undefined )
{
	$.fn.doubleTapToGo = function( params )
	{
		if( !( 'ontouchstart' in window ) &&
			!navigator.msMaxTouchPoints &&
			!navigator.userAgent.toLowerCase().match( /windows phone os 7/i ) ) return false;

		this.each( function()
		{
			var curItem = false;

			$( this ).on( 'click', function( e )
			{
				var item = $( this );
				if( item[ 0 ] != curItem[ 0 ] )
				{
					e.preventDefault();
					curItem = item;
				}
			});

			$( document ).on( 'click touchstart MSPointerDown PointerDown', function( e )
			{
				//e.stopPropagation();
				var resetItem = true,

					parents	  = $( e.target ).parents();
					console.log(e.target);

				for( var i = 0; i < parents.length; i++ )
					if( parents[ i ] == curItem[ 0 ] || e.target == curItem[ 0 ])
						resetItem = false;

				if( resetItem )
					curItem = false;
			});
		});
		return this;
	};
})( jQuery, window, document );
