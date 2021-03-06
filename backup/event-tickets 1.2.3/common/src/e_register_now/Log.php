<?php
if ( class_exists( 'E_Register_NowLog' ) ) {
	return;
}

/**
 * Provides access to and management of core logging facilities.
 */
class E_Register_NowLog {
	const DISABLE = 'disable';
	const DEBUG   = 'debug';
	const WARNING = 'warning';
	const ERROR   = 'error';
	const CLEANUP = 'tribe_common_log_cleanup';

	/**
	 * @var E_Register_NowLog__Admin
	 */
	protected $admin;

	/**
	 * @var E_Register_NowLog__Logger
	 */
	protected $current_logger;

	/**
	 * @var string
	 */
	protected $current_level;

	/**
	 * All logging levels in priority order. Each level is represented by
	 * an array in the form [ code => description ].
	 *
	 * @var array
	 */
	protected $levels = array();

	/**
	 * Alternative representation of the $levels property allowing quick look
	 * up of levels by priority.
	 *
	 * @var array
	 */
	protected $prioritized_levels = array();

	/**
	 * Instantiated loggers, stored for re-use.
	 *
	 * @var array
	 */
	protected $loggers = array();


	public function __construct() {
		if ( is_admin() ) {
			$this->admin = new E_Register_NowLog__Admin();
		}

		$this->current_level = $this->get_level();
		$this->log_cleanup();
	}

	/**
	 * @return E_Register_NowLog__Admin
	 */
	public function admin() {
		return $this->admin;
	}

	/**
	 * Facilitates daily cleanup and log rotation.
	 */
	protected function log_cleanup() {
		$this->register_cleanup_task();
		do_action( self::CLEANUP, array( $this, 'do_cleanup' ) );
	}

	/**
	 * Schedules a daily cleanup task if one is not already in place.
	 */
	protected function register_cleanup_task() {
		if ( ! wp_next_scheduled( self::CLEANUP ) ) {
			wp_schedule_event( strtotime( '+1 day' ), 'daily', self::CLEANUP );
		}
	}

	/**
	 * Call the cleanup() method for each available logging engine.
	 *
	 * We don't just call it on the current engine since, if there was a recent change,
	 * we'll generally still want the now unused engine's output to be cleaned up.
	 */
	public function do_cleanup() {
		foreach ( $this->get_logging_engines() as $engine ) {
			/**
			 * @var E_Register_NowLog__Logger $engine
			 */
			$engine->cleanup();
		}
	}

	/**
	 * Logs a debug-level entry.
	 *
	 * @param string $entry
	 * @param string $src
	 */
	public function log_debug( $entry, $src ) {
		$this->log( $entry, self::DEBUG, $src );
	}

	/**
	 * Logs a warning.
	 *
	 * @param string $entry
	 * @param string $src
	 */
	public function log_warning( $entry, $src ) {
		$this->log( $entry, self::WARNING, $src );
	}

	/**
	 * Logs an error.
	 *
	 * @param string $entry
	 * @param string $src
	 */
	public function log_error( $entry, $src ) {
		$this->log( $entry, self::ERROR, $src );
	}

	/**
	 * Adds an entry to the log (if it is at the appropriate level, etc).
	 *
	 * This is simply a shorthand for calling log() on the current logger.
	 */
	public function log( $entry, $type = self::DEBUG, $src = '' ) {
		if ( $this->should_log( $type ) ) {
			$this->get_current_logger()->log( $entry, $type, $src );
		}
	}

	/**
	 * Returns a list of available logging engines as an array where each
	 * key is the classname and the value is the logger itself.
	 *
	 * @return array
	 */
	public function get_logging_engines() {
		$available_engines = array();
		$bundled_engines   = array(
			'E_Register_NowLog__File_Logger',
		);

		foreach ( $bundled_engines as $engine_class ) {
			$engine = $this->get_engine( $engine_class );

			// Check that we have a valid engine that is available for use in the current environment
			if ( $engine && $engine->is_available() ) {
				$available_engines[ $engine_class ] = $engine;
			}
		}

		/**
		 * Offers a chance to modify the array of currently available logging engines.
		 *
		 * The array is organized with each key as the class name of the logging
		 * implementation and the matching value is the actual logger object.
		 *
		 * @var array $available_engines
		 */
		return apply_filters( 'tribe_common_logging_engines', $available_engines );
	}

	/**
	 * Returns the currently active logger or null if none is set/none are
	 * available.
	 *
	 * @return E_Register_NowLog__Logger|null
	 */
	public function get_current_logger() {
		if ( ! $this->current_logger ) {
			$engine = tribe_get_option( 'logging_class', null );
			$available = $this->get_logging_engines();

			if ( empty( $engine ) || ! isset( $available[ $engine ] ) ) {
				return null;
			}

			$this->current_logger = $this->get_engine( $engine );
		}

		return $this->current_logger;
	}

