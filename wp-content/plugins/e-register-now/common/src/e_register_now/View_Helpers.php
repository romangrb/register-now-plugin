<?php
/**
 * Various helper methods used in views
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'E_Register_Now__View_Helpers' ) ) {
	class E_Register_Now__View_Helpers {

		/**
		 * Get the countries being used and available for the plugin.
		 *
		 * @param string $postId     The post ID.
		 * @param bool   $useDefault Should we use the defaults?
		 *
		 * @return array The countries array.
		 */
		public static function constructCountries( $postId = '', $useDefault = true ) {

			if ( e_rn_get_option( 'tribeEventsCountries' ) != '' ) {
				$countries = array(
					'' => esc_html__( 'Select a Country:', 'e-rn-common' ),
				);

				$country_rows = explode( "\n", e_rn_get_option( 'tribeEventsCountries' ) );
				foreach ( $country_rows as $crow ) {
					$country = explode( ',', $crow );
					if ( isset( $country[0] ) && isset( $country[1] ) ) {
						$country[0] = trim( $country[0] );
						$country[1] = trim( $country[1] );

						if ( $country[0] && $country[1] ) {
							$countries[ $country[0] ] = $country[1];
						}
					}
				}
			}

			if ( ! isset( $countries ) || ! is_array( $countries ) || count( $countries ) == 1 ) {
				$countries = array(
					''   => esc_html__( 'Select a Country:', 'e-rn-common' ),
					'US' => esc_html__( 'United States', 'e-rn-common' ),
					'AF' => esc_html__( 'Afghanistan', 'e-rn-common' ),
					'AL' => esc_html__( 'Albania', 'e-rn-common' ),
					'DZ' => esc_html__( 'Algeria', 'e-rn-common' ),
					'AS' => esc_html__( 'American Samoa', 'e-rn-common' ),
					'AD' => esc_html__( 'Andorra', 'e-rn-common' ),
					'AO' => esc_html__( 'Angola', 'e-rn-common' ),
					'AI' => esc_html__( 'Anguilla', 'e-rn-common' ),
					'AQ' => esc_html__( 'Antarctica', 'e-rn-common' ),
					'AG' => esc_html__( 'Antigua And Barbuda', 'e-rn-common' ),
					'AR' => esc_html__( 'Argentina', 'e-rn-common' ),
					'AM' => esc_html__( 'Armenia', 'e-rn-common' ),
					'AW' => esc_html__( 'Aruba', 'e-rn-common' ),
					'AU' => esc_html__( 'Australia', 'e-rn-common' ),
					'AT' => esc_html__( 'Austria', 'e-rn-common' ),
					'AZ' => esc_html__( 'Azerbaijan', 'e-rn-common' ),
					'BS' => esc_html__( 'Bahamas', 'e-rn-common' ),
					'BH' => esc_html__( 'Bahrain', 'e-rn-common' ),
					'BD' => esc_html__( 'Bangladesh', 'e-rn-common' ),
					'BB' => esc_html__( 'Barbados', 'e-rn-common' ),
					'BY' => esc_html__( 'Belarus', 'e-rn-common' ),
					'BE' => esc_html__( 'Belgium', 'e-rn-common' ),
					'BZ' => esc_html__( 'Belize', 'e-rn-common' ),
					'BJ' => esc_html__( 'Benin', 'e-rn-common' ),
					'BM' => esc_html__( 'Bermuda', 'e-rn-common' ),
					'BT' => esc_html__( 'Bhutan', 'e-rn-common' ),
					'BO' => esc_html__( 'Bolivia', 'e-rn-common' ),
					'BA' => esc_html__( 'Bosnia And Herzegowina', 'e-rn-common' ),
					'BW' => esc_html__( 'Botswana', 'e-rn-common' ),
					'BV' => esc_html__( 'Bouvet Island', 'e-rn-common' ),
					'BR' => esc_html__( 'Brazil', 'e-rn-common' ),
					'IO' => esc_html__( 'British Indian Ocean Territory', 'e-rn-common' ),
					'BN' => esc_html__( 'Brunei Darussalam', 'e-rn-common' ),
					'BG' => esc_html__( 'Bulgaria', 'e-rn-common' ),
					'BF' => esc_html__( 'Burkina Faso', 'e-rn-common' ),
					'BI' => esc_html__( 'Burundi', 'e-rn-common' ),
					'KH' => esc_html__( 'Cambodia', 'e-rn-common' ),
					'CM' => esc_html__( 'Cameroon', 'e-rn-common' ),
					'CA' => esc_html__( 'Canada', 'e-rn-common' ),
					'CV' => esc_html__( 'Cape Verde', 'e-rn-common' ),
					'KY' => esc_html__( 'Cayman Islands', 'e-rn-common' ),
					'CF' => esc_html__( 'Central African Republic', 'e-rn-common' ),
					'TD' => esc_html__( 'Chad', 'e-rn-common' ),
					'CL' => esc_html__( 'Chile', 'e-rn-common' ),
					'CN' => esc_html__( 'China', 'e-rn-common' ),
					'CX' => esc_html__( 'Christmas Island', 'e-rn-common' ),
					'CC' => esc_html__( 'Cocos (Keeling) Islands', 'e-rn-common' ),
					'CO' => esc_html__( 'Colombia', 'e-rn-common' ),
					'KM' => esc_html__( 'Comoros', 'e-rn-common' ),
					'CG' => esc_html__( 'Congo', 'e-rn-common' ),
					'CD' => esc_html__( 'Congo, The Democratic Republic Of The', 'e-rn-common' ),
					'CK' => esc_html__( 'Cook Islands', 'e-rn-common' ),
					'CR' => esc_html__( 'Costa Rica', 'e-rn-common' ),
					'CI' => esc_html__( "Cote D'Ivoire", 'e-rn-common' ),
					'HR' => esc_html__( 'Croatia (Local Name: Hrvatska)', 'e-rn-common' ),
					'CU' => esc_html__( 'Cuba', 'e-rn-common' ),
					'CY' => esc_html__( 'Cyprus', 'e-rn-common' ),
					'CZ' => esc_html__( 'Czech Republic', 'e-rn-common' ),
					'DK' => esc_html__( 'Denmark', 'e-rn-common' ),
					'DJ' => esc_html__( 'Djibouti', 'e-rn-common' ),
					'DM' => esc_html__( 'Dominica', 'e-rn-common' ),
					'DO' => esc_html__( 'Dominican Republic', 'e-rn-common' ),
					'TP' => esc_html__( 'East Timor', 'e-rn-common' ),
					'EC' => esc_html__( 'Ecuador', 'e-rn-common' ),
					'EG' => esc_html__( 'Egypt', 'e-rn-common' ),
					'SV' => esc_html__( 'El Salvador', 'e-rn-common' ),
					'GQ' => esc_html__( 'Equatorial Guinea', 'e-rn-common' ),
					'ER' => esc_html__( 'Eritrea', 'e-rn-common' ),
					'EE' => esc_html__( 'Estonia', 'e-rn-common' ),
					'ET' => esc_html__( 'Ethiopia', 'e-rn-common' ),
					'FK' => esc_html__( 'Falkland Islands (Malvinas)', 'e-rn-common' ),
					'FO' => esc_html__( 'Faroe Islands', 'e-rn-common' ),
					'FJ' => esc_html__( 'Fiji', 'e-rn-common' ),
					'FI' => esc_html__( 'Finland', 'e-rn-common' ),
					'FR' => esc_html__( 'France', 'e-rn-common' ),
					'FX' => esc_html__( 'France, Metropolitan', 'e-rn-common' ),
					'GF' => esc_html__( 'French Guiana', 'e-rn-common' ),
					'PF' => esc_html__( 'French Polynesia', 'e-rn-common' ),
					'TF' => esc_html__( 'French Southern Territories', 'e-rn-common' ),
					'GA' => esc_html__( 'Gabon', 'e-rn-common' ),
					'GM' => esc_html__( 'Gambia', 'e-rn-common' ),
					'GE' => esc_html__( 'Georgia', 'e-rn-common' ),
					'DE' => esc_html__( 'Germany', 'e-rn-common' ),
					'GH' => esc_html__( 'Ghana', 'e-rn-common' ),
					'GI' => esc_html__( 'Gibraltar', 'e-rn-common' ),
					'GR' => esc_html__( 'Greece', 'e-rn-common' ),
					'GL' => esc_html__( 'Greenland', 'e-rn-common' ),
					'GD' => esc_html__( 'Grenada', 'e-rn-common' ),
					'GP' => esc_html__( 'Guadeloupe', 'e-rn-common' ),
					'GU' => esc_html__( 'Guam', 'e-rn-common' ),
					'GT' => esc_html__( 'Guatemala', 'e-rn-common' ),
					'GN' => esc_html__( 'Guinea', 'e-rn-common' ),
					'GW' => esc_html__( 'Guinea-Bissau', 'e-rn-common' ),
					'GY' => esc_html__( 'Guyana', 'e-rn-common' ),
					'HT' => esc_html__( 'Haiti', 'e-rn-common' ),
					'HM' => esc_html__( 'Heard And Mc Donald Islands', 'e-rn-common' ),
					'VA' => esc_html__( 'Holy See (Vatican City State)', 'e-rn-common' ),
					'HN' => esc_html__( 'Honduras', 'e-rn-common' ),
					'HK' => esc_html__( 'Hong Kong', 'e-rn-common' ),
					'HU' => esc_html__( 'Hungary', 'e-rn-common' ),
					'IS' => esc_html__( 'Iceland', 'e-rn-common' ),
					'IN' => esc_html__( 'India', 'e-rn-common' ),
					'ID' => esc_html__( 'Indonesia', 'e-rn-common' ),
					'IR' => esc_html__( 'Iran (Islamic Republic Of)', 'e-rn-common' ),
					'IQ' => esc_html__( 'Iraq', 'e-rn-common' ),
					'IE' => esc_html__( 'Ireland', 'e-rn-common' ),
					'IL' => esc_html__( 'Israel', 'e-rn-common' ),
					'IT' => esc_html__( 'Italy', 'e-rn-common' ),
					'JM' => esc_html__( 'Jamaica', 'e-rn-common' ),
					'JP' => esc_html__( 'Japan', 'e-rn-common' ),
					'JO' => esc_html__( 'Jordan', 'e-rn-common' ),
					'KZ' => esc_html__( 'Kazakhstan', 'e-rn-common' ),
					'KE' => esc_html__( 'Kenya', 'e-rn-common' ),
					'KI' => esc_html__( 'Kiribati', 'e-rn-common' ),
					'KP' => esc_html__( "Korea, Democratic People's Republic Of", 'e-rn-common' ),
					'KR' => esc_html__( 'Korea, Republic Of', 'e-rn-common' ),
					'KW' => esc_html__( 'Kuwait', 'e-rn-common' ),
					'KG' => esc_html__( 'Kyrgyzstan', 'e-rn-common' ),
					'LA' => esc_html__( "Lao People's Democratic Republic", 'e-rn-common' ),
					'LV' => esc_html__( 'Latvia', 'e-rn-common' ),
					'LB' => esc_html__( 'Lebanon', 'e-rn-common' ),
					'LS' => esc_html__( 'Lesotho', 'e-rn-common' ),
					'LR' => esc_html__( 'Liberia', 'e-rn-common' ),
					'LY' => esc_html__( 'Libya', 'e-rn-common' ),
					'LI' => esc_html__( 'Liechtenstein', 'e-rn-common' ),
					'LT' => esc_html__( 'Lithuania', 'e-rn-common' ),
					'LU' => esc_html__( 'Luxembourg', 'e-rn-common' ),
					'MO' => esc_html__( 'Macau', 'e-rn-common' ),
					'MK' => esc_html__( 'Macedonia', 'e-rn-common' ),
					'MG' => esc_html__( 'Madagascar', 'e-rn-common' ),
					'MW' => esc_html__( 'Malawi', 'e-rn-common' ),
					'MY' => esc_html__( 'Malaysia', 'e-rn-common' ),
					'MV' => esc_html__( 'Maldives', 'e-rn-common' ),
					'ML' => esc_html__( 'Mali', 'e-rn-common' ),
					'MT' => esc_html__( 'Malta', 'e-rn-common' ),
					'MH' => esc_html__( 'Marshall Islands', 'e-rn-common' ),
					'MQ' => esc_html__( 'Martinique', 'e-rn-common' ),
					'MR' => esc_html__( 'Mauritania', 'e-rn-common' ),
					'MU' => esc_html__( 'Mauritius', 'e-rn-common' ),
					'YT' => esc_html__( 'Mayotte', 'e-rn-common' ),
					'MX' => esc_html__( 'Mexico', 'e-rn-common' ),
					'FM' => esc_html__( 'Micronesia, Federated States Of', 'e-rn-common' ),
					'MD' => esc_html__( 'Moldova, Republic Of', 'e-rn-common' ),
					'MC' => esc_html__( 'Monaco', 'e-rn-common' ),
					'MN' => esc_html__( 'Mongolia', 'e-rn-common' ),
					'ME' => esc_html__( 'Montenegro', 'e-rn-common' ),
					'MS' => esc_html__( 'Montserrat', 'e-rn-common' ),
					'MA' => esc_html__( 'Morocco', 'e-rn-common' ),
					'MZ' => esc_html__( 'Mozambique', 'e-rn-common' ),
					'MM' => esc_html__( 'Myanmar', 'e-rn-common' ),
					'NA' => esc_html__( 'Namibia', 'e-rn-common' ),
					'NR' => esc_html__( 'Nauru', 'e-rn-common' ),
					'NP' => esc_html__( 'Nepal', 'e-rn-common' ),
					'NL' => esc_html__( 'Netherlands', 'e-rn-common' ),
					'AN' => esc_html__( 'Netherlands Antilles', 'e-rn-common' ),
					'NC' => esc_html__( 'New Caledonia', 'e-rn-common' ),
					'NZ' => esc_html__( 'New Zealand', 'e-rn-common' ),
					'NI' => esc_html__( 'Nicaragua', 'e-rn-common' ),
					'NE' => esc_html__( 'Niger', 'e-rn-common' ),
					'NG' => esc_html__( 'Nigeria', 'e-rn-common' ),
					'NU' => esc_html__( 'Niue', 'e-rn-common' ),
					'NF' => esc_html__( 'Norfolk Island', 'e-rn-common' ),
					'MP' => esc_html__( 'Northern Mariana Islands', 'e-rn-common' ),
					'NO' => esc_html__( 'Norway', 'e-rn-common' ),
					'OM' => esc_html__( 'Oman', 'e-rn-common' ),
					'PK' => esc_html__( 'Pakistan', 'e-rn-common' ),
					'PW' => esc_html__( 'Palau', 'e-rn-common' ),
					'PA' => esc_html__( 'Panama', 'e-rn-common' ),
					'PG' => esc_html__( 'Papua New Guinea', 'e-rn-common' ),
					'PY' => esc_html__( 'Paraguay', 'e-rn-common' ),
					'PE' => esc_html__( 'Peru', 'e-rn-common' ),
					'PH' => esc_html__( 'Philippines', 'e-rn-common' ),
					'PN' => esc_html__( 'Pitcairn', 'e-rn-common' ),
					'PL' => esc_html__( 'Poland', 'e-rn-common' ),
					'PT' => esc_html__( 'Portugal', 'e-rn-common' ),
					'PR' => esc_html__( 'Puerto Rico', 'e-rn-common' ),
					'QA' => esc_html__( 'Qatar', 'e-rn-common' ),
					'RE' => esc_html__( 'Reunion', 'e-rn-common' ),
					'RO' => esc_html__( 'Romania', 'e-rn-common' ),
					'RU' => esc_html__( 'Russian Federation', 'e-rn-common' ),
					'RW' => esc_html__( 'Rwanda', 'e-rn-common' ),
					'KN' => esc_html__( 'Saint Kitts And Nevis', 'e-rn-common' ),
					'LC' => esc_html__( 'Saint Lucia', 'e-rn-common' ),
					'VC' => esc_html__( 'Saint Vincent And The Grenadines', 'e-rn-common' ),
					'WS' => esc_html__( 'Samoa', 'e-rn-common' ),
					'SM' => esc_html__( 'San Marino', 'e-rn-common' ),
					'ST' => esc_html__( 'Sao Tome And Principe', 'e-rn-common' ),
					'SA' => esc_html__( 'Saudi Arabia', 'e-rn-common' ),
					'SN' => esc_html__( 'Senegal', 'e-rn-common' ),
					'RS' => esc_html__( 'Serbia', 'e-rn-common' ),
					'SC' => esc_html__( 'Seychelles', 'e-rn-common' ),
					'SL' => esc_html__( 'Sierra Leone', 'e-rn-common' ),
					'SG' => esc_html__( 'Singapore', 'e-rn-common' ),
					'SK' => esc_html__( 'Slovakia (Slovak Republic)', 'e-rn-common' ),
					'SI' => esc_html__( 'Slovenia', 'e-rn-common' ),
					'SB' => esc_html__( 'Solomon Islands', 'e-rn-common' ),
					'SO' => esc_html__( 'Somalia', 'e-rn-common' ),
					'ZA' => esc_html__( 'South Africa', 'e-rn-common' ),
					'GS' => esc_html__( 'South Georgia, South Sandwich Islands', 'e-rn-common' ),
					'ES' => esc_html__( 'Spain', 'e-rn-common' ),
					'LK' => esc_html__( 'Sri Lanka', 'e-rn-common' ),
					'SH' => esc_html__( 'St. Helena', 'e-rn-common' ),
					'PM' => esc_html__( 'St. Pierre And Miquelon', 'e-rn-common' ),
					'SD' => esc_html__( 'Sudan', 'e-rn-common' ),
					'SR' => esc_html__( 'Suriname', 'e-rn-common' ),
					'SJ' => esc_html__( 'Svalbard And Jan Mayen Islands', 'e-rn-common' ),
					'SZ' => esc_html__( 'Swaziland', 'e-rn-common' ),
					'SE' => esc_html__( 'Sweden', 'e-rn-common' ),
					'CH' => esc_html__( 'Switzerland', 'e-rn-common' ),
					'SY' => esc_html__( 'Syrian Arab Republic', 'e-rn-common' ),
					'TW' => esc_html__( 'Taiwan', 'e-rn-common' ),
					'TJ' => esc_html__( 'Tajikistan', 'e-rn-common' ),
					'TZ' => esc_html__( 'Tanzania, United Republic Of', 'e-rn-common' ),
					'TH' => esc_html__( 'Thailand', 'e-rn-common' ),
					'TG' => esc_html__( 'Togo', 'e-rn-common' ),
					'TK' => esc_html__( 'Tokelau', 'e-rn-common' ),
					'TO' => esc_html__( 'Tonga', 'e-rn-common' ),
					'TT' => esc_html__( 'Trinidad And Tobago', 'e-rn-common' ),
					'TN' => esc_html__( 'Tunisia', 'e-rn-common' ),
					'TR' => esc_html__( 'Turkey', 'e-rn-common' ),
					'TM' => esc_html__( 'Turkmenistan', 'e-rn-common' ),
					'TC' => esc_html__( 'Turks And Caicos Islands', 'e-rn-common' ),
					'TV' => esc_html__( 'Tuvalu', 'e-rn-common' ),
					'UG' => esc_html__( 'Uganda', 'e-rn-common' ),
					'UA' => esc_html__( 'Ukraine', 'e-rn-common' ),
					'AE' => esc_html__( 'United Arab Emirates', 'e-rn-common' ),
					'GB' => esc_html__( 'United Kingdom', 'e-rn-common' ),
					'UM' => esc_html__( 'United States Minor Outlying Islands', 'e-rn-common' ),
					'UY' => esc_html__( 'Uruguay', 'e-rn-common' ),
					'UZ' => esc_html__( 'Uzbekistan', 'e-rn-common' ),
					'VU' => esc_html__( 'Vanuatu', 'e-rn-common' ),
					'VE' => esc_html__( 'Venezuela', 'e-rn-common' ),
					'VN' => esc_html__( 'Viet Nam', 'e-rn-common' ),
					'VG' => esc_html__( 'Virgin Islands (British)', 'e-rn-common' ),
					'VI' => esc_html__( 'Virgin Islands (U.S.)', 'e-rn-common' ),
					'WF' => esc_html__( 'Wallis And Futuna Islands', 'e-rn-common' ),
					'EH' => esc_html__( 'Western Sahara', 'e-rn-common' ),
					'YE' => esc_html__( 'Yemen', 'e-rn-common' ),
					'ZM' => esc_html__( 'Zambia', 'e-rn-common' ),
					'ZW' => esc_html__( 'Zimbabwe', 'e-rn-common' ),
				);
			}
			if ( ( $postId || $useDefault ) ) {
				$countryValue = get_post_meta( $postId, '_EventCountry', true );
				if ( $countryValue ) {
					$defaultCountry = array( array_search( $countryValue, $countries ), $countryValue );
				} else {
					$defaultCountry = e_rn_get_default_value( 'country' );
				}
				if ( $defaultCountry && $defaultCountry[0] != '' ) {
					$selectCountry = array_shift( $countries );
					asort( $countries );
					$countries = array( $defaultCountry[0] => __( $defaultCountry[1], 'e-rn-common' ) ) + $countries;
					$countries = array( '' => __( $selectCountry, 'e-rn-common' ) ) + $countries;
					array_unique( $countries );
				}

				return $countries;
			} else {
				return $countries;
			}
		}

		/**
		 * Get the i18ned states available to the plugin.
		 *
		 * @return array The states array.
		 */
		public static function loadStates() {
			return array(
				'AL' => esc_html__( 'Alabama', 'e-rn-common' ),
				'AK' => esc_html__( 'Alaska', 'e-rn-common' ),
				'AZ' => esc_html__( 'Arizona', 'e-rn-common' ),
				'AR' => esc_html__( 'Arkansas', 'e-rn-common' ),
				'CA' => esc_html__( 'California', 'e-rn-common' ),
				'CO' => esc_html__( 'Colorado', 'e-rn-common' ),
				'CT' => esc_html__( 'Connecticut', 'e-rn-common' ),
				'DE' => esc_html__( 'Delaware', 'e-rn-common' ),
				'DC' => esc_html__( 'District of Columbia', 'e-rn-common' ),
				'FL' => esc_html__( 'Florida', 'e-rn-common' ),
				'GA' => esc_html__( 'Georgia', 'e-rn-common' ),
				'HI' => esc_html__( 'Hawaii', 'e-rn-common' ),
				'ID' => esc_html__( 'Idaho', 'e-rn-common' ),
				'IL' => esc_html__( 'Illinois', 'e-rn-common' ),
				'IN' => esc_html__( 'Indiana', 'e-rn-common' ),
				'IA' => esc_html__( 'Iowa', 'e-rn-common' ),
				'KS' => esc_html__( 'Kansas', 'e-rn-common' ),
				'KY' => esc_html__( 'Kentucky', 'e-rn-common' ),
				'LA' => esc_html__( 'Louisiana', 'e-rn-common' ),
				'ME' => esc_html__( 'Maine', 'e-rn-common' ),
				'MD' => esc_html__( 'Maryland', 'e-rn-common' ),
				'MA' => esc_html__( 'Massachusetts', 'e-rn-common' ),
				'MI' => esc_html__( 'Michigan', 'e-rn-common' ),
				'MN' => esc_html__( 'Minnesota', 'e-rn-common' ),
				'MS' => esc_html__( 'Mississippi', 'e-rn-common' ),
				'MO' => esc_html__( 'Missouri', 'e-rn-common' ),
				'MT' => esc_html__( 'Montana', 'e-rn-common' ),
				'NE' => esc_html__( 'Nebraska', 'e-rn-common' ),
				'NV' => esc_html__( 'Nevada', 'e-rn-common' ),
				'NH' => esc_html__( 'New Hampshire', 'e-rn-common' ),
				'NJ' => esc_html__( 'New Jersey', 'e-rn-common' ),
				'NM' => esc_html__( 'New Mexico', 'e-rn-common' ),
				'NY' => esc_html__( 'New York', 'e-rn-common' ),
				'NC' => esc_html__( 'North Carolina', 'e-rn-common' ),
				'ND' => esc_html__( 'North Dakota', 'e-rn-common' ),
				'OH' => esc_html__( 'Ohio', 'e-rn-common' ),
				'OK' => esc_html__( 'Oklahoma', 'e-rn-common' ),
				'OR' => esc_html__( 'Oregon', 'e-rn-common' ),
				'PA' => esc_html__( 'Pennsylvania', 'e-rn-common' ),
				'RI' => esc_html__( 'Rhode Island', 'e-rn-common' ),
				'SC' => esc_html__( 'South Carolina', 'e-rn-common' ),
				'SD' => esc_html__( 'South Dakota', 'e-rn-common' ),
				'TN' => esc_html__( 'Tennessee', 'e-rn-common' ),
				'TX' => esc_html__( 'Texas', 'e-rn-common' ),
				'UT' => esc_html__( 'Utah', 'e-rn-common' ),
				'VT' => esc_html__( 'Vermont', 'e-rn-common' ),
				'VA' => esc_html__( 'Virginia', 'e-rn-common' ),
				'WA' => esc_html__( 'Washington', 'e-rn-common' ),
				'WV' => esc_html__( 'West Virginia', 'e-rn-common' ),
				'WI' => esc_html__( 'Wisconsin', 'e-rn-common' ),
				'WY' => esc_html__( 'Wyoming', 'e-rn-common' ),
			);
		}

		/**
		 * Builds a set of options for displaying an hour chooser
		 *
		 * @param string $date the current date (optional)
		 * @param bool   $isStart
		 *
		 * @return string a set of HTML options with hours (current hour selected)
		 */
		public static function getHourOptions( $date = '', $isStart = false ) {
			$hours = self::hours();

			if ( count( $hours ) == 12 ) {
				$h = 'h';
			} else {
				$h = 'H';
			}
			$options = '';

			if ( empty( $date ) ) {
				$hour = ( $isStart ) ? '08' : ( count( $hours ) == 12 ? '05' : '17' );
			} else {
				$timestamp = strtotime( $date );
				$hour      = date( $h, $timestamp );
				// fix hours if time_format has changed from what is saved
				if ( preg_match( '(pm|PM)', $timestamp ) && $h == 'H' ) {
					$hour = $hour + 12;
				}
				if ( $hour > 12 && $h == 'h' ) {
					$hour = $hour - 12;
				}
			}

			$hour = apply_filters( 'e_rn_get_hour_options', $hour, $date, $isStart );

			foreach ( $hours as $hourText ) {
				if ( $hour == $hourText ) {
					$selected = 'selected="selected"';
				} else {
					$selected = '';
				}
				$options .= "<option value='$hourText' $selected>$hourText</option>\n";
			}

			return $options;
		}

		/**
		 * Builds a set of options for displaying a minute chooser
		 *
		 * @param string $date the current date (optional)
		 * @param bool   $isStart
		 *
		 * @return string a set of HTML options with minutes (current minute selected)
		 */
		public static function getMinuteOptions( $date = '', $isStart = false ) {
			$options = '';

			if ( empty( $date ) ) {
				$minute = '00';
			} else {
				$minute = date( 'i', strtotime( $date ) );
			}

			$minute = apply_filters( 'e_rn_get_minute_options', $minute, $date, $isStart );
			$minutes = self::minutes( $minute );

			foreach ( $minutes as $minuteText ) {
				if ( $minute == $minuteText ) {
					$selected = 'selected="selected"';
				} else {
					$selected = '';
				}
				$options .= "<option value='$minuteText' $selected>$minuteText</option>\n";
			}

			return $options;
		}

		/**
		 * Helper method to return an array of 1-12 for hours
		 *
		 * @return array The hours array.
		 */
		private static function hours() {
			$hours      = array();
			$rangeMax   = self::is_24hr_format() ? 23 : 12;
			$rangeStart = $rangeMax > 12 ? 0 : 1;
			foreach ( range( $rangeStart, $rangeMax ) as $hour ) {
				if ( $hour < 10 ) {
					$hour = '0' . $hour;
				}
				$hours[ $hour ] = $hour;
			}

			// In a 12hr context lets put 12 at the start (so the sequence will run 12, 1, 2, 3 ... 11)
			if ( 12 === $rangeMax ) {
				array_unshift( $hours, array_pop( $hours ) );
			}

			return $hours;
		}

		/**
		 * Determines if the provided date/time format (or else the default WordPress time_format)
		 * is 24hr or not.
		 *
		 * In inconclusive cases, such as if there are now hour-format characters, 12hr format is
		 * assumed.
		 *
		 * @param null $format
		 * @return bool
		 */
		public static function is_24hr_format( $format = null ) {
			// Use the provided format or else use the value of the current time_format setting
			$format = ( null === $format ) ? get_option( 'time_format', E_Register_Now__Date_Utils::TIMEFORMAT ) : $format;

			// Count instances of the H and G symbols
			$h_symbols = substr_count( $format, 'H' );
			$g_symbols = substr_count( $format, 'G' );

			// If none have been found then consider the format to be 12hr
			if ( ! $h_symbols && ! $g_symbols ) return false;

			// It's possible H or G have been included as escaped characters
			$h_escaped = substr_count( $format, '\H' );
			$g_escaped = substr_count( $format, '\G' );

			// Final check, accounting for possibility of escaped values
			return ( $h_symbols > $h_escaped || $g_symbols > $g_escaped );
		}

		/**
		 * Helper method to return an array of 00-59 for minutes
		 *
		 * @param  int $exact_minute optionally specify an exact minute to be included (outwith the default intervals)
		 *
		 * @return array The minutes array.
		 */
		private static function minutes( $exact_minute = 0 ) {
			$minutes = array();

			// The exact minute should be an absint between 0 and 59
			$exact_minute = absint( $exact_minute );

			if ( $exact_minute < 0 || $exact_minute > 59 ) {
				$exact_minute = 0;
			}

			/**
			 * Filters the amount of minutes to increment the minutes drop-down by
			 *
			 * @param int Increment amount (defaults to 5)
			 */
			$default_increment = apply_filters( 'e_rn_minutes_increment', 5 );

			// Unless an exact minute has been specified we can minimize the amount of looping we do
			$increment = ( 0 === $exact_minute ) ? $default_increment : 1;

			for ( $minute = 0; $minute < 60; $minute += $increment ) {
				// Skip if this $minute doesn't meet the increment pattern and isn't an additional exact minute
				if ( 0 !== $minute % $default_increment && $exact_minute !== $minute ) {
					continue;
				}

				if ( $minute < 10 ) {
					$minute = '0' . $minute;
				}
				$minutes[ $minute ] = $minute;
			}

			return $minutes;
		}

		/**
		 * Builds a set of options for diplaying a meridian chooser
		 *
		 * @param string $date YYYY-MM-DD HH:MM:SS to select (optional)
		 * @param bool   $isStart
		 *
		 * @return string a set of HTML options with all meridians
		 */
		public static function getMeridianOptions( $date = '', $isStart = false ) {
			if ( strstr( get_option( 'time_format', E_Register_Now__Date_Utils::TIMEFORMAT ), 'A' ) ) {
				$a         = 'A';
				$meridians = array( 'AM', 'PM' );
			} else {
				$a         = 'a';
				$meridians = array( 'am', 'pm' );
			}
			if ( empty( $date ) ) {
				$meridian = ( $isStart ) ? $meridians[0] : $meridians[1];
			} else {
				$meridian = date( $a, strtotime( $date ) );
			}

			$meridian = apply_filters( 'e_rn_get_meridian_options', $meridian, $date, $isStart );

			$return = '';
			foreach ( $meridians as $m ) {
				$return .= "<option value='$m'";
				if ( $m == $meridian ) {
					$return .= ' selected="selected"';
				}
				$return .= ">$m</option>\n";
			}

			return $return;
		}

		/**
		 * Helper method to return an array of years
		 * default is back 5 and forward 5
		 *
		 * @return array The array of years.
		 */
		private static function years() {
			$current_year  = (int) date_i18n( 'Y' );
			$years_back    = (int) apply_filters( 'e_rn_years_to_go_back', 5, $current_year );
			$years_forward = (int) apply_filters( 'e_rn_years_to_go_forward', 5, $current_year );
			$years         = array();
			for ( $i = $years_back; $i > 0; $i -- ) {
				$year    = $current_year - $i;
				$years[] = $year;
			}
			$years[] = $current_year;
			for ( $i = 1; $i <= $years_forward; $i ++ ) {
				$year    = $current_year + $i;
				$years[] = $year;
			}

			return (array) apply_filters( 'e_rn_years_array', $years );
		}

		/**
		 * Helper method to return an array of 1-31 for days
		 *
		 * @return array The days array.
		 */
		public static function days( $totalDays ) {
			$days = array();
			foreach ( range( 1, $totalDays ) as $day ) {
				$days[ $day ] = $day;
			}

			return $days;
		}
	}
}
