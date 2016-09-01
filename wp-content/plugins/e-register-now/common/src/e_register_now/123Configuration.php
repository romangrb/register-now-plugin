<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'E_Register_Now__Configuration' ) ) {
	/**
	 * Class that handles the integration with our Shop App API
	 */
	class E_Register_Now__Configuration {

		/**
		 * Slug of the WP admin menu item
		 */
		const MENU_SLUG  = 'e_rn_common';
		const ADMIN_SLUG = 'e_rn_common';

		/**
		 * Singleton instance
		 *
		 * @var null or E_Register_Now__Configuration
		 */
		private static $instance = null;
		
		/**
		 * The slug for the new admin page
		 *
		 * @var string
		 */
		private $admin_page = null;
		
		/**
		 * the current tab being displayed
		 * @var string
		 */
		public $currentTab;
		
		/**
		 * Class constructor
		 */
		public function __construct() {
			
			$this->menuName    = apply_filters( 'e_rn_settings_menu_name', esc_html__( 'Events', $this::MENU_SLUG ) );
			$this->requiredCap = apply_filters( 'e_rn_settings_req_cap', 'manage_options' );
			$this->adminSlug   = apply_filters( 'e_rn_settings_admin_slug', $this::ADMIN_SLUG );
			$this->errors      = get_option( 'e_rn_settings_errors', array() );
			$this->major_error = get_option( 'e_rn_settings_major_error', false );
			$this->sent_data   = get_option( 'e_rn_settings_sent_data', array() );
			$this->validated   = array();
			$this->defaultTab  = null;
			$this->currentTab  = null;
			
			add_action( 'admin_menu', array( $this, 'add_configuration_page' ), 130 );
			add_action( 'wp_before_admin_bar_render', array( $this, 'add_toolbar_item' ), 20 );
			
			add_action( 'admin_init', array( $this, 'initTabs' ) );
			add_action( 'e_rn_settings_below_tabs', array( $this, 'displayErrors' ) );
			add_action( 'e_rn_settings_below_tabs', array( $this, 'displaySuccess' ) );
		}
		// post_type=e_rn&page=e_rn_common
		// page=e_rn_common&tab=general
		// page=tribe-common&tab=general&post_type=tribe_events
		/**
		 * Adds the page to the admin menu
		 */
		public function add_configuration_page() {
			// if ( ! E_Register_Now__Settings::instance()->should_setup_pages() ) {
			// 	return;
			// }
			
			// if ( ! E_Register_Now__Settings::instance()->wp_allow_config() ) {
			// 	return;
			// }
			
			$page_title = esc_html__( 'Configuration', 'e_rn_common' );
			$menu_title = esc_html__( 'Configuration', 'e_rn_common' );
			$capability = apply_filters( 'e_rn_events_addon_page_capability', 'install_plugins' );

			$where = E_Register_Now__Settings::instance()->get_parent_slug();
			//e_rn_common
			$this->admin_page = add_submenu_page( 
				$where, 
				$page_title, 
				$menu_title, 
				$capability,
				self::MENU_SLUG, 
				array( $this, 'generatePage' ) );

			add_action( 'admin_print_styles-' . $this->admin_page, array( $this, 'enqueue' ) );
		}
		/**
		 * the slug used in the admin to generate the settings page
		 * @var string
		 */
		public $adminSlug;
		/**
		 * init all the tabs
		 *
		 * @return void
		 */
		 
		 /**
		 * Page of the parent menu
		 * @var string
		 */
		public static $parent_page = 'edit.php';
		/**
		 * the menu name used for the settings page
		 * @var string
		 */
		public $menuName;
		/**
		 * generate the main option page
		 * includes the view file
		 *
		 * @return void
		 */
		public function generatePage() {
			do_action( 'e_rn_settings_top' );
			echo '<div class="e_rn_settings wrap">';
			screen_icon();
			echo '<h1>';
			printf( esc_html__( '%s Settings', 'e_rn_common' ), $this->menuName );
			echo '</h1>';
			do_action( 'e_rn_settings_above_tabs' );
			$this->generateTabs( $this->currentTab );
			do_action( 'e_rn_settings_below_tabs' );
			do_action( 'e_rn_settings_below_tabs_tab_' . $this->currentTab );
			echo '<div class="e-rn-settings-form form">';
			do_action( 'e_rn_settings_above_form_element' );
			do_action( 'e_rn_settings_above_form_element_tab_' . $this->currentTab );
			echo apply_filters( 'e_rn_settings_form_element_tab_' . $this->currentTab, '<form method="post">' );
			do_action( 'e_rn_settings_before_content' );
			do_action( 'e_rn_settings_before_content_tab_' . $this->currentTab );
			do_action( 'e_rn_settings_content_tab_' . $this->currentTab );
			if ( ! has_action( 'e_rn_settings_content_tab_' . $this->currentTab ) ) {
				echo '<p>' . esc_html__( "You've requested a non-existent tab.", 'e_rn_common' ) . '</p>';
			}
			do_action( 'e_rn_settings_after_content_tab_' . $this->currentTab );
			do_action( 'e_rn_settings_after_content' );
			if ( has_action( 'e_rn_settings_content_tab_' . $this->currentTab ) && ! in_array( $this->currentTab, $this->noSaveTabs ) ) {
				wp_nonce_field( 'saving', 'e-rn-save-settings' );
				echo '<div class="clear"></div>';
				echo '<input type="hidden" name="current-settings-tab" id="current-settings-tab" value="' . esc_attr( $this->currentTab ) . '" />';
				echo '<input id="tribeSaveSettings" class="button-primary" type="submit" name="tribeSaveSettings" value="' . esc_attr__( 'Save Changes', 'e_rn_common' ) . '" />';
			}
			echo apply_filters( 'e_rn_settings_closing_form_element', '</form>' );
			do_action( 'e_rn_settings_after_form_element' );
			do_action( 'e_rn_settings_after_form_element_tab_' . $this->currentTab );
			echo '</div>';
			do_action( 'e_rn_settings_after_form_div' );
			echo '</div>';
			do_action( 'e_rn_settings_bottom' );
		}
		/**
		 * the tabs that will appear in the settings page
		 * filtered on class construct
		 * @var array
		 */
		public $tabs;
		/**
		 * generate the tabs in the settings screen
		 *
		 * @return void
		 */
		public function generateTabs() {
			if ( is_array( $this->tabs ) && ! empty( $this->tabs ) ) {
				echo '<h2 id="e-rn-settings-tabs" class="nav-tab-wrapper">';
				foreach ( $this->tabs as $tab => $name ) {
					if ( ! is_network_admin() ) {
						$url = '?page=' . $this->adminSlug . '&tab=' . urlencode( $tab );
						$url = apply_filters( 'e_rn_settings_url', $url );
					}
					if ( is_network_admin() ) {
						$url = '?page=' . $this->adminSlug . '&tab=' . urlencode( $tab );
					}
					$class = ( $tab == $this->currentTab ) ? ' nav-tab-active' : '';
					echo '<a id="' . esc_attr( $tab ) . '" class="nav-tab' . esc_attr( $class ) . '" href="' . esc_url( $url ) . '">' . esc_html( $name ) . '</a>';
				}
				do_action( 'e_rn_settings_after_tabs' );
				echo '</h2>';
			}
		}
		/**
		 * validate the settings
		 *
		 * @return void
		 */
		public function validate() {

			do_action( 'e_rn_settings_validate_before_checks' );

			// check that the right POST && variables are set
			if ( isset( $_POST['tribeSaveSettings'] ) && isset( $_POST['current-settings-tab'] ) ) {
				// check permissions
				if ( ! current_user_can( 'manage_options' ) ) {
					$this->errors[]    = esc_html__( "You don't have permission to do that.", 'e_rn_common' );
					$this->major_error = true;
				}

				// check the nonce
				if ( ! wp_verify_nonce( $_POST['e-rn-save-settings'], 'saving' ) ) {
					$this->errors[]    = esc_html__( 'The request was sent insecurely.', 'e_rn_common' );
					$this->major_error = true;
				}

				// check that the request originated from the current tab
				if ( $_POST['current-settings-tab'] != $this->currentTab ) {
					$this->errors[]    = esc_html__( "The request wasn't sent from this tab.", 'e_rn_common' );
					$this->major_error = true;
				}

				// bail if we have errors
				if ( count( $this->errors ) ) {
					remove_action( 'shutdown', array( $this, 'deleteOptions' ) );
					add_option( 'e_rn_settings_errors', $this->errors );
					add_option( 'e_rn_settings_major_error', $this->major_error );
					wp_redirect( $this->url );
					exit;
				}

				// some hooks
				do_action( 'e_rn_settings_validate' );
				do_action( 'e_rn_settings_validate_tab_' . $this->currentTab );

				// set the current tab and current fields
				$tab    = $this->currentTab;
				$fields = $this->fields_for_save[ $tab ];

				if ( is_array( $fields ) ) {
					// loop through the fields and validate them
					foreach ( $fields as $field_id => $field ) {
						// get the value
						$value = ( isset( $_POST[ $field_id ] ) ) ? $_POST[ $field_id ] : null;
						$value = apply_filters( 'e_rn_settings_validate_field_value', $value, $field_id, $field );

						// make sure it has validation set up for it, else do nothing
						if ( ( ! isset( $field['conditional'] ) || $field['conditional'] ) && ( ! empty( $field['validation_type'] ) || ! empty( $field['validation_callback'] ) ) ) {
							// some hooks
							do_action( 'e_rn_settings_validate_field', $field_id, $value, $field );
							do_action( 'e_rn_settings_validate_field_' . $field_id, $value, $field );

							// validate
							$validate = new E_Register_Now__Validate( $field_id, $field, $value );

							if ( isset( $validate->result->error ) ) {
								// uh oh; validation failed
								$this->errors[ $field_id ] = $validate->result->error;
							} elseif ( $validate->result->valid ) {
								// validation passed
								$this->validated[ $field_id ]        = new stdClass;
								$this->validated[ $field_id ]->field = $validate->field;
								$this->validated[ $field_id ]->value = $validate->value;
							}
						}
					}

					// run the saving method
					$this->save();
				}
			}

		}
		
		/**
		 * save the settings
		 *
		 * @return void
		 */
		public function save() {

			// some hooks
			do_action( 'e_rn_settings_save' );
			do_action( 'e_rn_settings_save_tab_' . $this->currentTab );

			// we'll need this later
			$parent_options = array();

			/**
			 * loop through each validated option and either
			 * save it as is or figure out its parent option ID
			 * (in that case, it's a serialized option array and
			 * will be saved in the next loop)
			 */
			if ( ! empty( $this->validated ) ) {
				foreach ( $this->validated as $field_id => $validated_field ) {
					// get the value and filter it
					$value = $validated_field->value;
					$value = apply_filters( 'e_rn_settings_save_field_value', $value, $field_id, $validated_field );

					// figure out the parent option [could be set to false] and filter it
					if ( is_network_admin() ) {
						$parent_option = ( isset( $validated_field->field['parent_option'] ) ) ? $validated_field->field['parent_option'] : E_Register_Now__Main::OPTIONNAMENETWORK;
					}
					if ( ! is_network_admin() ) {
						$parent_option = ( isset( $validated_field->field['parent_option'] ) ) ? $validated_field->field['parent_option'] : E_Register_Now__Main::OPTIONNAME;
					}

					$parent_option  = apply_filters( 'e_rn_settings_save_field_parent_option', $parent_option, $field_id );
					$network_option = isset( $validated_field->field['network_option'] ) ? (bool) $validated_field->field['network_option'] : false;

					// some hooks
					do_action( 'e_rn_settings_save_field', $field_id, $value, $validated_field );
					do_action( 'e_rn_settings_save_field_' . $field_id, $value, $validated_field );

					if ( ! $parent_option ) {
						if ( $network_option || is_network_admin() ) {
							update_site_option( $field_id, $value );
						} else {
							update_option( $field_id, $value );
						}
					} else {
						// set the parent option
						$parent_options[ $parent_option ][ $field_id ] = $value;
					}
				}
			}

			/**
			 * loop through parent option arrays
			 * and save them
			 * NOTE: in the case of the main option Tribe Options,
			 * this will save using the E_Register_Now__Settings_Manager::set_options method.
			 */
			foreach ( $parent_options as $option_id => $new_options ) {
				// get the old options
				if ( $option_id == E_Register_Now__Main::OPTIONNAME ) {
					$old_options = (array) get_option( $option_id );
				} else {
					$old_options = (array) get_site_option( $option_id );
				}

				// set the options by parsing old + new and filter that
				$options = apply_filters( 'e_rn_settings_save_option_array', wp_parse_args( $new_options, $old_options ), $option_id );

				if ( $option_id == E_Register_Now__Main::OPTIONNAME ) {
					// save using the E_Register_Now__Settings_Manager method
					E_Register_Now__Settings_Manager::set_options( $options );
				} elseif ( $option_id == E_Register_Now__Main::OPTIONNAMENETWORK ) {
					E_Register_Now__Settings_Manager::set_network_options( $options );
				} else {
					// save using regular WP method
					if ( is_network_admin() ) {
						update_site_option( $option_id, $options );
					} else {
						update_option( $option_id, $options );
					}
				}
			}

			do_action( 'e_rn_settings_after_save' );
			do_action( 'e_rn_settings_after_save_' . $this->currentTab );
			remove_action( 'shutdown', array( $this, 'deleteOptions' ) );
			add_option( 'e_rn_settings_sent_data', $_POST );
			add_option( 'e_rn_settings_errors', $this->errors );
			add_option( 'e_rn_settings_major_error', $this->major_error );
			wp_redirect( esc_url_raw( add_query_arg( array( 'saved' => true ), $this->url ) ) );
			exit;
		}
		
		public function initTabs() {
			if ( isset( $_GET['page'] ) && $_GET['page'] == $this->adminSlug ) {
				// Load settings tab-specific helpers and enhancements
				$this->live_date_preview = new E_Register_Now__Admin__Live_Date_Preview;

				do_action( 'e_rn_settings_do_tabs' ); // this is the hook to use to add new tabs
				$this->tabs       = (array) apply_filters( 'e_rn_settings_tabs', array() );
				$this->allTabs    = (array) apply_filters( 'e_rn_settings_all_tabs', array() );
				$this->noSaveTabs = (array) apply_filters( 'e_rn_settings_no_save_tabs', array() );
				if ( is_network_admin() ) {
					$this->defaultTab = apply_filters( 'e_rn_settings_default_tab_network', 'network' );
					$this->currentTab = apply_filters( 'e_rn_settings_current_tab', ( isset( $_GET['tab'] ) && $_GET['tab'] ) ? esc_attr( $_GET['tab'] ) : $this->defaultTab );
					$this->url        = apply_filters(
						'e_rn_settings_url', add_query_arg(
							array(
								'page' => $this->adminSlug,
								'tab'  => $this->currentTab,
							), network_admin_url( 'settings.php' )
						)
					);
				}
				if ( ! is_network_admin() ) {
					$tabs_keys        = array_keys( $this->tabs );
					$this->defaultTab = in_array( apply_filters( 'e_rn_settings_default_tab', 'general' ), $tabs_keys ) ? apply_filters( 'e_rn_settings_default_tab', 'general' ) : $tabs_keys[0];
					$this->currentTab = apply_filters( 'e_rn_settings_current_tab', ( isset( $_GET['tab'] ) && $_GET['tab'] ) ? esc_attr( $_GET['tab'] ) : $this->defaultTab );
					$this->url        = apply_filters(
						'e_rn_settings_url', add_query_arg(
							array(
								'page' => $this->adminSlug,
								'tab'  => $this->currentTab,
							),
							admin_url( self::$parent_page )
						)
					);
				}
				$this->fields_for_save = (array) apply_filters( 'e_rn_settings_fields', array() );
				do_action( 'e_rn_settings_after_do_tabs' );
				$this->fields = (array) apply_filters( 'e_rn_settings_fields', array() );
				$this->validate();
			}
		}

		/**
		 * display errors, if any, after saving
		 *
		 * @return void
		 */
		public function displayErrors() {

			// fetch the errors and filter them
			$errors = (array) apply_filters( 'e_rn_settings_display_errors', $this->errors );
			$count  = apply_filters( 'e_rn_settings_count_errors', count( $errors ) );

			if ( apply_filters( 'e_rn_settings_display_errors_or_not', ( $count > 0 ) ) ) {
				// output a message if we have errors

				$output = '<div id="message" class="error"><p><strong>';
				$output .= esc_html__( 'Your form had the following errors:', 'e_rn_common' );
				$output .= '</strong></p><ul class="e-rn-errors-list">';

				// loop through each error
				foreach ( $errors as $error ) {
					$output .= '<li>' . (string) $error . '</li>';
				}

				if ( count( $errors ) ) {
					$message = ( isset( $this->major_error ) && $this->major_error )
						? esc_html__( 'None of your settings were saved. Please try again.' )
						: esc_html( _n( 'The above setting was not saved. Other settings were successfully saved.', 'The above settings were not saved. Other settings were successfully saved.', $count, 'e_rn_common' ) );
				}

				$output .= '</ul><p>' . $message . '</p></div>';

				// final output, filtered of course
				echo apply_filters( 'e_rn_settings_error_message', $output );
			}
		}

		/**
		 * display success message after saving
		 *
		 * @return void
		 */
		public function displaySuccess() {
			$errors = (array) apply_filters( 'e_rn_settings_display_errors', $this->errors );
			$count  = apply_filters( 'e_rn_settings_count_errors', count( $errors ) );

			// are we coming from the saving place?
			if ( isset( $_GET['saved'] ) && ! apply_filters( 'e_rn_settings_display_errors_or_not', ( $count > 0 ) ) ) {
				// output the filtered message
				$message = esc_html__( 'Settings saved.', 'e_rn_common' );
				$output  = '<div id="message" class="updated"><p><strong>' . $message . '</strong></p></div>';
				echo apply_filters( 'e_rn_settings_success_message', $output, $this->currentTab );
			}

			//Delete Temporary Options After Display Errors and Success
			$this->deleteOptions();
		}

		/**
		 * delete temporary options
		 *
		 * @return void
		 */
		public function deleteOptions() {
			delete_option( 'e_rn_settings_errors' );
			delete_option( 'e_rn_settings_major_error' );
			delete_option( 'e_rn_settings_sent_data' );
		}
		
		/**
		 * Adds a link to the shop app to the WP admin bar
		 */
		public function add_toolbar_item() {

			if ( ! E_Register_Now__Settings::instance()->wp_allow_config() ) {
				return;
			}
			
			global $wp_admin_bar;
			
			$wp_admin_bar->add_menu( array(
				'id'     => 'e-rn-events-configuration',
				'title'  => esc_html__( 'Configuration', 'e_rn_common' ),
				'href'   => E_Register_Now__Settings::instance()->get_url( array( 'page' => self::MENU_SLUG ) ),
				'parent' => 'e-rn-events-settings-group',
			) );
		
		}

		/**
		 * Enqueue the styles and script
		 */
		public function enqueue() {
			wp_enqueue_style( 'configuration', e_rn_resource_url( 'app-shop.css', false, 'common' ), array(), apply_filters( 'e_rn_events_css_version', E_Register_Now__Main::VERSION ) );
			wp_enqueue_script( 'configuration', e_rn_resource_url( 'app-shop.js', false, 'common' ), array(), apply_filters( 'e_rn_events_js_version', E_Register_Now__Main::VERSION ) );
		}

		/**
		 * Renders the Shop App page
		 */
		public function do_menu_page() {
			
			
			// include_once E_Register_Now__Main::instance()->plugin_path . 'src/admin-views/configuration.php';
		}

		
		/**
		 * Static Singleton Factory Method
		 *
		 * @return E_Register_Now__Configuration
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) ) {
				$className      = __CLASS__;
				self::$instance = new $className;
			}

			return self::$instance;
		}
	}
}
