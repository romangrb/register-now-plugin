jQuery( document ).ready( function() {

	var maxHeight = 0;
	jQuery( "div.e-rn-addon .caption" ).each( function() {
		var h = jQuery( this ).height();
		maxHeight = h > maxHeight ? h : maxHeight;
	} );

	jQuery( "div.e-rn-addon:not(.first) .caption" ).css( 'height', maxHeight );
} );