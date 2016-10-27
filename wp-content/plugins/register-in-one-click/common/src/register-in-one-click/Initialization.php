<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'Register_In_One_Click__Initialization' ) ) {
	/**
	 * Class that handles the integration with our Shop App API
	 */
	class Register_In_One_Click__Initialization {

		/**
		 * Slug of the WP admin menu item
		 */
		const MENU_SLUG = 'rioc-init';

		/**
		 * Singleton instance
		 *
		 * @var null or Register_In_One_Click__Initialization
		 */
		private static $instance = null;
		/**
		 * The slug for the new admin page
		 *
		 * @var string
		 */
		private   $admin_page    = null;
		
		public    $auth_page_url    = '';
		public    $registr_page_url = '';
		public    $init_page_url 	= '';
		
		/**
		 * Class constructor
		 */
		public function __construct() {
			
			$this->gen_url();

			add_action( 'admin_menu', array( $this, 'add_menu_page' ), 110 );
			
			add_action( 'wp_before_admin_bar_render', array( $this, 'add_toolbar_item' ), 30 );

		}
		
		/**
		 * Adds current user prop to the auth. form
		 */
		public function gen_url() {
		  
		  $this->auth_page_url = add_query_arg( array(
			    'post_type' => 'rioc-common',
			    'page' => 'rioc-authentication',
			), NULL);
			
		  $this->registr_page_url = add_query_arg( array(
			    'post_type' => 'rioc-common',
			    'page' => 'rioc-registration',
			), NULL);
			
		  $this->init_page_url = add_query_arg( array(
			    'post_type' => 'rioc-common',
			    'page' => 'rioc-init',
			), NULL);
		  
		}
		

		/**
		 * Adds the page to the admin menu
		 */
		public function add_menu_page() {
			if ( ! Register_In_One_Click__Settings::instance()->should_setup_pages() ) {
				return;
			}

			$page_title = esc_html__( 'Authentication', 'rioc-common' );
			$menu_title = esc_html__( 'Initialize Plugin', 'rioc-common' );
			$capability = apply_filters( 'rioc_events_addon_page_capability', 'install_plugins' );

			$where = Register_In_One_Click__Settings::instance()->get_parent_slug();
			
			$this->admin_page = add_submenu_page( $where, $page_title, $menu_title, $capability, self::MENU_SLUG, array( $this, 'do_menu_page' ) );
			
		}

		/**
		 * Adds a link to the shop app to the WP admin bar
		 */
		public function add_toolbar_item() {

			$capability = apply_filters( 'rioc_events_addon_page_capability', 'install_plugins' );

			// prevent users who cannot install plugins from seeing addons link
			if ( current_user_can( $capability ) ) {
				global $wp_admin_bar;
				
				$wp_admin_bar->add_menu( array(
					'id'     => 'rioc-temp',
					'title'  => esc_html__( 'Temp', 'rioc-common' ),
					'href'   => Register_In_One_Click__Settings::instance()->get_url( array( 'page' => self::MENU_SLUG ) ),
					'parent' => 'rioc-events-settings-group',
				) );
			}
		}

		/**
		 * Enqueue the styles
		 */
		public function enqueue_style() {
			
			wp_enqueue_style( 'app-authentication-style', rioc_resource_url('rioc-init.css', false,'common' ), array(), apply_filters( 'rioc_events_css_version', Register_In_One_Click__Main::VERSION ) );
			
		}

		/**
		 * display errors, if any, after saving
		 *
		 * @return void
		 */

		public function displayErrors() {

			
		}

		/**
		 * display success message after saving
		 *
		 * @return void
		 */
		public function displaySuccess() {
			
		}

		/**
		 * Renders the rioc_Temp page
		 */
		 
		public function do_menu_page() {
					
			include_once Register_In_One_Click__Main::instance()->plugin_path . 'src/admin-views/rioc-initialization.php';

		}

		/**
		 * Static Singleton Factory Method
		 *
		 * @return Register_In_One_Click__Initialization
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
