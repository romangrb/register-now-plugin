<?php
/**
 * Display functions (template-tags) for use in WordPress templates.
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'E_Register_Now__Main' ) ) {
	return;
}

if ( ! function_exists( 'e_rn_get_option' ) ) {
	/**
	 * Get Options
	 *
	 * Retrieve specific key from options array, optionally provide a default return value
	 *
	 * @category Events
	 * @param string $optionName Name of the option to retrieve.
	 * @param string $default    Value to return if no such option is found.
	 *
	 * @return mixed Value of the option if found.
	 * @todo Abstract this function out of template tags or otherwise secure it from other namespace conflicts.
	 */
	function e_rn_get_option( $optionName, $default = '' ) {
		return apply_filters( 'e_rn_get_option', E_Register_Now__Settings_Manager::get_option( $optionName, $default ), $optionName, $default );
	}
}//end if

if ( ! function_exists( 'e_rn_update_option' ) ) {
	/**
	 * Update Option
	 *
	 * Set specific key from options array, optionally provide a default return value
	 *
	 * @category Events
	 * @param string $optionName Name of the option to retrieve.
	 * @param string $value      Value to save
	 *
	 * @return void
	 */
	function e_rn_update_option( $optionName, $value ) {
		E_Register_Now__Settings_Manager::set_option( $optionName, $value );
	}
}//end if

if ( ! function_exists( 'e_rn_get_network_option' ) ) {
	/**
	 * Get Network Options
	 *
	 * Retrieve specific key from options array, optionally provide a default return value
	 *
	 * @category Events
	 * @param string $optionName Name of the option to retrieve.
	 * @param string $default    Value to return if no such option is found.
	 *
	 * @return mixed Value of the option if found.
	 * @todo Abstract this function out of template tags or otherwise secure it from other namespace conflicts.
	 */
	function e_rn_get_network_option( $optionName, $default = '' ) {
		return E_Register_Now__Settings_Manager::get_network_option( $optionName, $default );
	}
}

if ( ! function_exists( 'e_rn_resource_url' ) ) {
	/**
	 * Returns or echoes a url to a file in the Events Calendar plugin resources directory
	 *
	 * @category Events
	 * @param string $resource the filename of the resource
	 * @param bool   $echo     whether or not to echo the url
	 * @param string $root_dir directory to hunt for resource files (src or common)
	 *
	 * @return string
	 **/
	function e_rn_resource_url( $resource, $echo = false, $root_dir = 'src' ) {
		$extension = pathinfo( $resource, PATHINFO_EXTENSION );

		if ( 'src' !== $root_dir ) {
			$root_dir .= '/src';
		}

		$resources_path = $root_dir . '/resources/';
		switch ( $extension ) {
			case 'css':
				$resource_path = $resources_path .'css/';
				break;
			case 'js':
				$resource_path = $resources_path .'js/';
				break;
			case 'scss':
				$resource_path = $resources_path .'scss/';
				break;
			default:
				$resource_path = $resources_path;
				break;
		}

		$path = $resource_path . $resource;

		$plugin_path = trailingslashit( dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) );
		$plugin_dir  = trailingslashit( basename( $plugin_path ) );
		$url  = plugins_url( $plugin_dir );

		/**
		 * Filters the resource URL
		 *
		 * @param $url
		 * @param $resource
		 */
		$url = apply_filters( 'e_rn_resource_url', $url . $path, $resource );

		/**
		 * Deprected the e_rn_events_resource_url filter in 4.0 in favor of e_rn_resource_url. Remove in 5.0
		 */
		$url = apply_filters( 'e_rn_events_resource_url', $url, $resource );

		if ( $echo ) {
			echo $url;
		}

		return $url;
	}
}//end if

if ( ! function_exists( 'e_rn_multi_line_remove_empty_lines' ) ) {
	/**
	 * helper function to remove empty lines from multi-line strings
	 *
	 * @category Events
	 * @link http://stackoverflow.com/questions/709669/how-do-i-remove-blank-lines-from-text-in-php
	 *
	 * @param string $multi_line_string a multiline string
	 *
	 * @return string the same string without empty lines
	 */
	function e_rn_multi_line_remove_empty_lines( $multi_line_string ) {
		return preg_replace( "/^\n+|^[\t\s]*\n+/m", '', $multi_line_string );
	}
}//end if

if ( ! function_exists( 'e_rn_get_date_format' ) ) {
	/**
	 * Get the date format specified in the tribe options
	 *
	 * @category Events
	 * @param bool $with_year
	 *
	 * @return mixed
	 */
	function e_rn_get_date_format( $with_year = false ) {
		if ( $with_year ) {
			$format = e_rn_get_date_option( 'dateWithYearFormat', get_option( 'date_format' ) );
		} else {
			$format = e_rn_get_date_option( 'dateWithoutYearFormat', 'F j' );
		}

		return apply_filters( 'e_rn_date_format', $format );
	}
}//end if

