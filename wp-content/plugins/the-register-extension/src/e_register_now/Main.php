<?php

class E__Register__Now__Tickets__Main {
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
		// ["name"=>"event-tickets", "class"=>"E__Register__Now", "v_def"=>"VERSION", "ptrn"=>"/event-tickets.php/", "ver"=>"4.2.4", "src"=>"http://.."],
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
		$this->plugin_path = trailingslashit( E__REGISTER__NOW__DIR );
		$this->plugin_dir  = trailingslashit( basename( $this->plugin_path ) );

		$dir_prefix = '';

		if ( false !== strstr( E__REGISTER__NOW__DIR, '/vendor/' ) ) {
			$dir_prefix = basename( dirname( dirname( E__REGISTER__NOW__DIR ) ) ) . '/vendor/';
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

		if (
			class_exists( 'TribeEvents', false )
			|| ( class_exists( 'E__Register__Now__Events__Main' ) && ! version_compare( E__Register__Now__Events__Main::VERSION, self::MIN_TEC_VERSION, '>=' ) )
		) {
			add_action( 'admin_notices', array( $this, 'tec_compatibility_notice' ) );

			/**
			 * Fires if Event Tickets cannot load due to compatibility or other problems.
			 */
			do_action( 'tribe_tickets_plugin_failed_to_load' );

			return;
		}

		$this->init_autoloading();

		// initialize the common libraries
		$this->common();

		E__Register__Now__Main::instance()->load_text_domain( 'event-tickets', $this->plugin_dir . 'lang/' );

		$this->hooks();

		$this->has_initialized = true;

		$this->rsvp();

		$this->user_event_confirmation_list_shortcode();

		// Load the Hooks on JSON_LD
		E__Register__Now__Tickets__JSON_LD__Order::hook();

		/**
		 * Fires once Event Tickets has completed basic setup.
		 */
		do_action( 'tribe_tickets_plugin_loaded' );
	}

	/**
	 * Hooked to admin_notices, this error is thrown when Event Tickets is run alongside a version of
	 * TEC that is too old
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
		$common_version = file_get_contents( $this->plugin_path . 'common/src/e_register_now/Main.php' );

		// if there isn't a ern-common version, bail
		if ( ! preg_match( "/const\s+VERSION\s*=\s*'([^']+)'/m", $common_version, $matches ) ) {
			add_action( 'admin_head', array( $this, 'missing_common_libs' ) );

			return;
		}

		$common_version = $matches[1];

		if ( empty( $GLOBALS['ern-common-info'] ) ) {
			$GLOBALS['ern-common-info'] = array(
				'dir' => "{$this->plugin_path}common/src/e_register_now",
				'version' => $common_version,
			);
		} elseif ( 1 == version_compare( $GLOBALS['ern-common-info']['version'], $common_version, '<' ) ) {
			$GLOBALS['ern-common-info'] = array(
				'dir' => "{$this->plugin_path}common/src/e_register_now",
				'version' => $common_version,
			);
		}
	}

	/**
	 * Common library object accessor method
	 */
	public function common() {
		static $common;

		if ( ! $common ) {
			$common = new E__Register__Now__Main( $this );
		}

		return $common;
	}

	/**
	 * Sets up autoloading
	 */
	protected function init_autoloading() {
		$prefixes = array(
			'E__Register__Now__Tickets__' => $this->plugin_path . 'src/e_register_now',
		);

		if ( ! class_exists( 'E__Register__Now__Autoloader' ) ) {
			require_once( $GLOBALS['ern-common-info']['dir'] . '/Autoloader.php' );

			$prefixes['E__Register__Now__'] = $GLOBALS['ern-common-info']['dir'];
		}

		$autoloader = E__Register__Now__Autoloader::instance();
		$autoloader->register_prefixes( $prefixes );

		require_once $this->plugin_path . 'src/template-tags/tickets.php';

		// deprecated classes are registered in a class to path fashion
		foreach ( glob( $this->plugin_path . '{common/src,src}/deprecated/*.php', GLOB_BRACE ) as $file ) {
			$class_name = str_replace( '.php', '', basename( $file ) );
			$autoloader->register_class( $class_name, $file );
		}

		$autoloader->register_autoloader();
	}

