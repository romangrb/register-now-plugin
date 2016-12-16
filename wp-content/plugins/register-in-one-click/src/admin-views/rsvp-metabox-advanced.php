<tr class="<?php $this->tr_class(); ?>">
	<td><label for="ticket_rsvp_stock"><?php esc_html_e( 'Stock:', 'event-tickets' ); ?></label></td>
	<td>
		<input type='text' id='ticket_rsvp_stock' name='ticket_rsvp_stock' class="ticket_field" size='7' value='<?php echo esc_attr( $stock ); ?>'/>

		<p class="description"><?php esc_html_e( "(Total available # of this ticket type. Once they're gone, ticket type is sold out.)", 'event-tickets' ); ?></p>
		
	</td>
</tr>