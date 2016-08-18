<?php
/*
Plugin Name: e-Register-Now
Description: This is wordpress plugin for e-Register-now service
Version: 1.0
Author: Roman Hrabar
Author URI: http://...
Text Domain: the-e-Register-Now-events-calendar
License: GPLv2 or later
*/

/*
Copyright 2016-2022 by Roman Hrabar Inc and the contributors

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

define( 'E__REGISTER__NOW_DIR', dirname( __FILE__ ) );

// the main plugin class
require_once E__REGISTER__NOW_DIR . '/src/e_register_now/Main.php';

E__Register__Now__Tickets__Main::instance();
