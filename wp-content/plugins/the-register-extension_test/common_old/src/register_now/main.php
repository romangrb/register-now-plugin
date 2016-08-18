<?php
/**
 * Main Tribe Common class.
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( class_exists( 'Register__Main' ) ) {
	return;
}

class Register__Main {
	const EVENTSERROROPT      = '_register_events_errors';
	
	const VERSION           = '1.0.0dev';
	const FEED_URL          = 'https://example-feed.com/feed/';

	
	/**
	 * Static Singleton Factory Method
	 *
	 * @return Register__Main
	 */
	public static function instance() {
		static $instance;

		if ( ! $instance ) {
			$instance = new self;
		}

		return $instance;
	}
}
