jQuery( document ).ready( function() {

	var maxHeight = 0;
	jQuery( "div.ern-addon .caption" ).each( function() {
		var h = jQuery( this ).height();
		maxHeight = h > maxHeight ? h : maxHeight;
	} );

	jQuery( "div.ern-addon:not(.first) .caption" ).css( 'height', maxHeight );
} );