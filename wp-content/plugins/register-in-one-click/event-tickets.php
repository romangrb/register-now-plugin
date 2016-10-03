<?php
/*
Plugin Name: Register In One Click
Description: This is a wordpress plugin for online registeration service
Version: 1.0
Author: ---
Author URI: http://...
Text Domain: Register In One Click
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

define( 'EVENT_TICKETS_DIR', dirname( __FILE__ ) );

// the main plugin class
require_once EVENT_TICKETS_DIR . '/src/register-in-one-click/Main.php';

E_Register_Now__Tickets__Main::instance();
