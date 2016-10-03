<?php
$link = add_query_arg(
	array(
		'utm_campaign' => 'in-app',
		'utm_medium'   => 'plugin-tec',
		'utm_source'   => 'notice',
	), Register_In_One_Click__Main::$tec_url . 'license-keys/'
);

$link = esc_url( $link );

// Explanatory text about license settings for the tab information box
$html = __( '<p>The license key you received when completing your purchase from %1$s will grant you access to support and updates until it expires. You do not need to enter the key below for the plugins to work, but you will need to enter it to get automatic updates. <strong>Find your license keys at <a href="%2$s" target="_blank">%3$s</a></strong>.</p> <p>Each paid add-on has its own unique license key. Simply paste the key into its appropriate field on below, and give it a moment to validate. You know you\'re set when a green expiration date appears alongside a "valid" message.</p> <p>If you\'re seeing a red message telling you that your key isn\'t valid or is out of installs, visit <a href="%4$s" target="_blank">%5$s</a> to manage your installs or renew / upgrade your license.</p><p>Not seeing an update but expecting one? In WordPress, go to <a href="%6$s">Dashboard > Updates</a> and click "Check Again".</p>', 'rioc-common' );

// Expand with extra information for mu network users
if ( is_multisite() ) {
	$network_all_sites_text = sprintf(
		esc_html__( '%1$s Using our plugins in a multisite network? %2$s Please note that your license key will be applied to the entire network, not just this site.', 'rioc-common' ),
		'<strong>',
		'</strong>'
	);

	$network_admin_only = '';

	if ( is_network_admin() ) {
		$network_admin_only = sprintf(
			esc_html__(
				'Only license fields for %1$snetwork activated%2$s plugins will be listed on this screen. ',
				'rioc-common'
			),
			'<strong>',
			'</strong>'
		);
	}

	$html .= "<p> $network_all_sites_text $network_admin_only </p>";
}

$licenses_tab = array(
	'info-start' => array(
		'type' => 'html',
		'html' => '<div id="rioc--info">',
	),
	'info-box-title' => array(
		'type' => 'html',
		'html' => '<h2>' . esc_html__( 'Licenses', 'rioc-common' ) . '</h2>',
	),
	'info-box-description' => array(
		'type' => 'html',
		'html' => sprintf(
			$html,
			Register_In_One_Click__Main::$tec_url,
			$link,
			Register_In_One_Click__Main::$tec_url . 'license-keys/',
			$link,
			Register_In_One_Click__Main::$tec_url . 'license-keys/',
			admin_url( '/update-core.php' )
		),
	),
	'info-end' => array(
		'type' => 'html',
		'html' => '</div>',
	),
	'rioc-form-content-start' => array(
		'type' => 'html',
		'html' => '<div class="rioc-settings-form-wrap">',
	),
	// TODO: Figure out how properly close this wrapper after the license content
	'rioc-form-content-end'   => array(
		'type' => 'html',
		'html' => '</div>',
	),
);
