<?php

$generalTabFields = array(
	'info-start'                    => array(
		'type' => 'html',
		'html' => '<div id="e-rn--info"><img src="' . plugins_url( 'resources/images/e-rn-@2x.png', dirname( __FILE__ ) ) . '" alt="Modern Tribe Inc." title="Modern Tribe Inc.">',
	),
	'event-tickets-info' => array(
		'type'        => 'html',
		'html'        => '<p>' . sprintf( esc_html__( 'Thank you for using Event Tickets! All of us at Modern Tribe sincerely appreciate your support and we\'re excited to see you using our plugins. Check out our handy %1$sNew User Primer%2$s to get started.', 'e-rn-common' ), '<a href="http://m.tri.be/18nd">', '</a>' ) . '</p>',
		'conditional' => ! class_exists( 'E_Register_Now__Events__Main' ),
	),
	'event-tickets-upsell-info' => array(
		'type'        => 'html',
		'html'        => '<p>' . sprintf( esc_html__( 'Optimize your site\'s event listings with %1$sThe Events Calendar%2$s, our free calendar plugin. Looking for additional functionality including recurring events, user-submission, advanced ticket sales and more? Check out our %3$spremium add-ons%4$s.', 'e-rn-common' ), '<a href="http://m.tri.be/18x6">', '</a>', '<a href="http://m.tri.be/18x5">', '</a>' ) . '</p>',
		'conditional' => ! class_exists( 'E_Register_Now__Events__Main' ),
	),
	'upsell-info'                   => array(
		'type'        => 'html',
		'html'        => '<p>' . esc_html__( 'Looking for additional functionality including recurring events, custom meta, community events, ticket sales and more?', 'e-rn-common' ) . ' <a href="' . E_Register_Now__Main::$tec_url . 'products/?utm_source=generaltab&utm_medium=plugin-tec&utm_campaign=in-app">' . esc_html__( 'Check out the available add-ons', 'e-rn-common' ) . '</a>.</p>',
		'conditional' => ( ! defined( 'TRIBE_HIDE_UPSELL' ) || ! TRIBE_HIDE_UPSELL ) && class_exists( 'E_Register_Now__Events__Main' ),
	),
	'donate-link-heading'           => array(
		'type'  => 'heading',
		'label' => esc_html__( 'We hope our plugin is helping you out.', 'e-rn-common' ),
		'conditional' => class_exists( 'E_Register_Now__Events__Main' ),
	),
	'donate-link-info'              => array(
		'type'        => 'html',
		'html'        => '<p>' . esc_html__( 'Are you thinking "Wow, this plugin is amazing! I should say thanks to Modern Tribe for all their hard work." The greatest thanks we could ask for is recognition. Add a small text-only link at the bottom of your calendar pointing to The Events Calendar project.', 'e-rn-common' ) . '<br><a href="' . esc_url( plugins_url( 'resources/images/donate-link-screenshot.jpg', dirname( __FILE__ ) ) ) . '" class="thickbox">' . esc_html__( 'See an example of the link', 'e-rn-common' ) . '</a>.</p>',
		'conditional' => ! class_exists( 'E_Register_Now__Events__Pro__Main' ) && class_exists( 'E_Register_Now__Events__Main' ),
	),
	'donate-link-pro-info'          => array(
		'type'        => 'html',
		'html'        => '<p>' . esc_html__( 'Are you thinking "Wow, this plugin is amazing! I should say thanks to Modern Tribe for all their hard work." The greatest thanks we could ask for is recognition. Add a small text only link at the bottom of your calendar pointing to The Events Calendar project.', 'e-rn-common' ) . '<br><a href="' . esc_url( plugins_url( 'resources/images/donate-link-pro-screenshot.jpg', dirname( __FILE__ ) ) ) . '" class="thickbox">' . esc_html__( 'See an example of the link', 'e-rn-common' ) . '</a>.</p>',
		'conditional' => class_exists( 'E_Register_Now__Events__Pro__Main' ),
	),
	'donate-link'                   => array(
		'type'            => 'checkbox_bool',
		'label'           => esc_html__( 'Show The Events Calendar link', 'e-rn-common' ),
		'default'         => false,
		'validation_type' => 'boolean',
		'conditional' => class_exists( 'E_Register_Now__Events__Main' ),
	),
	'info-end'                      => array(
		'type' => 'html',
		'html' => '</div>',
	),
	'e-rn-form-content-start'      => array(
		'type' => 'html',
		'html' => '<div class="e-rn-settings-form-wrap">',
	),
);

if ( is_super_admin() ) {
	$generalTabFields['debugEvents'] = array(
		'type'            => 'checkbox_bool',
		'label'           => esc_html__( 'Debug mode', 'e-rn-common' ),
		'default'         => false,
		'validation_type' => 'boolean',
	);
	$generalTabFields['debugEventsHelper'] = array(
		'type'        => 'html',
		'html'        => '<p class="e-rn-field-indent e-rn-field-description description" style="max-width:400px;">' . sprintf( esc_html__( 'Enable this option to log debug information. By default this will log to your server PHP error log. If you\'d like to see the log messages in your browser, then we recommend that you install the %s and look for the "Tribe" tab in the debug output.', 'e-rn-common' ), '<a href="http://wordpress.org/extend/plugins/debug-bar/" target="_blank">' . esc_html__( 'Debug Bar Plugin', 'e-rn-common' ) . '</a>' ) . '</p>',
		'conditional' => ( '' != get_option( 'permalink_structure' ) ),
	);
}

// Closes form
$generalTabFields['e-rn-form-content-end'] = array(
	'type' => 'html',
	'html' => '</div>',
);


$generalTab = array(
	'priority' => 10,
	'fields'   => apply_filters( 'e_rn_general_settings_tab_fields', $generalTabFields ),
);

