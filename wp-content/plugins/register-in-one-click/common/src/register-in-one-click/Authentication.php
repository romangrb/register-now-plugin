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
			
 
        register_activation_hook( 'active', array( $this, 'activate') );
        register_deactivation_hook( 'deactivate', array( $this, 'deactivate') );
 
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
 
        // Display the admin notice only if it hasn't been hidden
        // if( false == get_option( 'hide_ajax_notification' ) ) {
            add_action( 'admin_notices', array( $this, 'display_admin_notice' ) );
        // } // end if
 
        add_action( 'wp_ajax_hide_admin_notification', array( $this, 'hide_admin_notification' ) );
			
			
			
		}
		
		// display custom admin notice
		public function shapeSpace_custom_admin_notice() { ?>
			
			<div class="notice notice-success is-dismissible">
				<p><?php _e('Congratulations, you did it!', 'shapeSpace'); ?></p>
			</div>
			
		<?php }
		
		
		
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

	/*--------------------------------------------*
     * Core Functions
     *---------------------------------------------*/
 
    /**
     * Upon activation, add a new option used to track whether or not to display the notification.
     */
    public function activate() {
        add_option( 'hide_ajax_notification', false );
    } // end activate
 
    /**
     * Upon deactivation, removes the option that was created when the plugin was activated.
     */
    public function deactivate() {
        delete_option( 'hide_ajax_notification' );
    } // end deactivate
 
    /**
     * Registers and enqueues admin-specific minified JavaScript.
     */
    public function register_admin_scripts() {
		wp_register_script('ajax-notification-admin', rioc_resource_url('test.js', false, 'common' ), array('jquery'), apply_filters( 'rioc_events_js_version', Register_In_One_Click__Main::VERSION ), array( 'jquery' ) );
        wp_enqueue_script('ajax-notification-admin', rioc_resource_url('test.js', false, 'common' ), array(), apply_filters( 'rioc_events_js_version', Register_In_One_Click__Main::VERSION ) );
        
        
 
    } // end register_admin_scripts
 
    /**
     * Renders the administration notice. Also renders a hidden nonce used for security when processing the Ajax request.
     */
    public function display_admin_notice() {
 
        $html = '<div id="ajax-notification" class="updated">';
            $html .= '<p>';
                $html .= __( 'The Ajax Notification example plugin is active. This message will appear until you choose to <a href="javascript:;" id="dismiss-ajax-notification">dismiss it</a>.', 'ajax-notification' );
            $html .= '</p>';
            $html .= '<span id="ajax-notification-nonce" class="hidden">' . wp_create_nonce( 'ajax-notification-nonce' ) . '</span>';
        $html .= '</div><!-- /.updated -->';
 
        echo $html;
 
    } // end display_admin_notice
 
    /**
     * JavaScript callback used to hide the administration notice when the 'Dismiss' anchor is clicked on the front end.
     */
    public function hide_admin_notification() {
 
        // First, check the nonce to make sure it matches what we created when displaying the message.
        // If not, we won't do anything.
        if( wp_verify_nonce( $_REQUEST['nonce'], 'ajax-notification-nonce' ) ) {
 
            // If the update to the option is successful, send 1 back to the browser;
            // Otherwise, send 0.
            if( update_option( 'hide_ajax_notification', true ) ) {
                die( '1' );
            } else {
                die( '0' );
            } // end if/else
 
        } // end if
 
    } // end hide_admin_notification
    
    
    
    
    
    
		
		public function myajax_inputtitleSubmit_func() {
			// check nonce
			$nonce = $_POST['nextNonce'];
			if ( ! wp_verify_nonce( $nonce, 'myajax-next-nonce' ) ) {
				die ( 'forbidden request !' );
			}
			
						$response = array(
			   'what'=>'foobar',
			   'action'=>'update_something',
			   'id'=>'1',
			   'data'=>'<p><strong>Hello world!</strong></p>'
			);
			$xmlResponse = new WP_Ajax_Response($response);
			$xmlResponse->send();
		    // $this->shapeSpace_custom_admin_notice();
			// generate the response
			
			// response output
			// header( "Content-Type: application/json" );
			// echo $response;
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
					
			include_once Register_In_One_Click__Main::instance()->plugin_path . 'src/admin-views/rioc-authentication.php';
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
