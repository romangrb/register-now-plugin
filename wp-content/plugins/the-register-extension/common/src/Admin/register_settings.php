<?php
/**
 * Manages the admin configuration.
 */
class Register__Now__Settings {
	
	public function __construct() {
		add_action( 'register_settings_do_tabs', array( $this, 'settings_ui' ) );
	}

	/**
	 * Loads the ticket settings from an admin-view file and returns them as an array.
	 *
	 * @return array
	 */
	public function settings_ui() {
		$settings = $this->get_settings_array();

		new Tribe__Settings_Tab( 'event-tickets', __( 'Tickets', 'event-tickets' ), $settings );
	}

	/**
	 * Loads the timezone settings from an admin-view file and returns them as an array.
	 *
	 * @return array
	 */
	protected function get_settings_array() {
		$plugin_path = Tribe__Tickets__Main::instance()->plugin_path;
		include $plugin_path . 'src/admin-views/tribe-options-tickets.php';
		return $tickets_tab;
	}
}
