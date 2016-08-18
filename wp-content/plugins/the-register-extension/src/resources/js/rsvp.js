var tribe_tickets_rsvp = {
	num_attendees: 0,
	event: {}
};

(function( $, my ) {
	'use strict';

	my.init = function() {
		this.$rsvp = $( '.ern-events-tickets-rsvp' );
		this.attendee_template = $( document.getElementById( 'ern-tickets-rsvp-tmpl' ) ).html();

		this.$rsvp.on( 'change', '.ern-ticket-quantity', this.event.quantity_changed );

		this.$rsvp.closest( '.cart' )
			.on( 'submit', this.event.handle_submission );

		$( '.ern-rsvp-list' ).on( 'click', '.attendee-meta-row .toggle', function() {
			$( this )
				.toggleClass( 'on' )
				.siblings( '.attendee-meta-details' )
				.slideToggle();
		});
	};

	my.quantity_changed = function( $quantity ) {
		var i = 0;
		var $rsvp = $quantity.closest( '.ern-events-tickets-rsvp' );
		var quantity = parseInt( $quantity.val(), 10 );

		if ( ! quantity ) {
			$rsvp.removeClass( 'ern-tickets-has-rsvp' );
		} else {
			$rsvp.addClass( 'ern-tickets-has-rsvp' );
		}
	};

	my.validate_submission = function() {
		var $name = $( document.getElementById( 'ern-tickets-full-name' ) );
		var $email = $( document.getElementById( 'ern-tickets-email' ) );

		if ( ! $.trim( $name.val() ).length || ! $.trim( $email.val() ).length ) {
			return false;
		}

		return true;
	};

	my.event.quantity_changed = function() {
		my.quantity_changed( $( this ) );
	};

	my.event.handle_submission = function( e ) {
		if ( ! my.validate_submission() ) {
			e.preventDefault();
			var $form = $( this ).closest( 'form' );

			$form.addClass( 'ern-rsvp-message-display' );
			$form.find( '.ern-rsvp-message-confirmation-error' ).show();

			$( 'html, body').animate({
				scrollTop: $form.offset().top
			}, 300 );
			return false;
		}
	};

	$( function() {
		my.init();
	} );
})( jQuery, tribe_tickets_rsvp );