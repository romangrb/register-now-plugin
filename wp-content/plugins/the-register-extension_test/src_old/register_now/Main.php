<?php

class Register__Now__Main {
	/**
	 * Instance of this class for use as singleton
	 */
	private static $instance;

	/**
	 * Current version of this plugin
	 */
	const VERSION = '1.1.0';

	/**
	 * Min required The Events Calendar version
	 */
	const MIN_TEC_VERSION = '1.1.0';

	/**
	 * Name of the provider
	 * @var
	 */
	public $plugin_name;

	/**
	 * Directory of the plugin
	 * @var
	 */
	public $plugin_dir;

	/**
	 * Path of the plugin
	 * @var
	 */
	public $plugin_path;

	/**
	 * URL of the plugin
	 * @var
	 */
	public $plugin_url;
    
    /**
	 * Bool of plugin initialization 
	 * @var
	 */
    
    private $has_initialized = false;
	/**
	 * Get (and instantiate, if necessary) the instance of the class
	 *
	 * @static
	 * @return Register__Now__Woo__Main
	 */
	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	
	/**
	 * Plugin dependencies  
	 * @var
	 */
	
	private  $dependencies_plugin = array(
		["name"=>"event-tickets", "class"=>"Tribe__Tickets__Main", "v_def"=>"VERSION", "ptrn"=>"/event-tickets.php/", "ver"=>"4.2.4", "src"=>"http://.."],
		["name"=>"the-events-calendar", "class"=>"Tribe__Events__Main", "v_def"=>"VERSION", "ptrn"=>"/the-events-calendar.php/", "ver"=>"4.2.4", "src"=>"http://.."],
		["name"=>"woocommerce-gateway-stripe", "class"=>"Tribe__Events__Main","v_def"=>"WC_STRIPE_VERSION", "ptrn"=>"/woocommerce-gateway-stripe.php/", "ver"=>"3.0.2", "src"=>"http://.."],
		["name"=>"woocommerce", "class"=>"Tribe__Events__Main", "v_def"=>"WC_VERSION", "ptrn"=>"/woocommerce.php/", "ver"=>"2.6.4", "src"=>"http://.."]);
	/**
	 * Class constructor
	 */
	public function __construct() {
		/* Set up some parent's vars */
		$this->plugin_name = 'E-Register-Now';
		$this->plugin_slug = 'register_now';
		$this->plugin_path = trailingslashit( REGISTER_NOW_DIR );
		$this->plugin_dir  = trailingslashit( basename( $this->plugin_path ) );

		$dir_prefix = '';

		if ( false !== strstr( REGISTER_NOW_DIR, '/vendor/' ) ) {
			$dir_prefix = basename( dirname( dirname( REGISTER_NOW_DIR ) ) ) . '/vendor/';
		}

		$this->plugin_url = trailingslashit( plugins_url( $dir_prefix . $this->plugin_dir ) );
		
		$this->maybe_set_common_lib_info();
		
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 0 );
	}

	/**
	 * Finalize the initialization of this plugin
	 */
	public function plugins_loaded() {
		// It's possible we'll have initialized already (if the plugin has been embedded as a vendor lib
		// within another plugin, for example) in which case we need not repeat the process
		if ( $this->has_initialized ) {
			return;
		}

		if ($this->tec_compatibility_check()) {
				
			add_action( 'admin_notices', array( $this, 'tec_compatibility_notice' ) );

			/**
			 * Fires if Register Now cannot load due to compatibility or other problems.
			 */
			do_action( 'tribe_tickets_plugin_failed_to_load' );

			return;
		}

		$this->init_autoloading();

		// initialize the common libraries

		$this->hooks();

		$this->has_initialized = true;

		// $this->rsvp();

		// $this->user_event_confirmation_list_shortcode();

		// Load the Hooks on JSON_LD
		// Tribe__Tickets__JSON_LD__Order::hook();

		/**
		 * Fires once Register Now has completed basic setup.
		 */
		 do_action( 'register_now_plugin_loaded' );
	}
	
	private $plugins_need_to_install_or_update;
	/**
	 * Hooked to admin_check, this function chech TEC compabilities of plugin
	 */
	public function tec_compatibility_check() {
		
		$active_plugins = get_option( 'active_plugins' );
		$dependencies_plugin = $this->dependencies_plugin;
		$plugins_need_to_install_or_update = &$this->plugins_need_to_install_or_update;
    	$plugins_need_to_install_or_update = $dependencies_plugin;

	    foreach ($dependencies_plugin as $require_p => $value) {
	        foreach ($active_plugins  as $actived_p) {
	        	
	            if (preg_match($value['ptrn'], $actived_p)){
					$class_def = $value['class'].'::'.$value['v_def'];
				
		            if (class_exists($value['class'])&&defined($value['v_def'])||defined($class_def)){
		            	
		            	$current_ver = defined($value['v_def'])? $value['v_def'] : $class_def;
		            	
						if (version_compare( $current_ver, $value['ver'], '<=' )) unset($plugins_need_to_install_or_update[$require_p]);

		            }
	            } 
	        }
	    }
	    
	    return (count($plugins_need_to_install_or_update))? true : false;
	    
	} 
	
	/**
	 * Hooked to admin_notices, this error is thrown when Register Now dependencies is run alongside a version of
	 * TEC that is non compatibility
	 */
	 
	public function tec_compatibility_notice() {
		
		$target = $this->plugins_need_to_install_or_update;
		$message = '';
	    
	    foreach ($target as $key => $value) {
	    	$dwn_link = $value['src'];
	    	$message .= "<br>" . $value['name'] . "<a href=$dwn_link> version " .  $value['ver'] . "</a>"; 
	    }
		
		$output  = '<div class="error">';
		$output .= '<p>' . sprintf( __( "The Register Now dependence on those plugin/s. $message")) . '</p>';
		$output .= '</div>';

		echo $output;
	}

	public function maybe_set_common_lib_info() {
		$common_version = file_get_contents( $this->plugin_path . 'common/src/register_now/main.php' );

		// if there isn't a tribe-common version, bail
		if ( ! preg_match( "/const\s+VERSION\s*=\s*'([^']+)'/m", $common_version, $matches ) ) {
			add_action( 'admin_head', array( $this, 'missing_common_libs' ) );

			return;
		}

		$common_version = $matches[1];

		if ( empty( $GLOBALS['register-now-common-info'] ) ) {
			$GLOBALS['register-now-common-info'] = array(
				'dir' => "{$this->plugin_path}common/src/register_now",
				'version' => $common_version,
			);
		} elseif ( 1 == version_compare( $GLOBALS['tribe-common-info']['version'], $common_version, '<' ) ) {
			$GLOBALS['register-now-common-info'] = array(
				'dir' => "{$this->plugin_path}common/src/register_now",
				'version' => $common_version,
			);
		}
	}

	/**
	 * Sets up autoloading
	 */
	protected function init_autoloading() {
		$prefixes = array(
			'Register__Now__' => $this->plugin_path . 'src/register_now',
		);

		if ( ! class_exists( 'Register__Now__Autoloader' ) ) {
			require_once( $GLOBALS['register-now-common-info']['dir'] . '/autoloader.php' );
		
			$prefixes['Register__Now__Common'] = $GLOBALS['register-now-common-info']['dir'];
		}
	
		$autoloader = Register__Now__Autoloader::instance();
		var_dump($autoloader);
		$autoloader->register_prefixes( $prefixes );

		// require_once $this->plugin_path . 'src/template-tags/tickets.php';

		$autoloader->register_autoloader();
	}

	/**
	 * set up hooks for this class
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'init' ) );
// 		add_action( 'add_meta_boxes', array( 'Tribe__Tickets__Metabox', 'maybe_add_meta_box' ) );
// 		add_action( 'admin_enqueue_scripts', array( 'Tribe__Tickets__Metabox', 'add_admin_scripts' ) );
// 		add_filter( 'tribe_post_types', array( $this, 'inject_post_types' ) );

// 		// Setup Help Tab texting
// 		add_action( 'tribe_help_pre_get_sections', array( $this, 'add_help_section_support_content' ) );
// 		add_action( 'tribe_help_pre_get_sections', array( $this, 'add_help_section_featured_content' ) );
// 		add_action( 'tribe_help_pre_get_sections', array( $this, 'add_help_section_extra_content' ) );
// 		add_filter( 'tribe_support_registered_template_systems', array( $this, 'add_template_updates_check' ) );
 		// add_action( 'plugins_loaded', array( 'Register__Now__Support', 'getInstance' ) );

// 		// Hook to oembeds
// 		add_action( 'tribe_events_embed_after_the_cost_value', array( $this, 'inject_buy_button_into_oembed' ) );
// 		add_action( 'embed_head', array( $this, 'embed_head' ) );

		// CSV Import options
		// if ( class_exists( 'Tribe__Events__Main' ) ) {
		// 	add_filter( 'tribe_events_import_options_rows', array( Tribe__Tickets__CSV_Importer__Rows::instance(), 'filter_import_options_rows' ) );
		// 	add_filter( 'tribe_event_import_rsvp_column_names', array( Tribe__Tickets__CSV_Importer__Column_Names::instance(), 'filter_rsvp_column_names' ) );
		// 	add_filter( 'tribe_events_import_rsvp_importer', array( 'Tribe__Tickets__CSV_Importer__RSVP_Importer', 'instance' ), 10, 2 );
		// }
	}

	/**
	 * Hooked to the init action
	 */
	public function init() {
		// Provide continued support for legacy ticketing modules
		// $this->legacy_provider_support = new Tribe__Tickets__Legacy_Provider_Support;

		// $this->settings_tab();

//		$this->tickets_view();

 		// Tribe__Credits::init();
	}

	/**
	 * rsvp ticket object accessor
	 */
	// public function rsvp() {
	// 	return Tribe__Tickets__RSVP::get_instance();
	// }

	/**
	 * Creates the Register Now FrontEnd facing View class
	 *
	 * This will happen on `plugins_loaded` by default
	 *
	 * @return Register_Now_View
	 */
	public function tickets_view() {
		return Tribe__Tickets__Tickets_View::hook();
	}

	/**
	 * Provides the CSS version number for CSS files
	 *
	 * @return string
	 */
	public function css_version() {
		static $version;

		if ( ! $version ) {
			$version = apply_filters( 'tribe_tickets_css_version', self::VERSION );
		}

		return $version;
	}

	/**
	 * Provides the JS version number for JS scripts
	 *
	 * @return string
	 */
	public function js_version() {
		static $version;

		if ( ! $version ) {
			$version = apply_filters( 'tribe_tickets_js_version', self::VERSION );
		}

		return $version;
	}
	
	/**
	 * settings page object accessor
	 */
	public function settings_tab() {
		static $settings;

		if ( ! $settings ) {
			$settings = new Register__Now__Settings;
		}

		return $settings;
	}

	/**
	 * Injects a register button into oembeds for events when necessary
	 */
	public function inject_buy_button_into_oembed() {
		$event_id = get_the_ID();

		if ( ! tribe_events_has_tickets( $event_id ) ) {
			return;
		}

		$tickets      = Tribe__Tickets__Tickets::get_all_event_tickets( $event_id );
		$has_non_rsvp = false;
		$available    = false;
		$now          = current_time( 'timestamp' );

		foreach ( $tickets as $ticket ) {
			if ( 'Tribe__Tickets__RSVP' !== $ticket->provider_class ) {
				$has_non_rsvp = true;
			}

			if (
				$ticket->date_in_range( $now )
				&& $ticket->is_in_stock()
			) {
				$available = true;
			}
		}

		// if there aren't any tickets available, bail
		if ( ! $available ) {
			return;
		}

		$button_text = $has_non_rsvp ? __( 'Buy', 'event-tickets' ) : __( 'RSVP', 'event-tickets' );
		/**
		 * Filters the text that appears in the buy/rsvp button on event oembeds
		 *
		 * @var string The button text
		 * @var int Event ID
		 */
		$button_text = apply_filters( 'event_tickets_embed_buy_button_text', $button_text, $event_id );

		ob_start();
		?>
		<a class="tribe-event-buy" href="<?php echo esc_url( tribe_get_event_link() ); ?>" title="<?php the_title_attribute() ?>" rel="bookmark"><?php echo esc_html( $button_text ); ?></a>
		<?php
		$buy_button = ob_get_clean();

		/**
		 * Filters the buy button that appears on event oembeds
		 *
		 * @var string The button markup
		 * @var int Event ID
		 */
		echo apply_filters( 'event_tickets_embed_buy_button', $buy_button, $event_id );
	}

	/**
	 * Adds content to the embed head tag
	 *
	 * The embed header DOES NOT have wp_head() executed inside of it. Instead, any scripts/styles
	 * are explicitly output
	 */
	public function embed_head() {
		$css_path = Tribe__Template_Factory::getMinFile( $this->plugin_url . 'src/resources/css/tickets-embed.css', true );
		$css_path = add_query_arg( 'ver', self::VERSION, $css_path );
		?>
		<link rel="stylesheet" id="tribe-tickets-embed-css" href="<?php echo esc_url( $css_path ); ?>" type="text/css" media="all">
		<?php
	}
}
