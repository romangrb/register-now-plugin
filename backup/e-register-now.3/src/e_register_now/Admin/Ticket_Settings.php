<?php
/**
 * Manages the admin settings UI in relation to ticket configuration.
 */
class E_Register_Now__Tickets__Admin__Ticket_Settings {
	/**
	 * Sets up the display of timezone-related settings and listeners to deal with timezone-update
	 * requests (which are initiated from within the settings screen).
	 */
	public function __construct() {
		add_action( 'e_rn_settings_do_tabs', array( $this, 'settings_ui' ) );
	}

	/**
	 * Loads the ticket settings from an admin-view file and returns them as an array.
	 *
	 * @return array
	 */
	public function settings_ui() {
		$settings = $this->get_settings_array();

		new E_Register_Now__Settings_Tab( 'event-tickets', __( 'Tickets', 'event-tickets' ), $settings );
	}

	/**
	 * Loads the timezone settings from an admin-view file and returns them as an array.
	 *
	 * @return array
	 */
	protected function get_settings_array() {
		$plugin_path = E_Register_Now__Tickets__Main::instance()->plugin_path;
		include $plugin_path . 'src/admin-views/e-rn-options-tickets.php';
		return $tickets_tab;
	}
}
