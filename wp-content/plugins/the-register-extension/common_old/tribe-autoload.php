<?php
$common = dirname( __FILE__ ) . '/src';

require_once $common . '/Tribe/Autoloader.php';

$autoloader = Tribe__Autoloader::instance();
$autoloader->register_prefix( 'Tribe__', $common . '/Tribe' );
var_dump($autoloader);
$autoloader->register_autoloader();
