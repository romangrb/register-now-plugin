<?php
/**
 * Various helper methods used in views
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'E__Register__Now__View_Helpers' ) ) {
	class E__Register__Now__View_Helpers {

		/**
		 * Get the countries being used and available for the plugin.
		 *
		 * @param string $postId     The post ID.
		 * @param bool   $useDefault Should we use the defaults?
		 *
		 * @return array The countries array.
		 */
		public static function constructCountries( $postId = '', $useDefault = true ) {

			if ( tribe_get_option( 'tribeEventsCountries' ) != '' ) {
				$countries = array(
					'' => esc_html__( 'Select a Country:', 'ern-common' ),
				);

				$country_rows = explode( "\n", tribe_get_option( 'tribeEventsCountries' ) );
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
					''   => esc_html__( 'Select a Country:', 'ern-common' ),
					'US' => esc_html__( 'United States', 'ern-common' ),
					'AF' => esc_html__( 'Afghanistan', 'ern-common' ),
					'AL' => esc_html__( 'Albania', 'ern-common' ),
					'DZ' => esc_html__( 'Algeria', 'ern-common' ),
					'AS' => esc_html__( 'American Samoa', 'ern-common' ),
					'AD' => esc_html__( 'Andorra', 'ern-common' ),
					'AO' => esc_html__( 'Angola', 'ern-common' ),
					'AI' => esc_html__( 'Anguilla', 'ern-common' ),
					'AQ' => esc_html__( 'Antarctica', 'ern-common' ),
					'AG' => esc_html__( 'Antigua And Barbuda', 'ern-common' ),
					'AR' => esc_html__( 'Argentina', 'ern-common' ),
					'AM' => esc_html__( 'Armenia', 'ern-common' ),
					'AW' => esc_html__( 'Aruba', 'ern-common' ),
					'AU' => esc_html__( 'Australia', 'ern-common' ),
					'AT' => esc_html__( 'Austria', 'ern-common' ),
					'AZ' => esc_html__( 'Azerbaijan', 'ern-common' ),
					'BS' => esc_html__( 'Bahamas', 'ern-common' ),
					'BH' => esc_html__( 'Bahrain', 'ern-common' ),
					'BD' => esc_html__( 'Bangladesh', 'ern-common' ),
					'BB' => esc_html__( 'Barbados', 'ern-common' ),
					'BY' => esc_html__( 'Belarus', 'ern-common' ),
					'BE' => esc_html__( 'Belgium', 'ern-common' ),
					'BZ' => esc_html__( 'Belize', 'ern-common' ),
					'BJ' => esc_html__( 'Benin', 'ern-common' ),
					'BM' => esc_html__( 'Bermuda', 'ern-common' ),
					'BT' => esc_html__( 'Bhutan', 'ern-common' ),
					'BO' => esc_html__( 'Bolivia', 'ern-common' ),
					'BA' => esc_html__( 'Bosnia And Herzegowina', 'ern-common' ),
					'BW' => esc_html__( 'Botswana', 'ern-common' ),
					'BV' => esc_html__( 'Bouvet Island', 'ern-common' ),
					'BR' => esc_html__( 'Brazil', 'ern-common' ),
					'IO' => esc_html__( 'British Indian Ocean Territory', 'ern-common' ),
					'BN' => esc_html__( 'Brunei Darussalam', 'ern-common' ),
					'BG' => esc_html__( 'Bulgaria', 'ern-common' ),
					'BF' => esc_html__( 'Burkina Faso', 'ern-common' ),
					'BI' => esc_html__( 'Burundi', 'ern-common' ),
					'KH' => esc_html__( 'Cambodia', 'ern-common' ),
					'CM' => esc_html__( 'Cameroon', 'ern-common' ),
					'CA' => esc_html__( 'Canada', 'ern-common' ),
					'CV' => esc_html__( 'Cape Verde', 'ern-common' ),
					'KY' => esc_html__( 'Cayman Islands', 'ern-common' ),
					'CF' => esc_html__( 'Central African Republic', 'ern-common' ),
					'TD' => esc_html__( 'Chad', 'ern-common' ),
					'CL' => esc_html__( 'Chile', 'ern-common' ),
					'CN' => esc_html__( 'China', 'ern-common' ),
					'CX' => esc_html__( 'Christmas Island', 'ern-common' ),
					'CC' => esc_html__( 'Cocos (Keeling) Islands', 'ern-common' ),
					'CO' => esc_html__( 'Colombia', 'ern-common' ),
					'KM' => esc_html__( 'Comoros', 'ern-common' ),
					'CG' => esc_html__( 'Congo', 'ern-common' ),
					'CD' => esc_html__( 'Congo, The Democratic Republic Of The', 'ern-common' ),
					'CK' => esc_html__( 'Cook Islands', 'ern-common' ),
					'CR' => esc_html__( 'Costa Rica', 'ern-common' ),
					'CI' => esc_html__( "Cote D'Ivoire", 'ern-common' ),
					'HR' => esc_html__( 'Croatia (Local Name: Hrvatska)', 'ern-common' ),
					'CU' => esc_html__( 'Cuba', 'ern-common' ),
					'CY' => esc_html__( 'Cyprus', 'ern-common' ),
					'CZ' => esc_html__( 'Czech Republic', 'ern-common' ),
					'DK' => esc_html__( 'Denmark', 'ern-common' ),
					'DJ' => esc_html__( 'Djibouti', 'ern-common' ),
					'DM' => esc_html__( 'Dominica', 'ern-common' ),
					'DO' => esc_html__( 'Dominican Republic', 'ern-common' ),
					'TP' => esc_html__( 'East Timor', 'ern-common' ),
					'EC' => esc_html__( 'Ecuador', 'ern-common' ),
					'EG' => esc_html__( 'Egypt', 'ern-common' ),
					'SV' => esc_html__( 'El Salvador', 'ern-common' ),
					'GQ' => esc_html__( 'Equatorial Guinea', 'ern-common' ),
					'ER' => esc_html__( 'Eritrea', 'ern-common' ),
					'EE' => esc_html__( 'Estonia', 'ern-common' ),
					'ET' => esc_html__( 'Ethiopia', 'ern-common' ),
					'FK' => esc_html__( 'Falkland Islands (Malvinas)', 'ern-common' ),
					'FO' => esc_html__( 'Faroe Islands', 'ern-common' ),
					'FJ' => esc_html__( 'Fiji', 'ern-common' ),
					'FI' => esc_html__( 'Finland', 'ern-common' ),
					'FR' => esc_html__( 'France', 'ern-common' ),
					'FX' => esc_html__( 'France, Metropolitan', 'ern-common' ),
					'GF' => esc_html__( 'French Guiana', 'ern-common' ),
					'PF' => esc_html__( 'French Polynesia', 'ern-common' ),
					'TF' => esc_html__( 'French Southern Territories', 'ern-common' ),
					'GA' => esc_html__( 'Gabon', 'ern-common' ),
					'GM' => esc_html__( 'Gambia', 'ern-common' ),
					'GE' => esc_html__( 'Georgia', 'ern-common' ),
					'DE' => esc_html__( 'Germany', 'ern-common' ),
					'GH' => esc_html__( 'Ghana', 'ern-common' ),
					'GI' => esc_html__( 'Gibraltar', 'ern-common' ),
					'GR' => esc_html__( 'Greece', 'ern-common' ),
					'GL' => esc_html__( 'Greenland', 'ern-common' ),
					'GD' => esc_html__( 'Grenada', 'ern-common' ),
					'GP' => esc_html__( 'Guadeloupe', 'ern-common' ),
					'GU' => esc_html__( 'Guam', 'ern-common' ),
					'GT' => esc_html__( 'Guatemala', 'ern-common' ),
					'GN' => esc_html__( 'Guinea', 'ern-common' ),
					'GW' => esc_html__( 'Guinea-Bissau', 'ern-common' ),
					'GY' => esc_html__( 'Guyana', 'ern-common' ),
					'HT' => esc_html__( 'Haiti', 'ern-common' ),
					'HM' => esc_html__( 'Heard And Mc Donald Islands', 'ern-common' ),
					'VA' => esc_html__( 'Holy See (Vatican City State)', 'ern-common' ),
					'HN' => esc_html__( 'Honduras', 'ern-common' ),
					'HK' => esc_html__( 'Hong Kong', 'ern-common' ),
					'HU' => esc_html__( 'Hungary', 'ern-common' ),
					'IS' => esc_html__( 'Iceland', 'ern-common' ),
					'IN' => esc_html__( 'India', 'ern-common' ),
					'ID' => esc_html__( 'Indonesia', 'ern-common' ),
					'IR' => esc_html__( 'Iran (Islamic Republic Of)', 'ern-common' ),
					'IQ' => esc_html__( 'Iraq', 'ern-common' ),
					'IE' => esc_html__( 'Ireland', 'ern-common' ),
					'IL' => esc_html__( 'Israel', 'ern-common' ),
					'IT' => esc_html__( 'Italy', 'ern-common' ),
					'JM' => esc_html__( 'Jamaica', 'ern-common' ),
					'JP' => esc_html__( 'Japan', 'ern-common' ),
					'JO' => esc_html__( 'Jordan', 'ern-common' ),
					'KZ' => esc_html__( 'Kazakhstan', 'ern-common' ),
					'KE' => esc_html__( 'Kenya', 'ern-common' ),
					'KI' => esc_html__( 'Kiribati', 'ern-common' ),
					'KP' => esc_html__( "Korea, Democratic People's Republic Of", 'ern-common' ),
					'KR' => esc_html__( 'Korea, Republic Of', 'ern-common' ),
					'KW' => esc_html__( 'Kuwait', 'ern-common' ),
					'KG' => esc_html__( 'Kyrgyzstan', 'ern-common' ),
					'LA' => esc_html__( "Lao People's Democratic Republic", 'ern-common' ),
					'LV' => esc_html__( 'Latvia', 'ern-common' ),
					'LB' => esc_html__( 'Lebanon', 'ern-common' ),
					'LS' => esc_html__( 'Lesotho', 'ern-common' ),
					'LR' => esc_html__( 'Liberia', 'ern-common' ),
					'LY' => esc_html__( 'Libya', 'ern-common' ),
					'LI' => esc_html__( 'Liechtenstein', 'ern-common' ),
					'LT' => esc_html__( 'Lithuania', 'ern-common' ),
					'LU' => esc_html__( 'Luxembourg', 'ern-common' ),
					'MO' => esc_html__( 'Macau', 'ern-common' ),
					'MK' => esc_html__( 'Macedonia', 'ern-common' ),
					'MG' => esc_html__( 'Madagascar', 'ern-common' ),
					'MW' => esc_html__( 'Malawi', 'ern-common' ),
					'MY' => esc_html__( 'Malaysia', 'ern-common' ),
					'MV' => esc_html__( 'Maldives', 'ern-common' ),
					'ML' => esc_html__( 'Mali', 'ern-common' ),
					'MT' => esc_html__( 'Malta', 'ern-common' ),
					'MH' => esc_html__( 'Marshall Islands', 'ern-common' ),
					'MQ' => esc_html__( 'Martinique', 'ern-common' ),
					'MR' => esc_html__( 'Mauritania', 'ern-common' ),
					'MU' => esc_html__( 'Mauritius', 'ern-common' ),
					'YT' => esc_html__( 'Mayotte', 'ern-common' ),
					'MX' => esc_html__( 'Mexico', 'ern-common' ),
					'FM' => esc_html__( 'Micronesia, Federated States Of', 'ern-common' ),
					'MD' => esc_html__( 'Moldova, Republic Of', 'ern-common' ),
					'MC' => esc_html__( 'Monaco', 'ern-common' ),
					'MN' => esc_html__( 'Mongolia', 'ern-common' ),
					'ME' => esc_html__( 'Montenegro', 'ern-common' ),
					'MS' => esc_html__( 'Montserrat', 'ern-common' ),
					'MA' => esc_html__( 'Morocco', 'ern-common' ),
					'MZ' => esc_html__( 'Mozambique', 'ern-common' ),
					'MM' => esc_html__( 'Myanmar', 'ern-common' ),
					'NA' => esc_html__( 'Namibia', 'ern-common' ),
					'NR' => esc_html__( 'Nauru', 'ern-common' ),
					'NP' => esc_html__( 'Nepal', 'ern-common' ),
					'NL' => esc_html__( 'Netherlands', 'ern-common' ),
					'AN' => esc_html__( 'Netherlands Antilles', 'ern-common' ),
					'NC' => esc_html__( 'New Caledonia', 'ern-common' ),
					'NZ' => esc_html__( 'New Zealand', 'ern-common' ),
					'NI' => esc_html__( 'Nicaragua', 'ern-common' ),
					'NE' => esc_html__( 'Niger', 'ern-common' ),
					'NG' => esc_html__( 'Nigeria', 'ern-common' ),
					'NU' => esc_html__( 'Niue', 'ern-common' ),
					'NF' => esc_html__( 'Norfolk Island', 'ern-common' ),
					'MP' => esc_html__( 'Northern Mariana Islands', 'ern-common' ),
					'NO' => esc_html__( 'Norway', 'ern-common' ),
					'OM' => esc_html__( 'Oman', 'ern-common' ),
					'PK' => esc_html__( 'Pakistan', 'ern-common' ),
					'PW' => esc_html__( 'Palau', 'ern-common' ),
					'PA' => esc_html__( 'Panama', 'ern-common' ),
					'PG' => esc_html__( 'Papua New Guinea', 'ern-common' ),
					'PY' => esc_html__( 'Paraguay', 'ern-common' ),
					'PE' => esc_html__( 'Peru', 'ern-common' ),
					'PH' => esc_html__( 'Philippines', 'ern-common' ),
					'PN' => esc_html__( 'Pitcairn', 'ern-common' ),
					'PL' => esc_html__( 'Poland', 'ern-common' ),
					'PT' => esc_html__( 'Portugal', 'ern-common' ),
					'PR' => esc_html__( 'Puerto Rico', 'ern-common' ),
					'QA' => esc_html__( 'Qatar', 'ern-common' ),
					'RE' => esc_html__( 'Reunion', 'ern-common' ),
					'RO' => esc_html__( 'Romania', 'ern-common' ),
					'RU' => esc_html__( 'Russian Federation', 'ern-common' ),
					'RW' => esc_html__( 'Rwanda', 'ern-common' ),
					'KN' => esc_html__( 'Saint Kitts And Nevis', 'ern-common' ),
					'LC' => esc_html__( 'Saint Lucia', 'ern-common' ),
					'VC' => esc_html__( 'Saint Vincent And The Grenadines', 'ern-common' ),
					'WS' => esc_html__( 'Samoa', 'ern-common' ),
					'SM' => esc_html__( 'San Marino', 'ern-common' ),
					'ST' => esc_html__( 'Sao Tome And Principe', 'ern-common' ),
					'SA' => esc_html__( 'Saudi Arabia', 'ern-common' ),
					'SN' => esc_html__( 'Senegal', 'ern-common' ),
					'RS' => esc_html__( 'Serbia', 'ern-common' ),
					'SC' => esc_html__( 'Seychelles', 'ern-common' ),
					'SL' => esc_html__( 'Sierra Leone', 'ern-common' ),
					'SG' => esc_html__( 'Singapore', 'ern-common' ),
					'SK' => esc_html__( 'Slovakia (Slovak Republic)', 'ern-common' ),
					'SI' => esc_html__( 'Slovenia', 'ern-common' ),
					'SB' => esc_html__( 'Solomon Islands', 'ern-common' ),
					'SO' => esc_html__( 'Somalia', 'ern-common' ),
					'ZA' => esc_html__( 'South Africa', 'ern-common' ),
					'GS' => esc_html__( 'South Georgia, South Sandwich Islands', 'ern-common' ),
					'ES' => esc_html__( 'Spain', 'ern-common' ),
					'LK' => esc_html__( 'Sri Lanka', 'ern-common' ),
					'SH' => esc_html__( 'St. Helena', 'ern-common' ),
					'PM' => esc_html__( 'St. Pierre And Miquelon', 'ern-common' ),
					'SD' => esc_html__( 'Sudan', 'ern-common' ),
					'SR' => esc_html__( 'Suriname', 'ern-common' ),
					'SJ' => esc_html__( 'Svalbard And Jan Mayen Islands', 'ern-common' ),
					'SZ' => esc_html__( 'Swaziland', 'ern-common' ),
					'SE' => esc_html__( 'Sweden', 'ern-common' ),
					'CH' => esc_html__( 'Switzerland', 'ern-common' ),
					'SY' => esc_html__( 'Syrian Arab Republic', 'ern-common' ),
					'TW' => esc_html__( 'Taiwan', 'ern-common' ),
					'TJ' => esc_html__( 'Tajikistan', 'ern-common' ),
					'TZ' => esc_html__( 'Tanzania, United Republic Of', 'ern-common' ),
					'TH' => esc_html__( 'Thailand', 'ern-common' ),
					'TG' => esc_html__( 'Togo', 'ern-common' ),
					'TK' => esc_html__( 'Tokelau', 'ern-common' ),
					'TO' => esc_html__( 'Tonga', 'ern-common' ),
					'TT' => esc_html__( 'Trinidad And Tobago', 'ern-common' ),
					'TN' => esc_html__( 'Tunisia', 'ern-common' ),
					'TR' => esc_html__( 'Turkey', 'ern-common' ),
					'TM' => esc_html__( 'Turkmenistan', 'ern-common' ),
					'TC' => esc_html__( 'Turks And Caicos Islands', 'ern-common' ),
					'TV' => esc_html__( 'Tuvalu', 'ern-common' ),
					'UG' => esc_html__( 'Uganda', 'ern-common' ),
					'UA' => esc_html__( 'Ukraine', 'ern-common' ),
					'AE' => esc_html__( 'United Arab Emirates', 'ern-common' ),
					'GB' => esc_html__( 'United Kingdom', 'ern-common' ),
					'UM' => esc_html__( 'United States Minor Outlying Islands', 'ern-common' ),
					'UY' => esc_html__( 'Uruguay', 'ern-common' ),
					'UZ' => esc_html__( 'Uzbekistan', 'ern-common' ),
					'VU' => esc_html__( 'Vanuatu', 'ern-common' ),
					'VE' => esc_html__( 'Venezuela', 'ern-common' ),
					'VN' => esc_html__( 'Viet Nam', 'ern-common' ),
					'VG' => esc_html__( 'Virgin Islands (British)', 'ern-common' ),
					'VI' => esc_html__( 'Virgin Islands (U.S.)', 'ern-common' ),
					'WF' => esc_html__( 'Wallis And Futuna Islands', 'ern-common' ),
					'EH' => esc_html__( 'Western Sahara', 'ern-common' ),
					'YE' => esc_html__( 'Yemen', 'ern-common' ),
					'ZM' => esc_html__( 'Zambia', 'ern-common' ),
					'ZW' => esc_html__( 'Zimbabwe', 'ern-common' ),
				);
			}
			if ( ( $postId || $useDefault ) ) {
				$countryValue = get_post_meta( $postId, '_EventCountry', true );
				if ( $countryValue ) {
					$defaultCountry = array( array_search( $countryValue, $countries ), $countryValue );
				} else {
					$defaultCountry = tribe_get_default_value( 'country' );
				}
				if ( $defaultCountry && $defaultCountry[0] != '' ) {
					$selectCountry = array_shift( $countries );
					asort( $countries );
					$countries = array( $defaultCountry[0] => __( $defaultCountry[1], 'ern-common' ) ) + $countries;
					$countries = array( '' => __( $selectCountry, 'ern-common' ) ) + $countries;
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
				'AL' => esc_html__( 'Alabama', 'ern-common' ),
				'AK' => esc_html__( 'Alaska', 'ern-common' ),
				'AZ' => esc_html__( 'Arizona', 'ern-common' ),
				'AR' => esc_html__( 'Arkansas', 'ern-common' ),
				'CA' => esc_html__( 'California', 'ern-common' ),
				'CO' => esc_html__( 'Colorado', 'ern-common' ),
				'CT' => esc_html__( 'Connecticut', 'ern-common' ),
				'DE' => esc_html__( 'Delaware', 'ern-common' ),
				'DC' => esc_html__( 'District of Columbia', 'ern-common' ),
				'FL' => esc_html__( 'Florida', 'ern-common' ),
				'GA' => esc_html__( 'Georgia', 'ern-common' ),
				'HI' => esc_html__( 'Hawaii', 'ern-common' ),
				'ID' => esc_html__( 'Idaho', 'ern-common' ),
				'IL' => esc_html__( 'Illinois', 'ern-common' ),
				'IN' => esc_html__( 'Indiana', 'ern-common' ),
				'IA' => esc_html__( 'Iowa', 'ern-common' ),
				'KS' => esc_html__( 'Kansas', 'ern-common' ),
				'KY' => esc_html__( 'Kentucky', 'ern-common' ),
				'LA' => esc_html__( 'Louisiana', 'ern-common' ),
				'ME' => esc_html__( 'Maine', 'ern-common' ),
				'MD' => esc_html__( 'Maryland', 'ern-common' ),
				'MA' => esc_html__( 'Massachusetts', 'ern-common' ),
				'MI' => esc_html__( 'Michigan', 'ern-common' ),
				'MN' => esc_html__( 'Minnesota', 'ern-common' ),
				'MS' => esc_html__( 'Mississippi', 'ern-common' ),
				'MO' => esc_html__( 'Missouri', 'ern-common' ),
				'MT' => esc_html__( 'Montana', 'ern-common' ),
				'NE' => esc_html__( 'Nebraska', 'ern-common' ),
				'NV' => esc_html__( 'Nevada', 'ern-common' ),
				'NH' => esc_html__( 'New Hampshire', 'ern-common' ),
				'NJ' => esc_html__( 'New Jersey', 'ern-common' ),
				'NM' => esc_html__( 'New Mexico', 'ern-common' ),
				'NY' => esc_html__( 'New York', 'ern-common' ),
				'NC' => esc_html__( 'North Carolina', 'ern-common' ),
				'ND' => esc_html__( 'North Dakota', 'ern-common' ),
				'OH' => esc_html__( 'Ohio', 'ern-common' ),
				'OK' => esc_html__( 'Oklahoma', 'ern-common' ),
				'OR' => esc_html__( 'Oregon', 'ern-common' ),
				'PA' => esc_html__( 'Pennsylvania', 'ern-common' ),
				'RI' => esc_html__( 'Rhode Island', 'ern-common' ),
				'SC' => esc_html__( 'South Carolina', 'ern-common' ),
				'SD' => esc_html__( 'South Dakota', 'ern-common' ),
				'TN' => esc_html__( 'Tennessee', 'ern-common' ),
				'TX' => esc_html__( 'Texas', 'ern-common' ),
				'UT' => esc_html__( 'Utah', 'ern-common' ),
				'VT' => esc_html__( 'Vermont', 'ern-common' ),
				'VA' => esc_html__( 'Virginia', 'ern-common' ),
				'WA' => esc_html__( 'Washington', 'ern-common' ),
				'WV' => esc_html__( 'West Virginia', 'ern-common' ),
				'WI' => esc_html__( 'Wisconsin', 'ern-common' ),
				'WY' => esc_html__( 'Wyoming', 'ern-common' ),
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

			$hour = apply_filters( 'tribe_get_hour_options', $hour, $date, $isStart );

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

			$minute = apply_filters( 'tribe_get_minute_options', $minute, $date, $isStart );
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
			$format = ( null === $format ) ? get_option( 'time_format', E__Register__Now__Date_Utils::TIMEFORMAT ) : $format;

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
			$default_increment = apply_filters( 'tribe_minutes_increment', 5 );

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
			if ( strstr( get_option( 'time_format', E__Register__Now__Date_Utils::TIMEFORMAT ), 'A' ) ) {
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

			$meridian = apply_filters( 'tribe_get_meridian_options', $meridian, $date, $isStart );

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
			$years_back    = (int) apply_filters( 'tribe_years_to_go_back', 5, $current_year );
			$years_forward = (int) apply_filters( 'tribe_years_to_go_forward', 5, $current_year );
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

			return (array) apply_filters( 'tribe_years_array', $years );
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