	/**
	 * Sets the current logging engine to the provided class (if it is a valid
	 * and currently available logging class, else will set this to null - ie
	 * no logging).
	 *
	 * @param string $engine
	 *
	 * @throws Exception if the specified logging engine is invalid
	 */
	public function set_current_logger( $engine ) {
		$available_engines = $this->get_logging_engines();

		if ( ! isset( $available_engines[ $engine ] ) ) {
			throw new Exception( sprintf( __( 'Cannot set %s as the current logging engine', 'tribe-common' ), $engine ) );
		}

		tribe_update_option( 'logging_class', $engine );
		$this->current_logger = $available_engines[ $engine ];
	}

	/**
	 * Attempts to return the requested logging object or null if that
	 * is not possible.
	 *
	 * @param $class_name
	 *
	 * @return E_Register_NowLog__Logger|null
	 */
	public function get_engine( $class_name ) {
		if ( ! isset( $this->loggers[ $class_name ] ) ) {
			$object = new $class_name;

			if ( is_a( $object, 'E_Register_NowLog__Logger' ) ) {
				$this->loggers[ $class_name ] = new $class_name();
			}
		}

		if ( isset( $this->loggers[ $class_name ] ) ) {
			return $this->loggers[ $class_name ];
		}

		return null;
	}

	/**
	 * Sets the current logging level to the provided level (if it is a valid
	 * level, else will set the level to 'default').
	 *
	 * @param string $level
	 */
	public function set_level( $level ) {
		$available_levels = wp_list_pluck( $this->get_logging_levels(), 0 );

		if ( ! in_array( $level, $available_levels ) ) {
			$level = self::DISABLE;
		}

		tribe_update_option( 'logging_level', $level );
		$this->current_level = $level;
	}

	/**
	 * Returns the current logging level as a string.
	 *
	 * @return string
	 */
	public function get_level() {
		$current_level = tribe_get_option( 'logging_level', null );
		$available_levels = wp_list_pluck( $this->get_logging_levels(), 0 );

		if ( ! in_array( $current_level, $available_levels ) ) {
			$current_level = self::DISABLE;
		}

		return $current_level;
	}

	/**
	 * Returns a list of logging levels.
	 *
	 * The format is an array of arrays, each inner array being comprised of the
	 * level code (index 0) and a human readable description (index 1).
	 *
	 * The ordering of the inner arrays is critical as it dictates what will be logged
	 * when a given logging level is in effect. Example: if the current logging level
	 * is "error" mode (only record error-level problems) then debug-level notices will
	 * *not* be recorded and nor will warnings.
	 *
	 * On the other hand, if the current logging level is "debug" then debug level
	 * notices *and* all higher levels (including warnings and errors) will be recorded.
	 *
	 * @return array
	 */
	public function get_logging_levels() {
		if ( empty( $this->levels ) ) {
			/**
			 * Provides an opportunity to add or remove logging levels. This is expected
			 * to be organized as an array of arrays: the ordering of each inner array
			 * is critical, see E_Register_NowLog::get_logging_levels() docs.
			 *
			 * General form:
			 *
			 *     [
			 *         [ 'disable' => 'description' ],  // * Do not log anything
			 *         [ 'error'   => 'description' ],  // ^ Log only the most critical problems
			 *         [ 'warning' => 'description' ],  // | ...
			 *         [ 'debug'   => 'description' ]   // v Log as much data as possible, including less important trivia
			 *     ]
			 *
			 * @param array $logging_levels
			 */
			$this->levels = (array) apply_filters( 'tribe_common_logging_levels', array(
				array( self::DISABLE, __( 'Disabled', 'tribe-common' ) ),
				array( self::ERROR,   __( 'Only errors', 'tribe-common' ) ),
				array( self::WARNING, __( 'Warnings and errors', 'tribe-common' ) ),
				array( self::DEBUG,   __( 'Full debug (all events)', 'tribe-common' ) ),
			) );
		}

		return $this->levels;
	}

	/**
	 * Indicates if errors relating to the specified logging level should indeed
	 * be logged.
	 *
	 * Examples if the current logging level is "warning" (log all warnings and errors):
	 *
	 *     * Returns true for "error"
	 *     * Returns true for "warning"
	 *     * Returns false for "debug"
	 *
	 * The above assumes we are using the default logging levels.
	 *
	 * @param string $level_code
	 *
	 * @return bool
	 */
	protected function should_log( $level_code ) {
		if ( empty( $this->prioritized_levels ) ) {
			$this->build_prioritized_levels();
		}

		return $this->prioritized_levels[ $level_code ] <= $this->prioritized_levels[ $this->current_level ];
	}

	/**
	 * Creates a second list of logging levels allowing easy lookup of
	 * their relative priorities (ie, a means of quickly checking if
	 * an "error" level entry should be recorded when we're in debug
	 * mode).
	 */
	protected function build_prioritized_levels() {
		foreach ( $this->get_logging_levels() as $index => $level_data ) {
			$this->prioritized_levels[ $level_data[1] ] = $index;
		}
	}
}