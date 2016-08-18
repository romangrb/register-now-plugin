<?php
$common = dirname( __FILE__ ) . '/src';

require_once $common . '/register__now/autoloader.php';

$autoloader = Register__Now__Autoloader::instance();
//$autoloader->register_prefix( 'Tribe__Admin__', $this_dir . '/src/Tribe/admin' );
$autoloader->register_prefix( 'register__', $common . '/register_now' );

$autoloader->register_autoloader();
