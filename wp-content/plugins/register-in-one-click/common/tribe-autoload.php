<?php
$common = dirname( __FILE__ ) . '/src';

require_once $common . '/e_register_now/Autoloader.php';

$autoloader = Register_In_One_Click__Autoloader::instance();
$autoloader->register_prefix( 'Register_In_One_Click__', $common . '/e_register_now' );
$autoloader->register_autoloader();
