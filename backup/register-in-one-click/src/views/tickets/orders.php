<?php
/**
 * Edit Event Tickets
 *
 * Override this template in your own theme by creating a file at [your-theme]/rioc-events/tickets/orders.php
 *
 * @package TribeEventsCalendar
 * @version 4.2
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$view = Register_In_One_Click__Tickets__Tickets_View::instance();
$event_id = get_the_ID();
$event = get_post( $event_id );
$post_type = get_post_type_object( $event->post_type );

$is_event_page = class_exists( 'Register_In_One_Click__Events__Main' ) && Register_In_One_Click__Events__Main::POSTTYPE === $event->post_type ? true : false;
?>

<div id="rioc-events-content" class="rioc-events-single">
	<p class="rioc-back">
		<a href="<?php echo esc_url( get_permalink( $event_id ) ); ?>">
			<?php printf( '&laquo; ' . esc_html__( 'View %s', 'event-tickets' ), $post_type->labels->singular_name ); ?>
		</a>
	</p>

	<?php if ( $is_event_page ): ?>
	<?php the_title( '<h1 class="rioc-events-single-event-title">', '</h1>' ); ?>

	<div class="rioc-events-schedule rioc-clearfix">
		<?php echo rioc_events_event_schedule_details( $event_id, '<h2>', '</h2>' ); ?>
		<?php if ( rioc_get_cost() ) : ?>
			<span class="rioc-events-cost"><?php echo rioc_get_cost( null, true ) ?></span>
		<?php endif; ?>
	</div>
	<?php endif; ?>

	<!-- Notices -->
	<?php rioc_the_notices() ?>

	<form method="post">

	<?php rioc_tickets_get_template_part( 'tickets/orders-rsvp' ); ?>

	<?php
	/**
	 * Fires before the process tickets submission button is rendered
	 */
	do_action( 'rioc_tickets_orders_before_submit' );
	?>

	<?php if ( $view->has_rsvp_attendees( $event_id ) || $view->has_ticket_attendees( $event_id ) ): ?>
		<div class="rioc-submit-tickets-form">
			<button type="submit" name="process-tickets" value="1" class="button alt"><?php echo sprintf( esc_html__( 'Update %s', 'event-tickets' ), $view->get_description_rsvp_ticket( $event_id, get_current_user_id(), true ) ); ?></button>
		</div>
	<?php endif; ?>

	</form>

</div><!-- #rioc-events-content -->