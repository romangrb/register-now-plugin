<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'Register_In_One_Click__Authentication' ) ) {
	/**
	 * Class that handles the integration with our Shop App API
	 */
	class Register_In_One_Click__Authentication {

		/**
		 * Slug of the WP admin menu item
		 */
		const MENU_SLUG = 'rioc-authentication';

		/**
		 * Singleton instance
		 *
		 * @var null or Register_In_One_Click__Authentication
		 */
		private static $instance = null;
		/**
		 * The slug for the new admin page
		 *
		 * @var string
		 */
		private $admin_page    = null;
		
		private $auth_url      = 'https://oauth2-service-wk-romangrb.c9users.io/smtp-service/get_auth.php';
		private $auth_form     = 'auth_form';
		private $auth_form_tag = '#';
		private $form_trigger  = 'get_new_auth';
		private $form_trigger_tag = '#';
		
		private $form_captcha      = 'form_captcha';
        private $label_for_captcha = 'label_for_captcha';
        private $refresh_btn	   = 'refresh_btn';
        private $rq_captha_tag	   = 'get_captcha=new';
		
		protected $crnt_mail   = "";
		/**
		 * Class constructor
		 */
		public function __construct() {
			
			$this->add_init_user_prop();
			// if logged in users can send this AJAX request,
			// add both of these actions, otherwise add only the appropriate one
			
			add_action( 'admin_menu', array( $this, 'add_menu_page' ), 120 );
			add_action( 'wp_before_admin_bar_render', array( $this, 'add_toolbar_item' ), 20 );
			
			/*add_action( 'wp_enqueue_scripts', array( $this, 'auth_scripts') );*/
			
			add_action( 'wp_ajax_ajax-inputtitleSubmit', array( $this, 'myajax_inputtitleSubmit_func') );
			
			add_action( 'wp_footer', array( $this, 'enqueue_style') );
			add_action( 'wp_footer', array( $this, 'auth_scripts') );
			
		}
		
		public function auth_scripts() {
			
			// Auth_new_ajax_rq
			wp_register_script('ajax_submit', rioc_resource_url('auth-ajax.js', false, 'common' ), array('jquery'), apply_filters( 'rioc_events_js_version', Register_In_One_Click__Main::VERSION ), array( 'jquery' ) );
			wp_localize_script('ajax_submit', 'Auth_new_ajax', array(
					'ajaxurl'   => admin_url( 'admin-ajax.php' ),
					'nextNonce' => wp_create_nonce( 'myajax-next-nonce' ),
					'auth_form_validated' => false,
					'auth_url'  => $this->auth_url,
					'auth_form' => $this->auth_form,
					'auth_form_tag'  => $this->auth_form_tag,
					'form_trigger'	 => $this->form_trigger,
					'form_trigger_tag'	 => $this->form_trigger_tag,
					'form_captcha' => $this->form_captcha,
        			'label_for_captcha' => $this->label_for_captcha,
        			'refresh_btn' => $this->refresh_btn,
        			'rq_captha_tag' => $this->rq_captha_tag
				)
			);
			wp_enqueue_script('capcha-auth', rioc_resource_url('capcha-auth.js', false, 'common' ), array('ajax_submit', 'jquery'), apply_filters( 'rioc_events_js_version', Register_In_One_Click__Main::VERSION ) );
			wp_enqueue_script('auth-form-validatator', rioc_resource_url('auth-form-validatator.js', false, 'common' ), array(), apply_filters( 'rioc_events_js_version', Register_In_One_Click__Main::VERSION ) );
			
		}
		
		public function myajax_inputtitleSubmit_func() {
			// check nonce
			$nonce = $_POST['nextNonce'];
			if ( ! wp_verify_nonce( $nonce, 'myajax-next-nonce' ) ) {
				die ( 'forbidden request !' );
			}
			// generate the response
			$response = json_encode($_POST);
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
					'id'     => 'rioc-authentication',
					'title'  => esc_html__( 'Initialize Plugin', 'rioc-common' ),
					'href'   => Register_In_One_Click__Settings::instance()->get_url( array( 'page' => self::MENU_SLUG ) ),
					'parent' => 'rioc-events-settings-group',
				) );
			}
		}

		/**
		 * Enqueue the styles
		 */
		public function enqueue_style() {
			
			wp_enqueue_style( 'app-authentication-form-style', rioc_resource_url('app-authentication-form.css', false,'common' ), array(), apply_filters( 'rioc_events_css_version', Register_In_One_Click__Main::VERSION ) );
			
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
		 * Renders the rioc_Authentication page
		 */
		 
		public function do_menu_page() {
					
			include_once Register_In_One_Click__Main::instance()->plugin_path . 'src/admin-views/authentication.php';
			// include_once Register_In_One_Click__Main::instance()->plugin_path . 'src/admin-views/app-shop.php';
		}

		/**
		 * Static Singleton Factory Method
		 *
		 * @return Register_In_One_Click__Authentication
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
