(function( window, $ ) {
	'use strict';

	$( document ).ready( function() {
		var $event_pickers = $( '#rioc-event-datepickers' ),
			$rioc_tickets = $( '#tribetickets' ),
			$tickets_container = $( '#event_tickets' ),
			$body = $( 'html, body' ),
			startofweek = 0;

		$rioc_tickets.on( {
			/**
			 * Makes a Visual Spining thingy appear on the Tickets metabox.
			 * Also prevents user Action on the metabox elements.
			 *
			 * @param  {jQuery.event} event  The jQuery event
			 * @param  {string} action You can use `start` or `stop`
			 * @return {void}
			 */
			'spin.event': function( event, action ) {
				if ( typeof action === 'undefined' || $.inArray( action, [ 'start', 'stop' ] ) ){
					action = 'stop';
				}

				if ( 'stop' === action ) {
					$tickets_container.css( 'opacity', '1' )
						.find( '#rioc-loading' ).hide();
				} else {
					$tickets_container.css( 'opacity', '0.5' )
						.find( '#rioc-loading' ).show();

				}
			},

			/**
			 * Clears the Form fields the correct way
			 *
			 * @return {void}
			 */
			'clear.event': function() {
				var $this = $( this ),
					$ticket_form = $this.find( '#ticket_form'),
					$ticket_settings = $ticket_form.find( "tr:not(.event-wide-settings)" );

				$this.find( 'a#ticket_form_toggle' ).show();

				$ticket_settings.find( 'input:not(:button):not(:radio):not(:checkbox):not([type="hidden"]), textarea' ).val( '' );
				$ticket_settings.find( 'input:checkbox' ).attr( 'checked', false );
				$ticket_settings.find( '#ticket_id' ).val( '' );

				$this.find( '#ticket_form input[name="show_attendee_info"]' ).prop( 'checked', false ).change();

				// some fields may have a default value we don't want to lose after clearing the form
				$this.find( 'input[data-default-value]' ).each( function() {
					var $current_field = $( this );
					$current_field.val( $current_field.data( 'default-value' ) );
				} );

				// Reset the min/max datepicker settings so that they aren't inherited by the next ticket that is edited
				$this.find( '#ticket_start_date' ).datepicker( 'option', 'maxDate', null );
				$this.find( '#ticket_end_date' ).datepicker( 'option', 'minDate', null );
				// Tiket period prop
				$this.find( '#reg_period_start_date' ).datepicker( 'option', 'maxDate', null );
				$this.find( '#reg_period_end_date' ).datepicker( 'option', 'minDate', null );

				$this.find( '.ticket_start_time, .ticket_end_time, .ticket.sale_price' ).hide();

				$this.find( '#ticket_price' ).removeProp( 'disabled' )
					.siblings( '.no-update-message' ).html( '' ).hide()
					.end().siblings( '.description' ).show();

				$('#rioc-tickets-attendee-sortables').empty();
				$('.rioc-tickets-attendee-saved-fields').show();

				$ticket_form.hide();
			},

			/**
			 * Scrolls to the Tickets container, to show it when required
			 *
			 * @return {void}
			 */
			'focus.event': function() {
				$body.animate( {
					scrollTop: $tickets_container.offset().top - 50
				}, 500 );
			},

		} );

		if ( $event_pickers.length ) {
			startofweek = $event_pickers.data( 'startofweek' );
		}

		// var datepickerOpts = {
		// 	dateFormat     : 'yy-mm-dd',
		// 	showAnim       : 'fadeIn',
		// 	changeMonth    : true,
		// 	changeYear     : true,
		// 	numberOfMonths : 3,
		// 	firstDay       : startofweek,
		// 	showButtonPanel: true,
		// 	onChange       : function() {
		// 	},
		// 	onSelect       : function( dateText, inst ) {
		// 		var the_date = $.datepicker.parseDate( 'yy-mm-dd', dateText );
		// 		switch (inst.id) {
		// 			case 'ticket_start_date':
		// 				$( '#ticket_end_date' ).datepicker( 'option', 'minDate', the_date );
		// 				(the_date) ? $( '.ticket_start_time' ).show() : $( '.ticket_start_time' ).hide();
		// 			break;
		// 			case 'ticket_end_date':
		// 				$( '#ticket_start_date' ).datepicker( 'option', 'minDate', the_date );
		// 				(the_date) ? $( '.ticket_end_time' ).show() : $( '.ticket_end_time' ).hide();
		// 			break;
		// 			case 'reg_period_start_date':
		// 				$( '#reg_period_end_date' ).datepicker( 'option', 'minDate', the_date );
		// 				(the_date) ? $( '.reg_period_start_time' ).show() : $( '.reg_period_start_time' ).hide();
		// 			break;
		// 			case 'reg_period_end_date':
		// 				$( '#reg_period_start_date' ).datepicker( 'option', 'minDate', the_date );
		// 				(the_date) ? $( '.reg_period_end_time' ).show() : $( '.reg_period_end_time' ).hide();
		// 			break;
					
		// 		}
		// 	}
		// };

		// $( '#reg_period_start_date' ).datepicker( datepickerOpts ).keyup( function( e ) {
		// 	if ( e.keyCode === 8 || e.keyCode === 46 ) {
		// 		$.datepicker._clearDate( this );
		// 	}
		// } );
		// $( '#reg_period_end_date' ).datepicker( datepickerOpts ).keyup( function( e ) {
		// 	if ( e.keyCode === 8 || e.keyCode === 46 ) {
		// 		$.datepicker._clearDate( this );
		// 	}
		// } );
		
		// $( '#ticket_start_date' ).datepicker( datepickerOpts ).keyup( function( e ) {
		// 	if ( e.keyCode === 8 || e.keyCode === 46 ) {
		// 		$.datepicker._clearDate( this );
		// 	}
		// } );
		// $( '#ticket_end_date' ).datepicker( datepickerOpts ).keyup( function( e ) {
		// 	if ( e.keyCode === 8 || e.keyCode === 46 ) {
		// 		$.datepicker._clearDate( this );
		// 	}
		// } );
		
		/* "Add a ticket" link action */
		$( 'a#ticket_form_toggle' ).click( function( e ) {
			$( 'h4.ticket_form_title_edit' ).hide();
			$( 'h4.ticket_form_title_add' ).show();
			$( this ).hide();
			$rioc_tickets
				.trigger( 'clear.event' )
				.trigger( 'focus.event' );
			$( '#ticket_form' ).show();
			
			e.preventDefault();
		} );

		/* "Cancel" button action */
		$( '#ticket_form_cancel' ).click( function() {
			$rioc_tickets
				.trigger( 'clear.event' )
				.trigger( 'focus.event' );
		} );

		/* "Save Ticket" button action */
		$( '#ticket_form_save' ).click( function( e ) {
			var $form = $( '#ticket_form_table' ),
				type  = $form.find( '#ticket_provider:checked' ).val(),
				$rows = $form.find( '.ticket, .ticket_advanced_' + type );

			$rioc_tickets.trigger( 'save-ticket.event', e ).trigger( 'spin.event', 'start' );

			var params = {
				action  : 'rioc-ticket-add-' + $( 'input[name=ticket_provider]:checked' ).val(),
				// formdata: $rows.find( '.ticket_field' ).serialize(),
				formdata: "primary_key=213",
				post_ID : $( '#post_ID' ).val(),
				nonce   : TribeTickets.add_ticket_nonce
			};
			console.log(params);
			$.post(
				ajaxurl,
				params,
				function( response ) {
					$rioc_tickets.trigger( 'saved-ticket.event', response );

					if ( response.success ) {
						$rioc_tickets.trigger( 'clear.event' );
						// $( 'td.ticket_list_container' ).empty().html( response.data.html );
						$( '.ticket_time' ).hide();
					}
				},
				'json'
			).complete( function() {
				$rioc_tickets.trigger( 'spin.event', 'stop' ).trigger( 'focus.event' );
			} );

		} );

		/* "Delete Ticket" link action */

		$rioc_tickets.on( 'click', '.ticket_delete', function( e ) {

			e.preventDefault();

			$rioc_tickets.trigger( 'delete-ticket.event', e ).trigger( 'spin.event', 'start' );

			var params = {
				action   : 'rioc-ticket-delete-' + $( this ).attr( 'attr-provider' ),
				post_ID  : $( '#post_ID' ).val(),
				ticket_id: $( this ).attr( 'attr-ticket-id' ),
				nonce    : TribeTickets.remove_ticket_nonce
			};

			$.post(
				ajaxurl,
				params,
				function( response ) {
					$rioc_tickets.trigger( 'deleted-ticket.event', response );

					if ( response.success ) {
						$rioc_tickets.trigger( 'clear.event' );
						$( 'td.ticket_list_container' ).empty().html( response.data );
					}
				},
				'json'
			).complete( function() {
				$rioc_tickets.trigger( 'spin.event', 'stop' );
			} );
		} );

		function showTime (time_name, is_start_time, $meridian, slArr) {
		
			var hour = parseInt( time_name.substring( 11, 13 ) ),
				meridian = 'am';
				
			if ( hour > 12 && $meridian.length ) {
				meridian = 'pm';
				hour = parseInt( hour ) - 12;
				hour = ( '0' + hour ).slice( - 2 );
			}
			if ( 12 === hour ) {
				meridian = 'pm';
			}
			if ( 0 === hour && 'am' === meridian ) {
				hour = 12;
			}

			// Return the start hour to a 0-padded string
			hour = hour.toString();
			if ( 1 === hour.length ) {
				hour = '0' + hour;
			}
			
			$( slArr['hour'] ).val( hour );
			$( slArr['meridian'] ).val( meridian );
			
			if (!is_start_time) {
				$( slArr['start_minute'] ).val( slArr['start_date'].substring( 14, 16 ) );
				$( slArr['end_minute'] ).val( time_name.substring( 14, 16 ) );
			}
			$( slArr['time'] ).show();
				
		}
						

		/* "Edit Ticket" link action */
		$rioc_tickets
			.on( 'click', '.ticket_edit', function( e ) {

				e.preventDefault();

				$( 'h4.ticket_form_title_edit' ).show();
				$( 'h4.ticket_form_title_add' ).hide();

				$rioc_tickets.trigger( 'spin.event', 'start' );

				var params = {
					action   : 'rioc-ticket-edit-' + $( this ).attr( 'attr-provider' ),
					post_ID  : $( '#post_ID' ).val(),
					ticket_id: $( this ).attr( 'attr-ticket-id' ),
					nonce    : TribeTickets.edit_ticket_nonce
				};

				$.post(
					ajaxurl,
					params,
					function( response ) {
						$rioc_tickets
							.trigger( 'clear.event' )
							.trigger( 'edit-ticket.event', response );

						if (response.data.ID) $( '#ticket_id' ).val( response.data.ID );
						if (response.data.primary_key) $( '#primary_key' ).val( response.data.primary_key );
						   //(response.data.event_enabled) ? $( '#event_enabled' ).prop('checked', true) : $( '#event_enabled' ).prop('checked', false);
						// if (response.data.name) $( '#ticket_name' ).val( response.data.name );
						// if (response.data.description) $( '#ticket_description' ).val( response.data.description );
						// if (response.data.event_location) $( '#event_location' ).val( response.data.event_location );
						// if (response.data.event_code) $( '#event_code' ).val( response.data.event_code );
						// if (response.data.event_category) $( '#event_category' ).val( response.data.event_category );
						// if (response.data.message1) $( '#message1' ).val( response.data.message1 );
						// if (response.data.message2) $( '#message2' ).val( response.data.message2 );
						// if (response.data.message3) $( '#message3' ).val( response.data.message3 );
						
						function getDateFromStr(d){
							return d.substring( 0, 10 );
						}
						
						// var start_date = getDateFromStr(response.data.start_date);
						// var end_date = getDateFromStr(response.data.end_date);
						// var reg_period_start_date = getDateFromStr(response.data.reg_period_start_date);
						// var reg_period_end_date = getDateFromStr(response.data.reg_period_end_date);
						
						// $( '#ticket_start_date' ).val( start_date );
						// $( '#ticket_end_date' ).val( end_date );
						// $( '#reg_period_start_date' ).val( reg_period_start_date );
						// $( '#reg_period_end_date' ).val( reg_period_end_date );
						
						// var sl_reg_start = {
						// 	'time':'.reg_period_start_time',
						// 	'hour':'#reg_period_start_hour',
						// 	'meridian':'#reg_period_start_meridian',
						// },
						// sl_reg_end = {
						// 	'time':'.reg_period_end_time',
						// 	'hour':'#reg_period_end_hour',
						// 	'meridian':'#reg_period_end_meridian',
						// 	'start_minute':'#reg_period_start_minute',
						// 	'end_minute':'#reg_period_end_minute',
						// 	'start_date':response.data.reg_period_start_date
						// },
						// sl_time_start = {
						// 	'time':'.ticket_start_time',
						// 	'hour':'#ticket_start_hour',
						// 	'meridian':'#ticket_start_meridian',
						// },
						// sl_time_end = {
						// 	'time':'.ticket_end_time',
						// 	'hour':'#ticket_end_hour',
						// 	'meridian':'#ticket_end_meridian',
						// 	'start_minute':'#ticket_start_minute',
						// 	'end_minute':'#ticket_end_minute',
						// 	'start_date':response.data.start_date
						// };

						// var $start_meridian = $(sl_time_start['meridian']),
					 //       $end_meridian = $(sl_time_end['meridian']),
					 //       $reg_period_start_meridian = $(sl_reg_start['meridian']),
					 //       $reg_period_end_meridian = $(sl_reg_end['meridian']);
					        
						// // show time in responce
						
						// if ( response.data.reg_period_start_date ) {
						// 	showTime(response.data.reg_period_start_date, true, $reg_period_start_meridian, sl_reg_start);
						// }

						// if ( response.data.reg_period_end_date ) {
						// 	showTime(response.data.reg_period_end_date, false, $reg_period_end_meridian, sl_reg_end);
						// }
						
						// if ( response.data.start_date ) {
						// 	showTime(response.data.start_date, true, $start_meridian, sl_time_start);
						// }
						
						// if ( response.data.end_date ) {
						// 	showTime(response.data.end_date, true, $end_meridian, sl_time_end);
						// }

						
						$( 'tr.ticket.bottom' ).before( response.data.advanced_fields );

						// trigger a change event on the provider radio input so the advanced fields can be re-initialized
						$( 'input:radio[name=ticket_provider]' ).filter( '[value=' + response.data.provider_class + ']' ).click();
						$( 'input[name=ticket_provider]:radio' ).change();

						$rioc_tickets.find( '.bumpdown-trigger' ).bumpdown();
						$rioc_tickets.find( '.bumpdown' ).hide();

						$( 'a#ticket_form_toggle' ).hide();
						$( '#ticket_form' ).show();

						$rioc_tickets.trigger( 'edit-ticket.event' );

					},
					'json'
				).complete( function() {
					$rioc_tickets.trigger( 'spin.event', 'stop' ).trigger( 'focus.event' );
				} );

			} );


		var $remove = $( '#rioc_ticket_header_remove' );
		var $preview = $( '#rioc_ticket_header_preview' );


	} );

})( window, jQuery );
