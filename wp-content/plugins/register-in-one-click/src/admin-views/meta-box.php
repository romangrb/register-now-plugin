<?php
/**
 * @var WP_Post $post
 * @var bool $show_global_stock
 * @var Register_In_One_Click__Tickets__Global_Stock $global_stock
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$modules = Register_In_One_Click__Tickets__Tickets::modules();

?>

<table id="event_tickets" class="eventtable">
	<?php
	wp_nonce_field( 'rioc-tickets-meta-box', 'rioc-tickets-post-settings' );

	if ( get_post_meta( get_the_ID(), '_EventOrigin', true ) === 'community-events' ) {
		?>
		<tr>
			<td colspan="2" class="rioc_sectionheader updated">
				<p class="error-message"><?php esc_html_e( 'This event was created using Community Events. Are you sure you want to sell tickets for it?', 'rioc-event' ); ?></p>
			</td>
		</tr>
	<?php
	}
	?>
	
	<?php if ( $show_global_stock ): ?>
		<tr id="rioc-global-stock-settings" class="event-wide-settings">
			<td colspan="2">
				<table class="eventtable ticket_list eventForm">
					<tr>
						<td>
							<label for="rioc-tickets-enable-global-stock">
								<?php esc_html_e( 'Enable global stock', 'rioc-event' ); ?>
							</label>
						</td>
						<td>
							<input type="checkbox" name="rioc-tickets-enable-global-stock" id="rioc-tickets-enable-global-stock" value="1" <?php checked( $global_stock->is_enabled() ); ?> />
						</td>
					</tr>
					<tr id="rioc-tickets-global-stock-level">
						<td>
							<label for="rioc-tickets-global-stock">
								<?php esc_html_e( 'Global stock level', 'rioc-event' ); ?>
							</label>
						</td>
						<td>
							<input type="number" name="rioc-tickets-global-stock" id="rioc-tickets-global-stock" value="<?php echo esc_attr( $global_stock->get_stock_level() ); ?>" />
							<span class="rioc-tickets-global-sales">
								<?php echo esc_html( sprintf( _n( '(%s sold)', '(%s sold)', $global_stock->tickets_sold(), 'rioc-event' ), $global_stock->tickets_sold() ) ); ?>
							</span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	<?php endif; ?>

	<?php
	/**
	 * Fired to allow for the insertion of additional content into the ticket admin form before the tickets listing
	 *
	 * @param Post ID
	 */
	do_action( 'rioc_events_tickets_metabox_pre', get_the_ID() ); ?>
	
	<tr>
		<td colspan="2" class="rioc_sectionheader ticket_list_container">
			<?php $this->ticket_list_markup( $tickets ); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="rioc_sectionheader">
			<h4><a href="#" class="button-secondary" id="ticket_form_toggle"><?php esc_html_e( 'Add new Event', 'rioc-event' ); ?></a></h4>
		</td>
	</tr>
	
	<tr id="ticket_form" class="ticket_form">
		<td colspan="2" class="rioc_sectionheader">
			<div id="rioc-loading"><span></span></div>
			<table class="eventtable ticket_form">
				
			<tr>
				<td colspan="2">
					<h4 class="ticket_form_title_edit"><?php esc_html_e( 'Edit ticket', 'event-tickets' ); ?></h4>
				</td>
			</tr>
			
			</table>

				<div>
					<ul class="rioc-tab-buttons" id="rioc-slider-toolbar">
						<li class="laststep"><input id="ticket_form_save" name="ticket_form_save" class="button button-primary" type="button" value="<?php esc_attr_e( 'Save Event', 'rioc-event' ); ?>"></input></li>
						<li class="laststep"><input id="ticket_form_cancel" name="ticket_form_cancel" class="button button-default" type="button" value="<?php esc_attr_e( 'Cancel', 'rioc-event' ); ?>"></input></li>
					</ul>
					<ul class="rioc-tabs" id="rioc-slider-tabs">
						<li class="rioc-tab rioc-tab-selected">
							<div class="rioc-slider-options">
								<div class="rioc-slider-options-menu" id="rioc-slider-options-menu">
									<div class="rioc-slider-options-menu-item rioc-options-menu-item-selected"><?php _e( 'Event Information', 'rioc_slider' ); ?></div>
									<div class="rioc-slider-options-menu-item"><?php _e( 'Registration / Enrolment', 'rioc_slider' ); ?></div>
									<div class="rioc-slider-options-menu-item"><?php _e( 'Event Pricing (Basic)', 'rioc_slider' ); ?></div>
								</div>
								
								<div id="ticket_form_table" class="rioc-slider-options-tabs" id="rioc-slider-options-tabs">
									<div class="rioc-slider-options-tab rioc-slider-options-tab-selected">
										<table class="rioc-form-table-noborder">
											<tr class="ticket" style="display:none">
												<td>
												<?php $checked = true;
												foreach ( $modules as $class => $module ) {
												?>
													<input <?php checked( $checked ); ?> 
													type="radio" name="ticket_provider" id="ticket_provider"
												    value="<?php echo esc_attr( $class ); ?>"
													class="ticket_field">
													<span><?php echo esc_html( $module ); ?></span>
													<?php
													$checked = false;
												}
												?>
												</td>
											</tr>
											<tr class="ticket">
												<td><label for="primary_key"><?php esc_html_e( 'Event Primary Key :', 'rioc-event' ); ?></label></td>
												<td>
													<input type='number' id='primary_key' name='primary_key' class="ticket_field" value="" />
												</td>
											</tr>
											<tr class="ticket">
												<td><label for="event_enabled"><?php esc_html_e( 'Event Enabled ?:', 'rioc-event' ); ?></label></td>
												<td>
													<input type="checkbox" checked name="event_enabled" id="event_enabled" class="ticket_field">
												</td>
											</tr>
											<tr class="ticket">
												<td><label for="ticket_start_date"><?php esc_html_e( 'Event Date / Time Start:', 'rioc-event' ); ?></label></td>
												<td>
													<input autocomplete="off" type="text" class="ticket_field" size='10' name="ticket_start_date" id="ticket_start_date" value="">
													<span class="ticket_start_time ticket_time">
														<?php echo rioc_get_datetime_separator(); ?>
														<select name="ticket_start_hour" id="ticket_start_hour" class="ticket_field">
															<?php echo $startHourOptions; ?>
														</select>
														<select name="ticket_start_minute" id="ticket_start_minute" class="ticket_field">
															<?php echo $startMinuteOptions; ?>
														</select>
														<?php if ( ! strstr( get_option( 'time_format', Register_In_One_Click__Date_Utils::TIMEFORMAT ), 'H' ) ) : ?>
															<select name="ticket_start_meridian" id="ticket_start_meridian" class="ticket_field">
																<?php echo $startMeridianOptions; ?>
															</select>
														<?php endif; ?>
													</span>
												</td>
											</tr>
											<tr class="ticket">
												<td valign="top"><label for="ticket_end_date"><?php esc_html_e( 'Event Date / Time End', 'rioc-event' ); ?></label>
												</td>
												<td valign="top">
													<input autocomplete="off" type="text" class="ticket_field" size='10' name="ticket_end_date" id="ticket_end_date" value="">
							
													<span class="ticket_end_time ticket_time">
														<?php echo rioc_get_datetime_separator(); ?>
														<select name="ticket_end_hour" id="ticket_end_hour" class="ticket_field">
															<?php echo $endHourOptions; ?>
														</select>
														<select name="ticket_end_minute" id="ticket_end_minute" class="ticket_field">
															<?php echo $endMinuteOptions; ?>
														</select>
														<?php if ( ! strstr( get_option( 'time_format', Register_In_One_Click__Date_Utils::TIMEFORMAT ), 'H' ) ) : ?>
															<select name="ticket_end_meridian" id="ticket_end_meridian" class="ticket_field">
																<?php echo $endMeridianOptions; ?>
															</select>
														<?php endif; ?>
													</span>
													<br />
													<p class="description"><?php esc_html_e( 'When will ticket sales occur?', 'rioc-event' ); ?></p>
												</td>
											</tr>
					
											<tr class="ticket">
												<td><label for="ticket_name"><?php esc_html_e( 'Event Name:', 'rioc-event' ); ?></label></td>
												<td>
													<input type='text' id='ticket_name' name='ticket_name' class="ticket_field" value="" />
												</td>
											</tr>
											
											<tr class="ticket">
												<td><label for="ticket_description"><?php esc_html_e( 'Ticket Description:', 'rioc-event' ); ?></label></td>
												<td>
													<textarea rows="5" cols="40" name="ticket_description" class="ticket_field" id="ticket_description"></textarea>
												</td>
											</tr>
											
											<tr class="ticket">
												<td><label for="event_location"><?php esc_html_e( 'Event Location:', 'rioc-event' ); ?></label></td>
												<td>
													<input type='text' id='event_location' name='event_location' class="ticket_field" value="" />
												</td>
											</tr>
											
											<tr class="ticket">
												<td><label for="event_code"><?php esc_html_e( 'Event Code :', 'rioc-event' ); ?></label></td>
												<td>
													<input type='text' id='event_code' name='event_code' class="ticket_field" value="" />
												</td>
											</tr>
											
											<tr class="ticket">
												<td><label for="event_category"><?php esc_html_e( 'Event Category (e.g. sports~camp~youth)						:', 'rioc-event' ); ?></label></td>
												<td>
													<input type='text' id='event_category' name='event_category' class="ticket_field" value="" />
												</td>
											</tr>
											
											<tr class="ticket">
												<td><label for="message1"><?php esc_html_e( 'Message 1:', 'rioc-event' ); ?></label>
												</td>
												<td>
													<textarea rows="5" cols="40" name="message1" value="" class="ticket_field" id="message1"></textarea>
												</td>
											</tr>
											<tr class="ticket">
												<td><label for="message2"><?php esc_html_e( 'Message 2:', 'rioc-event' ); ?></label>
												</td>
												<td>
													<textarea rows="5" cols="40" name="message2" value="" class="ticket_field" id="message2"></textarea>
												</td>
											</tr>
											<tr class="ticket">
												<td><label for="message3"><?php esc_html_e( 'Message 3:', 'rioc-event' ); ?></label></td>
												<td>
													<textarea rows="5" cols="40" name="message3" value="" class="ticket_field" id="message3"></textarea>
												</td>
											</tr>
												
										</table>
									</div>
									<div class="rioc-slider-options-tab">
										<table class="rioc-form-table-noborder">
										<th>Registration Settings (nb: cashiers are exempt from this date range.)</th>
										
										<tr>
											<th>Registration Period</th>
											<td>
												<tr class="ticket">
													<td><label for="reg_period_start_date"><?php esc_html_e( 'Period Start:', 'rioc-event' ); ?></label></td>
													<td>
														<input autocomplete="off" type="text" class="ticket_field" size='10' name="reg_period_start_date" id="reg_period_start_date" value="">
														<span class="reg_period_start_time ticket_time">
															<?php echo rioc_get_datetime_separator(); ?>
															<select name="reg_period_start_hour" id="reg_period_start_hour" class="ticket_field">
																<?php echo $startHourOptions; ?>
															</select>
															<select name="reg_period_start_minute" id="reg_period_start_minute" class="ticket_field">
																<?php echo $startMinuteOptions; ?>
															</select>
															<?php if ( ! strstr( get_option( 'time_format', Register_In_One_Click__Date_Utils::TIMEFORMAT ), 'H' ) ) : ?>
																<select name="reg_period_start_meridian" id="reg_period_start_meridian" class="ticket_field">
																	<?php echo $startMeridianOptions; ?>
																</select>
															<?php endif; ?>
														</span>
													</td>
												</tr>
												<tr class="ticket">
													<td valign="top"><label for="reg_period_end_date"><?php esc_html_e( 'Period End:', 'rioc-event' ); ?></label>
													</td>
													<td valign="top">
														<input autocomplete="off" type="text" class="ticket_field" size='10' name="reg_period_end_date" id="reg_period_end_date" value="">
								
														<span class="reg_period_end_time ticket_time">
															<?php echo rioc_get_datetime_separator(); ?>
															<select name="reg_period_end_hour" id="reg_period_end_hour" class="ticket_field">
																<?php echo $endHourOptions; ?>
															</select>
															<select name="reg_period_end_minute" id="reg_period_end_minute" class="ticket_field">
																<?php echo $endMinuteOptions; ?>
															</select>
															<?php if ( ! strstr( get_option( 'time_format', Register_In_One_Click__Date_Utils::TIMEFORMAT ), 'H' ) ) : ?>
																<select name="reg_period_end_meridian" id="reg_period_end_meridian" class="ticket_field">
																	<?php echo $endMeridianOptions; ?>
																</select>
															<?php endif; ?>
														</span>
														<br />
													</td>
												</tr>
											</td>
										</tr>
									</table>
										
									</div>
									
								</div>
							</div>
							<div style="clear:both;"></div>
						</li>
						<li class="rioc-tab">
							<div id="rioc-slider-preview-tab">
								<div id="rioc-slider-preview-message"></div>
								<div class="riocslider-container" id="rioc-slider-preview-container">
								</div>
							</div>
						</li>
						<li class="rioc-tab">
							<div id="rioc-slider-publish-loading"></div>
							<div id="rioc-slider-publish-information"></div>
						</li>
					</ul>
				</div>

			</table>
		</td>
	</tr>
</table>
