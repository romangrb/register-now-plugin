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

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

define( 'REGISTER_NOW_DIR', dirname( __FILE__ ) );

// the main plugin class
require_once REGISTER_NOW_DIR . '/src/register_now/main.php';

Register__Now__Main::instance();


/**
* created by romangrb on 15.07.2016
* get the base class of EventCalendar
*/
// require_once dirname(plugin_dir_path( __FILE__ )) . '/the-events-calendar/src/Tribe/Main.php';
// require_once dirname(plugin_dir_path( __FILE__ )) . '/the-events-calendar/src/Tribe/API.php';
// require_once dirname(plugin_dir_path( __FILE__ )) . '/the-events-calendar/src/Tribe/Query.php';
// // require_once dirname(plugin_dir_path( __FILE__ )) . '/the-events-calendar/src/functions/template_tags/general.php';

// if (!class_exists( 'Tribe__Events__Main' ) ) return;

// class Register__Now__Child extends Tribe__Events__Main{
    
//     public static function class_existed($cl){
//         self::io(class_exists( $cl ));
//     }
    
//     public static function gwt_ev(){
//         // Retrieve the next 5 upcoming events
//         $events =  array(
//          'posts_per_page' => 5,
//          'start_date' => date( 'Y-m-d H:i:s' )
//           );
//         $args = array(
//               'post_title'    => 'test',
//               'post_content'  => 'test',
//               'post_status'   => 'publish'
//             );
            
//         $query = Tribe__Events__API::createEvent();
//         self::io($query);
    
//     }
    
//     protected function io($info){
//       echo "<textarea rows=\"15\" style=\"position:relative;left:10%;top:10px;width:90%\">" ,  
//                 var_dump($info),
//             "</textarea>"; 
//     }
// }

// // Register__Now__Child::class_existed('Tribe__Events__API');

// Register__Now__Child::gwt_ev();

// $args = [
//      'posts_per_page' => 50,
//      'start_date' => date( 'Y-m-d H:i:s' )
//  ];

 








// if ( class_exists( 'Tribe__Events__API' ) ) {

//     class Register__Now__Child extends Tribe__Events__API{
//         // class definition
//         function show__classes() { 
            
//         echo "<textarea rows=\"15\" style=\"position:relative;left:10%;top:10px;width:90%\">" ,  
                
//                 var_dump(get_class_methods('Tribe__Events__API')), 
//                 var_dump(get_class_vars('Tribe__Events__API')),
        
//              "</textarea>";
//         }
        
//         public function createNew(){
//         //   $args = isset($args)? 
//         //         $args :
//         //         [];
//         //   $id = self::createEvent($args);
//         //   $this->show__oi($args);
        
//             // $args = array(
//             //   'post_title'    => 'test',
//             //   'post_content'  => 'test',
//             //   'post_status'   => 'publish'
//             // );
//             // $id = self::createEvent($args);
//             // $this->show__oi($id);
// // Retrieve the next 5 upcoming events

//  $args = [
//      'posts_per_page' => 5,
//      'start_date' => date( 'Y-m-d H:i:s' )
//  ];
 
// //$events = Tribe__Events__Query::getEvents( $args, true );
// //$events = get_class_methods('Tribe__Events__Query');
// // $events = Tribe__Events__Query::getEventCounts();
// // $this->show__oi($events);
            
            
            
            
//     // 		$postId = Tribe__Events__API::createEvent( $args );
//     //          $this->show__oi($postId);
//             // 		return $postId;
        
//         }
        
//     //     function tribe_create_event( $args ) {
//     // 		$args['post_type'] = Tribe__Events__Main::POSTTYPE;
//     // 		$postId = Tribe__Events__API::createEvent( $args );
    
//     // 		return $postId;
//     // 	}
        
//         function show__oi($cst) { 
          
//     //         $active_plugins = get_option( 'active_plugins' );
//     // 		$plugins_short_path = null;
//     // 		$subject = "woocommerce-gateway-stripe/woocommerce-gateway-stripe.php";
//     //         $pattern = "/woocommerce-gateway-stripe/";
//     //         $matches = preg_match($pattern, $subject);
//     //         $name = $this->dependencies_plugin[0]["name_ptrn"];
            
//             // $cst =  defined('LLA')? LALA :  Register::MIN_TEC_VERSION;
            
//         echo "<textarea rows=\"15\" style=\"position:relative;left:10%;top:10px;width:90%\">" ,  
//               var_dump($cst),
//     //         strstr( "woocommerce-gateway-stripe", "woocommerce-gateway-stripe/woocommerce-gateway-stripe.php"),
//     // 		print_r($matches);
//              "</textarea>";
        
//         }
//     }
// }else{
//     echo "<textarea rows=\"15\" style=\"position:relative;left:10%;top:10px;width:90%\">" ,  
//     "not init", 
//          "</textarea>";
    
// }


//$cl->show__cl();
//$cl->instance();
// $cl->saveEventMeta( $event_id, $data, $event = null ) 


//echo phpinfo();



?>