<?php
/**
 * @var WP_Post $post
 * @var bool $show_global_stock
 * @var Tribe__Tickets__Global_Stock $global_stock
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

$modules = Tribe__Tickets__Tickets::modules();
?>

<table id="event_tickets" class="eventtable">

	<?php if ( $show_global_stock ): ?>
		<tr id="tribe-global-stock-settings" class="event-wide-settings">
			<td colspan="2">
				<table class="eventtable ticket_list eventForm">
					<tr>
						<td>
							<label for="tribe-tickets-enable-global-stock">
								<?php esc_html_e( 'Enable global stock', 'event-tickets' ); ?>
							</label>
						</td>
						<td>
							<input type="checkbox" name="tribe-tickets-enable-global-stock" id="tribe-tickets-enable-global-stock" value="1" <?php checked( $global_stock->is_enabled() ); ?> />
						</td>
					</tr>
					<tr id="tribe-tickets-global-stock-level">
						<td>
							<label for="tribe-tickets-global-stock">
								<?php esc_html_e( 'Global stock level', 'event-tickets' ); ?>
							</label>
						</td>
						<td>
							<input type="number" name="tribe-tickets-global-stock" id="tribe-tickets-global-stock" value="<?php echo esc_attr( $global_stock->get_stock_level() ); ?>" />
							<span class="tribe-tickets-global-sales">
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
	do_action( 'tribe_events_tickets_metabox_pre', get_the_ID() ); ?>

	<tr>
		<td colspan="2" class="tribe_sectionheader ticket_list_container">

			<?php $this->ticket_list_markup( $tickets ); ?>

		</td>
	</tr>
	<tr>
		<td colspan="2" class="tribe_sectionheader">
			<a href="#" class="button-secondary"
			   id="ticket_form_toggle"><?php esc_html_e( 'Add new event', 'event-tickets' ); ?></a>
		</td>
	</tr>
	<tr id="ticket_form" class="ticket_form">
		<td colspan="2" class="tribe_sectionheader">
			<div id="tribe-loading"><span></span></div>
			<table id="ticket_form_table" class="eventtable ticket_form">

				<tr>
					<td colspan="2">
						<h4 class="ticket_form_title_add"><?php esc_html_e( 'Add new ticket', 'event-tickets' ); ?></h4>
						<h4 class="ticket_form_title_edit"><?php esc_html_e( 'Edit ticket', 'event-tickets' ); ?></h4>
					</td>
				</tr>

				<tr class="ticket">
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
				<tr class="ticket">
					<td><label for="event_name"><?php esc_html_e( 'Event name:', 'event-tickets' ); ?></label></td>
					<td>
						<input type='text' id='event_name' name='event_name' class="ticket_field" size='25' value='' />
					</td>
				</tr>
				<tr class="ticket">
					<td><label
							for="event_description"><?php esc_html_e( 'Event Description:', 'event-tickets' ); ?></label>
					</td>
					<td>
						<textarea rows="5" cols="40" name="event_description" class="ticket_field"
								  id="event_description"></textarea>
					</td>
				</tr>
				<tr class="ticket">
					<td><label
							for="event_start_date"><?php esc_html_e( 'Start sale:', 'event-tickets' ); ?></label>
					</td>
					<td>
						<input autocomplete="off" type="text" class="ticket_field" size='10' name="event_start_date"
							   id="ticket_start_date" value="">
						<span class="ticket_start_time ticket_time">
							<?php echo tribe_get_datetime_separator(); ?>
							<select name="event_start_hour" id="ticket_start_hour" class="ticket_field">
								<?php echo $startHourOptions; ?>
							</select>
							<select name="event_start_minute" id="ticket_start_minute" class="ticket_field">
								<?php echo $startMinuteOptions; ?>
							</select>
							<?php if ( ! strstr( get_option( 'time_format', Tribe__Date_Utils::TIMEFORMAT ), 'H' ) ) : ?>
								<select name="event_start_meridian" id="ticket_start_meridian" class="ticket_field">
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
							<select name="event_end_hour" id="ticket_end_hour" class="ticket_field">
								<?php echo $endHourOptions; ?>
							</select>
							<select name="event_end_minute" id="ticket_end_minute" class="ticket_field">
								<?php echo $endMinuteOptions; ?>
							</select>
							<?php if ( ! strstr( get_option( 'time_format', Tribe__Date_Utils::TIMEFORMAT ), 'H' ) ) : ?>
								<select name="event_end_meridian" id="ticket_end_meridian" class="ticket_field">
									<?php echo $endMeridianOptions; ?>
								</select>
							<?php endif; ?>
						</span>
						<br />

						<p class="description">
							<?php esc_html_e( 'When will ticket sales occur?', 'event-tickets' ); ?>
							<?php
							// Why break in and out of PHP? because I want the space between the phrases without including them in the translations
							if ( class_exists( 'Tribe__Events__Main' ) && Tribe__Events__Main::POSTTYPE === get_post_type( $post ) ) {
								esc_html_e( "If you don't set a start/end date for sales, tickets will be available from now until the event ends.", 'event-tickets' );
							}
							?>
						</p>
					</td>
				</tr>

				<?php
				/**
				 * Fired to allow for the insertion of additional content into the ticket admin form
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
