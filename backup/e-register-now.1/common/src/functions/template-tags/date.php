<?php
/**
 * Date Functions
 *
 * Display functions (template-tags) for use in WordPress templates.
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'E_Register_Now__Main' ) ) {
	return;
}

if ( ! function_exists( 'e_rn_format_date' ) ) {
	/**
	 * Formatted Date
	 *
	 * Returns formatted date
	 *
	 * @category Events
	 *
	 * @param string $date         String representing the datetime, assumed to be UTC (relevant if timezone conversion is used)
	 * @param bool   $display_time If true shows date and time, if false only shows date
	 * @param string $date_format  Allows date and time formating using standard php syntax (http://php.net/manual/en/function.date.php)
	 *
	 * @return string
	 */
	function e_rn_format_date( $date, $display_time = true, $date_format = '' ) {

		if ( ! E_Register_Now__Date_Utils::is_timestamp( $date ) ) {
			$date = strtotime( $date );
		}

		if ( $date_format ) {
			$format = $date_format;
		} else {
			$date_year = date( 'Y', $date );
			$cur_year  = date( 'Y', current_time( 'timestamp' ) );

			// only show the year in the date if it's not in the current year
			$with_year = $date_year == $cur_year ? false : true;

			if ( $display_time ) {
				$format = e_rn_get_datetime_format( $with_year );
			} else {
				$format = e_rn_get_date_format( $with_year );
			}
		}

		$date = date_i18n( $format, $date );

		/**
		 * Deprecated e_rn_event_formatted_date in 4.0 in favor of e_rn_formatted_date. Remove in 5.0
		 */
		$date = apply_filters( 'e_rn_event_formatted_date', $date, $display_time, $date_format );

		return apply_filters( 'e_rn_formatted_date', $date, $display_time, $date_format );
	}
}//end if

if ( ! function_exists( 'e_rn_beginning_of_day' ) ) {
	/**
	 * Returns formatted date for the official beginning of the day according to the Multi-day cutoff time option
	 *
	 * @category Events
	 *
	 * @param string $date   The date to find the beginning of the day, defaults to today
	 * @param string $format Allows date and time formating using standard php syntax (http://php.net/manual/en/function.date.php)
	 *
	 * @return string
	 */
	function e_rn_beginning_of_day( $date = null, $format = 'Y-m-d H:i:s' ) {
		$multiday_cutoff = explode( ':', e_rn_get_option( 'multiDayCutoff', '00:00' ) );
		$hours_to_add    = $multiday_cutoff[0];
		$minutes_to_add  = $multiday_cutoff[1];
		if ( is_null( $date ) || empty( $date ) ) {
			$date = date( $format, strtotime( date( 'Y-m-d' ) . ' +' . $hours_to_add . ' hours ' . $minutes_to_add . ' minutes' ) );
		} else {
			$date = date( $format, strtotime( date( 'Y-m-d', strtotime( $date ) ) . ' +' . $hours_to_add . ' hours ' . $minutes_to_add . ' minutes' ) );
		}

		/**
		 * Deprecated filter e_rn_event_beginning_of_day in 4.0 in favor of e_rn_beginning_of_day. Remove in 5.0
		 */
		$date = apply_filters( 'e_rn_event_beginning_of_day', $date );

		/**
		 * Filters the beginning of day date
		 *
		 * @param string $date
		 */
		return apply_filters( 'e_rn_beginning_of_day', $date );
	}
}//end if

