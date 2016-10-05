<?php


/**
 * Class Register_In_One_Click__Admin__Notice__Archive_Slug_Conflict
 *
 * Takes care of adding an admin notice if a page with the `/events` slug has been created in the site.
 */
class Register_In_One_Click__Admin__Notice__Archive_Slug_Conflict {

	/**
	 * @var static
	 */
	protected static $instance;

	/**
	 * @var string The slug of The Events Calendar archive page.
	 */
	protected $archive_slug;

	/**
	 * @var WP_Post The page post object.
	 */
	protected $page;

	/**
	 * @return Register_In_One_Click__Admin__Notice__Archive_Slug_Conflict
	 */
	public static function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Hooks the action to show an admin notice if a page with the `/events` slug exists on the site.
	 */
	public function maybe_add_admin_notice() {
		$this->archive_slug = Register_In_One_Click__Settings_Manager::get_option( 'eventsSlug', 'events' );
		$page               = get_page_by_path( $this->archive_slug );
		if ( ! $page || $page->post_status == 'trash' ) {
			return;
		}
		$this->page = $page;
		$dismissed_notices = get_user_meta( get_current_user_id(), 'rioc-dismiss-notice' );

		if ( is_array( $dismissed_notices ) && in_array( 'archive-slug-conflict', $dismissed_notices ) ) {
			return;
		}
		add_action( 'admin_notices', array( $this, 'notice' ) );
	}

	/**
	 * Hooked before maybe_add_admin_notice to prevent a notice to show it has been dimissed
	 * @return void
	 */
	public function maybe_dismiss() {
		if ( empty( $_GET['rioc-dismiss-notice'] ) ) {
			return;
		}

		$notice = esc_attr( $_GET['rioc-dismiss-notice'] );

		if ( 'archive-slug-conflict' !== $notice ) {
			return;
		}

		$dimissed_notices = get_user_meta( get_current_user_id(), 'rioc-dismiss-notice' );
		if ( in_array( 'archive-slug-conflict', $dimissed_notices ) ) {
			return;
		}

		add_user_meta( get_current_user_id(), 'rioc-dismiss-notice', 'archive-slug-conflict', false );
	}

	/**
	 * Echoes the admin notice to the page
	 */
	public function notice() {
		// What's happening?
		$page_title = apply_filters( 'the_title', $this->page->post_title, $this->page->ID );
		$line_1     = __( sprintf( 'The page "%1$s" uses the "/%2$s" slug: the Events Calendar plugin will show its calendar in place of the page.', $page_title, $this->archive_slug ), 'rioc-common' );

		// What the user can do
		$page_edit_link = get_edit_post_link( $this->page->ID );
		$can_edit_page_link    = sprintf( __( '<a href="%s">Edit the page slug</a>', 'rioc-common' ), $page_edit_link );
		$page_edit_link_string = current_user_can( 'edit_pages' ) ? $can_edit_page_link : __( 'Ask the site administrator to edit the page slug', 'rioc-common' );

		$settings_cap                = apply_filters( 'rioc_settings_req_cap', 'manage_options' );
		$admin_slug                  = apply_filters( 'rioc_settings_admin_slug', 'rioc-common' );
		$setting_page_link           = apply_filters( 'rioc_settings_url', admin_url( 'edit.php?page=' . $admin_slug . '#rioc-field-eventsSlug' ) );
		$can_edit_settings_link      = sprintf( __( '<a href="%s">edit Events settings</a>.', 'rioc-common' ), $setting_page_link );
		$events_settings_link_string = current_user_can( $settings_cap ) ? $can_edit_settings_link : __( ' ask the site administrator set a different Events URL slug.', 'rioc-common' );

		$line_2 = __( sprintf( '%1$s or %2$s', $page_edit_link_string, $events_settings_link_string ), 'rioc-common' );

		echo sprintf( '<div id="message" class="notice error is-dismissible rioc-dismiss-notice" data-ref="archive-slug-conflict"><p>%s</p><p>%s</p></div>', $line_1, $line_2 );
	}
}
