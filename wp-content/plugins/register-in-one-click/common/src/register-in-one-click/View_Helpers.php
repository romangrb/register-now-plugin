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
					'' => esc_html__( 'Select a Country:', 'rioc-common' ),
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
					''   => esc_html__( 'Select a Country:', 'rioc-common' ),
					'US' => esc_html__( 'United States', 'rioc-common' ),
					'AF' => esc_html__( 'Afghanistan', 'rioc-common' ),
					'AL' => esc_html__( 'Albania', 'rioc-common' ),
					'DZ' => esc_html__( 'Algeria', 'rioc-common' ),
					'AS' => esc_html__( 'American Samoa', 'rioc-common' ),
					'AD' => esc_html__( 'Andorra', 'rioc-common' ),
					'AO' => esc_html__( 'Angola', 'rioc-common' ),
					'AI' => esc_html__( 'Anguilla', 'rioc-common' ),
					'AQ' => esc_html__( 'Antarctica', 'rioc-common' ),
					'AG' => esc_html__( 'Antigua And Barbuda', 'rioc-common' ),
					'AR' => esc_html__( 'Argentina', 'rioc-common' ),
					'AM' => esc_html__( 'Armenia', 'rioc-common' ),
					'AW' => esc_html__( 'Aruba', 'rioc-common' ),
					'AU' => esc_html__( 'Australia', 'rioc-common' ),
					'AT' => esc_html__( 'Austria', 'rioc-common' ),
					'AZ' => esc_html__( 'Azerbaijan', 'rioc-common' ),
					'BS' => esc_html__( 'Bahamas', 'rioc-common' ),
					'BH' => esc_html__( 'Bahrain', 'rioc-common' ),
					'BD' => esc_html__( 'Bangladesh', 'rioc-common' ),
					'BB' => esc_html__( 'Barbados', 'rioc-common' ),
					'BY' => esc_html__( 'Belarus', 'rioc-common' ),
					'BE' => esc_html__( 'Belgium', 'rioc-common' ),
					'BZ' => esc_html__( 'Belize', 'rioc-common' ),
					'BJ' => esc_html__( 'Benin', 'rioc-common' ),
					'BM' => esc_html__( 'Bermuda', 'rioc-common' ),
					'BT' => esc_html__( 'Bhutan', 'rioc-common' ),
					'BO' => esc_html__( 'Bolivia', 'rioc-common' ),
					'BA' => esc_html__( 'Bosnia And Herzegowina', 'rioc-common' ),
					'BW' => esc_html__( 'Botswana', 'rioc-common' ),
					'BV' => esc_html__( 'Bouvet Island', 'rioc-common' ),
					'BR' => esc_html__( 'Brazil', 'rioc-common' ),
					'IO' => esc_html__( 'British Indian Ocean Territory', 'rioc-common' ),
					'BN' => esc_html__( 'Brunei Darussalam', 'rioc-common' ),
					'BG' => esc_html__( 'Bulgaria', 'rioc-common' ),
					'BF' => esc_html__( 'Burkina Faso', 'rioc-common' ),
					'BI' => esc_html__( 'Burundi', 'rioc-common' ),
					'KH' => esc_html__( 'Cambodia', 'rioc-common' ),
					'CM' => esc_html__( 'Cameroon', 'rioc-common' ),
					'CA' => esc_html__( 'Canada', 'rioc-common' ),
					'CV' => esc_html__( 'Cape Verde', 'rioc-common' ),
					'KY' => esc_html__( 'Cayman Islands', 'rioc-common' ),
					'CF' => esc_html__( 'Central African Republic', 'rioc-common' ),
					'TD' => esc_html__( 'Chad', 'rioc-common' ),
					'CL' => esc_html__( 'Chile', 'rioc-common' ),
					'CN' => esc_html__( 'China', 'rioc-common' ),
					'CX' => esc_html__( 'Christmas Island', 'rioc-common' ),
					'CC' => esc_html__( 'Cocos (Keeling) Islands', 'rioc-common' ),
					'CO' => esc_html__( 'Colombia', 'rioc-common' ),
					'KM' => esc_html__( 'Comoros', 'rioc-common' ),
					'CG' => esc_html__( 'Congo', 'rioc-common' ),
					'CD' => esc_html__( 'Congo, The Democratic Republic Of The', 'rioc-common' ),
					'CK' => esc_html__( 'Cook Islands', 'rioc-common' ),
					'CR' => esc_html__( 'Costa Rica', 'rioc-common' ),
					'CI' => esc_html__( "Cote D'Ivoire", 'rioc-common' ),
					'HR' => esc_html__( 'Croatia (Local Name: Hrvatska)', 'rioc-common' ),
					'CU' => esc_html__( 'Cuba', 'rioc-common' ),
					'CY' => esc_html__( 'Cyprus', 'rioc-common' ),
					'CZ' => esc_html__( 'Czech Republic', 'rioc-common' ),
					'DK' => esc_html__( 'Denmark', 'rioc-common' ),
					'DJ' => esc_html__( 'Djibouti', 'rioc-common' ),
					'DM' => esc_html__( 'Dominica', 'rioc-common' ),
					'DO' => esc_html__( 'Dominican Republic', 'rioc-common' ),
					'TP' => esc_html__( 'East Timor', 'rioc-common' ),
					'EC' => esc_html__( 'Ecuador', 'rioc-common' ),
					'EG' => esc_html__( 'Egypt', 'rioc-common' ),
					'SV' => esc_html__( 'El Salvador', 'rioc-common' ),
					'GQ' => esc_html__( 'Equatorial Guinea', 'rioc-common' ),
					'ER' => esc_html__( 'Eritrea', 'rioc-common' ),
					'EE' => esc_html__( 'Estonia', 'rioc-common' ),
					'ET' => esc_html__( 'Ethiopia', 'rioc-common' ),
					'FK' => esc_html__( 'Falkland Islands (Malvinas)', 'rioc-common' ),
					'FO' => esc_html__( 'Faroe Islands', 'rioc-common' ),
					'FJ' => esc_html__( 'Fiji', 'rioc-common' ),
					'FI' => esc_html__( 'Finland', 'rioc-common' ),
					'FR' => esc_html__( 'France', 'rioc-common' ),
					'FX' => esc_html__( 'France, Metropolitan', 'rioc-common' ),
					'GF' => esc_html__( 'French Guiana', 'rioc-common' ),
					'PF' => esc_html__( 'French Polynesia', 'rioc-common' ),
					'TF' => esc_html__( 'French Southern Territories', 'rioc-common' ),
					'GA' => esc_html__( 'Gabon', 'rioc-common' ),
					'GM' => esc_html__( 'Gambia', 'rioc-common' ),
					'GE' => esc_html__( 'Georgia', 'rioc-common' ),
					'DE' => esc_html__( 'Germany', 'rioc-common' ),
					'GH' => esc_html__( 'Ghana', 'rioc-common' ),
					'GI' => esc_html__( 'Gibraltar', 'rioc-common' ),
					'GR' => esc_html__( 'Greece', 'rioc-common' ),
					'GL' => esc_html__( 'Greenland', 'rioc-common' ),
					'GD' => esc_html__( 'Grenada', 'rioc-common' ),
					'GP' => esc_html__( 'Guadeloupe', 'rioc-common' ),
					'GU' => esc_html__( 'Guam', 'rioc-common' ),
					'GT' => esc_html__( 'Guatemala', 'rioc-common' ),
					'GN' => esc_html__( 'Guinea', 'rioc-common' ),
					'GW' => esc_html__( 'Guinea-Bissau', 'rioc-common' ),
					'GY' => esc_html__( 'Guyana', 'rioc-common' ),
					'HT' => esc_html__( 'Haiti', 'rioc-common' ),
					'HM' => esc_html__( 'Heard And Mc Donald Islands', 'rioc-common' ),
					'VA' => esc_html__( 'Holy See (Vatican City State)', 'rioc-common' ),
					'HN' => esc_html__( 'Honduras', 'rioc-common' ),
					'HK' => esc_html__( 'Hong Kong', 'rioc-common' ),
					'HU' => esc_html__( 'Hungary', 'rioc-common' ),
					'IS' => esc_html__( 'Iceland', 'rioc-common' ),
					'IN' => esc_html__( 'India', 'rioc-common' ),
					'ID' => esc_html__( 'Indonesia', 'rioc-common' ),
					'IR' => esc_html__( 'Iran (Islamic Republic Of)', 'rioc-common' ),
					'IQ' => esc_html__( 'Iraq', 'rioc-common' ),
					'IE' => esc_html__( 'Ireland', 'rioc-common' ),
					'IL' => esc_html__( 'Israel', 'rioc-common' ),
					'IT' => esc_html__( 'Italy', 'rioc-common' ),
					'JM' => esc_html__( 'Jamaica', 'rioc-common' ),
					'JP' => esc_html__( 'Japan', 'rioc-common' ),
					'JO' => esc_html__( 'Jordan', 'rioc-common' ),
					'KZ' => esc_html__( 'Kazakhstan', 'rioc-common' ),
					'KE' => esc_html__( 'Kenya', 'rioc-common' ),
					'KI' => esc_html__( 'Kiribati', 'rioc-common' ),
					'KP' => esc_html__( "Korea, Democratic People's Republic Of", 'rioc-common' ),
					'KR' => esc_html__( 'Korea, Republic Of', 'rioc-common' ),
					'KW' => esc_html__( 'Kuwait', 'rioc-common' ),
					'KG' => esc_html__( 'Kyrgyzstan', 'rioc-common' ),
					'LA' => esc_html__( "Lao People's Democratic Republic", 'rioc-common' ),
					'LV' => esc_html__( 'Latvia', 'rioc-common' ),
					'LB' => esc_html__( 'Lebanon', 'rioc-common' ),
					'LS' => esc_html__( 'Lesotho', 'rioc-common' ),
					'LR' => esc_html__( 'Liberia', 'rioc-common' ),
					'LY' => esc_html__( 'Libya', 'rioc-common' ),
					'LI' => esc_html__( 'Liechtenstein', 'rioc-common' ),
					'LT' => esc_html__( 'Lithuania', 'rioc-common' ),
					'LU' => esc_html__( 'Luxembourg', 'rioc-common' ),
					'MO' => esc_html__( 'Macau', 'rioc-common' ),
					'MK' => esc_html__( 'Macedonia', 'rioc-common' ),
					'MG' => esc_html__( 'Madagascar', 'rioc-common' ),
					'MW' => esc_html__( 'Malawi', 'rioc-common' ),
					'MY' => esc_html__( 'Malaysia', 'rioc-common' ),
					'MV' => esc_html__( 'Maldives', 'rioc-common' ),
					'ML' => esc_html__( 'Mali', 'rioc-common' ),
					'MT' => esc_html__( 'Malta', 'rioc-common' ),
					'MH' => esc_html__( 'Marshall Islands', 'rioc-common' ),
					'MQ' => esc_html__( 'Martinique', 'rioc-common' ),
					'MR' => esc_html__( 'Mauritania', 'rioc-common' ),
					'MU' => esc_html__( 'Mauritius', 'rioc-common' ),
					'YT' => esc_html__( 'Mayotte', 'rioc-common' ),
					'MX' => esc_html__( 'Mexico', 'rioc-common' ),
					'FM' => esc_html__( 'Micronesia, Federated States Of', 'rioc-common' ),
					'MD' => esc_html__( 'Moldova, Republic Of', 'rioc-common' ),
					'MC' => esc_html__( 'Monaco', 'rioc-common' ),
					'MN' => esc_html__( 'Mongolia', 'rioc-common' ),
					'ME' => esc_html__( 'Montenegro', 'rioc-common' ),
					'MS' => esc_html__( 'Montserrat', 'rioc-common' ),
					'MA' => esc_html__( 'Morocco', 'rioc-common' ),
					'MZ' => esc_html__( 'Mozambique', 'rioc-common' ),
					'MM' => esc_html__( 'Myanmar', 'rioc-common' ),
					'NA' => esc_html__( 'Namibia', 'rioc-common' ),
					'NR' => esc_html__( 'Nauru', 'rioc-common' ),
					'NP' => esc_html__( 'Nepal', 'rioc-common' ),
					'NL' => esc_html__( 'Netherlands', 'rioc-common' ),
					'AN' => esc_html__( 'Netherlands Antilles', 'rioc-common' ),
					'NC' => esc_html__( 'New Caledonia', 'rioc-common' ),
					'NZ' => esc_html__( 'New Zealand', 'rioc-common' ),
					'NI' => esc_html__( 'Nicaragua', 'rioc-common' ),
					'NE' => esc_html__( 'Niger', 'rioc-common' ),
					'NG' => esc_html__( 'Nigeria', 'rioc-common' ),
					'NU' => esc_html__( 'Niue', 'rioc-common' ),
					'NF' => esc_html__( 'Norfolk Island', 'rioc-common' ),
					'MP' => esc_html__( 'Northern Mariana Islands', 'rioc-common' ),
					'NO' => esc_html__( 'Norway', 'rioc-common' ),
					'OM' => esc_html__( 'Oman', 'rioc-common' ),
					'PK' => esc_html__( 'Pakistan', 'rioc-common' ),
					'PW' => esc_html__( 'Palau', 'rioc-common' ),
					'PA' => esc_html__( 'Panama', 'rioc-common' ),
					'PG' => esc_html__( 'Papua New Guinea', 'rioc-common' ),
					'PY' => esc_html__( 'Paraguay', 'rioc-common' ),
					'PE' => esc_html__( 'Peru', 'rioc-common' ),
					'PH' => esc_html__( 'Philippines', 'rioc-common' ),
					'PN' => esc_html__( 'Pitcairn', 'rioc-common' ),
					'PL' => esc_html__( 'Poland', 'rioc-common' ),
					'PT' => esc_html__( 'Portugal', 'rioc-common' ),
					'PR' => esc_html__( 'Puerto Rico', 'rioc-common' ),
					'QA' => esc_html__( 'Qatar', 'rioc-common' ),
					'RE' => esc_html__( 'Reunion', 'rioc-common' ),
					'RO' => esc_html__( 'Romania', 'rioc-common' ),
					'RU' => esc_html__( 'Russian Federation', 'rioc-common' ),
					'RW' => esc_html__( 'Rwanda', 'rioc-common' ),
					'KN' => esc_html__( 'Saint Kitts And Nevis', 'rioc-common' ),
					'LC' => esc_html__( 'Saint Lucia', 'rioc-common' ),
					'VC' => esc_html__( 'Saint Vincent And The Grenadines', 'rioc-common' ),
					'WS' => esc_html__( 'Samoa', 'rioc-common' ),
					'SM' => esc_html__( 'San Marino', 'rioc-common' ),
					'ST' => esc_html__( 'Sao Tome And Principe', 'rioc-common' ),
					'SA' => esc_html__( 'Saudi Arabia', 'rioc-common' ),
					'SN' => esc_html__( 'Senegal', 'rioc-common' ),
					'RS' => esc_html__( 'Serbia', 'rioc-common' ),
					'SC' => esc_html__( 'Seychelles', 'rioc-common' ),
					'SL' => esc_html__( 'Sierra Leone', 'rioc-common' ),
					'SG' => esc_html__( 'Singapore', 'rioc-common' ),
					'SK' => esc_html__( 'Slovakia (Slovak Republic)', 'rioc-common' ),
					'SI' => esc_html__( 'Slovenia', 'rioc-common' ),
					'SB' => esc_html__( 'Solomon Islands', 'rioc-common' ),
					'SO' => esc_html__( 'Somalia', 'rioc-common' ),
					'ZA' => esc_html__( 'South Africa', 'rioc-common' ),
					'GS' => esc_html__( 'South Georgia, South Sandwich Islands', 'rioc-common' ),
					'ES' => esc_html__( 'Spain', 'rioc-common' ),
					'LK' => esc_html__( 'Sri Lanka', 'rioc-common' ),
					'SH' => esc_html__( 'St. Helena', 'rioc-common' ),
					'PM' => esc_html__( 'St. Pierre And Miquelon', 'rioc-common' ),
					'SD' => esc_html__( 'Sudan', 'rioc-common' ),
					'SR' => esc_html__( 'Suriname', 'rioc-common' ),
					'SJ' => esc_html__( 'Svalbard And Jan Mayen Islands', 'rioc-common' ),
					'SZ' => esc_html__( 'Swaziland', 'rioc-common' ),
					'SE' => esc_html__( 'Sweden', 'rioc-common' ),
					'CH' => esc_html__( 'Switzerland', 'rioc-common' ),
					'SY' => esc_html__( 'Syrian Arab Republic', 'rioc-common' ),
					'TW' => esc_html__( 'Taiwan', 'rioc-common' ),
					'TJ' => esc_html__( 'Tajikistan', 'rioc-common' ),
					'TZ' => esc_html__( 'Tanzania, United Republic Of', 'rioc-common' ),
					'TH' => esc_html__( 'Thailand', 'rioc-common' ),
					'TG' => esc_html__( 'Togo', 'rioc-common' ),
					'TK' => esc_html__( 'Tokelau', 'rioc-common' ),
					'TO' => esc_html__( 'Tonga', 'rioc-common' ),
					'TT' => esc_html__( 'Trinidad And Tobago', 'rioc-common' ),
					'TN' => esc_html__( 'Tunisia', 'rioc-common' ),
					'TR' => esc_html__( 'Turkey', 'rioc-common' ),
					'TM' => esc_html__( 'Turkmenistan', 'rioc-common' ),
					'TC' => esc_html__( 'Turks And Caicos Islands', 'rioc-common' ),
					'TV' => esc_html__( 'Tuvalu', 'rioc-common' ),
					'UG' => esc_html__( 'Uganda', 'rioc-common' ),
					'UA' => esc_html__( 'Ukraine', 'rioc-common' ),
					'AE' => esc_html__( 'United Arab Emirates', 'rioc-common' ),
					'GB' => esc_html__( 'United Kingdom', 'rioc-common' ),
					'UM' => esc_html__( 'United States Minor Outlying Islands', 'rioc-common' ),
					'UY' => esc_html__( 'Uruguay', 'rioc-common' ),
					'UZ' => esc_html__( 'Uzbekistan', 'rioc-common' ),
					'VU' => esc_html__( 'Vanuatu', 'rioc-common' ),
					'VE' => esc_html__( 'Venezuela', 'rioc-common' ),
					'VN' => esc_html__( 'Viet Nam', 'rioc-common' ),
					'VG' => esc_html__( 'Virgin Islands (British)', 'rioc-common' ),
					'VI' => esc_html__( 'Virgin Islands (U.S.)', 'rioc-common' ),
					'WF' => esc_html__( 'Wallis And Futuna Islands', 'rioc-common' ),
					'EH' => esc_html__( 'Western Sahara', 'rioc-common' ),
					'YE' => esc_html__( 'Yemen', 'rioc-common' ),
					'ZM' => esc_html__( 'Zambia', 'rioc-common' ),
					'ZW' => esc_html__( 'Zimbabwe', 'rioc-common' ),
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
					$countries = array( $defaultCountry[0] => __( $defaultCountry[1], 'rioc-common' ) ) + $countries;
					$countries = array( '' => __( $selectCountry, 'rioc-common' ) ) + $countries;
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
				'AL' => esc_html__( 'Alabama', 'rioc-common' ),
				'AK' => esc_html__( 'Alaska', 'rioc-common' ),
				'AZ' => esc_html__( 'Arizona', 'rioc-common' ),
				'AR' => esc_html__( 'Arkansas', 'rioc-common' ),
				'CA' => esc_html__( 'California', 'rioc-common' ),
				'CO' => esc_html__( 'Colorado', 'rioc-common' ),
				'CT' => esc_html__( 'Connecticut', 'rioc-common' ),
				'DE' => esc_html__( 'Delaware', 'rioc-common' ),
				'DC' => esc_html__( 'District of Columbia', 'rioc-common' ),
				'FL' => esc_html__( 'Florida', 'rioc-common' ),
				'GA' => esc_html__( 'Georgia', 'rioc-common' ),
				'HI' => esc_html__( 'Hawaii', 'rioc-common' ),
				'ID' => esc_html__( 'Idaho', 'rioc-common' ),
				'IL' => esc_html__( 'Illinois', 'rioc-common' ),
				'IN' => esc_html__( 'Indiana', 'rioc-common' ),
				'IA' => esc_html__( 'Iowa', 'rioc-common' ),
				'KS' => esc_html__( 'Kansas', 'rioc-common' ),
				'KY' => esc_html__( 'Kentucky', 'rioc-common' ),
				'LA' => esc_html__( 'Louisiana', 'rioc-common' ),
				'ME' => esc_html__( 'Maine', 'rioc-common' ),
				'MD' => esc_html__( 'Maryland', 'rioc-common' ),
				'MA' => esc_html__( 'Massachusetts', 'rioc-common' ),
				'MI' => esc_html__( 'Michigan', 'rioc-common' ),
				'MN' => esc_html__( 'Minnesota', 'rioc-common' ),
				'MS' => esc_html__( 'Mississippi', 'rioc-common' ),
				'MO' => esc_html__( 'Missouri', 'rioc-common' ),
				'MT' => esc_html__( 'Montana', 'rioc-common' ),
				'NE' => esc_html__( 'Nebraska', 'rioc-common' ),
				'NV' => esc_html__( 'Nevada', 'rioc-common' ),
				'NH' => esc_html__( 'New Hampshire', 'rioc-common' ),
				'NJ' => esc_html__( 'New Jersey', 'rioc-common' ),
				'NM' => esc_html__( 'New Mexico', 'rioc-common' ),
				'NY' => esc_html__( 'New York', 'rioc-common' ),
				'NC' => esc_html__( 'North Carolina', 'rioc-common' ),
				'ND' => esc_html__( 'North Dakota', 'rioc-common' ),
				'OH' => esc_html__( 'Ohio', 'rioc-common' ),
				'OK' => esc_html__( 'Oklahoma', 'rioc-common' ),
				'OR' => esc_html__( 'Oregon', 'rioc-common' ),
				'PA' => esc_html__( 'Pennsylvania', 'rioc-common' ),
				'RI' => esc_html__( 'Rhode Island', 'rioc-common' ),
				'SC' => esc_html__( 'South Carolina', 'rioc-common' ),
				'SD' => esc_html__( 'South Dakota', 'rioc-common' ),
				'TN' => esc_html__( 'Tennessee', 'rioc-common' ),
				'TX' => esc_html__( 'Texas', 'rioc-common' ),
				'UT' => esc_html__( 'Utah', 'rioc-common' ),
				'VT' => esc_html__( 'Vermont', 'rioc-common' ),
				'VA' => esc_html__( 'Virginia', 'rioc-common' ),
				'WA' => esc_html__( 'Washington', 'rioc-common' ),
				'WV' => esc_html__( 'West Virginia', 'rioc-common' ),
				'WI' => esc_html__( 'Wisconsin', 'rioc-common' ),
				'WY' => esc_html__( 'Wyoming', 'rioc-common' ),
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
