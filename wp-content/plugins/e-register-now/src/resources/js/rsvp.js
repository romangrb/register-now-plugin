var e_rn_tickets_rsvp = {
	num_attendees: 0,
	event: {}
};

(function( $, my ) {
	'use strict';

	my.init = function() {
		this.$rsvp = $( '.e-rn-events-tickets-rsvp' );
		this.attendee_template = $( document.getElementById( 'e-rn-tickets-rsvp-tmpl' ) ).html();

		this.$rsvp.on( 'change', '.e-rn-ticket-quantity', this.event.quantity_changed );

		this.$rsvp.closest( '.cart' )
			.on( 'submit', this.event.handle_submission );

		$( '.e-rn-rsvp-list' ).on( 'click', '.attendee-meta-row .toggle', function() {
			$( this )
				.toggleClass( 'on' )
				.siblings( '.attendee-meta-details' )
				.slideToggle();
		});
	};

	my.quantity_changed = function( $quantity ) {
		var i = 0;
		var $rsvp = $quantity.closest( '.e-rn-events-tickets-rsvp' );
		var quantity = parseInt( $quantity.val(), 10 );

		if ( ! quantity ) {
			$rsvp.removeClass( 'e-rn-tickets-has-rsvp' );
		} else {
			$rsvp.addClass( 'e-rn-tickets-has-rsvp' );
		}
	};

	my.validate_submission = function() {
		var $name = $( document.getElementById( 'e-rn-tickets-full-name' ) );
		var $email = $( document.getElementById( 'e-rn-tickets-email' ) );

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

			$form.addClass( 'e-rn-rsvp-message-display' );
			$form.find( '.e-rn-rsvp-message-confirmation-error' ).show();

			$( 'html, body').animate({
				scrollTop: $form.offset().top
			}, 300 );
			return false;
		}
	};

	$( function() {
		my.init();
	} );
})( jQuery, e_rn_tickets_rsvp );