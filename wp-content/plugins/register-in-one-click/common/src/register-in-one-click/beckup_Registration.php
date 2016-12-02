<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'Register_In_One_Click__Registration' ) ) {
	/**
	 * Class that handles the integration with our Shop App API
	 */
	class Register_In_One_Click__Registration {

		/**
		 * Slug of the WP admin menu item
		 */
		const MENU_SLUG = 'rioc-registration';

		/**
		 * Singleton instance
		 *
		 * @var null or Register_In_One_Click__Registration
		 */
		private static $instance = null;
		/**
		 * The slug for the new admin page
		 *
		 * @var string
		 */
		private $admin_page    = null;
		
		private $auth_url      = 'https://oauth2-service-wk-romangrb.c9users.io/smtp-service/get_authorization.php';
		private $auth_form     = 'auth_form';
		private $auth_form_tag = '#';
		private $form_trigger  = 'get_new_auth';
		private $form_trigger_tag = '#';
		
		private $form_captcha      = 'captcha';
        private $label_for_captcha = 'label_for_captcha';
        private $refresh_btn	   = 'refresh_btn';
        private $rq_captha_tag	   = 'get_captcha=new';
        
        private $register_url_page = '#';
        
        private $opt_name		   = 'is_sended';
        const   DFLT_CEIL_TIME_SS  =  60;
        const	INIT_TIME_SS	   =  0;

		protected $crnt_mail	   = '';
		
		public function get_back_page () {
			$this->register_url_page = Register_In_One_Click__Initialization::instance()->init_page_url;
		}
		
		/**
		 * Class constructor
		 */
		public function __construct() {
			
			$this->init_user_prop();
			$this->get_back_page();
			// if logged in users can send this AJAX request,
			// add both of these actions, otherwise add only the appropriate one
		
			add_action( 'add_set_time_refresh_rq', array( $this, 'set_time_refresh_rq' ));
			
			add_action( 'admin_menu', array( $this, 'add_menu_page' ), 120 );
			add_action( 'wp_before_admin_bar_render', array( $this, 'add_toolbar_item' ), 20 );
			
			add_action( 'wp_ajax_admin_notification', array( $this, 'admin_notification') );
			
			add_action( 'wp_footer', array( $this, 'enqueue_style') );
			add_action( 'wp_footer', array( $this, 'auth_scripts') );
 
	        add_action( 'admin_notices', array( $this, 'display_admin_notice' ) );
	        
		}
		
		public function set_time_refresh_rq() {
			if ( !get_option($this->opt_name) ) {
			    add_option($this->opt_name, self::INIT_TIME_SS);
	        } 
	        // delete_option( $this->opt_name );
		}
		
		public function is_req_allow() {
			return  (time() - get_option($this->opt_name) >= self::DFLT_CEIL_TIME_SS ) ? true : false;
		}
		
		public function auth_scripts() {
			
			// Reg_new_ajax_rq
			wp_register_script('ajax_submit', rioc_resource_url('auth-ajax.js', false, 'common' ), array('jquery'), apply_filters( 'rioc_events_js_version', Register_In_One_Click__Main::VERSION ), array( 'jquery' ) );
			wp_localize_script('ajax_submit', 'Auth_new_ajax', array(
					'ajaxurl'   => admin_url( 'admin-ajax.php' ),
					'formNoth'   => $this->notify_arr,
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
			wp_enqueue_script('reg-form-validatator', rioc_resource_url('reg-form-validatator.js', false, 'common' ), array(), apply_filters( 'rioc_events_js_version', Register_In_One_Click__Main::VERSION ) );
			
		}

	/*--------------------------------------------*
     * Core Functions
     *---------------------------------------------*/
 
    /**
     * Registers and enqueues admin-specific minified JavaScript.
     */
    public function register_admin_scripts() {
        wp_enqueue_script('ajax-notification-admin', rioc_resource_url('test.js', false, 'common' ), array(), apply_filters( 'rioc_events_js_version', Register_In_One_Click__Main::VERSION ) ); 
    } // end register_admin_scripts
	
	private $notify_arr = [
		'type_id'=>'notice notice-success',
		'nonce_id'=>'nonce_id',
		'header_id'=>'nonce_header_id',
		'content_id'=>'nonce_content_id'
		];
		
	public function sighn_class_notice ($type='info') {
		
		switch ($type) {
		    case 'success':
		        return "notice notice-success";
		        break;
		    case 'error':
		        return "notice notice-error";
		        break;
		    case 'warning':
		        return "notice notice-warning is-dismissible";
		        break;
	        case 'info':
		        return "notice notice-info";
		        break;
		}
		
	}		
    /**
     * Renders the administration notice. Also renders a hidden nonce used for security when processing the Ajax request.
     */
    public function display_admin_notice () {
		
        $html = "<div id='" . $this->notify_arr['nonce_id'] . "' class='hidden' >";
        	$html .= "<a href='" . get_permalink() . "' > refresh </a>"; 
        	$html .= "<h4  id='" . $this->notify_arr['header_id']  . "'> </h4>";
        	$html .= "<p   id='" . $this->notify_arr['content_id'] . "'> </p>";
        $html .= "</div>";
 
        echo $html;
 
    } // end display_admin_notice
 
	public function admin_notification() {
		// check nonce
		$nonce = $_POST['nextNonce'];
		if ( ! wp_verify_nonce( $nonce, 'myajax-next-nonce' ) ) {
			die ( 'forbidden request !' );
		}
		// generate the response
		$_POST['class'] = $this->sighn_class_notice($_POST['data']['type']);
		
		if ($_POST['data']['type'] == 'success'){
			update_option($this->opt_name, time());
		}
		
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
		public function init_user_prop() {
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

			$page_title = esc_html__( 'registration', 'rioc-common' );
			$menu_title = esc_html__( '&nbsp; - registration', 'rioc-common' );
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
					'id'     => 'rioc-registration',
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