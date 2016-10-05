<div id="rioc-app-shop" class="wrap">

	<div class="header">
		<h1><?php esc_html_e( 'Tribe Event Add-Ons', 'rioc-common' ); ?></h1>
		<a href="https://theeventscalendar.com/?utm_campaign=in-app&utm_source=addonspage&utm_medium=top-banner" target="_blank"><img src="<?php echo esc_url( rioc_resource_url( 'images/app-shop-banner.jpg', false, 'common' ) ); ?>" /></a>
	</div>

	<div class="content-wrapper">
		<div class="addon-grid">
			<?php
			$i = 0;
			foreach ( $products as $product ) {
				?>
				<div class="rioc-addon<?php echo ( $i % 4 == 0 ) ? ' first rioc-clearfix' : '';?>">
					<div class="thumb">
						<a href="<?php echo esc_url( $product->link ); ?>"><img src="<?php echo esc_url( rioc_resource_url( $product->image, false, 'common' ) ); ?>" /></a>
					</div>
					<div class="caption">
						<h4><a href="<?php echo esc_url( $product->link ); ?>"><?php echo esc_html( $product->title ); ?></a></h4>

						<div class="description">
							<p><?php echo $product->description; ?></p>
							<?php
							if ( isset( $product->requires ) ) {
								?>
								<p><strong><?php esc_html_e( 'Requires:', 'rioc-common' );?></strong> <?php echo esc_html( $product->requires );?></p>
								<?php
							}
							?>
						</div>

						<a class="button button-primary" href="<?php echo esc_url( $product->link ); ?>">Get This Add-on</a>
					</div>
				</div>

				<?php
				$i++;
			}
			?>
		</div>
	</div>
</div>
