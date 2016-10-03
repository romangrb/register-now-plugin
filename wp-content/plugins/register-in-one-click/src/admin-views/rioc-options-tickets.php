<?php

$post_types_to_ignore = apply_filters( 'e_rn_tickets_settings_post_type_ignore_list', array(
	'attachment',
) );

$all_post_type_objects = get_post_types( array( 'public' => true ), 'objects' );
$all_post_types = array();

foreach ( $all_post_type_objects as $post_type => $post_type_object ) {
	$should_ignore = false;

	foreach ( $post_types_to_ignore as $ignore ) {
		if ( preg_match( '/' . preg_quote( $ignore ) . '/', $post_type ) ) {
			$should_ignore = true;
			break;
		}
	}

	if ( $should_ignore ) {
		continue;
	}

	$all_post_types[ $post_type ] = $post_type_object->labels->singular_name;
}

$all_post_types = apply_filters( 'e_rn_tickets_settings_post_types', $all_post_types );
$options = get_option( E_Register_Now__Main::OPTIONNAME, array() );

/**
 * List of ticketing solutions that support login requirements (ie, disabling or
 * enabling the ticket form according to whether a user is logged in or not).
 *
 * @param array $ticket_systems
 */
$ticket_addons = apply_filters( 'e_rn_tickets_settings_systems_supporting_login_requirements', array(
	'event-tickets_rsvp' => __( 'Require users to log in before they RSVP', 'event-tickets' ),
) );

$tickets_tab = array(
	'priority' => 20,
	'fields' => apply_filters(
		'e_rn_tickets_settings_tab_fields',
		array(
			'rioc-form-content-start' => array(
				'type' => 'html',
				'html' => '<div class="rioc-settings-form-wrap">',
			),
			'tickets-title' => array(
				'type' => 'html',
				'html' => '<h3>' . esc_html__( 'Ticket Settings', 'event-tickets' ) . '</h3>',
			),
			'ticket-enabled-post-types' => array(
				'type' => 'checkbox_list',
				'label' => esc_html__( 'Post types that can have tickets', 'event-tickets' ),
				// only set the default to e_rn_events if the ticket-endabled-post-types index has never been saved
				'default' => array_key_exists( 'ticket-enabled-post-types', $options ) ? false : 'e_rn_events',
				'options' => $all_post_types,
				'validation_type' => 'options_multi',
				'can_be_empty' => true,
			),
			'ticket-authentication-requirements-heading' => array(
				'type' => 'html',
				'html' => '<h3>' . __( 'Login requirements', 'event-tickets' ) . '</h3>',
			),
			'ticket-authentication-requirements-advice' => array(
				'type' => 'html',
				'html' => '<p>' . sprintf(
						__( 'You can require that users log into your site before they are able to RSVP (or buy tickets). Please review your WordPress Membership option (via the General Settings admin screen) before adjusting this setting.', 'event-tickets' ),
						'<a href="' . get_admin_url( null, 'options-general.php' ) . '" target="_blank">',
						'</a>'
					) . '</p>',
			),
			'ticket-authentication-requirements' => array(
				'type' => 'checkbox_list',
				'options' => $ticket_addons,
				'validation_type' => 'options_multi',
				'can_be_empty' => true,
			),
			'rioc-form-content-end' => array(
				'type' => 'html',
				'html' => '</div>',
			),
		)
	),
);
