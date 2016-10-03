<?php

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'E_Register_Now__Settings' ) ) {
	/**
	 * helper class that allows registration of settings
	 * this is a static class & uses the singleton design method
	 * instantiation takes place in E_Register_Now__Main
	 *
	 */
	class E_Register_Now__Settings {

		/**
		 * Slug of the parent menu slug
		 * @var string
		 */
		public static $parent_slug = 'rioc-common';

		/**
		 * Page of the parent menu
		 * @var string
		 */
		public static $parent_page = 'admin.php';

		/**
		 * @var E_Register_Now__Admin__Live_Date_Preview
		 */
		public $live_date_preview;

		/**
		 * the tabs that will appear in the settings page
		 * filtered on class construct
		 * @var array
		 */
		public $tabs;

		/**
		 * All the tabs registered, not just the ones that will appear
		 * @var array
		 */
		public $allTabs;

		/**
		 * multidimentional array of the fields that will be generated
		 * for the entire settings panel, tabs are represented in the array keys
		 * @var array
		 */
		public $fields;

		/**
		 * the default tab for the settings panel
		 * this should be a tab ID
		 * @var string
		 */
		public $defaultTab;

		/**
		 * the current tab being displayed
		 * @var string
		 */
		public $currentTab;

		/**
		 * tabs that shouldn't show the save button
		 * @var array
		 */
		public $noSaveTabs;

		/**
		 * the slug used in the admin to generate the settings page
		 * @var string
		 */
		public $adminSlug;

		/**
		 * the menu name used for the settings page
		 * @var string
		 */
		public $menuName;

		/**
		 * the required capability for the settings page
		 * @var string
		 */
		public $requiredCap;

		/**
		 * errors that occur after a save operation
		 * @var mixed
		 */
		public $errors;

		/**
		 * POST data before/after save
		 * @var mixed
		 */
		public $sent_data;

		/**
		 * the $current_screen name corresponding to the admin page
		 * @var string
		 */
		public $admin_page;

		/**
		 * true if a major error that prevents saving occurred
		 * @var bool
		 */
		public $major_error;

		/**
		 * Static Singleton Holder
		 * @var E_Register_Now__Settings|null
		 */
		private static $instance;

		/**
		 * Static Singleton Factory Method
		 *
		 * @return E_Register_Now__Settings
		 */
		public static function instance() {
			if ( empty( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Class constructor
		 *
		 * @return void
		 */
		public function __construct() {
			// run actions & filters
			add_action( 'admin_menu', array( $this, 'addPage' ) );
			add_action( 'network_admin_menu', array( $this, 'addNetworkPage' ) );
			
		}

		/**
		 * Determines whether or not the full admin pages should be initialized.
		 *
		 * When running in parallel with TEC 3.12.4, TEC should be relied on to handle the admin screens
		 * that version of TEC (and lower) is rioc-common ignorant. Therefore, rioc-common has to be
		 * the smarter, more lenient codebase.
		 *
		 * @return boolean
		 */
		public function should_setup_pages() {
			
			if ( ! class_exists( 'E_Register_Now__Events__Main' )) {
				return true;
			}

			if ( version_compare( E_Register_Now__Events__Main::VERSION, '4.0beta', '>=' ) ) {
				return true;
			}

			return false;
		}
		
		protected function check_role() {
			
			global $wp_roles;
			
			$current_user = wp_get_current_user();
			$roles = $current_user->roles;
			$role = array_shift($roles);
			
			return isset($wp_roles->role_names[$role]) ? translate_user_role($wp_roles->role_names[$role] ) : false;
		}
		
		public function wp_allow_config() {
			
			$current_role = $this->check_role(); 
			return ( $current_role === 'Subscriber' )? false : true;
		}
			// Super Admin – somebody with access to the site network administration features and all other features. See the Create a Network article.
			// Administrator – somebody who has access to all the administration features within a single site.
			// Editor – somebody who can publish and manage posts including the posts of other users.
			// Author – somebody who can publish and manage their own posts.
			// Contributor – somebody who can write and manage their own posts but cannot publish them.
			// Subscriber – somebody who can only manage their profile.
		

		/**
		 * create the main option page
		 *
		 * @return void
		 */
		public function addPage() {
			if ( ! $this->should_setup_pages() ) {
				return;
			}

			if ( ! is_multisite() || ( is_multisite() && '0' == E_Register_Now__Settings_Manager::get_network_option( 'allSettingsTabsHidden', '0' ) ) ) {
				if ( post_type_exists( 'rioc-common' ) ) {
					self::$parent_page = 'edit.php?post_type=rioc-common';
				} else {
					self::$parent_page = 'admin.php?post_type=rioc-common';
					
					add_menu_page(
						esc_html__( 'Events', 'rioc-common' ),
						esc_html__( 'Register in One Click', 'rioc-common' ),
						apply_filters( 'e_rn_common_event_page_capability', 'manage_options' ),
						self::$parent_slug,
						null,
						'dashicons-calendar',
						6
					);
				}
			}
		}
		/**
		 * The "slug" used for adding submenu pages
		 *
		 * @return string
		 */
		public function get_parent_slug() {
			$slug = self::$parent_page;

			// if we don't have an event post type, then we can just use the rioc-common slug
			if ( 'edit.php' === $slug || 'admin.php?post_type=rioc-common' === $slug ) {
				$slug = self::$parent_slug;
			}

			return $slug;
		}
		
		/**
		 * Returns the main admin settings URL.
		 *
		 * @return string
		 */
		public function get_url( array $args = array() ) {
			$defaults = array(
				'post_type' => $this->adminSlug,
				'parent' => self::$parent_page,
			);

			// Allow the link to be "changed" on the fly
			$args = wp_parse_args( $args, $defaults );

			$url = admin_url( $args['parent'] );

			// keep the resulting URL args clean
			unset( $args['parent'] );

			return apply_filters( 'e_rn_settings_url', add_query_arg( $args, $url ), $args, $url );
		}
		
		/**
		 * create the network options page
		 *
		 * @return void
		 */
		public function addNetworkPage() {
			if ( ! $this->should_setup_pages() ) {
				return;
			}

			$this->admin_page = add_submenu_page(
				'settings.php', esc_html__( 'Events Settings', 'rioc-common' ), esc_html__( 'Events Settings', 'rioc-common' ), $this->requiredCap, $this->adminSlug, array(
					$this,
					'generatePage',
				)
			);
		}
	} // end class
} // endif class_exists
