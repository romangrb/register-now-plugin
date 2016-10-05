<?php
// Fetch the Help page Instance
$help = Register_In_One_Click__Admin__Help_Page::instance();

// Fetch plugins
$plugins = $help->get_plugins( null, false );

// Creates the Feature Box section
$help->add_section( 'feature-box', null, 0, 'box' );
$help->add_section_content( 'feature-box', '<img src="' . esc_url( plugins_url( 'resources/images/rioc-@2x.png', dirname( __FILE__ ) ) ) . '" alt="Modern Tribe Inc." title="Modern Tribe Inc.">' );
$help->add_section_content( 'feature-box', sprintf( esc_html__( 'Thanks you for using %s! All of us at Modern Tribe sincerely appreciate your support and we’re excited to see you using our plugins.', 'rioc-common' ), $help->get_plugins_text() ) );

// Creates the Support section
$help->add_section( 'support', __( 'Getting Support', 'rioc-common' ), 10 );
$help->add_section_content( 'support', sprintf( __( 'Our website’s %s is a great place to find tips and tricks for using and customizing our plugins.', 'rioc-common' ), '<a href="http://m.tri.be/18j9" target="_blank">' . __( 'Knowledgebase', 'rioc-common' ) . '</a>' ), 0 );
$help->add_section_content( 'support', sprintf( __( '<strong>Want to dive deeper?</strong> Check out our %s for developers.', 'rioc-common' ), '<a href="http://m.tri.be/18jf" target="_blank">' . __( 'list of available functions', 'rioc-common' ) . '</a>' ), 50 );

// Creates the Extra Help section
$help->add_section( 'extra-help', __( 'Getting More Help', 'rioc-common' ), 20 );
$help->add_section_content( 'extra-help', __( 'While the resources above help solve a majority of the issues we see, there are times you might be looking for extra support. If you need assistance using our plugins and would like us to take a look, please follow these steps:', 'rioc-common' ), 0 );
$help->add_section_content( 'extra-help', array(
	'type' => 'ol',

	sprintf( __( '%s. All of the common (and not-so-common) answers to questions we see are here. It’s often the fastest path to finding an answer!', 'rioc-common' ), '<strong><a href="http://m.tri.be/18j9" target="_blank">' . __( 'Check our Knowledgebase', 'rioc-common' ) . '</a></strong>' ),
	sprintf( __( '%s. Testing for an existing conflict is the best start for in-depth troubleshooting. We will often ask you to follow these steps when opening a new thread, so doing this ahead of time will be super helpful.', 'rioc-common' ), '<strong><a href="http://m.tri.be/18jh" target="_blank">' . __( 'Test for a theme or plugin conflict', 'rioc-common' ) . '</a></strong>' ),
	sprintf( __( '%s. There are very few issues we haven’t seen and it’s likely another user has already asked your question and gotten an answer from our support staff. While posting to the forums is open only to paid customers, they are open for anyone to search and review.', 'rioc-common' ), '<strong><a href="http://m.tri.be/4w/" target="_blank">' . __( 'Search our support forum', 'rioc-common' ) . '</a></strong>' ),
), 10 );

// By default these three will be gathered
$help->add_section_content( 'extra-help', __( 'Please note that all hands-on support is provided via the forums. You can email or tweet at us… ​but we will probably point you back to the forums 😄', 'rioc-common' ), 40 );
$help->add_section_content( 'extra-help', '<div style="text-align: right;"><a href="http://m.tri.be/18ji" target="_blank" class="button">' . __( 'Read more about our support policy', 'rioc-common' ) . '</a></div>', 40 );

// Creates the System Info section
$help->add_section( 'system-info', __( 'System Information', 'rioc-common' ), 30 );
$help->add_section_content( 'system-info', __( 'The details of your calendar plugin and settings is often needed for you or our staff to help troubleshoot an issue. We may ask you to share this information if you ask for support. If you post in one of our premium forums, please copy and paste this information into the System Information field and it will help us help you faster!', 'rioc-common' ), 0 );

$help->add_section( 'template-changes', __( 'Recent Template Changes', 'rioc-common' ), 40 );
$help->add_section_content( 'template-changes', Register_In_One_Click__Support__Template_Checker_Report::generate() );
?>

<div id="rioc-help-general">
	<?php $help->get_sections(); ?>
</div>


<div id="rioc-help-sidebar">
	<?php
	/**
	 * Fires at the top of the sidebar on Settings > Help tab
	 */
	do_action( 'rioc_help_sidebar_before' );

	foreach ( $plugins as $key => $plugin ) {
		$help->print_plugin_box( $key );
	}
	?>
	<h3><?php esc_html_e( 'News and Tutorials', 'rioc-common' ); ?></h3>
	<ul>
		<?php
		foreach ( $help->get_feed_items() as $item ) {
			echo '<li><a href="' . $help->get_ga_link( $item['link'], false ) . '">' . $item['title'] . '</a></li>';
		}
		echo '<li><a href="' . $help->get_ga_link( 'category/products' ) . '">' . esc_html__( 'More...', 'rioc-common' ) . '</a></li>';
		?>
	</ul>

	<?php
	/**
	 * Fires at the bottom of the sidebar on the Settings > Help tab
	 */
	do_action( 'rioc_help_sidebar_after' ); ?>

</div>