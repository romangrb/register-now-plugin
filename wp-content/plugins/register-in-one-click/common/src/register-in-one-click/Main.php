<?php
/**
 * Main Tribe Common class.
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( class_exists( 'Register_In_One_Click__Main' ) ) {
	return;
}

class Register_In_One_Click__Main {
	const EVENTSERROROPT      = '_rioc_events_errors';
	const OPTIONNAME          = 'rioc_events_calendar_options';
	const OPTIONNAMENETWORK   = 'rioc_events_calendar_network_options';

	const VERSION           = '4.2.4dev';
	const FEED_URL          = 'https://theeventscalendar.com/feed/';

	protected $plugin_context;
	protected $plugin_context_class;
	protected $doing_ajax = false;
	protected $log;

	public static $rioc_url = 'http://tri.be/';
	public static $tec_url  = 'http://theeventscalendar.com/';

	public $plugin_dir;
	public $plugin_path;
	public $plugin_url;

	/**
	 * constructor
	 */
	public function __construct( $context = null ) {
		if ( is_object( $context ) ) {
			$this->plugin_context = $context;
			$this->plugin_context_class = get_class( $context );
		}
		
		
		
		$this->plugin_path = trailingslashit( dirname( dirname( dirname( __FILE__ ) ) ) );
		$this->plugin_dir  = trailingslashit( basename( $this->plugin_path ) );
		$this->plugin_url  = plugins_url( $this->plugin_dir );

		$this->load_text_domain( 'rioc-common', basename( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) . '/common/lang/' );

		$this->init_autoloading();

		$this->init_libraries();
		$this->add_hooks();

		$this->doing_ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;
	}

	/**
	 * Get's the instantiated context of this class. I.e. the object that instantiated this one.
	 */
	public function context() {
		return $this->plugin_context;
	}

	/**
	 * Setup the autoloader for common files
	 */
	protected function init_autoloading() {
		if ( ! class_exists( 'Register_In_One_Click__Autoloader' ) ) {
			require_once dirname( __FILE__ ) . '/Autoloader.php';
		}

		$prefixes = array( 'Register_In_One_Click__' => dirname( __FILE__ ) );
		$autoloader = Register_In_One_Click__Autoloader::instance();
		$autoloader->register_prefixes( $prefixes );
		$autoloader->register_autoloader();
	}

	/**
	 * Get's the class name of the instantiated plugin context of this class. I.e. the class name of the object that instantiated this one.
	 */
	public function context_class() {
		return $this->plugin_context_class;
	}

	/**
	 * initializes all required libraries
	 */
	public function init_libraries() {
		Register_In_One_Click__Debug::instance();
		Register_In_One_Click__Settings_Manager::instance();

		require_once $this->plugin_path . 'src/functions/template-tags/general.php';
		require_once $this->plugin_path . 'src/functions/template-tags/date.php';

		// Starting the log manager needs to wait until after the rioc_*_option() functions have loaded
		$this->log = new Register_In_One_Click__Log();
	}

	/**
	 * Registers resources that can/should be enqueued
	 */
	public function register_resources() {
		$resources_url = plugins_url( 'src/resources', dirname( dirname( __FILE__ ) ) );
		
		wp_register_script(
			'rioc-jquery-form-validator',
			$resources_url . '/jquery-form-validator/form-validator/jquery.form-validator.min.js',
			array(
				'jquery',
			),
			apply_filters( 'rioc_events_css_version', self::VERSION ),
			true
		);
		
		// wp_register_style(
		// 	'rioc-bootstrap-theme-v3.3.7',
		// 	$resources_url . '/bootstrap/dist/css/bootstrap.min.css',
		// 	array(),
		// 	apply_filters( 'rioc_events_css_version', self::VERSION ),
		// 	false
		// );

		wp_register_style(
			'rioc-common-admin',
			$resources_url . '/css/rioc-common-admin.css',
			array(),
			apply_filters( 'rioc_events_css_version', self::VERSION )
		);

		wp_register_script(
			'ba-dotimeout',
			$resources_url . '/js/jquery.ba-dotimeout.js',
			array(
				'jquery',
			),
			apply_filters( 'rioc_events_css_version', self::VERSION ),
			true
		);

		wp_register_script(
			'rioc-inline-bumpdown',
			$resources_url . '/js/inline-bumpdown.js',
			array(
				'ba-dotimeout',
			),
			apply_filters( 'rioc_events_css_version', self::VERSION ),
			true
		);

		wp_register_script(
			'rioc-notice-dismiss',
			$resources_url . '/js/notice-dismiss.js',
			array( 'jquery' ),
			apply_filters( 'rioc_events_css_version', self::VERSION ),
			true
		);
	}

	/**
	 * Registers vendor assets that can/should be enqueued
	 */
	public function register_vendor() {
		$vendor_base = plugins_url( 'vendor', dirname( dirname( __FILE__ ) ) );
		
		wp_register_style(
			'rioc-jquery-ui-theme',
			$vendor_base . '/jquery/ui.theme.css',
			array(),
			apply_filters( 'rioc_events_css_version', self::VERSION )
		);

		wp_register_style(
			'rioc-jquery-ui-datepicker',
			$vendor_base . '/jquery/ui.datepicker.css',
			array( 'rioc-jquery-ui-theme' ),
			apply_filters( 'rioc_events_css_version', self::VERSION )
		);

	}

	/**
	 * Adds core hooks
	 */
	public function add_hooks() {
		add_action( 'plugins_loaded', array( 'Register_In_One_Click__Initialization', 'instance' ) );
		
		add_action( 'plugins_loaded', array( 'Register_In_One_Click__Authentication', 'instance' ) );
		add_action( 'plugins_loaded', array( 'Register_In_One_Click__Registration', 'instance' ) );
		add_action( 'plugins_loaded', array( 'Register_In_One_Click__Configuration', 'instance' ) );
		add_action( 'plugins_loaded', array( 'Register_In_One_Click__App_Shop', 'instance' ) );
		// hook for install table
		register_activation_hook( __FILE__, array( 'RIOC_Table_Install', 'install' ) );
		// Register for the assets to be availble everywhere
		add_action( 'init', array( $this, 'register_resources' ), 1 );
		add_action( 'init', array( $this, 'register_vendor' ), 1 );

		// Enqueue only when needed (admin)
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}
	

	/**
	 * A Helper method to load text domain
	 * First it tries to load the wp-content/languages translation then if falls to the
	 * try to load $dir language files
	 *
	 * @param string $domain The text domain that will be loaded
	 * @param string $dir    What directory should be used to try to load if the default doenst work
	 *
	 * @return bool  If it was able to load the text domain
	 */
	public function load_text_domain( $domain, $dir = false ) {
		// Added safety just in case this runs twice...
		if ( is_textdomain_loaded( $domain ) && ! is_a( $GLOBALS['l10n'][ $domain ], 'NOOP_Translations' ) ) {
			return true;
		}

		$locale = get_locale();
		$mofile = WP_LANG_DIR . '/plugins/' . $domain . '-' . $locale . '.mo';

		/**
		 * Allows users to filter which file will be loaded for a given text domain
		 * Be careful when using this filter, it will apply across the whole plugin suite.
		 *
		 * @param string      $mofile The path for the .mo File
		 * @param string      $domain Which plugin domain we are trying to load
		 * @param string      $locale Which Language we will load
		 * @param string|bool $dir    If there was a custom directory passed on the method call
		 */
		$mofile = apply_filters( 'rioc_load_text_domain', $mofile, $domain, $locale, $dir );

		$loaded = load_plugin_textdomain( $domain, false, $mofile );

		if ( $dir !== false && ! $loaded ) {
			return load_plugin_textdomain( $domain, false, $dir );
		}

		return $loaded;
	}

	public function admin_enqueue_scripts() {
		
		wp_enqueue_style( 'rioc-bootstrap-theme-v3.3.7' );
		
		wp_enqueue_script( 'rioc-inline-bumpdown' );
		wp_enqueue_script( 'rioc-notice-dismiss' );
		wp_enqueue_style( 'rioc-common-admin' );
		
		wp_enqueue_script( 'rioc-jquery-form-validator' );

		$helper = Register_In_One_Click__Admin__Helpers::instance();
		if ( $helper->is_post_type_screen() ) {
			wp_enqueue_style( 'rioc-jquery-ui-datepicker' );
		}
	}

	/**
	 * @return Register_In_One_Click__Log
	 */
	public function log() {
		return $this->log;
	}

	/**
	 * Returns the post types registered by Tribe plugins
	 */
	public static function get_post_types() {
		// we default the post type array to empty in rioc-common. Plugins like TEC add to it
		return apply_filters( 'rioc_post_types', array() );
	}

	/**
	 * Insert an array after a specified key within another array.
	 *
	 * @param $key
	 * @param $source_array
	 * @param $insert_array
	 *
	 * @return array
	 *
	 */
	public static function array_insert_after_key( $key, $source_array, $insert_array ) {
		if ( array_key_exists( $key, $source_array ) ) {
			$position     = array_search( $key, array_keys( $source_array ) ) + 1;
			$source_array = array_slice( $source_array, 0, $position, true ) + $insert_array + array_slice( $source_array, $position, null, true );
		} else {
			// If no key is found, then add it to the end of the array.
			$source_array += $insert_array;
		}

		return $source_array;
	}

	/**
	 * Insert an array immediately before a specified key within another array.
	 *
	 * @param $key
	 * @param $source_array
	 * @param $insert_array
	 *
	 * @return array
	 */
	public static function array_insert_before_key( $key, $source_array, $insert_array ) {
		if ( array_key_exists( $key, $source_array ) ) {
			$position     = array_search( $key, array_keys( $source_array ) );
			$source_array = array_slice( $source_array, 0, $position, true ) + $insert_array + array_slice( $source_array, $position, null, true );
		} else {
			// If no key is found, then add it to the end of the array.
			$source_array += $insert_array;
		}

		return $source_array;
	}

	/**
	 * Helper function for getting Post Id. Accepts null or a post id. If no $post object exists, returns false to avoid a PHP NOTICE
	 *
	 * @param int $post (optional)
	 *
	 * @return int post ID or False
	 */
	public static function post_id_helper( $post = null ) {
		if ( ! is_null( $post ) && is_numeric( $post ) > 0 ) {
			return (int) $post;
		} elseif ( is_object( $post ) && ! empty( $post->ID ) ) {
			return (int) $post->ID;
		} else {
			if ( ! empty( $GLOBALS['post'] ) && $GLOBALS['post'] instanceof WP_Post ) {
				return get_the_ID();
			} else {
				return false;
			}
		}
	}

	/**
	 * Helper function to indicate whether the current execution context is AJAX
	 *
	 * This method exists to allow us test code that behaves differently depending on the execution
	 * context.
	 *
	 * @since 4.0
	 * @return boolean
	 */
	public function doing_ajax( $doing_ajax = null ) {
		if ( ! is_null( $doing_ajax ) ) {
			$this->doing_ajax = $doing_ajax;
		}

		return $this->doing_ajax;
	}

	/**
	 * Static Singleton Factory Method
	 *
	 * @return Register_In_One_Click__Main
	 */
	public static function instance() {
		static $instance;

		if ( ! $instance ) {
			$instance = new self;
		}

		return $instance;
	}
}
