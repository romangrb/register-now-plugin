<?php
	/**
	 * Listen for events and update their timestamps
	 */
	class E_Register_NowCache_Listener {

		private static $instance = null;
		private $cache    = null;

		/**
		 * Class constructor.
		 *
		 * @return void
		 */
		public function __construct() {
			$this->cache = new E_Register_NowCache();
		}

		/**
		 * Run the init functionality (like add_hooks).
		 *
		 * @return void
		 */
		public function init() {
			$this->add_hooks();
		}

		/**
		 * Add the hooks necessary.
		 *
		 * @return void
		 */
		private function add_hooks() {
			add_action( 'save_post', array( $this, 'save_post' ), 0, 2 );
			add_action( 'updated_option', array( $this, 'update_last_save_post' ) );
		}

		/**
		 * Run the caching functionality that is executed on save post.
		 *
		 * @param int     $post_id The post_id.
		 * @param WP_Post $post    The current post object being saved.
		 */
		public function save_post( $post_id, $post ) {
			if ( in_array( $post->post_type, E_Register_NowMain::get_post_types() ) ) {
				$this->cache->set_last_occurrence( 'save_post' );
			}
		}

		/**
		 * Run the caching functionality that is executed on saving tribe calendar options.
		 *
		 * @param string    $option
		 * @see 'updated_option'
		 */
		public function update_last_save_post( $option ) {
			if ( $option != 'tribe_last_save_post' ) {
				$this->cache->set_last_occurrence( 'save_post' );
			}
		}

		/**
		 * For any hook that doesn't need any additional filtering
		 *
		 * @param $method
		 * @param $args
		 */
		public function __call( $method, $args ) {
			$this->cache->set_last_occurrence( $method );
		}

		/**
		 * Instance method of the cache listener.
		 *
		 * @return E_Register_NowCache_Listener
		 */
		public static function instance() {
			if ( empty( self::$instance ) ) {
				self::$instance = self::create_listener();
			}

			return self::$instance;
		}

		/**
		 * Create a cache listener.
		 *
		 * @return E_Register_NowCache_Listener
		 */
		private static function create_listener() {
			$listener = new self();
			$listener->init();

			return $listener;
		}
	}
