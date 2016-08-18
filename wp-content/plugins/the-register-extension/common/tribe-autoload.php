<?php
$common = dirname( __FILE__ ) . '/src';

require_once $common . '/e_register_now/Autoloader.php';

$autoloader = Tribe__Autoloader::instance();
$autoloader->register_prefix( 'Tribe__', $common . '/e_register_now' );
$autoloader->register_autoloader();
