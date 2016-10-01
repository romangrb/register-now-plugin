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

		protected $crnt_mail = "";
		/**
		 * Class constructor
		 */
		public function __construct() {
			
			$this->add_init_user_prop();
			
			
			add_action( 'admin_menu', array( $this, 'add_menu_page' ), 120 );
			add_action( 'wp_before_admin_bar_render', array( $this, 'add_toolbar_item' ), 20 );
			
			// if logged in users can send this AJAX request,
			// add both of these actions, otherwise add only the appropriate one
			
			add_action('wp_ajax_my_action', array( $this, 'my_action_callback' ));
			
		add_action( 'wp_enqueue_scripts', array( $this, 'inputtitle_submit_scripts') );
		add_action( 'wp_ajax_ajax-inputtitleSubmit', array( $this, 'myajax_inputtitleSubmit_func') );
		add_action( 'wp_ajax_nopriv_ajax-inputtitleSubmit', array( $this, 'myajax_inputtitleSubmit_func') );
			
		}
		
		
		public function inputtitle_submit_scripts() {
			wp_enqueue_script( 'inputtitle_submit', e_rn_resource_url('test.js', false, 'common' ), array(), apply_filters( 'e_rn_events_js_version', E_Register_Now__Main::VERSION ), array( 'jquery' ) );
			wp_localize_script( 'inputtitle_submit', 'PT_Ajax', array(
					'ajaxurl'   => admin_url( 'admin-ajax.php' ),
					'nextNonce' => wp_create_nonce( 'myajax-next-nonce' )
				)
			);
		}
		public function myajax_inputtitleSubmit_func() {
			// check nonce
			$nonce = $_POST['nextNonce'];
			if ( ! wp_verify_nonce( $nonce, 'myajax-next-nonce' ) ) {
				die ( 'Busted!' );
			}
			// generate the response
			$response = json_encode( $_POST );
			// response output
			header( "Content-Type: application/json" );
			echo $response;
			// IMPORTANT: don't forget to "exit"
			exit;
		}
		
		/**
		 * Adds current user prop to the auth. form
		 */
		public function add_init_user_prop() {
			$current_user = wp_get_current_user();
			if ( 0 != $current_user->ID ){
			    $this->crnt_mail = ($current_user->user_email)? $current_user->user_email: "";
			}
		}
		
		public function my_action_callback() {
			
			$whatever = intval( $_POST['whatever'] );
			
			$whatever += 10;
			
			echo "<div>$whatever</div>";
		
			wp_die();
		}
		
		public function my_acf_notice() {
             
	            echo  "<div class=\"update-nag notice\" style:\"width:100px\">
	                	  <p>NOTIFY</p>
	            	   </div>";
            }
		
		
		public function add_err() {
			if( !function_exists( 'the_field' ) ) {
              add_action( 'admin_notices', 'my_acf_notice' );
            }
            
            function my_acf_notice() {
             
	            echo  "<div class=\"update-nag notice\" style:\"width:100px\">
	                	  <p>NOTIFY</p>
	            	   </div>";
            }
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
			
			wp_enqueue_script( 'app-capcha-authentication', e_rn_resource_url('app-capcha-authentication.js', false, 'common' ), array(), apply_filters( 'e_rn_events_js_version', E_Register_Now__Main::VERSION ) );
			wp_enqueue_script( 'app-authentication-form-validate', e_rn_resource_url('app-authentication-form-validate.js', false, 'common' ), array(), apply_filters( 'e_rn_events_js_version', E_Register_Now__Main::VERSION ) );
			wp_enqueue_style( 'app-authentication-form-style', e_rn_resource_url('app-authentication-form.css', false,'common' ), array(), apply_filters( 'e_rn_events_css_version', E_Register_Now__Main::VERSION ) );

			wp_enqueue_script( 'authentication-capcha-form-validate', e_rn_resource_url('authentication-capcha-form-validate.js', false, 'common' ), array(), apply_filters( 'e_rn_events_js_version', E_Register_Now__Main::VERSION ) );
			
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