if ( ! function_exists( 'e_rn_get_datetime_format' ) ) {
	/**
	 * Get the Datetime Format
	 *
	 * @category Events
	 *
	 * @param bool $with_year
	 *
	 * @return mixed|void
	 */
	function e_rn_get_datetime_format( $with_year = false ) {
		$separator = (array) str_split( e_rn_get_option( 'dateTimeSeparator', ' @ ' ) );

		$format = e_rn_get_date_format( $with_year );
		$format .= ( ! empty( $separator ) ? '\\' : '' ) . implode( '\\', $separator );
		$format .= get_option( 'time_format' );

		return apply_filters( 'e_rn_datetime_format', $format );

	}
}//end if

if ( ! function_exists( 'e_rn_get_time_format' ) ) {
	/**
	 * Get the time format
	 *
	 * @category Events
	 *
	 * @return mixed|void
	 */
	function e_rn_get_time_format( ) {
		$format = get_option( 'time_format' );
		return apply_filters( 'e_rn_time_format', $format );
	}
}//end if

if ( ! function_exists( 'e_rn_get_days_between' ) ) {
	/**
	 * Accepts two dates and returns the number of days between them
	 *
	 * @category Events
	 *
	 * @param string      $start_date
	 * @param string      $end_date
	 * @param string|bool $day_cutoff
	 *
	 * @return int
	 * @see E_Register_Now__Date_Utils::date_diff()
	 **/
	function e_rn_get_days_between( $start_date, $end_date, $day_cutoff = '00:00' ) {
		if ( $day_cutoff === false ) {
			$day_cutoff = '00:00';
		} elseif ( $day_cutoff === true ) {
			$day_cutoff = e_rn_get_option( 'multiDayCutoff', '00:00' );
		}

		$start_date = new DateTime( $start_date );
		if ( $start_date < new DateTime( $start_date->format( 'Y-m-d ' . $day_cutoff ) ) ) {
			$start_date->modify( '-1 day' );
		}
		$end_date = new DateTime( $end_date );
		if ( $end_date <= new DateTime( $end_date->format( 'Y-m-d ' . $day_cutoff ) ) ) {
			$end_date->modify( '-1 day' );
		}

		return E_Register_Now__Date_Utils::date_diff( $start_date->format( 'Y-m-d ' . $day_cutoff ), $end_date->format( 'Y-m-d ' . $day_cutoff ) );
	}
}//end if

if ( ! function_exists( 'e_rn_prepare_for_json' ) ) {
	/**
	 * Function to prepare content for use as a value in a json encoded string destined for storage on a html data attribute.
	 * Hence the double quote fun, especially in case they pass html encoded &quot; along. Any of those getting through to the data att will break jquery's parseJSON method.
	 * Themers can use this function to prepare data they may want to send to e_rn_events_template_data() in the templates, and we use it in that function ourselves.
	 *
	 * @category Events
	 *
	 * @param $string
	 *
	 * @return string
	 */
	function e_rn_prepare_for_json( $string ) {

		$value = trim( htmlspecialchars( $string, ENT_QUOTES, 'UTF-8' ) );
		$value = str_replace( '&quot;', '"', $value );

		return $value;
	}
}//end if

if ( ! function_exists( 'e_rn_prepare_for_json_deep' ) ) {
	/**
	 * Recursively iterate through an nested structure, calling
	 * e_rn_prepare_for_json() on all scalar values
	 *
	 * @category Events
	 *
	 * @param mixed $value The data to be cleaned
	 *
	 * @return mixed The clean data
	 */
	function e_rn_prepare_for_json_deep( $value ) {
		if ( is_array( $value ) ) {
			$value = array_map( 'e_rn_prepare_for_json_deep', $value );
		} elseif ( is_object( $value ) ) {
			$vars = get_object_vars( $value );
			foreach ( $vars as $key => $data ) {
				$value->{$key} = e_rn_prepare_for_json_deep( $data );
			}
		} elseif ( is_string( $value ) ) {
			$value = e_rn_prepare_for_json( $value );
		}
		return $value;
	}
}//end if

if ( ! function_exists( 'e_rn_the_notices' ) ) {
	/**
	 * Generates html for any notices that have been queued on the current view
	 *
	 * @category Events
	 *
	 * @param bool $echo Whether or not to echo the notices html
	 *
	 * @return void | string
	 * @see E_Register_Now__Notices::get()
	 **/
	function e_rn_the_notices( $echo = true ) {
		$notices = E_Register_Now__Notices::get();

		$html        = ! empty( $notices ) ? '<div class="rioc-events-notices"><ul><li>' . implode( '</li><li>', $notices ) . '</li></ul></div>' : '';

		/**
		 * Deprecated the e_rn_events_the_notices filter in 4.0 in favor of e_rn_the_notices. Remove in 5.0
		 */
		$the_notices = apply_filters( 'e_rn_events_the_notices', $html, $notices );

		/**
		 * filters the notices HTML
		 */
		$the_notices = apply_filters( 'e_rn_the_notices', $html, $notices );
		if ( $echo ) {
			echo $the_notices;
		} else {
			return $the_notices;
		}
	}
}//end if

