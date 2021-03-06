<tr class="<?php $this->tr_class(); ?>">
	<td><label for="ticket_rsvp_stock"><?php esc_html_e( 'Stock:', 'event-tickets' ); ?></label></td>
	<td>
		<input type='text' id='ticket_rsvp_stock' name='ticket_rsvp_stock' class="ticket_field" size='7' value='<?php echo esc_attr( $stock ); ?>'/>

		<p class="description"><?php esc_html_e( "(Total available # of this ticket type. Once they're gone, ticket type is sold out.)", 'event-tickets' ); ?></p>
		
		<input type='text' id='' name='' class="" size='7' value='<?php echo esc_attr(var_dump($s)); ?>'/>
	</td>
</tr>

<?php
if ( class_exists( 'E_Register_Now__Events__Pro__Main' ) ) {
	?>
	<tr class="<?php $this->tr_class(); ?>">
		<td colspan="2" class="e_rn_sectionheader updated">
			<p>
				<?php esc_html_e( 'Selling tickets for recurring events', 'event-tickets' ); ?>
				<span id="selling-tickets-info" class="target dashicons dashicons-editor-help bumpdown-trigger"></span>
			</p>
			<div class="bumpdown" data-trigger="selling-tickets-info">
				<?php
					if ( is_admin() ) {
						esc_html_e( 'Currently, Tickets will only show up on the frontend once per full event. For PRO users this means the same ticket will appear across all events in the series. Please configure your events accordingly.', 'event-tickets' );
					} else {
						esc_html_e( 'If you are creating a recurring event, Tickets will only show up once per Event Series, meaning that the same ticket will apper across all events. Please configure your events accordingly.', 'event-tickets' );
					}
				?>
			</div>
		</td>
	</tr>
	<?php
}