	/**
	 * set up hooks for this class
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'add_meta_boxes', array( 'E__Register__Now__Tickets__Metabox', 'maybe_add_meta_box' ) );
		add_action( 'admin_enqueue_scripts', array( 'E__Register__Now__Tickets__Metabox', 'add_admin_scripts' ) );
		add_filter( 'tribe_post_types', array( $this, 'inject_post_types' ) );

		// Setup Help Tab texting
		add_action( 'tribe_help_pre_get_sections', array( $this, 'add_help_section_support_content' ) );
		add_action( 'tribe_help_pre_get_sections', array( $this, 'add_help_section_featured_content' ) );
		add_action( 'tribe_help_pre_get_sections', array( $this, 'add_help_section_extra_content' ) );
		add_filter( 'tribe_support_registered_template_systems', array( $this, 'add_template_updates_check' ) );
		add_action( 'plugins_loaded', array( 'E__Register__Now__Support', 'getInstance' ) );
		add_action( 'tribe_events_single_event_after_the_meta', array( $this, 'add_linking_archor' ), 5 );

		// Hook to oembeds
		add_action( 'tribe_events_embed_after_the_cost_value', array( $this, 'inject_buy_button_into_oembed' ) );
		add_action( 'embed_head', array( $this, 'embed_head' ) );

		// CSV Import options
		if ( class_exists( 'E__Register__Now__Events__Main' ) ) {
			add_filter( 'tribe_events_import_options_rows', array( E__Register__Now__Tickets__CSV_Importer__Rows::instance(), 'filter_import_options_rows' ) );
			add_filter( 'tribe_event_import_rsvp_column_names', array( E__Register__Now__Tickets__CSV_Importer__Column_Names::instance(), 'filter_rsvp_column_names' ) );
			add_filter( 'tribe_events_import_rsvp_importer', array( 'E__Register__Now__Tickets__CSV_Importer__RSVP_Importer', 'instance' ), 10, 2 );
		}
	}

	/**
	 * Used to add our beloved tickets to the JSON-LD markup
	 *
	 * @deprecated
	 *
	 * @param  array   $data The actual json-ld variable
	 * @param  array   $args Arguments used to create the Markup
	 * @param  WP_Post $post What post does this referer too
	 * @return false
	 */
	public function inject_tickets_json_ld( $data, $args, $post ) {
		/**
		 * @todo remove this after 4.4
		 */
		_deprecated_function( __METHOD__, '4.2', 'E__Register__Now__Tickets__JSON_LD__Order' );

		return false;
	}

	/**
	 * Add an Anchor for users to be able to link to
	 * The height is to make sure it links on all browsers
	 *
	 * @return void
	 */
	public function add_linking_archor() {
		echo '<div id="buy-tickets" style="height: 1px;"></div>';
	}

	/**
	 * Append the text about Event Tickets to the support section on the Help page
	 *
	 * @filter "tribe_help_pre_get_sections"
	 * @param E__Register__Now__Admin__Help_Page $help The Help Page Instance
	 * @return void
	 */
	public function add_help_section_support_content( $help ) {
		$help->add_section_content( 'support', '<strong>' . esc_html__( 'Support for Event Tickets', 'event-tickets' ) . '</strong>', 20 );
		$help->add_section_content( 'support', array(
			'<strong><a href="http://m.tri.be/18ne" target="_blank">' . esc_html__( 'Settings overview', 'event-tickets' ) . '</a></strong>',
			'<strong><a href="http://m.tri.be/18nf" target="_blank">' . esc_html__( 'Features overview', 'event-tickets' ) . '</a></strong>',
			'<strong><a href="http://m.tri.be/18jb" target="_blank">' . esc_html__( 'Troubleshooting common problems', 'event-tickets' ) . '</a></strong>',
			'<strong><a href="http://m.tri.be/18ng" target="_blank">' . esc_html__( 'Customizing Event Tickets', 'event-tickets' ) . '</a></strong>',
		), 20 );
	}

