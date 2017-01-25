<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'Register_In_One_Click__Registration' ) ) {
	/**
	 * Class that handles the integration with our Shop App API
	 */
	class Register_In_One_Click__Registration extends Register_In_One_Click__Abstract_Menu_Page {

		/**
		 * Singleton instance
		 *
		 * @var null or Register_In_One_Click__Registration
		 */
		private static $instance   = null;
		/**
		 * The slug for the new admin page
		 *
		 * @var string
		 */
		private $admin_page 	   =  null;
        
        const	OPT_NAME		   = 'is_sended';
        
        const   DFLT_CEIL_TIME_SS  =  60;
        const	INIT_TIME_SS	   =  0;

		/**
		 * Class constructor
		 */
		public function __construct() {
		
			add_action( 'add_set_time_refresh_rq', array( $this, 'set_time_refresh_rq' ));
			add_action( 'admin_menu', array( $this, 'add_menu_page' ), 120 );
			add_action( 'wp_ajax_admin_notification', array( $this, 'admin_notification') );
			add_action( 'wp_footer', array( $this, 'enqueue_style') );
			add_action( 'wp_footer', array( $this, 'enqueue_script') );
	        
		}
		
		final public function set_time_refresh_rq() {
			
			if ( !get_option(self::OPT_NAME) ) {
			      add_option(self::OPT_NAME, self::INIT_TIME_SS);
	        } 
		}
		
		final public function is_req_allow() {
			
			return  (time() - get_option(self::OPT_NAME) >= self::DFLT_CEIL_TIME_SS ) ? true : false;
		}
		
		public function enqueue_script() {
				
				// Reg_new_ajax_rq
				wp_register_script('ajax_submit', rioc_resource_url('auth-ajax.js', false, 'common' ), array('jquery'), apply_filters( 'rioc_events_js_version', Register_In_One_Click__Main::VERSION ), array( 'jquery' ) );
				wp_localize_script('ajax_submit', 'Auth_new_ajax', array(
						'ajaxurl'   		=> admin_url( 'admin-ajax.php' ),
						'formNoth'  		=> Register_In_One_Click__Notice::instance()->get_notify_arr(),
						'auth_form_validated' => false,
						'auth_url'    		=> self::REG_URL,
						'auth_form' 		=> self::AUTH_FORM,
						'auth_form_tag'		=> self::AUTH_TAG,
						'form_trigger'		=> self::FORM_TRIGGER,
						'form_trigger_tag'	=> self::AUTH_TAG,
						'form_captcha'		=> self::FORM_CAPTCHA,
	        			'label_for_captcha' => self::LABEL_FOR_CAPTCHA,
	        			'refresh_btn'		=> self::REFRESH_TRIGGER,
	        			'rq_captha_query' 	=> self::RQ_CAPTCHA_QUERY
					)
				);
				wp_enqueue_script('capcha-auth', rioc_resource_url('capcha-auth.js', false, 'common' ), array('ajax_submit', 'jquery'), apply_filters( 'rioc_events_js_version', Register_In_One_Click__Main::VERSION ) );
				wp_enqueue_script('reg-form-validator', rioc_resource_url('reg-form-validator.js', false, 'common' ), array(), apply_filters( 'rioc_events_js_version', Register_In_One_Click__Main::VERSION ) );
				
		}
	 
	    /**
	     * Registers and enqueues admin-specific minified JavaScript.
	     */
	    public function register_admin_scripts() {
	        wp_enqueue_script('ajax-notification-admin', rioc_resource_url('notify.js', false, 'common' ), array(), apply_filters( 'rioc_events_js_version', Register_In_One_Click__Main::VERSION ) ); 
	    }
	 
		public function admin_notification() {
			// generate the response
		
			$_POST['class'] = Register_In_One_Click__Notice::instance()->sighn_class_notice($_POST['data']['type']);
			
			if ($_POST['data']['type'] == 'success'){
				update_option($this->opt_name, time());
			}
			
			$response = json_encode($_POST);
			// response output
			header( "Content-Type: application/json" );
			echo $response;
			// IMPORTANT: don't forget to "exit"
			die();
		}
		/**
		 * Adds the page to the admin menu
		 */
		public function add_menu_page() {
			
			if ( ! Register_In_One_Click__Settings::instance()->should_setup_pages() ) {
				return;
			}

			$page_title = esc_html__( 'registration', 'rioc-common' );
			$menu_title = esc_html__( '&nbsp; - registration', 'rioc-common' );
			$capability = apply_filters( 'rioc_events_addon_page_capability', 'install_plugins' );

			$where = Register_In_One_Click__Settings::instance()->get_parent_slug();

			$this->admin_page = add_submenu_page( $where, $page_title, $menu_title, $capability, self::REG_SLUG, array( $this, 'do_menu_page' ) );

		}
		
		/**
		 * Enqueue the styles
		 */
		public function enqueue_style() {
			wp_enqueue_style( 'app-registration-style', rioc_resource_url('rioc-auth.css', false,'common' ), array(), apply_filters( 'rioc_events_css_version', Register_In_One_Click__Main::VERSION ) );
		}
		
		/**
		 * Renders the rioc_Registration page
		 */
		public function do_menu_page() {
			
		   ( $this->is_req_allow() )? 
				include_once Register_In_One_Click__Main::instance()->plugin_path . 'src/admin-views/rioc-registration.php':
			   	include_once Register_In_One_Click__Main::instance()->plugin_path . 'src/admin-views/rioc-registration-pause.php';
		}

		/**
		 * Static Singleton Factory Method
		 *
		 * @return Register_In_One_Click__Registration
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
