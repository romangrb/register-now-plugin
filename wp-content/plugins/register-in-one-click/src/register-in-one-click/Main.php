<?php

class Register_In_One_Click__Tickets__Main {
	/**
	 * Instance of this class for use as singleton
	 */
	private static $instance;

	/**
	 * Current version of this plugin
	 */
	const VERSION = '4.2.5';

	/**
	 * Min required The Events Calendar version
	 */
	const MIN_TEC_VERSION = '4.2.2';

	const POSTTYPE		  = 'rioc-common';
	const TAXONOMY		  = 'rioc-common-cat';
	
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
	 * @var Register_In_One_Click__Tickets__Legacy_Provider_Support
	 */
	public $legacy_provider_support;

	/**
	 * @var Register_In_One_Click__Tickets__Shortcodes__User_Event_Confirmation_List
	 */
	private $user_event_confirmation_list_shortcode;

	private $has_initialized = false;

	/**
	 * Get (and instantiate, if necessary) the instance of the class
	 *
	 * @static
	 * @return Register_In_One_Click__Tickets__Woo__Main
	 */
	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	
	public $pluginName;
	public $singular_event_label;
	public $plural_event_label;
	public $rewriteSlug   = 'rioc_events';
	public $category_slug = 'rioc_category';
	
	/**
	 * Args for the event post type
	 * @var array
	 */
	protected $postTypeArgs = array(
		'public'          => true,
		'rewrite'         => array( 'slug' => 'rioc_events', 'with_front' => false ),
		'menu_position'   => 1,
		'supports'        => array(
			'title',
			'editor',
			'excerpt',
			'author',
			'thumbnail',
			'custom-fields',
			'comments',
			'revisions',
		),
		'map_meta_cap'    => true,
		'has_archive'     => true,
	);
	
	/**
	 * Args for venue post type
	 * @var array
	 */
	public $postVenueTypeArgs = array();

	protected $taxonomyLabels;
	
	/**
	 * Allow users to specify their own singular label for Events
	 * @return string
	 */
	public function get_event_label_singular() {
		return apply_filters( 'rioc_event_label_singular', esc_html__( 'Event', $this::POSTTYPE ) );
	}
	/**
	 * Allow users to specify their own plural label for Events
	 * @return string
	 */
	public function get_event_label_plural() {
		return apply_filters( 'tribe_event_label_plural', esc_html__( 'Events', 'the-events-calendar' ) );
	}

	/**
	 * Class constructor
	 */
	public function __construct() {
		/* Set up some parent's vars */
		$this->plugin_name = 'E-Register-Now';
		$this->plugin_slug = $this::POSTTYPE;
		$this->plugin_path = trailingslashit( EVENT_TICKETS_DIR );
		$this->plugin_dir  = trailingslashit( basename( $this->plugin_path ) );

		$dir_prefix = '';

		if ( false !== strstr( EVENT_TICKETS_DIR, '/vendor/' ) ) {
			$dir_prefix = basename( dirname( dirname( EVENT_TICKETS_DIR ) ) ) . '/vendor/';
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
			|| ( class_exists( 'Register_In_One_Click__Events__Main' ) && ! version_compare( Register_In_One_Click__Events__Main::VERSION, self::MIN_TEC_VERSION, '>=' ) )
		) {
			add_action( 'admin_notices', array( $this, 'tec_compatibility_notice' ) );

			/**
			 * Fires if Event Tickets cannot load due to compatibility or other problems.
			 */
			do_action( 'rioc_tickets_plugin_failed_to_load' );
			
			return;
		}
		
		$this->init_autoloading();
		
		// initialize the common libraries
		$this->common();
		
		Register_In_One_Click__Main::instance()->load_text_domain( 'event-tickets', $this->plugin_dir . 'lang/' );
		
		$this->hooks();
		
		$this->has_initialized = true;

		$this->rsvp();

		$this->user_event_confirmation_list_shortcode();

		// Load the Hooks on JSON_LD
		Register_In_One_Click__Tickets__JSON_LD__Order::hook();

		/**
		 * Fires once Event Tickets has completed basic setup.
		 */
		do_action( 'rioc_tickets_plugin_loaded' );
	}

