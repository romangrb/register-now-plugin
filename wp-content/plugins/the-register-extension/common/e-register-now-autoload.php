<?php
$common = dirname( __FILE__ ) . '/src';

require_once $common . '/e_register_now/Autoloader.php';

$autoloader = E__Register__Now__Autoloader::instance();
$autoloader->register_prefix( 'E__Register__Now__', $common . '/e_register_now' );
$autoloader->register_autoloader();
