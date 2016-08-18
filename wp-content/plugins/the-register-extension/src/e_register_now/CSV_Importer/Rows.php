<?php


/**
 * Class E__Register__Now__Tickets__CSV_Importer__Rows
 *
 * Modifies the CSV Importer import option rows.
 */
class E__Register__Now__Tickets__CSV_Importer__Rows {

	/**
	 * @var self
	 */
	protected static $instance;

	/**
	 * The class singleton constructor.
	 *
	 * @return E__Register__Now__Tickets__CSV_Importer__Rows
	 */
	public static function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @param array $import_options
	 *
	 * @return array
	 */
	public function filter_import_options_rows( array $import_options ) {
		$import_options['rsvp'] = esc_html__( 'RSVPs', 'event-tickets' );

		return $import_options;
	}
}