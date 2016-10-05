<?php
$allTabs = apply_filters( 'rioc_settings_all_tabs', array() );

$networkTab = array(
	'priority'      => 10,
	'network_admin' => true,
	'fields'        => apply_filters(
		'rioc_network_settings_tab_fields', array(
			'info-start'           => array(
				'type' => 'html',
				'html' => '<div id="rioc--info">',
			),
			'info-box-title'       => array(
				'type' => 'html',
				'html' => '<h1>' . esc_html__( 'Network Settings', 'rioc-common' ) . '</h1>',
			),
			'info-box-description' => array(
				'type' => 'html',
				'html' => '<p>' . esc_html__( 'This is where all of the global network settings for Modern Tribe\'s The Events Calendar can be modified.', 'rioc-common' ) . '</p>',
			),
			'info-end'             => array(
				'type' => 'html',
				'html' => '</div>',
			),
			'hideSettingsTabs'     => array(
				'type'            => 'checkbox_list',
				'label'           => esc_html__( 'Hide the following settings tabs on every site:', 'rioc-common' ),
				'default'         => false,
				'options'         => $allTabs,
				'validation_type' => 'options_multi',
				'can_be_empty'    => true,
			),
		)
	),
);
