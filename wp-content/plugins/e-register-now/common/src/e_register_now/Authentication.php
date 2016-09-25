<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'E_Register_Now__Authentication' ) ) {
	/**
	 * Class that handles the integration with our Shop App API
	 */
	class E_Register_Now__Authentication {

		/**
		 * Slug of the WP admin menu item
		 */
		const MENU_SLUG = 'e-rn-authentication';

		/**
		 * Singleton instance
		 *
		 * @var null or E_Register_Now__Authentication
		 */
		private static $instance = null;
		/**
		 * The slug for the new admin page
		 *
		 * @var string
		 */
		private $admin_page = null;

		/**
		 * Class constructor
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'add_menu_page' ), 120 );
			add_action( 'wp_before_admin_bar_render', array( $this, 'add_toolbar_item' ), 20 );
		}

		/**
		 * Adds the page to the admin menu
		 */
		public function add_menu_page() {
			if ( ! E_Register_Now__Settings::instance()->should_setup_pages() ) {
				return;
			}

			$page_title = esc_html__( 'Authentication', 'e-rn-common' );
			$menu_title = esc_html__( 'Initialize Plugin', 'e-rn-common' );
			$capability = apply_filters( 'e_rn_events_addon_page_capability', 'install_plugins' );

			$where = E_Register_Now__Settings::instance()->get_parent_slug();

			$this->admin_page = add_submenu_page( $where, $page_title, $menu_title, $capability, self::MENU_SLUG, array( $this, 'do_menu_page' ) );

			add_action( 'admin_print_styles-' . $this->admin_page, array( $this, 'enqueue' ) );
		}

		/**
		 * Adds a link to the shop app to the WP admin bar
		 */
		public function add_toolbar_item() {

			$capability = apply_filters( 'e_rn_events_addon_page_capability', 'install_plugins' );

			// prevent users who cannot install plugins from seeing addons link
			if ( current_user_can( $capability ) ) {
				global $wp_admin_bar;

				$wp_admin_bar->add_menu( array(
					'id'     => 'e-rn-authentication',
					'title'  => esc_html__( 'Initialize Plugin', 'e-rn-common' ),
					'href'   => E_Register_Now__Settings::instance()->get_url( array( 'page' => self::MENU_SLUG ) ),
					'parent' => 'e-rn-events-settings-group',
				) );
			}
		}

		/**
		 * Enqueue the styles and script
		 */
		public function enqueue() {
			
			wp_enqueue_script( 'app-authentication', e_rn_resource_url('app-authentication.js', false, 'common' ), array(), apply_filters( 'e_rn_events_js_version', E_Register_Now__Main::VERSION ) );
			wp_enqueue_script( 'app-authentication', e_rn_resource_url('app-authentication.js', false, 'common' ), array(), apply_filters( 'e_rn_events_js_version', E_Register_Now__Main::VERSION ) );
			wp_enqueue_style( 'app-authentication', e_rn_resource_url( 'app-authentication.css', false, 'common' ), array(), apply_filters( 'e_rn_events_css_version', E_Register_Now__Main::VERSION ) );
			
		}

		/**
		 * Renders the e_rn_Authentication page
		 */
		public function do_menu_page() {
			
			include_once E_Register_Now__Main::instance()->plugin_path . 'src/admin-views/authentication.php';
		}

		/**
		 * Static Singleton Factory Method
		 *
		 * @return E_Register_Now__Authentication
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) ) {
				$className      = __CLASS__;
				self::$instance = new $className;
			}

			return self::$instance;
		}
	}
}
