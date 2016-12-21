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

$header_id  = get_post_meta( get_the_ID(), $this->image_header_field, true );
$header_id  = ! empty( $header_id ) ? $header_id : '';
$header_img = '';
if ( ! empty( $header_id ) ) {
	$header_img = wp_get_attachment_image( $header_id, 'full' );
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
				<p class="error-message"><?php esc_html_e( 'This event was created using Community Events. Are you sure you want to sell tickets for it?', 'event-tickets' ); ?></p>
			</td>
		</tr>
	<?php
	}
	?>
	<tr class="event-wide-settings">
		<td colspan="2" class="rioc_sectionheader updated">
			<table class="eventtable ticket_list eventForm">
				<tr class="rioc-tickets-image-upload">
					<td>
						<?php esc_html_e( 'Upload image for the ticket header.', 'event-tickets' ); ?>
						<p class="description"><?php esc_html_e( 'The maximum image size in the email will be 580px wide by any height, and then scaled for mobile. If you would like "retina" support use an image sized to 1160px wide.', 'event-tickets' ); ?></p>
					</td>
					<td>
						<input type="button" class="button" name="rioc_ticket_header_image" id="rioc_ticket_header_image" value="<?php esc_html_e( 'Select an Image', 'event-tickets' ); ?>" />
					</td>
				</tr>
				<tr class="rioc-tickets-image-preview">
					<td colspan="2">
						<div class="rioc_preview" id="rioc_ticket_header_preview">
							<?php echo $header_img; ?>
						</div>
						<p class="description"><a href="#" id="rioc_ticket_header_remove"><?php esc_html_e( 'Remove', 'event-tickets' ); ?></a></p>

						<input type="hidden" id="rioc_ticket_header_image_id" name="rioc_ticket_header_image_id" value="<?php echo esc_attr( $header_id ); ?>" />
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php if ( $show_global_stock ): ?>
		<tr id="rioc-global-stock-settings" class="event-wide-settings">
			<td colspan="2">
				<table class="eventtable ticket_list eventForm">
					<tr>
						<td>
							<label for="rioc-tickets-enable-global-stock">
								<?php esc_html_e( 'Enable global stock', 'event-tickets' ); ?>
							</label>
						</td>
						<td>
							<input type="checkbox" name="rioc-tickets-enable-global-stock" id="rioc-tickets-enable-global-stock" value="1" <?php checked( $global_stock->is_enabled() ); ?> />
						</td>
					</tr>
					<tr id="rioc-tickets-global-stock-level">
						<td>
							<label for="rioc-tickets-global-stock">
								<?php esc_html_e( 'Global stock level', 'event-tickets' ); ?>
							</label>
						</td>
						<td>
							<input type="number" name="rioc-tickets-global-stock" id="rioc-tickets-global-stock" value="<?php echo esc_attr( $global_stock->get_stock_level() ); ?>" />
							<span class="rioc-tickets-global-sales">
								<?php echo esc_html( sprintf( _n( '(%s sold)', '(%s sold)', $global_stock->tickets_sold(), 'event-tickets' ), $global_stock->tickets_sold() ) ); ?>
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
			<a href="#" class="button-secondary"
			   id="ticket_form_toggle"><?php esc_html_e( 'Add new ticket', 'event-tickets' ); ?></a>
		</td>
	</tr>
	<tr id="ticket_form" class="ticket_form">
		<td colspan="2" class="rioc_sectionheader">
			<div id="rioc-loading"><span></span></div>
			<table id="ticket_form_table" class="eventtable ticket_form">

				<tr>
					<td colspan="2">
						<h4 class="ticket_form_title_add"><?php esc_html_e( 'Add new ticket', 'event-tickets' ); ?></h4>
						<h4 class="ticket_form_title_edit"><?php esc_html_e( 'Edit ticket', 'event-tickets' ); ?></h4>
					</td>
				</tr>

				<tr class="ticket" style="visibility:hidden"
					<td><label for="ticket_provider"><?php esc_html_e( 'Sell using:', 'event-tickets' ); ?></label></td>
					<td>
						<?php
						$checked = true;
						foreach ( $modules as $class => $module ) {
							?>
							<input <?php checked( $checked ); ?> type="radio" name="ticket_provider" id="ticket_provider"
																 value="<?php echo esc_attr( $class ); ?>"
																 class="ticket_field">
							<span><?php echo esc_html( $module ); ?></span>
							<?php
							$checked = false;
						}
						?>
					</td>
				</tr>
				<tr> Event Information </tr>
				<tr class="ticket">
					<td><label for="primary_key"><?php esc_html_e( 'Event Primary Key :', 'event-tickets' ); ?></label></td>
					<td>
						<input type='number' id='primary_key' name='primary_key' class="ticket_field" value="" />
					</td>
				</tr>
				
				<tr class="ticket">
					<td><label for="ticket_name"><?php esc_html_e( 'Event Name:', 'event-tickets' ); ?></label></td>
					<td>
						<input type='text' id='ticket_name' name='ticket_name' class="ticket_field" value="" />
					</td>
				</tr>
				<tr class="ticket">
					<td><label for="event_enabled"><?php esc_html_e( 'Event Enabled ?:', 'event-tickets' ); ?></label></td>
					<td>
						<input type="checkbox" checked name="event_enabled" id="event_enabled" class="ticket_field">
					</td>
				</tr>
				<!--desk for test-->
				<tr class="ticket">
					<td><label
							for="ticket_description"><?php esc_html_e( 'Ticket Description:', 'event-tickets' ); ?></label>
					</td>
					
					<td>
						<textarea rows="5" cols="40" name="ticket_description" class="ticket_field"
								  id="ticket_description">
						</textarea>
					</td>
				</tr>
				<tr class="ticket">
					<td><label
							for="ticket_start_date"><?php esc_html_e( 'Start sale:', 'event-tickets' ); ?></label>
					</td>
					<td>
						<input autocomplete="off" type="text" class="ticket_field" size='10' name="ticket_start_date"
							   id="ticket_start_date" value="">
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
					<td valign="top"><label
							for="ticket_end_date"><?php esc_html_e( 'End sale:', 'event-tickets' ); ?></label>
					</td>
					<td valign="top">
						<input autocomplete="off" type="text" class="ticket_field" size='10' name="ticket_end_date"
							   id="ticket_end_date" value="">

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

						<p class="description">
							<?php esc_html_e( 'When will ticket sales occur?', 'event-tickets' ); ?>
							<?php
							// Why break in and out of PHP? because I want the space between the phrases without including them in the translations
							if ( class_exists( 'Register_In_One_Click__Events__Main' ) && Register_In_One_Click__Events__Main::POSTTYPE === get_post_type( $post ) ) {
								esc_html_e( "If you don't set a start/end date for sales, tickets will be available from now until the event ends.", 'event-tickets' );
							}
							?>
						</p>
					</td>
				</tr>
				
				<tr class="ticket">
					<td><label
							for="message1"><?php esc_html_e( 'Message 1:', 'event-tickets' ); ?></label>
					</td>
					<td>
						<textarea rows="5" cols="40" name="message1" value="" class="ticket_field"
								  id="message1">
						</textarea>
					</td>
				</tr>
				<tr class="ticket">
					<td><label
							for="message2"><?php esc_html_e( 'Message 2:', 'event-tickets' ); ?></label>
					</td>
					<td>
						<textarea rows="5" cols="40" name="message2" value="" class="ticket_field"
								  id="message2">
						</textarea>
					</td>
				</tr>
				<?php
				/**
				 * Fired to allow for the insertion of additional content into the ticket admin form
				 *
				 * @var Post ID
				 * @var null Ticket ID
				 */
				do_action( 'rioc_events_tickets_metabox_advanced', get_the_ID(), null ); ?>

				<tr class="ticket bottom">
					<td></td>
					<td>
						<input type="hidden" name="ticket_id" id="ticket_id" class="ticket_field" value="" />
						<input type="button" id="ticket_form_save" name="ticket_form_save" value="<?php esc_attr_e( 'Save this ticket', 'event-tickets' ); ?>" class="button-primary" />
						<input type="button" id="ticket_form_cancel" name="ticket_form_cancel" value="<?php esc_attr_e( 'Cancel', 'event-tickets' ); ?>" class="button-secondary" />
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
