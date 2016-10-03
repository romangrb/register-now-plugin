<?php
$common = dirname( __FILE__ ) . '/src';

require_once $common . '/e_register_now/Autoloader.php';

$autoloader = E_Register_Now__Autoloader::instance();
$autoloader->register_prefix( 'E_Register_Now__', $common . '/e_register_now' );
$autoloader->register_autoloader();
