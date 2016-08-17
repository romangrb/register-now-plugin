<?php

	if ( ! class_exists( 'Register__Now__Autoloader' ) ) {
		/**
		 * Class Register__Now__Autoloader
		 *
		 * Allows for autoloading of Tribe plugins classes.
		 *
		 **/
		 
		class Register__Now__Autoloader {

			/**
			 * @var Register__Now__Autoloader
			 */
			protected static $instance;

			/**
			 * An arrays of arrays each containing absolute paths.
			 *
			 * Paths are stored trimming any trailing `/`.
			 * E.g. `/var/www/register-now/wp-content/plugins/the-register-now/src/register`
			 *
			 * @var string[][]
			 */
			protected $prefixes;

			/**
			 * The string acting as a directory separator in a class name.
			 *
			 * E.g.: given `__` as `$dir_separator` then `Admin__Metabox__Some_Metabox`
			 * will map to `/Admin/Metabox/SomeMetabox.php`.
			 *
			 * @var string
			 */
			protected $dir_separator = '__';

			/** @var string[] */
			protected $fallback_dirs = array();

			/**
			 * @var array
			 */
			protected $class_paths = array();

			/**
			 * Returns the singleton instance of the class.
			 *
			 * @return Tribe__Autoloader
			 */
			public static function instance() {
				if ( ! self::$instance instanceof Register__Now__Autoloader ) {
					self::$instance = new self();
				}

				return self::$instance;
			}

			/**
			 * Registers prefixes and root dirs using an array.
			 *
			 * Same as calling `register_prefix` on each one.
			 *
			 * @param array $prefixes_to_root_dirs
			 */
			public function register_prefixes( array $prefixes_to_root_dirs ) {
				foreach ( $prefixes_to_root_dirs as $prefix => $root_dir ) {
					$this->register_prefix( $prefix, $root_dir );
				}
			}

			/**
			 * Associates a class prefix to an absolute path.
			 *
			 * @param string $prefix   A class prefix, e.g. `Register__Admin__`
			 * @param string $root_dir The absolute path to the dir containing
			 *                         the prefixed classes.
			 */
			public function register_prefix( $prefix, $root_dir ) {
				$root_dir = $this->normalize_root_dir( $root_dir );

				if ( ! isset( $this->prefixes[ $prefix ] ) ) {
					$this->prefixes[ $prefix ] = array();
				}
				$this->prefixes[ $prefix ][] = $root_dir;
			}

			/**
			 * Triggers the registration of the autoload method in the SPL
			 * autoload register.
			 */
			public function register_autoloader() {
				spl_autoload_register( array( $this, 'autoload' ) );
			}

			/**
			 * Includes the file defining a class.
			 *
			 * This is the function that's registered as an autoloader.
			 *
			 * @param string $class
			 */
			public function autoload( $class ) {
				$include_path = $this->get_class_path( $class );
				if ( ! empty( $include_path ) ) {
					include_once( $include_path );
				}
			}

			private function normalize_root_dir( $root_dir ) {
				return rtrim( $root_dir, '/' );
			}

			protected function get_prefixed_path( $class ) {
				foreach ( $this->prefixes as $prefix => $dirs ) {
					if ( strpos( $class, $prefix ) !== 0 ) {
						continue;
					}
					$class_name = str_replace( $prefix, '', $class );
					$class_path_frag = implode( '/', explode( $this->dir_separator, $class_name ) ) . '.php';
					foreach ( $dirs as $dir ) {
						$path = $dir . '/' . $class_path_frag;
						if ( ! file_exists( $path ) ) {
							// check if the file exists in lowercase
							$class_path_frag = strtolower( $class_path_frag );
							$path = $dir . '/' . $class_path_frag;
						}
						if ( ! file_exists( $path ) ) {
							continue;
						}

						return $path;
					}
				}
				return false;
			}

			protected function get_fallback_path( $class ) {
				foreach ( $this->fallback_dirs as $fallback_dir ) {
					$include_path = $fallback_dir . '/' . $class . '.php';
					if ( ! file_exists( $include_path ) ) {
						// check if the file exists in lowercase
						$class = strtolower( $class );
						$include_path = $fallback_dir . '/' . $class . '.php';
					}
					if ( ! file_exists( $include_path ) ) {
						continue;
					}

					return $include_path;
				}
			}

			/**
			 * Gets the absolute path to a class file.
			 *
			 * @param string $class The class name
			 *
			 * @return string Either the absolute path to the class file or an
			 *                empty string if the file was not found.
			 */
			public function get_class_path( $class ) {
				$prefixed_path = $this->get_prefixed_path( $class );
				if ( $prefixed_path ) {
					return $prefixed_path;
				}

				$class_path = ! empty( $this->class_paths[ $class ] ) ? $this->class_paths[ $class ] :false;
				if ( $class_path ) {
					return $class_path;
				}

				$fallback_path = $this->get_fallback_path( $class );

				return $fallback_path ? $fallback_path : '';
			}

			/**
			 * Adds a folder to search for classes that were not found among
			 * the prefixed ones.
			 *
			 * This is the method to use to register a directory of deprecated
			 * classes.
			 *
			 * @param string $dir An absolute path dto a dir.
			 */
			public function add_fallback_dir( $dir ) {
				if ( in_array( $dir, $this->fallback_dirs ) ) {
					return;
				}
				$this->fallback_dirs[] = $this->normalize_root_dir( $dir );
			}

			/**
			 * @return string
			 */
			public function get_dir_separator() {
				return $this->dir_separator;
			}

			/**
			 * @param string $dir_separator
			 */
			public function set_dir_separator( $dir_separator ) {
				$this->dir_separator = $dir_separator;
			}

			public function register_class( $class, $path ) {
				$this->class_paths[ $class ] = $path;
			}
		}
	}
