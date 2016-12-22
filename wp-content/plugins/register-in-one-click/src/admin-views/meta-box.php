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

				<div style="margin:0 12px;">
					<table class="wonderplugin-form-table">
						
					</table>
				</div>
				
				<div style="margin:0 12px;">
					<ul class="wonderplugin-tab-buttons" id="wonderplugin-slider-toolbar">
						<li class="laststep"><input class="button button-primary" type="button" value="<?php _e( 'Save & Publish', 'wonderplugin_slider' ); ?>"></input></li>
					</ul>
					<ul class="wonderplugin-tabs" id="wonderplugin-slider-tabs">
						<li class="wonderplugin-tab wonderplugin-tab-selected">
							<div class="wonderplugin-slider-options">
								<div class="wonderplugin-slider-options-menu" id="wonderplugin-slider-options-menu">
									<div class="rioc-slider-options-menu-item rioc-options-menu-item-selected"><?php _e( 'Slider options', 'wonderplugin_slider' ); ?></div>
									<div class="rioc-slider-options-menu-item"><?php _e( 'Advanced options', 'wonderplugin_slider' ); ?></div>
								</div>
								
								<div class="rioc-slider-options-tabs" id="rioc-slider-options-tabs">
									<div class="rioc-slider-options-tab rioc-slider-options-tab-selected">
										<table class="wonderplugin-form-table-noborder">
											<tr>
												<th>Slideshow</th>
												<td><label><input name='wonderplugin-slider-autoplay' type='checkbox' id='wonderplugin-slider-autoplay' value='' /> Auto play</label>
												<br /><label><input name='wonderplugin-slider-randomplay' type='checkbox' id='wonderplugin-slider-randomplay' value='' /> Random play</label>
												<br /><label><input name='wonderplugin-slider-loadimageondemand' type='checkbox' id='wonderplugin-slider-loadimageondemand' value='' /> Load images on demand</label>
												<br /><label><input name='wonderplugin-slider-transitiononfirstslide' type='checkbox' id='wonderplugin-slider-transitiononfirstslide' value='' /> Apply transition to first slide</label>
												</td>
											</tr>
											<tr>
												<th>Video</th>
												<td><label><input name='wonderplugin-slider-autoplayvideo' type='checkbox' id='wonderplugin-slider-autoplayvideo' value='' /> Auto play video</label>
												</td>
											</tr>
										</table>
									</div>
									<div class="rioc-slider-options-tab">
									<table class="wonderplugin-form-table-noborder">
										<tr>
											<td><p><label><input name='wonderplugin-slider-donotinit' type='checkbox' id='wonderplugin-slider-donotinit'  /> Do not init the slider when the page is loaded. Check this option if you would like to manually init the slider with JavaScript API.</label></p>
											<p><label><input name='wonderplugin-slider-addinitscript' type='checkbox' id='wonderplugin-slider-addinitscript'  /> Add init scripts together with slider HTML code. Check this option if your WordPress site uses Ajax to load pages and posts.</label></p></td>
										</tr>
										<tr>
											<th>Custom CSS</th>
											<td><textarea name='wonderplugin-slider-custom-css' id='wonderplugin-slider-custom-css' value='' class='large-text' rows="10"></textarea></td>
										</tr>
										<tr>
											<th>Advanced Options</th>
											<td><textarea name='wonderplugin-slider-data-options' id='wonderplugin-slider-data-options' value='' class='large-text' rows="10"></textarea></td>
										</tr>
									</table>
								</div>
								</div>
							
							</div>
							<div style="clear:both;"></div>
						</li>
						<li class="wonderplugin-tab">
							<div id="wonderplugin-slider-preview-tab">
								<div id="wonderplugin-slider-preview-message"></div>
								<div class="wonderpluginslider-container" id="wonderplugin-slider-preview-container">
								</div>
							</div>
						</li>
						<li class="wonderplugin-tab">
							<div id="wonderplugin-slider-publish-loading"></div>
							<div id="wonderplugin-slider-publish-information"></div>
						</li>
					</ul>
				</div>
						
	


		


		








	






				
				
			
			</table>
		</td>
	</tr>
</table>