	/**
	 * Hooked to admin_notices, this error is thrown when Event Tickets is run alongside a version of
	 * TEC that is too old
	 */
	public function tec_compatibility_notice() {
		$active_plugins = get_option( 'active_plugins' );

		$plugin_short_path = null;

		foreach ( $active_plugins as $plugin ) {
			if ( false !== strstr( $plugin, $this::POSTTYPE . 'php' ) ) {
				$plugin_short_path = $plugin;
				break;
			}
		}

		$upgrade_path      = wp_nonce_url(
			add_query_arg(
				array(
					'action' => 'upgrade-plugin',
					'plugin' => $plugin_short_path,
				), get_admin_url() . 'update.php'
			), 'upgrade-plugin_' . $plugin_short_path
		);
		$output = '<div class="error">';
		$output .= '<p>' . sprintf( __( 'When The Events Calendar and Event Tickets are both activated, The Events Calendar must be running version %1$s or greater. Please %2$supdate now.%3$s', 'event-tickets' ), self::MIN_TEC_VERSION, '<a href="' . esc_url( $upgrade_path ) . '">', '</a>' ) . '</p>';
		$output .= '</div>';

		echo $output;
	}

	public function maybe_set_common_lib_info() {
		$common_version = file_get_contents( $this->plugin_path . 'common/src/register-in-one-click/Main.php' );

		// if there isn't a rioc-common version, bail
		if ( ! preg_match( "/const\s+VERSION\s*=\s*'([^']+)'/m", $common_version, $matches ) ) {
			add_action( 'admin_head', array( $this, 'missing_common_libs' ) );

			return;
		}

		$common_version = $matches[1];

		if ( empty( $GLOBALS['rioc-common-info'] ) ) {
			$GLOBALS['rioc-common-info'] = array(
				'dir' => "{$this->plugin_path}common/src/register-in-one-click",
				'version' => $common_version,
			);
		} elseif ( 1 == version_compare( $GLOBALS['rioc-common-info']['version'], $common_version, '<' ) ) {
			$GLOBALS['rioc-common-info'] = array(
				'dir' => "{$this->plugin_path}common/src/register-in-one-click",
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
			$common = new Register_In_One_Click__Main( $this );
		}

		return $common;
	}

	/**
	 * Sets up autoloading
	 */
	protected function init_autoloading() {
		$prefixes = array(
			'Register_In_One_Click__Tickets__' => $this->plugin_path . 'src/register-in-one-click',
		);

		if ( ! class_exists( 'Register_In_One_Click__Autoloader' ) ) {
			require_once( $GLOBALS['rioc-common-info']['dir'] . '/Autoloader.php' );

			$prefixes['Register_In_One_Click__'] = $GLOBALS['rioc-common-info']['dir'];
		}

		$autoloader = Register_In_One_Click__Autoloader::instance();
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
		add_action( 'add_meta_boxes', array( 'Register_In_One_Click__Tickets__Metabox', 'maybe_add_meta_box' ) );
		add_action( 'admin_enqueue_scripts', array( 'Register_In_One_Click__Tickets__Metabox', 'add_admin_scripts' ) );
		add_filter( 'rioc_post_types', array( $this, 'inject_post_types' ) );

		// Setup Help Tab texting
		// add_action( 'rioc_help_pre_get_sections', array( $this, 'add_help_section_support_content' ) );
		// add_action( 'rioc_help_pre_get_sections', array( $this, 'add_help_section_featured_content' ) );
		// add_action( 'rioc_help_pre_get_sections', array( $this, 'add_help_section_extra_content' ) );
		add_filter( 'rioc_support_registered_template_systems', array( $this, 'add_template_updates_check' ) );
		add_action( 'plugins_loaded', array( 'Register_In_One_Click__Support', 'getInstance' ) );
		add_action( 'rioc_events_single_event_after_the_meta', array( $this, 'add_linking_archor' ), 5 );

		// Hook to oembeds
		add_action( 'rioc_events_embed_after_the_cost_value', array( $this, 'inject_buy_button_into_oembed' ) );
		add_action( 'embed_head', array( $this, 'embed_head' ) );

		// CSV Import options
		if ( class_exists( 'Register_In_One_Click__Events__Main' ) ) {
			add_filter( 'rioc_events_import_options_rows', array( Register_In_One_Click__Tickets__CSV_Importer__Rows::instance(), 'filter_import_options_rows' ) );
			add_filter( 'rioc_event_import_rsvp_column_names', array( Register_In_One_Click__Tickets__CSV_Importer__Column_Names::instance(), 'filter_rsvp_column_names' ) );
			add_filter( 'rioc_events_import_rsvp_importer', array( 'Register_In_One_Click__Tickets__CSV_Importer__RSVP_Importer', 'instance' ), 10, 2 );
	    }
	}
	
	/**
	 * Register the post types.
	 */
	public function registerPostType() {
		$this->generatePostTypeLabels();
		register_post_type( self::POSTTYPE, apply_filters( 'rioc_type_args', $this->postTypeArgs ) );

		// Setup Linked Posts singleton after we've set up the post types that we care about
		// Tribe__Events__Linked_Posts::instance();

		register_taxonomy(
			self::TAXONOMY, self::POSTTYPE, array(
				'hierarchical'          => true,
				'update_count_callback' => '',
				'rewrite'               => array(
					'slug'         => $this->rewriteSlug . '/' . $this->category_slug,
					'with_front'   => false,
					'hierarchical' => true,
				),
				'public'                => true,
				'show_ui'               => true,
				'labels'                => $this->taxonomyLabels
			)
		);

		if ( Tribe__Settings_Manager::get_option( 'showComments', 'no' ) == 'yes' ) {
			add_post_type_support( self::POSTTYPE, 'comments' );
		}

	}
	/**
	 * Generate custom post type lables
	 */
	protected function generatePostTypeLabels() {
		/**
		 * Provides an opportunity to modify the labels used for the event post type.
		 *
		 * @var array
		 */
		$this->postTypeArgs['labels'] = apply_filters( 'rioc_post_type_labels', array(

			'name'               => $this->pluginName,
			'singular_name'      => $this->pluginName,
			'add_new'            => sprintf( esc_html__( 'Add New %s', 'rioc' ), $this->singular_event_label ),
			'add_new_item'       => sprintf( esc_html__( 'Add New %s', 'rioc' ), $this->singular_event_label ),
			'edit_item'          => sprintf( esc_html__( 'Edit %s', 'rioc' ), $this->singular_event_label ),
			'new_item'           => sprintf( esc_html__( 'New %s', 'rioc' ), $this->singular_event_label ),
			'view_item'          => sprintf( esc_html__( 'View %s', 'rioc' ), $this->singular_event_label ),
			'search_items'       => sprintf( esc_html__( 'Search %s', 'rioc' ), $this->singular_event_label ),
			'not_found'          => sprintf( esc_html__( 'No %s found', 'rioc' ), $this->singular_event_label ),
			'not_found_in_trash' => sprintf( esc_html__( 'No %s found in Trash', 'rioc' ), $this->singular_event_label ),
		) );

		/**
		 * Provides an opportunity to modify the labels used for the event category taxonomy.
		 *
		 * @var array
		 */
		$this->taxonomyLabels = apply_filters( 'rioc_taxonomy_labels', array(

			'name'              => sprintf( esc_html__( '%s Categories', 'rioc' ), $this->singular_event_label ),
			'singular_name'     => sprintf( esc_html__( '%s Category', 'rioc' ), $this->singular_event_label ),
			'search_items'      => sprintf( esc_html__( 'Search %s Categories', 'rioc' ), $this->singular_event_label ),
			'all_items'         => sprintf( esc_html__( 'All %s Categories', 'rioc' ), $this->singular_event_label ),
			'parent_item'       => sprintf( esc_html__( 'Parent %s Category', 'rioc' ), $this->singular_event_label ),
			'parent_item_colon' => sprintf( esc_html__( 'Parent %s Category:', 'rioc' ), $this->singular_event_label ),
			'edit_item'         => sprintf( esc_html__( 'Edit %s Category', 'rioc' ), $this->singular_event_label ),
			'update_item'       => sprintf( esc_html__( 'Update %s Category', 'rioc' ), $this->singular_event_label ),
			'add_new_item'      => sprintf( esc_html__( 'Add New %s Category', 'rioc' ), $this->singular_event_label ),
			'new_item_name'     => sprintf( esc_html__( 'New %s Category Name', 'rioc' ), $this->singular_event_label ),
		) );
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
		_deprecated_function( __METHOD__, '4.2', 'E_teter_Now__Tickets__JSON_LD__Order' );

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
	 * @filter "rioc_help_pre_get_sections"
	 * @param Register_In_One_Click__Admin__Help_Page $help The Help Page Instance
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
	 * @filter "rioc_help_pre_get_sections"
	 * @param Register_In_One_Click__Admin__Help_Page $help The Help Page Instance
	 * @return void
	 */
	public function add_help_section_featured_content( $help ) {
		// If The Events Calendar is active dont add
		if ( $help->is_active( 'rioc-common', true ) ) {
			return;
		}

		$link = '<a href="http://m.tri.be/18nd" target="_blank">' . esc_html__( 'New User Primer', 'event-tickets' ) . '</a>';

		$help->add_section_content( 'feature-box', sprintf( __( 'We are committed to helping you sell tickets for your event. Check out our handy %s to get started.', 'event-tickets' ), $link ), 20 );
	}

	/**
	 * Append the text about Event Tickets to the Extra Help section on the Help page
	 *
	 * @filter "rioc_help_pre_get_sections"
	 * @param Register_In_One_Click__Admin__Help_Page $help The Help Page Instance
	 * @return void
	 */
	public function add_help_section_extra_content( $help ) {
		if ( ! $help->is_active( array( 'events-calendar-pro', 'event-tickets-plus' ) ) && $help->is_active( 'rioc-common' ) ) {
			// We just skip because it's treated on TEC
			return;
		} elseif ( ! $help->is_active( 'rioc-common' ) ) {
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
			trailingslashit( get_stylesheet_directory() ) . 'rioc-events/tickets',
		);

		return $plugins;
	}

	/**
	 * Hooked to the init action
	 */
	public function init() {
		
		$this->pluginName = $this->plugin_name  = esc_html__( 'Register In One Click', 'rioc-common' );
		$this->singular_event_label				= $this->get_event_label_singular();
		$this->plural_event_label				= $this->get_event_label_plural();
		
		// Provide continued support for legacy ticketing modules
		
		$this->legacy_provider_support = new Register_In_One_Click__Tickets__Legacy_Provider_Support;
		
		$this->registerPostType();
		
		$this->settings_tab();
		
		$this->tickets_view();

		Register_In_One_Click__Credits::init();
	}

	/**
	 * rsvp ticket object accessor
	 */
	public function rsvp() {
		return Register_In_One_Click__Tickets__RSVP::get_instance();
	}

	/**
	 * Creates the Tickets FrontEnd facing View class
	 *
	 * This will happen on `plugins_loaded` by default
	 *
	 * @return Register_In_One_Click__Tickets__Tickets_View
	 */
	public function tickets_view() {
		return Register_In_One_Click__Tickets__Tickets_View::hook();
	}

	/**
	 * Default attendee list shortcode handler.
	 *
	 * @return Register_In_One_Click__Tickets__Shortcodes__User_Event_Confirmation_List
	 */
	public function user_event_confirmation_list_shortcode() {
		if ( empty( $this->user_event_confirmation_list_shortcode ) ) {
			$this->user_event_confirmation_list_shortcode = new Register_In_One_Click__Tickets__Shortcodes__User_Event_Confirmation_List;
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
			$version = apply_filters( 'rioc_tickets_css_version', self::VERSION );
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
			$version = apply_filters( 'rioc_tickets_js_version', self::VERSION );
		}

		return $version;
	}

	/**
	 * settings page object accessor
	 */
	public function settings_tab() {
		static $settings;

		if ( ! $settings ) {
			$settings = new Register_In_One_Click__Tickets__Admin__Ticket_Settings;
		}

		return $settings;
	}

	/**
	 * Returns the supported post types for tickets
	 */
	public function post_types() {
		$options = get_option( Register_In_One_Click__Main::OPTIONNAME, array() );

		// if the ticket-enabled-post-types index has never been set, default it to rioc_events
		if ( ! array_key_exists( 'ticket-enabled-post-types', $options ) ) {
			$options['ticket-enabled-post-types'] = array(
				'rioc_events',
			);
		}

		/**
		 * Filters the list of post types that support tickets
		 *
		 * @param array $post_types Array of post types
		 */
		return apply_filters( 'rioc_tickets_post_types', (array) $options['ticket-enabled-post-types'] );
	}

	/**
	 * Injects post types into the rioc-common post_types array
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

		if ( ! rioc_events_has_tickets( $event_id ) ) {
			return;
		}

		$tickets      = Register_In_One_Click__Tickets__Tickets::get_all_event_tickets( $event_id );
		$has_non_rsvp = false;
		$available    = false;
		$now          = current_time( 'timestamp' );

		foreach ( $tickets as $ticket ) {
			if ( 'Register_In_One_Click__Tickets__RSVP' !== $ticket->provider_class ) {
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
		<a class="rioc-event-buy" href="<?php echo esc_url( rioc_get_event_link() ); ?>" title="<?php the_title_attribute() ?>" rel="bookmark"><?php echo esc_html( $button_text ); ?></a>
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
		$css_path = Register_In_One_Click__Template_Factory::getMinFile( $this->plugin_url . 'src/resources/css/tickets-embed.css', true );
		$css_path = add_query_arg( 'ver', self::VERSION, $css_path );
		?>
		<link rel="stylesheet" id="rioc-tickets-embed-css" href="<?php echo esc_url( $css_path ); ?>" type="text/css" media="all">
		<?php
	}
}
