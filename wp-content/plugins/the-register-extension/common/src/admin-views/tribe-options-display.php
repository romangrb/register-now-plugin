<?php

$sample_date = strtotime( 'January 15 ' . date( 'Y' ) );

$displayTab = array(
	'priority' => 20,
	'fields'   =>
	/**
	 * Filter the fields available on the display settings tab
	 *
	 * @param array $fields a nested associative array of fields & field info passed to E__Register__Now__Field
	 * @see E__Register__Now__Field
	 */
		apply_filters(
		'tribe_display_settings_tab_fields', array(
			'ern-form-content-start'           => array(
				'type' => 'html',
				'html' => '<div class="ern-settings-form-wrap">',
			),
			'tribeEventsDateFormatSettingsTitle' => array(
				'type' => 'html',
				'html' => '<h3>' . esc_html__( 'Date Format Settings', 'ern-common' ) . '</h3>',
			),
			'tribeEventsDateFormatExplanation'   => array(
				'type' => 'html',
				'html' => __( '<p>The following three fields accept the date format options available to the php date() function. <a href="http://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">Learn how to make your own date format here</a>.</p>', 'ern-common' ),
			),
			'datepickerFormat'                   => array(
				'type'            => 'dropdown_select2',
				'label'           => esc_html__( 'Datepicker Date Format', 'ern-common' ),
				'tooltip'         => esc_html__( 'Select the date format to use in datepickers', 'ern-common' ),
				'default'         => 'Y-m-d',
				'options'         => array(
					'0' => date( 'Y-m-d', $sample_date ),
					'1' => date( 'n/j/Y', $sample_date ),
					'2' => date( 'm/d/Y', $sample_date ),
					'3' => date( 'j/n/Y', $sample_date ),
					'4' => date( 'd/m/Y', $sample_date ),
					'5' => date( 'n-j-Y', $sample_date ),
					'6' => date( 'm-d-Y', $sample_date ),
					'7' => date( 'j-n-Y', $sample_date ),
					'8' => date( 'd-m-Y', $sample_date ),
				),
				'validation_type' => 'options',
			),
			'ern-form-content-end' => array(
				'type' => 'html',
				'html' => '</div>',
			),
		)
	),
);
