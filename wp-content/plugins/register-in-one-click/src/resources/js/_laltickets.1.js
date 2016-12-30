(function( window, $, undefined ) {
	'use strict';
	
	$( document ).ready( function() {
		var $event_pickers = $( '#rioc-event-datepickers' ),
			$rioc_tickets = $( '#tribetickets' ),
			$tickets_container = $( '#event_tickets' ),
			$enable_global_stock = $( "#rioc-tickets-enable-global-stock" ),
			$global_stock_level = $( "#rioc-tickets-global-stock-level" ),
			global_stock_setting_changed = false,
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
			'spin.rioc_event': function( event, action ) {
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
			'clear.rioc_event': function() {
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
			'focus.rioc_event': function() {
				$body.animate( {
					scrollTop: $tickets_container.offset().top - 50
				}, 500 );
			},

			/**
			 * Sets/Swaps out the name & id attributes on Advanced ticket meta fields so we don't have (or submit)
			 * duplicate fields
			 *
			 * @return {void}
			 */

			'set-global-stock-fields.rioc_event': function() {
				var provider_class   = currently_selected_provider();
				var $provider_fields = $( this ).find( '#ticket_form').find( '.ticket_advanced_' + provider_class );

				if ( $provider_fields.length < 1 ) {
					return;
				}

				var $normal_stock_field  = $provider_fields.filter( '.stock' );
				var $global_stock_fields = $provider_fields.filter( '.global-stock-mode' );
				var $sales_cap_field     = $global_stock_fields.filter( '.sales-cap-field' );

				var mode     = $( '#ticket_global_stock' ).val();
				var enabled  = global_stock_enabled();

				// Show or hide global (and normal, "per-ticket") stock settings as appropriate
				$global_stock_level.toggle( enabled );
				$global_stock_fields.toggle( global_stock_enabled() );
				$normal_stock_field.toggle( ! enabled );

				// If global stock is not enabled we need go no further
				if ( ! enabled ) {
					return;
				}

				// Otherwise, toggle on and off the relevant stock quantity fields
				switch ( mode ) {
					case "global":
						$sales_cap_field.hide();
						$normal_stock_field.hide();
						break;
					case "capped":
						$sales_cap_field.show();
						$normal_stock_field.hide();
						break;
					case "own":
						$sales_cap_field.hide();
						$normal_stock_field.show();
						break;
				}
			}
		} );

		if ( $event_pickers.length ) {
			startofweek = $event_pickers.data( 'startofweek' );
		}

		var datepickerOpts = {
			dateFormat     : 'yy-mm-dd',
			showAnim       : 'fadeIn',
			changeMonth    : true,
			changeYear     : true,
			numberOfMonths : 3,
			firstDay       : startofweek,
			showButtonPanel: true,
			onChange       : function() {
			},
			onSelect       : function( dateText, inst ) {
				var the_date = $.datepicker.parseDate( 'yy-mm-dd', dateText );
				switch (inst.id) {
					case 'ticket_start_date':
						$( '#ticket_end_date' ).datepicker( 'option', 'minDate', the_date );
						(the_date) ? $( '.ticket_start_time' ).show() : $( '.ticket_start_time' ).hide();
					break;
					case 'ticket_end_date':
						$( '#ticket_start_date' ).datepicker( 'option', 'minDate', the_date );
						(the_date) ? $( '.ticket_end_time' ).show() : $( '.ticket_end_time' ).hide();
					break;
					case 'reg_period_start_date':
						$( '#reg_period_end_date' ).datepicker( 'option', 'minDate', the_date );
						(the_date) ? $( '.reg_period_start_time' ).show() : $( '.reg_period_start_time' ).hide();
					break;
					case 'reg_period_end_date':
						$( '#reg_period_start_date' ).datepicker( 'option', 'minDate', the_date );
						(the_date) ? $( '.reg_period_end_time' ).show() : $( '.reg_period_end_time' ).hide();
					break;
					
				}
			}
		};

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
		
		// check if id exist another code will not run
		if ( $( '#ticket_start_date' ) ){
			$( '#ticket_start_date' ).datepicker( datepickerOpts ).keyup( function( e ) {
				if ( e.keyCode === 8 || e.keyCode === 46 ) {
					$.datepicker._clearDate( this );
				}
			} );
		}
		
		if ( $( '#ticket_end_date' ) ){
			$( '#ticket_end_date' ).datepicker( datepickerOpts ).keyup( function( e ) {
				if ( e.keyCode === 8 || e.keyCode === 46 ) {
					$.datepicker._clearDate( this );
				}
			} );
		}

		/**
		 * Indicates if the "enable global stock" field has been checked.
		 *
		 * @returns boolean
		 */
		function global_stock_enabled() {
			return $enable_global_stock.prop( "checked" );
		}

		/**
		 * Show or hide global stock fields and settings as appropriate.
		 */
		function show_hide_global_stock() {
			global_stock_setting_changed = true;
			$rioc_tickets.trigger( 'set-global-stock-fields.rioc_event' );
		}

		/**
		 * Show or hide the appropriate set of provider-specific fields.
		 */
		function show_hide_advanced_fields() {
			$( 'tr.ticket_advanced' ).hide();
			$( 'tr.ticket_advanced_' + currently_selected_provider() + ':not(.sale_price)' ).show();
			$rioc_tickets.trigger( 'set-advanced-fields.rioc_event' );
			$( document.getElementById( 'tribetickets' ) ).trigger( 'ticket-provider-changed.rioc_event' );
		}

		/**
		 * Returns the currently selected ticketing provider.
		 *
		 * @return string
		 */
		function currently_selected_provider() {
			var $checked_provider = $( 'input[name="ticket_provider"]:checked' );
			return ( $checked_provider.length > 0 )
				? $checked_provider[0].value
				: "";
		}

		// Show or hide the global stock level as appropriate, both initially and thereafter
		$enable_global_stock.change( show_hide_global_stock );
		$enable_global_stock.trigger( 'change' );

		// Triggering a change event will falsely set the global_stock_setting_changed flag to
		// true - undo this as it is a one-time false positive
		global_stock_setting_changed = false;

		/* Show the advanced metabox for the selected provider and hide the others on selection change */
		$( 'input[name=ticket_provider]:radio' ).change( function() {
			show_hide_advanced_fields();
		} );

		/* Show the advanced metabox for the selected provider and hide the others at ready */
		$( 'input[name=ticket_provider]:checked' ).each( function() {
			show_hide_advanced_fields();
		} );

		/* "Add a ticket" link action */
		$( 'a#ticket_form_toggle' ).click( function( e ) {
			$( 'h4.ticket_form_title_edit' ).hide();
			$( 'h4.ticket_form_title_add' ).show();
			$( this ).hide();
			$rioc_tickets
				.trigger( 'clear.rioc_event' )
				.trigger( 'set-advanced-fields.rioc_event' )
				.trigger( 'focus.rioc_event' );
			$( '#ticket_form' ).show();
			$( document.getElementById( 'tribetickets' ) ).trigger( 'ticket-provider-changed.rioc_event' );
			e.preventDefault();
		} );

		/* "Cancel" button action */
		$( '#ticket_form_cancel' ).click( function() {
			$rioc_tickets
				.trigger( 'clear.rioc_event' )
				.trigger( 'set-advanced-fields.rioc_event' )
				.trigger( 'focus.rioc_event' );
		} );

		/* "Save Ticket" button action */
		$( '#ticket_form_save' ).click( function( e ) {
			var $form = $( '#ticket_form_table' ),
				type = $form.find( '#ticket_provider:checked' ).val(),
				$rows = $form.find( '.ticket, .ticket_advanced_meta, .ticket_advanced_' + type );

			$rioc_tickets.trigger( 'save-ticket.rioc_event', e ).trigger( 'spin.rioc_event', 'start' );

			var params = {
				action  : 'rioc-ticket-add-' + $( 'input[name=ticket_provider]:checked' ).val(),
				// formdata: $rows.find( '.ticket_field' ).serialize(),
				formdata: "message1=mess",
				post_ID : $( '#post_ID' ).val(),
				nonce   : TribeTickets.add_ticket_nonce
			};

			$.post(
				ajaxurl,
				params,
				function( response ) {
					$rioc_tickets.trigger( 'saved-ticket.rioc_event', response );

					if ( response.success ) {
						$rioc_tickets.trigger( 'clear.rioc_event' );
						$( 'td.ticket_list_container' ).empty().html( response.data.html );
						$( '.ticket_time' ).hide();
					}
				},
				'json'
			).complete( function() {
			console.log(params);
				$rioc_tickets.trigger( 'spin.rioc_event', 'stop' ).trigger( 'focus.rioc_event' );
			} );

		} );

		/* "Delete Ticket" link action */

		$rioc_tickets.on( 'click', '.ticket_delete', function( e ) {

			e.preventDefault();

			$rioc_tickets.trigger( 'delete-ticket.rioc_event', e ).trigger( 'spin.rioc_event', 'start' );

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
					$rioc_tickets.trigger( 'deleted-ticket.rioc_event', response );

					if ( response.success ) {
						$rioc_tickets.trigger( 'clear.rioc_event' );
						$( 'td.ticket_list_container' ).empty().html( response.data );
					}
				},
				'json'
			).complete( function() {
				$rioc_tickets.trigger( 'spin.rioc_event', 'stop' );
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

				$rioc_tickets.trigger( 'spin.rioc_event', 'start' );

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
							.trigger( 'clear.rioc_event' )
							.trigger( 'set-advanced-fields.rioc_event' )
							.trigger( 'edit-ticket.rioc_event', response );

						var regularPrice = response.data.price;
						var salePrice    = regularPrice;
						var onSale       = false;

						if ( 'undefined' !== typeof response.data.on_sale && response.data.on_sale ) {
							onSale       = true;
							regularPrice = response.data.regular_price;
						}
						console.log(response);
						
						$( '#ticket_id' ).val( response.data.ID );
						$( '#ticket_name' ).val( response.data.name );
						$( '#ticket_description' ).val( response.data.description );
						$( '#primary_key' ).val( response.data.primary_key );
						$( '#message1' ).val( response.data.message1 );
						$( '#message2' ).val( response.data.message2 );
						
						(response.data.event_enabled) ? $( '#event_enabled' ).prop('checked', true) : $( '#event_enabled' ).prop('checked', false);
						
						
						if ( onSale ) {
							$( '.ticket_advanced_' + response.data.provider_class + '.sale_price' ).show();
						}
						
						function getDateFromStr(d){
							return d.substring( 0, 10 );
						}
						
						var start_date = getDateFromStr(response.data.start_date);
						var end_date = getDateFromStr(response.data.end_date);

						$( '#ticket_start_date' ).val( start_date );
						$( '#ticket_end_date' ).val( end_date );
						
						var sl_reg_start = {
							'time':'.reg_period_start_time',
							'hour':'#reg_period_start_hour',
							'meridian':'#reg_period_start_meridian',
						},
						sl_reg_end = {
							'time':'.reg_period_end_time',
							'hour':'#reg_period_end_hour',
							'meridian':'#reg_period_end_meridian',
							'start_minute':'#reg_period_start_minute',
							'end_minute':'#reg_period_end_minute',
							'start_date':response.data.reg_period_start_date
						},
						sl_time_start = {
							'time':'.ticket_start_time',
							'hour':'#ticket_start_hour',
							'meridian':'#ticket_start_meridian',
						},
						sl_time_end = {
							'time':'.ticket_end_time',
							'hour':'#ticket_end_hour',
							'meridian':'#ticket_end_meridian',
							'start_minute':'#ticket_start_minute',
							'end_minute':'#ticket_end_minute',
							'start_date':response.data.start_date
						};

						var $start_meridian = $( document.getElementById( 'ticket_start_meridian' ) ),
						      $end_meridian = $( document.getElementById( 'ticket_end_meridian' ) );

						if ( response.data.start_date ) {
							showTime(response.data.start_date, true, $start_meridian, sl_time_start);
						}
						
						if ( response.data.end_date ) {
							showTime(response.data.end_date, true, $end_meridian, sl_time_end);
						}

						var $ticket_advanced = $( 'tr.ticket_advanced input' );
						$ticket_advanced.data( 'name', $ticket_advanced.attr( 'name' ) ).attr( {
							'name': '',
							'id': ''
						} );
						
						$( 'tr.ticket_advanced' ).remove();
						$( 'tr.ticket.bottom' ).before( response.data.advanced_fields );

						// trigger a change event on the provider radio input so the advanced fields can be re-initialized
						$( 'input:radio[name=ticket_provider]' ).filter( '[value=' + response.data.provider_class + ']' ).click();
						$( 'input[name=ticket_provider]:radio' ).change();

						// set the prices after the advanced fields have been added to the form
						var $ticket_price = $rioc_tickets.find( '#ticket_price' );
						$ticket_price.val( regularPrice );

						if ( 'undefined' !== typeof response.data.disallow_update_price_message ) {
							$ticket_price.siblings( '.no-update-message' ).html( response.data.disallow_update_price_message );
						} else {
							$ticket_price.siblings( '.no-update-message' ).html( '' );
						}

						if ( 'undefined' !== typeof response.data.can_update_price && ! response.data.can_update_price ) {
							$ticket_price.prop( 'disabled', 'disabled' );
							$ticket_price.siblings( '.description' ).hide();
							$ticket_price.siblings( '.no-update-message' ).show();
						} else {
							$ticket_price.removeProp( 'disabled' );
							$ticket_price.siblings( '.description' ).show();
							$ticket_price.siblings( '.no-update-message' ).hide();
						}

						var $sale_field = $rioc_tickets.find( '#ticket_sale_price' );

						if ( onSale ) {
							$sale_field
								.val( salePrice )
								.closest( 'tr' )
								.show();
						} else {
							$sale_field.closest( 'tr' ).hide();
						}

						if ( 'undefined' !== typeof response.data.purchase_limit && response.data.purchase_limit ) {
							$( '#ticket_purchase_limit' ).val( response.data.purchase_limit );
						}

						$rioc_tickets.find( '.bumpdown-trigger' ).bumpdown();
						$rioc_tickets.find( '.bumpdown' ).hide();

						$( 'a#ticket_form_toggle' ).hide();
						$( '#ticket_form' ).show();

						$rioc_tickets
							.trigger( 'set-advanced-fields.rioc_event' )
							.trigger( 'edit-ticket.rioc_event' );

					},
					'json'
				).complete( function() {
					$rioc_tickets.trigger( 'spin.rioc_event', 'stop' ).trigger( 'focus.rioc_event' );
				} );

			} )
			.on( 'click', '#rioc_ticket_header_image', function( e ) {
				e.preventDefault();
				ticketHeaderImage.uploader( '', '' );
			} );


		var $remove = $( '#rioc_ticket_header_remove' );
		var $preview = $( '#rioc_ticket_header_preview' );

		if ( $preview.find( 'img' ).length ) {
			$remove.show();
		}

		/**
		 * Track changes to the global stock level. Changes to the global stock
		 * checkbox itself is handled elsewhere.
		 */
		$global_stock_level.change( function() {
			global_stock_setting_changed = true;
		} );

		/**
		 * Unset the global stock settings changed flag if the post is being
		 * saved/updated (no need to trigger a confirmation dialog in these
		 * cases).
		 */
		$( 'input[type="submit"]' ).click( function() {
			global_stock_setting_changed = false;
		} );

		/**
		 * If the user attempts to nav away without saving global stock setting
		 * changes then try to bring this to their attention!
		 */
		$( window ).on( 'beforeunload', function() {
			// If the global stock settings have not changed, do not interfere
			if ( ! global_stock_setting_changed ) {
				return;
			}

			// We can't trigger a confirm() dialog from within this action but returning
			// a string should achieve effectively the same result
			return rioc_global_stock_admin_ui.nav_away_msg;

		} );

		

		
	} );

})( window, jQuery, undefined );