if ( ! function_exists( 'e_rn_end_of_day' ) ) {
	/**
	 * Returns formatted date for the official end of the day according to the Multi-day cutoff time option
	 *
	 * @category Events
	 *
	 * @param string $date   The date to find the end of the day, defaults to today
	 * @param string $format Allows date and time formating using standard php syntax (http://php.net/manual/en/function.date.php)
	 *
	 * @return string
	 */
	function e_rn_end_of_day( $date = null, $format = 'Y-m-d H:i:s' ) {
		$multiday_cutoff = explode( ':', e_rn_get_option( 'multiDayCutoff', '00:00' ) );
		$hours_to_add    = $multiday_cutoff[0];
		$minutes_to_add  = $multiday_cutoff[1];
		if ( is_null( $date ) || empty( $date ) ) {
			$date = date( $format, strtotime( 'tomorrow  +' . $hours_to_add . ' hours ' . $minutes_to_add . ' minutes' ) - 1 );
		} else {
			$date = date( $format, strtotime( date( 'Y-m-d', strtotime( $date ) ) . ' +1 day ' . $hours_to_add . ' hours ' . $minutes_to_add . ' minutes' ) - 1 );
		}

		/**
		 * Deprecated filter e_rn_event_end_of_day in 4.0 in favor of e_rn_end_of_day. Remove in 5.0
		 */
		$date = apply_filters( 'e_rn_event_end_of_day', $date );

		/**
		 * Filters the end of day date
		 *
		 * @param string $date
		 */
		return apply_filters( 'e_rn_end_of_day', $date );
	}
}//end if

if ( ! function_exists( 'e_rn_get_datetime_separator' ) ) {
	/**
	 * Get the datetime saparator from the database option with escaped characters or not ;)
	 *
	 * @param string $default Default Separator if it's blank on the Database
	 * @param bool   $esc     If it's going to be used on a `date` function or method it needs to be escaped
	 *
	 * @filter e_rn_datetime_separator
	 *
	 * @return string
	 */
	function e_rn_get_datetime_separator( $default = ' @ ', $esc = false ) {
		$separator = (string) e_rn_get_option( 'dateTimeSeparator', $default );
		if ( $esc ) {
			$separator = (array) str_split( $separator );
			$separator = ( ! empty( $separator ) ? '\\' : '' ) . implode( '\\', $separator );
		}

		return apply_filters( 'e_rn_datetime_separator', $separator );
	}
}//end if

if ( ! function_exists( 'e_rn_get_start_time' ) ) {
	/**
	 * Start Time
	 *
	 * Returns the event start time
	 *
	 * @category Events
	 *
	 * @param int    $event       (optional)
	 * @param string $date_format Allows date and time formating using standard php syntax (http://php.net/manual/en/function.date.php)
	 * @param string $timezone    Timezone in which to present the date/time (or default behaviour if not set)
	 *
	 * @return string|null Time
	 */
	function e_rn_get_start_time( $event = null, $date_format = '', $timezone = null ) {
		if ( is_null( $event ) ) {
			global $post;
			$event = $post;
		}

		if ( is_numeric( $event ) ) {
			$event = get_post( $event );
		}

		if ( ! is_object( $event ) ) {
			return;
		}

		if ( E_Register_Now__Date_Utils::is_all_day( get_post_meta( $event->ID, '_EventAllDay', true ) ) ) {
			return;
		}

		// @todo move timezones to Common
		if ( class_exists( 'E_Register_Now__Events__Timezones' ) ) {
			$start_date = E_Register_Now__Events__Timezones::event_start_timestamp( $event->ID, $timezone );
		}

		if ( '' == $date_format ) {
			$date_format = e_rn_get_time_format();
		}

		return e_rn_format_date( $start_date, false, $date_format );
	}
}

if ( ! function_exists( 'e_rn_get_end_time' ) ) {
	/**
	 * End Time
	 *
	 * Returns the event end time
	 *
	 * @category Events
	 *
	 * @param int    $event       (optional)
	 * @param string $date_format Allows date and time formating using standard php syntax (http://php.net/manual/en/function.date.php)
	 * @param string $timezone    Timezone in which to present the date/time (or default behaviour if not set)
	 *
	 * @return string|null Time
	 */
	function e_rn_get_end_time( $event = null, $date_format = '', $timezone = null ) {
		if ( is_null( $event ) ) {
			global $post;
			$event = $post;
		}

		if ( is_numeric( $event ) ) {
			$event = get_post( $event );
		}

		if ( ! is_object( $event ) ) {
			return;
		}

		if ( E_Register_Now__Date_Utils::is_all_day( get_post_meta( $event->ID, '_EventAllDay', true ) ) ) {
			return;
		}

		// @todo move timezones to Common
		if ( class_exists( 'E_Register_Now__Events__Timezones' ) ) {
			$end_date = E_Register_Now__Events__Timezones::event_end_timestamp( $event->ID, $timezone );
		}

		if ( '' == $date_format ) {
			$date_format = e_rn_get_time_format();
		}

		return e_rn_format_date( $end_date, false, $date_format );
	}
}

