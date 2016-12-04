<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'Register_In_One_Click__Authentication' ) ) {
	/**
	 * Class that handles the integration with our Shop App API
	 */
	class Register_In_One_Click__Authentication extends Register_In_One_Click__Abstract_Menu_Page {

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
		private $admin_page 	 = null;
		
		private $token 			 = '';
		 
		/**
		 * Class constructor
		 */
		public function __construct() {

			add_action( 'init_rioc_tables', array( __CLASS__, 'install_rioc_tables' ), 5 );
			add_action( 'admin_menu', array( $this, 'add_menu_page' ), 120 );
			add_action( 'wp_footer', array( $this, 'enqueue_script') );
			add_action( 'wp_footer', array( $this, 'enqueue_style') );
			add_action( 'wp_ajax_refresh_token_f_md', array( $this, 'refresh_token_f_md' ) );
			add_action( 'wp_ajax_get_token_f_md', array( $this, 'get_token_f_md') );
			// for test init token
			$this->get_init_token();
	        
		}
		
		public function enqueue_script() {
			
			wp_enqueue_script('ajax_token_handler', rioc_resource_url('refresh-tkn.js', false, 'common' ), array('jquery'), apply_filters( 'rioc_events_js_version', Register_In_One_Click__Main::VERSION ), array( 'jquery' ) );
			wp_localize_script('ajax_token_handler', 'token_handler', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'cnt_tkn'  => $this->token,
				'nounce_tkn' => wp_create_nonce("ajax_secret_qazxswredcfv_nounce")
			));
			wp_enqueue_script('ajax_token_example', rioc_resource_url('token-example.js', false, 'common' ), array('jquery'), apply_filters( 'rioc_events_js_version', Register_In_One_Click__Main::VERSION ), array( 'jquery' ) );
			
		}
		
		public function get_curr_tkn(){
			
			$result = Register_In_One_Click__Query_Db_Rioc::instance()->get_token();
			return ($result) ? $result : '';
			
		}
			
		protected function get_init_token() {
			
			$this->token = $this->get_curr_tkn();
			
		}
		
		public function get_token_f_md() {
			// define if this AJAX request
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) { 
				
				check_ajax_referer( 'ajax_secret_qazxswredcfv_nounce', 'security');
				echo json_encode(array('token_key'=>$this->token));
			}
			die();
		}
			
		public function refresh_token_f_md() {
			// define if this AJAX request
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) { 
				
				check_ajax_referer( 'ajax_secret_qazxswredcfv_nounce', 'security');
				
				/*if (empty($_REQUEST['token_hash']['token']) ||
					empty($_REQUEST['token_hash']['token_expire'])){
				die();
				}*/
				
				$tmp_d = array('token_key'=>(string)$_REQUEST['token_hash']['token_key'], 
							   'token_expire'=>(int)$_REQUEST['token_hash']['token_expire'],
							   'token_life'=>(int)$_REQUEST['token_hash']['token_life'],
							   'refresh_token'=>(int)$_REQUEST['token_hash']['refresh_token']);	
				// save token into db
				Register_In_One_Click__Query_Db_Rioc::instance()->refresh_token($tmp_d);
				
				echo json_encode($_REQUEST['token_hash']);
			}
			die();
		}
		
		protected static function install_rioc_tables() {
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
			
			$sql_f_check_valid_key = "
			CREATE DEFINER=`root`@`localhost` FUNCTION `get_valid_key`(`KEY_ID` INT) RETURNS text CHARSET latin1
			    READS SQL DATA
			    COMMENT 'check and return token_key if valid on time limit'
			BEGIN
			
			DECLARE TOKEN_EXPIRE1, TOKEN_LIFE1 INT;
			DECLARE TOKEN_KEY1 TEXT DEFAULT '';
			
			SELECT `token_key`, `token_life`, `token_expire` INTO TOKEN_KEY1, TOKEN_LIFE1, TOKEN_EXPIRE1
			FROM {$wpdb->prefix}rioc_d WHERE token_id = KEY_ID;
			
			IF (((UNIX_TIMESTAMP()) - TOKEN_LIFE1) < TOKEN_EXPIRE1)
				THEN 
					RETURN TOKEN_KEY1; 
				ELSE 
					RETURN '';				 
				END IF;	
			END;
			";
			mysqli_multi_query($wpdb->dbh,	$sql_f_check_valid_key);
			
		
			
			$tables = "
				CREATE TABLE {$wpdb->prefix}rioc_d (
				  token_id bigint(20) NOT NULL AUTO_INCREMENT,
				  token_key char(32) NOT NULL,
				  token_expire bigint(20) NOT NULL,
				  token_life   bigint(20) NOT NULL,
				  refresh_token  char(32) NOT NULL,
				  UNIQUE KEY token_id (token_id),
				  PRIMARY KEY  (token_key)
				) $collate;
			";
			return $tables;
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
			$this->admin_page = add_submenu_page( $where, $page_title, $menu_title, $capability, self::AUTH_SLUG, array( $this, 'do_menu_page' ) );
			
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
