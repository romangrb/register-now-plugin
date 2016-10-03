var rioc_tickets_rsvp = {
	num_attendees: 0,
	event: {}
};

(function( $, my ) {
	'use strict';

	my.init = function() {
		this.$rsvp = $( '.rioc-events-tickets-rsvp' );
		this.attendee_template = $( document.getElementById( 'rioc-tickets-rsvp-tmpl' ) ).html();

		this.$rsvp.on( 'change', '.rioc-ticket-quantity', this.event.quantity_changed );

		this.$rsvp.closest( '.cart' )
			.on( 'submit', this.event.handle_submission );

		$( '.rioc-rsvp-list' ).on( 'click', '.attendee-meta-row .toggle', function() {
			$( this )
				.toggleClass( 'on' )
				.siblings( '.attendee-meta-details' )
				.slideToggle();
		});
	};

	my.quantity_changed = function( $quantity ) {
		var i = 0;
		var $rsvp = $quantity.closest( '.rioc-events-tickets-rsvp' );
		var quantity = parseInt( $quantity.val(), 10 );

		if ( ! quantity ) {
			$rsvp.removeClass( 'rioc-tickets-has-rsvp' );
		} else {
			$rsvp.addClass( 'rioc-tickets-has-rsvp' );
		}
	};

	my.validate_submission = function() {
		var $name = $( document.getElementById( 'rioc-tickets-full-name' ) );
		var $email = $( document.getElementById( 'rioc-tickets-email' ) );

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

			$form.addClass( 'rioc-rsvp-message-display' );
			$form.find( '.rioc-rsvp-message-confirmation-error' ).show();

			$( 'html, body').animate({
				scrollTop: $form.offset().top
			}, 300 );
			return false;
		}
	};

	$( function() {
		my.init();
	} );
})( jQuery, rioc_tickets_rsvp );