if ( ! function_exists( 'e_rn_get_start_date' ) ) {
	/**
	 * Start Date
	 *
	 * Returns the event start date and time
	 *
	 * @category Events
	 *
	 * @param int    $event        (optional)
	 * @param bool   $display_time If true shows date and time, if false only shows date
	 * @param string $date_format  Allows date and time formating using standard php syntax (http://php.net/manual/en/function.date.php)
	 * @param string $timezone     Timezone in which to present the date/time (or default behaviour if not set)
	 *
	 * @return string|null Date
	 */
	function e_rn_get_start_date( $event = null, $display_time = true, $date_format = '', $timezone = null ) {
		if ( is_null( $event ) ) {
			global $post;
			$event = $post;
		}

		if ( is_numeric( $event ) ) {
			$event = get_post( $event );
		}

		if ( ! is_object( $event ) ) {
			return '';
		}

		if ( E_Register_Now__Date_Utils::is_all_day( get_post_meta( $event->ID, '_EventAllDay', true ) ) ) {
			$display_time = false;
		}

		// @todo move timezones to Common
		if ( class_exists( 'E_Register_Now__Events__Timezones' ) ) {
			$start_date = E_Register_Now__Events__Timezones::event_start_timestamp( $event->ID, $timezone );
		} else {
			return null;
		}

		return e_rn_format_date( $start_date, $display_time, $date_format );
	}
}

if ( ! function_exists( 'e_rn_get_end_date' ) ) {
	/**
	 * End Date
	 *
	 * Returns the event end date
	 *
	 * @category Events
	 *
	 * @param int    $event        (optional)
	 * @param bool   $display_time If true shows date and time, if false only shows date
	 * @param string $date_format  Allows date and time formating using standard php syntax (http://php.net/manual/en/function.date.php)
	 * @param string $timezone     Timezone in which to present the date/time (or default behaviour if not set)
	 *
	 * @return string|null Date
	 */
	function e_rn_get_end_date( $event = null, $display_time = true, $date_format = '', $timezone = null ) {
		if ( is_null( $event ) ) {
			global $post;
			$event = $post;
		}

		if ( is_numeric( $event ) ) {
			$event = get_post( $event );
		}

		if ( ! is_object( $event ) ) {
			return '';
		}

		if ( E_Register_Now__Date_Utils::is_all_day( get_post_meta( $event->ID, '_EventAllDay', true ) ) ) {
			$display_time = false;
		}

		// @todo move timezones to Common
		if ( class_exists( 'E_Register_Now__Events__Timezones' ) ) {
			$end_date = E_Register_Now__Events__Timezones::event_end_timestamp( $event->ID, $timezone );
		} else {
			return null;
		}

		return e_rn_format_date( $end_date, $display_time, $date_format );
	}
}

if ( ! function_exists( 'e_rn_normalize_manual_utc_offset' ) ) {
	/**
	 * Normalizes a manual UTC offset string.
	 *
	 * @param string $utc_offset
	 *
	 * @return string The normalized manual UTC offset.
	 *                e.g. 'UTC+3', 'UTC-4.5', 'UTC+2.75'
	 */
	function e_rn_normalize_manual_utc_offset( $utc_offset ) {
		$matches = array();
		if ( preg_match( '/^UTC\\s*((\\+|-)(\\d{1,2}))((:|.|,)(\\d{1,2})+)*/ui', $utc_offset, $matches ) ) {
			if ( ! empty( $matches[6] ) ) {
				$minutes = $matches[6] > 10 && $matches[6] <= 60 ? $minutes = $matches[6] / 60 : $matches[6];
				$minutes = str_replace( '0.', '', $minutes );
			}

			$utc_offset = sprintf( 'UTC%s%s', $matches[1], ! empty( $minutes ) ? '.' . $minutes : '' );

		}

		return $utc_offset;
	}
}
