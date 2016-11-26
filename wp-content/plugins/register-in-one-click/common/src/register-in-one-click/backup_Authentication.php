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
		
		private $auth_form     = 'auth_formm';
		private $auth_form_tag = '#';
		private $form_trigger  = 'get_new_auth';
		private $form_trigger_tag = '#';
		
		private $form_captcha      = 'form_captcha';
        private $label_for_captcha = 'label_for_captcha';
        private $refresh_btn	   = 'refresh_btn';
        private $rq_captha_tag	   = 'get_captcha=new';
        
        private $register_url_page = '#';
		
		#
        #	TEST
        #
        
        private $auth_url_test	   = 'https://oauth2-service-wk-romangrb.c9users.io';
        private $get_token_id	   = 'get_token';
        private $get_init_token_id = 'init_token';
        
        #
        # END	TEST
        #
		
		protected $crnt_mail   = "";
		/**
		 * Class constructor
		 */
		public function __construct() {
			
			$this->add_init_user_prop();
			// if logged in users can send this AJAX request,
			// add both of these actions, otherwise add only the appropriate one
			$this->get_back_page();
			
			add_action( 'init_rioc_tables', array( __CLASS__, 'install_rioc_tables' ), 5 );
			
			add_action( 'admin_menu', array( $this, 'add_menu_page' ), 120 );
			
			add_action( 'wp_before_admin_bar_render', array( $this, 'add_toolbar_item' ), 30 );
 
			add_action( 'wp_footer', array( $this, 'refresh_script') );
	
			add_action( 'wp_footer', array( $this, 'enqueue_style') );
			add_action( 'wp_footer', array( $this, 'auth_scripts') );
	
	        add_action( 'admin_notices', array( $this, 'display_admin_notice' ) );
	        add_action( 'auth_opt', array( $this, 'set_auth_opt' ) );
	        
	        add_action( 'refresh_token', array( $this, 'rioc_refresh_token') );
	        
	        add_action( 'wp_ajax_nopriv_refresh_token_f_md', array( $this, 'refresh_token_f_md') );
			add_action( 'wp_ajax_refresh_token_f_md', array( $this, 'refresh_token_f_md' ) );
	        
		}
		
		public function get_back_page () {
			$this->register_url_page = Register_In_One_Click__Initialization::instance()->init_page_url;
		}
		
		public function set_auth_opt () {
			if ( get_option('is_auth') ) {
			     add_option('is_auth', false);
	        }
		}
		
		
		public function auth_scripts() {
	
			// Auth_new_ajax_rq
			wp_register_script('ajax_submit_auth', rioc_resource_url('auth-ajax.js', false, 'common' ), array('jquery'), apply_filters( 'rioc_events_js_version', Register_In_One_Click__Main::VERSION ), array( 'jquery' ) );
			wp_localize_script('ajax_submit_auth', 'Auth_new_ajax', array(
					'ajaxurl'   => admin_url( 'admin-ajax.php' ),
					'nextNonce' => wp_create_nonce( 'myajax-next-nonce' ),
					'auth_form_validated' => false,
					'auth_url_test'  => $this->auth_url_test,
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
			// wp_enqueue_script('capcha-auth', rioc_resource_url('capcha-auth.js', false, 'common' ), array('ajax_submit', 'jquery'), apply_filters( 'rioc_events_js_version', Register_In_One_Click__Main::VERSION ) );
		
		}
		
		public function refresh_script() {
			
			wp_enqueue_script('ajax_token_handler', rioc_resource_url('refresh_tkn.js', false, 'common' ), array('jquery'), apply_filters( 'rioc_events_js_version', Register_In_One_Click__Main::VERSION ), array( 'jquery' ) );
			wp_localize_script('ajax_token_handler', 'token_handler', array(
				'ajax_url' => admin_url( 'admin-ajax.php' )
			));
			wp_enqueue_script('auth-form-validatator', rioc_resource_url('auth-form-validatator.js', false, 'common' ), array(), apply_filters( 'rioc_events_js_version', Register_In_One_Click__Main::VERSION ) );
			
		}
	
		public function refresh_token_f_md() {
			// define if this AJAX request
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) { 
				
				/*if (empty($_REQUEST['token_hash']['token']) ||
					empty($_REQUEST['token_hash']['token_expiry'])){
				die();
				}*/
				
				$tmp_d = array('token_key'=>(string)$_REQUEST['token_hash']['token'], 'token_expiry'=>(int)$_REQUEST['token_hash']['expires_in']);	
				
				Register_In_One_Click__Query_Db_Rioc::instance()->refresh_token($tmp_d);
				echo json_encode($_REQUEST['token_hash']);
			}
			die();
		}
		
		public static function install_rioc_tables() {
	
			self::create_tables();
		}
		
			
		private static function create_tables() {
				global $wpdb;
		
				$wpdb->hide_errors();
		
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
				/**
				 * Before updating with DBDELTA, remove any primary keys which could be
				 * modified due to schema updates.
				 */
				if ( $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}rioc_d';" ) ) {
					if ( ! $wpdb->get_var( "SHOW COLUMNS FROM `{$wpdb->prefix}rioc_d` LIKE 'token_id';" ) ) {
						$wpdb->query( "ALTER TABLE {$wpdb->prefix}rioc_d DROP PRIMARY KEY, ADD `token_id` bigint(20) NOT NULL PRIMARY KEY AUTO_INCREMENT;" );
					}
				} 
				dbDelta( self::get_schema() );
				
			}
			
			
		private static function get_schema() {
			global $wpdb;
			
			$collate = '';
	
			if ( $wpdb->has_cap( 'collation' ) ) {
				$collate = $wpdb->get_charset_collate();
			}
	
			/*
			 * Indexes have a maximum size of 767 bytes. Historically, we haven't need to be concerned about that.
			 * As of WordPress 4.2, however, we moved to utf8mb4, which uses 4 bytes per character. This means that an index which
			 * used to have room for floor(767/3) = 255 characters, now only has room for floor(767/4) = 191 characters.
			 *
			 * This may cause duplicate index notices in logs due to https://core.trac.wordpress.org/ticket/34870 but dropping
			 * indexes first causes too much load on some servers/larger DB.
			 */
			$max_index_length = 191;
			
			$tables = "
				CREATE TABLE {$wpdb->prefix}rioc_d (
				  token_id bigint(20) NOT NULL AUTO_INCREMENT,
				  token_key char(32) NOT NULL,
				  token_expiry bigint(20) NOT NULL,
				  UNIQUE KEY token_id (token_id),
				  PRIMARY KEY  (token_key)
				) $collate;
			";
			return $tables;
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
			        return "notice notice-warning";
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
			// $_POST['class'] = $this->sighn_class_notice($_POST['data']['type']);
			// $_POST['ids'] = $this->notify_arr;
			
			if ($_POST['data']['type'] == 'success'){
				update_option('is_auth', true);
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

			$page_title = esc_html__( 'authentication', 'rioc-common' );
			$menu_title = esc_html__( '&nbsp; - authentication', 'rioc-common' );
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
			
			wp_enqueue_style( 'app-authentication-style', rioc_resource_url('rioc-auth.css', false,'common' ), array(), apply_filters( 'rioc_events_css_version', Register_In_One_Click__Main::VERSION ) );
			
		}

		/**
		 * Renders the rioc_Authentication page
		 */
		 
		public function do_menu_page() {
			
			if ( get_option('is_auth') ) {
		        include_once Register_In_One_Click__Main::instance()->plugin_path . 'src/admin-views/rioc-authentication.php';
		    }else{
		        include_once Register_In_One_Click__Main::instance()->plugin_path . 'src/admin-views/rioc-authentication.php';
		    }		
			
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