	/**
	 * Append the text about Event Tickets to the Feature box section on the Help page
	 *
	 * @filter "tribe_help_pre_get_sections"
	 * @param E__Register__Now__Admin__Help_Page $help The Help Page Instance
	 * @return void
	 */
	public function add_help_section_featured_content( $help ) {
		// If The Events Calendar is active dont add
		if ( $help->is_active( 'the-events-calendar', true ) ) {
			return;
		}

		$link = '<a href="http://m.tri.be/18nd" target="_blank">' . esc_html__( 'New User Primer', 'event-tickets' ) . '</a>';

		$help->add_section_content( 'feature-box', sprintf( __( 'We are committed to helping you sell tickets for your event. Check out our handy %s to get started.', 'event-tickets' ), $link ), 20 );
	}

	/**
	 * Append the text about Event Tickets to the Extra Help section on the Help page
	 *
	 * @filter "tribe_help_pre_get_sections"
	 * @param E__Register__Now__Admin__Help_Page $help The Help Page Instance
	 * @return void
	 */
	public function add_help_section_extra_content( $help ) {
		if ( ! $help->is_active( array( 'events-calendar-pro', 'event-tickets-plus' ) ) && $help->is_active( 'the-events-calendar' ) ) {
			// We just skip because it's treated on TEC
			return;
		} elseif ( ! $help->is_active( 'the-events-calendar' ) ) {
			if ( ! $help->is_active( 'event-tickets-plus' ) ) {

				$link = '<a href="https://wordpress.org/support/plugin/event-tickets/" target="_blank">' . esc_html__( 'open-source forum on WordPress.org', 'event-tickets' ) . '</a>';
				$help->add_section_content( 'extra-help', sprintf( __( 'If you have tried the above steps and are still having trouble, you can post a new thread to our %s. Our support staff monitors these forums once a week and would be happy to assist you there.', 'event-tickets' ), $link ), 20 );

				$link_forum = '<a href="http://m.tri.be/4w/" target="_blank">' . esc_html__( 'premium support on our website', 'event-tickets' ) . '</a>';
				$link_plus = '<a href="http://m.tri.be/18ni" target="_blank">' . esc_html__( 'Events Tickets Plus', 'event-tickets' ) . '</a>';
				$help->add_section_content( 'extra-help', sprintf( __( 'Looking for more immediate support? We offer %1$s with the purchase of any of our premium plugins (like %2$s). Pick up a license and you can post there directly and expect a response within 24-48 hours during weekdays.', 'event-tickets' ), $link_forum, $link_plus ), 20 );

				$link = '<a href="http://m.tri.be/4w/" target="_blank">' . esc_html__( 'post a thread', 'event-tickets' ) . '</a>';
				$help->add_section_content( 'extra-help', sprintf( __( 'Already have Events Tickets Plus? You can %s in our premium support forums. Our support team monitors the forums and will respond to your thread within 24-48 hours (during the week).', 'event-tickets' ), $link ), 20 );

			}  else {

				$link = '<a href="http://m.tri.be/4w/" target="_blank">' . esc_html__( 'post a thread', 'event-tickets' ) . '</a>';
				$help->add_section_content( 'extra-help', sprintf( __( 'If you have a valid license for one of our paid plugins, you can %s in our premium support forums. Our support team monitors the forums and will respond to your thread within 24-48 hours (during the week).', 'event-tickets' ), $link ), 20 );

			}
		}
	}

	/**
	 * Register Event Tickets with the template update checker.
	 *
	 * @param array $plugins
	 *
	 * @return array
	 */
	public function add_template_updates_check( $plugins ) {
		$plugins[ __( 'Event Tickets', 'event-tickets' ) ] = array(
			self::VERSION,
			$this->plugin_path . 'src/views/tickets',
			trailingslashit( get_stylesheet_directory() ) . 'ern-events/tickets',
		);

		return $plugins;
	}

