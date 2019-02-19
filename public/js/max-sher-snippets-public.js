(function( $ ) {
	'use strict';
	$('.mss_snippet_btn').on('click', function(e){
		e.preventDefault();
		var el = $(this);

		el.siblings('.mss_snippet_excerpt').toggle();
		el.siblings('.mss_snippet_full').toggle();
		
		if ( el.text() === 'Read More' ) {
			el.text('Close');
		} else {
			el.text('Read More');
		}
		
		el.parent().siblings().children('.mss_snippet_excerpt').slideDown('fast');
		el.parent().siblings().children('.mss_snippet_full').slideUp('fast');
		el.parent().siblings().children('a').text('Read More');
		return false;
		
	});

})( jQuery );
