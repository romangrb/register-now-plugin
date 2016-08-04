<?php

/*
Plugin Name: e-Register-Now plugin
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


// the main plugin class

/**
* created by romangrb on 15.07.2016
* get the base class of EventCalendar
*/

if ( ! class_exists( 'Tribe__Events__Main' ) ) {
    echo "<h1>class Tribe__Events__Main isn\'t locaded</h1>"; 
}

/**
 * Class Definition
 */
 
class MyChildClass extends Tribe__Events__Main{
    // class definition
    function __construct() {
       parent::__construct();
    }
    
    function print_hello() { 
      echo "<h1>Hello</h1>";
     
    }
    
}

//$cl = new MyChildClass;
//echo phpinfo();
//$cl->print_hello();
// all avaible methods
//var_dump(get_class_methods($cl));
// all avaible attributes
//var_dump(get_class_vars($cl));

// $a = method_exists (  Tribe__Events__Main , "instance" ) ?  "yes" :  "no";
// var_dump($a);
// class Cat {
//   function parent_funct() { echo "<h1>Это родительская функция</h1>"; }
//   function test () { echo "<h1>Это родительский класс</h1>"; }
// }

// class Tiger extends Cat {
//   function child_funct() { echo "<h2>Это дочерняя функция</h2>"; }
//   function test () { echo "<h2>Это дочерний класс</h2>"; }
// }

// $cat = new Cat;
// $tiger = new Tiger;



?>