	/**
	 * Hooked to the init action
	 */
	public function init() {
		// Provide continued support for legacy ticketing modules
		$this->legacy_provider_support = new E__Register__Now__Tickets__Legacy_Provider_Support;

		$this->settings_tab();

		$this->tickets_view();

		E__Register__Now__Credits::init();
	}

	/**
	 * rsvp ticket object accessor
	 */
	public function rsvp() {
		return E__Register__Now__Tickets__RSVP::get_instance();
	}

	/**
	 * Creates the Tickets FrontEnd facing View class
	 *
	 * This will happen on `plugins_loaded` by default
	 *
	 * @return E__Register__Now__Tickets__Tickets_View
	 */
	public function tickets_view() {
		return E__Register__Now__Tickets__Tickets_View::hook();
	}

	/**
	 * Default attendee list shortcode handler.
	 *
	 * @return E__Register__Now__Tickets__Shortcodes__User_Event_Confirmation_List
	 */
	public function user_event_confirmation_list_shortcode() {
		if ( empty( $this->user_event_confirmation_list_shortcode ) ) {
			$this->user_event_confirmation_list_shortcode = new E__Register__Now__Tickets__Shortcodes__User_Event_Confirmation_List;
		}

		return $this->user_event_confirmation_list_shortcode;
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
			$settings = new E__Register__Now__Tickets__Admin__Ticket_Settings;
		}

		return $settings;
	}

	/**
	 * Returns the supported post types for tickets
	 */
	public function post_types() {
		$options = get_option( E__Register__Now__Main::OPTIONNAME, array() );

		// if the ticket-enabled-post-types index has never been set, default it to tribe_events
		if ( ! array_key_exists( 'ticket-enabled-post-types', $options ) ) {
			$options['ticket-enabled-post-types'] = array(
				'tribe_events',
			);
		}

		/**
		 * Filters the list of post types that support tickets
		 *
		 * @param array $post_types Array of post types
		 */
		return apply_filters( 'tribe_tickets_post_types', (array) $options['ticket-enabled-post-types'] );
	}

	/**
	 * Injects post types into the ern-common post_types array
	 */
	public function inject_post_types( $post_types ) {
		$post_types = array_merge( $post_types, $this->post_types() );
		return $post_types;
	}

	/**
	 * Injects a buy/RSVP button into oembeds for events when necessary
	 */
	public function inject_buy_button_into_oembed() {
		$event_id = get_the_ID();

		if ( ! tribe_events_has_tickets( $event_id ) ) {
			return;
		}

		$tickets      = E__Register__Now__Tickets__Tickets::get_all_e__register__now( $event_id );
		$has_non_rsvp = false;
		$available    = false;
		$now          = current_time( 'timestamp' );

		foreach ( $tickets as $ticket ) {
			if ( 'E__Register__Now__Tickets__RSVP' !== $ticket->provider_class ) {
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
		$button_text = apply_filters( 'e__register__now_embed_buy_button_text', $button_text, $event_id );

		ob_start();
		?>
		<a class="ern-event-buy" href="<?php echo esc_url( tribe_get_event_link() ); ?>" title="<?php the_title_attribute() ?>" rel="bookmark"><?php echo esc_html( $button_text ); ?></a>
		<?php
		$buy_button = ob_get_clean();

		/**
		 * Filters the buy button that appears on event oembeds
		 *
		 * @var string The button markup
		 * @var int Event ID
		 */
		echo apply_filters( 'e__register__now_embed_buy_button', $buy_button, $event_id );
	}

	/**
	 * Adds content to the embed head tag
	 *
	 * The embed header DOES NOT have wp_head() executed inside of it. Instead, any scripts/styles
	 * are explicitly output
	 */
	public function embed_head() {
		$css_path = E__Register__Now__Template_Factory::getMinFile( $this->plugin_url . 'src/resources/css/tickets-embed.css', true );
		$css_path = add_query_arg( 'ver', self::VERSION, $css_path );
		?>
		<link rel="stylesheet" id="ern-tickets-embed-css" href="<?php echo esc_url( $css_path ); ?>" type="text/css" media="all">
		<?php
	}
}
