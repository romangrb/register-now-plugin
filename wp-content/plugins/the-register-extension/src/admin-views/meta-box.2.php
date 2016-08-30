<?php
/**
 * @var WP_Post $post
 * @var bool $show_global_stock
 * @var E__Register__Now__Tickets__Global_Stock $global_stock
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

$modules = E__Register__Now__Tickets__Tickets::modules();
?>

<table id="e__register__now" class="eventtable">
	<?php
	wp_nonce_field( 'ern-tickets-meta-box', 'ern-tickets-post-settings' );

	if ( get_post_meta( get_the_ID(), '_EventOrigin', true ) === 'community-events' ) {
		?>
		<tr>
			<td colspan="2" class="tribe_sectionheader updated">
				<p class="error-message"><?php esc_html_e( 'This event was created using Community Events. Are you sure you want to sell tickets for it?', 'event-tickets' ); ?></p>
			</td>
		</tr>
	<?php
	}
	?>
	

	<?php
	/**
	 * Fired to allow for the insertion of additional content into the ticket admin form before the tickets listing
	 *
	 * @param Post ID
	 */
	do_action( 'tribe_events_tickets_metabox_pre', get_the_ID() ); ?>

	<tr>
		<td colspan="2" class="tribe_sectionheader ticket_list_container">

			<?php $this->ticket_list_markup( $tickets ); ?>

		</td>
	</tr>
	<tr>
		<td colspan="2" class="tribe_sectionheader">
			<a href="#" class="button-secondary"
			   id="ticket_form_toggle"><?php esc_html_e( 'Add new ticket', 'event-tickets' ); ?></a>
		</td>
	</tr>
	<tr id="ticket_form" class="ticket_form">
		<td colspan="2" class="tribe_sectionheader">
			<div id="ern-loading"><span></span></div>
			<table id="ticket_form_table" class="eventtable ticket_form">

				<tr>
					<td colspan="2">
						<h4 class="ticket_form_title_add"><?php esc_html_e( 'Add new ticket', 'event-tickets' ); ?></h4>
						<h4 class="ticket_form_title_edit"><?php esc_html_e( 'Edit ticket', 'event-tickets' ); ?></h4>
					</td>
				</tr>
				<tr class="ticket">
					<td><label for="event_name"><?php esc_html_e( 'Event Name:', 'event-tickets' ); ?></label></td>
					<td>
						<input type='text' id='event_name' name='event_name' class="ticket_field" size='25' value='' />
					</td>
				</tr>
				<tr class="ticket">
					<td><label
							for="ticket_description"><?php esc_html_e( 'Ticket Description:', 'event-tickets' ); ?></label>
					</td>
					<td>
						<textarea rows="5" cols="40" name="ticket_description" class="ticket_field"
								  id="ticket_description"></textarea>
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
							<?php echo tribe_get_datetime_separator(); ?>
							<select name="ticket_start_hour" id="ticket_start_hour" class="ticket_field">
								<?php echo $startHourOptions; ?>
							</select>
							<select name="ticket_start_minute" id="ticket_start_minute" class="ticket_field">
								<?php echo $startMinuteOptions; ?>
							</select>
							<?php if ( ! strstr( get_option( 'time_format', E__Register__Now__Date_Utils::TIMEFORMAT ), 'H' ) ) : ?>
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
							<?php echo tribe_get_datetime_separator(); ?>
							<select name="ticket_end_hour" id="ticket_end_hour" class="ticket_field">
								<?php echo $endHourOptions; ?>
							</select>
							<select name="ticket_end_minute" id="ticket_end_minute" class="ticket_field">
								<?php echo $endMinuteOptions; ?>
							</select>
							<?php if ( ! strstr( get_option( 'time_format', E__Register__Now__Date_Utils::TIMEFORMAT ), 'H' ) ) : ?>
								<select name="ticket_end_meridian" id="ticket_end_meridian" class="ticket_field">
									<?php echo $endMeridianOptions; ?>
								</select>
							<?php endif; ?>
						</span>
						<br />

						<p class="description">
							<?php
							if ( class_exists( 'E__Register__Now__Events__Main' ) && E__Register__Now__Events__Main::POSTTYPE === get_post_type( $post ) ) {
								esc_html_e( "If you don't set a start/end date for sales, tickets will be available from now until the event ends.", 'event-tickets' );
							}
							?>
						</p>
					</td>
				</tr>

				<?php
				/**
				 * Fired to allow for the insertion content into the ticket admin form
				 *
				 * @var Post ID
				 * @var null Ticket ID
				 */
				do_action( 'tribe_events_tickets_metabox_advanced', get_the_ID(), null ); ?>

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