if ( ! function_exists( 'e_rn_is_bot' ) ) {
	/**
	 * e_rn_is_bot checks if the visitor is a bot and returns status
	 *
	 * @category Events
	 *
	 * @return bool
	 */
	function e_rn_is_bot() {
		// get the current user agent
		$user_agent = strtolower( $_SERVER['HTTP_USER_AGENT'] );

		// check if the user agent is empty since most browsers identify themselves, so possibly a bot
		if ( empty( $user_agent ) ) {
			return apply_filters( 'e_rn_is_bot_status', true, $user_agent, null );
		}

		// declare known bot user agents (lowercase)
		$user_agent_bots = (array) apply_filters(
			'e_rn_is_bot_list', array(
				'bot',
				'slurp',
				'spider',
				'crawler',
				'yandex',
			)
		);

		foreach ( $user_agent_bots as $bot ) {
			if ( stripos( $user_agent, $bot ) !== false ) {
				return apply_filters( 'e_rn_is_bot_status', true, $user_agent, $bot );
			}
		}

		// we think this is probably a real human
		return apply_filters( 'e_rn_is_bot_status', false, $user_agent, null );
	}
}//end if

if ( ! function_exists( 'e_rn_count_hierarchical_keys' ) ) {
	/**
	 * Count keys in a hierarchical array
	 *
	 * @param $value
	 * @param $key
	 * @todo - remove, only used in the meta walker
	 */
	function e_rn_count_hierarchical_keys( $value, $key ) {
		global $e_rn_count_hierarchical_increment;
		$e_rn_count_hierarchical_increment++;
	}
}//end if

if ( ! function_exists( 'e_rn_count_hierarchical' ) ) {
	/**
	 * Count items in a hierarchical array
	 *
	 * @param array $walk
	 *
	 * @return int
	 * @todo - remove, only used in the meta walker
	 */
	function e_rn_count_hierarchical( array $walk ) {
		global $e_rn_count_hierarchical_increment;
		$e_rn_count_hierarchical_increment = 0;
		array_walk_recursive( $walk, 'e_rn_count_hierarchical_keys' );

		return $e_rn_count_hierarchical_increment;
	}
}//end if

if ( ! function_exists( 'e_rn_get_mobile_breakpoint' ) ) {
	/**
	 * Mobile breakpoint
	 *
	 * Get the breakpoint for switching to mobile styles. Defaults to 768.
	 *
	 * @category Events
	 *
	 * @param int $default The default width (in pixels) at which to break into mobile styles
	 *
	 * @return int
	 */
	function e_rn_get_mobile_breakpoint( $default = 768 ) {
		return apply_filters( 'e_rn_events_mobile_breakpoint', $default );
	}
}//end if

if ( ! function_exists( 'e_rn_format_currency' ) ) {
	/**
	 * Receives a float and formats it with a currency symbol
	 *
	 * @category Cost
	 * @param string $cost pricing to format
	 * @param null|int $post_id
	 * @param null|string $currency_symbol
	 * @param null|bool $reverse_position
	 *
	 * @return string
	 */
	function e_rn_format_currency( $cost, $post_id = null, $currency_symbol = null, $reverse_position = null ) {

		$post_id = E_Register_Now__Main::post_id_helper( $post_id );

		$currency_symbol = apply_filters( 'e_rn_currency_symbol', $currency_symbol, $post_id );

		// if no currency symbol was passed, or we're not looking at a particular event,
		// let's get the default currency symbol
		if ( ! $post_id || ! $currency_symbol ) {
			$currency_symbol = e_rn_get_option( 'defaultCurrencySymbol', '$' );
		}

		$reverse_position = apply_filters( 'e_rn_reverse_currency_position', $reverse_position, $post_id );

		if ( ! $reverse_position || ! $post_id ) {
			$reverse_position = e_rn_get_option( 'reverseCurrencyPosition', false );
		}

		$cost = $reverse_position ? $cost . $currency_symbol : $currency_symbol . $cost;

		return $cost;

	}
}//end if

if ( ! function_exists( 'e_rn_get_date_option' ) ) {
	/**
	 * Get a date option.
	 *
	 * Retrieve an option value taking care to escape it to preserve date format slashes.
	 *
	 * @category Events
	 * @param  string $optionName Name of the option to retrieve.
	 * @param string  $default    Value to return if no such option is found.
	 *
	 * @return mixed Value of the option if found
	 */
	function e_rn_get_date_option( $optionName, $default = '' ) {
		$value = e_rn_get_option( $optionName, $default );

		return E_Register_Now__Date_Utils::unescape_date_format($value);
	}
}
