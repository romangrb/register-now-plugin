<?php
$common = dirname( __FILE__ ) . '/src';

require_once $common . '/register__now/autoloader.php';

$autoloader = Register__Now__Autoloader::instance();
$autoloader->register_prefix( 'register__', $common . '/register' );
$autoloader->register_autoloader();
