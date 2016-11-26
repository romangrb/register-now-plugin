<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
// http://stackoverflow.com/questions/5306612/using-wpdb-in-standalone-script
if ( ! class_exists( 'Register_In_One_Click__Query_Db_Rioc' ) ) {
	/**
	 * Class that handles query to wp db
	 */
	class Register_In_One_Click__Query_Db_Rioc {
		
		private static $instance = null;
		
		private $db;
		
		private $t_name;
		
		/**
		 * Class constructor
		 */
		private function __construct() {
			global $wpdb;
			$this->db = $wpdb;
			
			$this->t_name = "{$wpdb->prefix}rioc_d";
		}
		
		private function beckup_token($id, $fromId) {
			//is more than 2 rows of id's 
			// update new token
			$result = $this->db->update( 
				$this->t_name, 
			array( 
				'token_key'    => $this->db->get_var( "SELECT token_key	   FROM	$this->t_name WHERE token_id = $fromId" ), 
				'token_expiry' => $this->db->get_var( "SELECT token_expiry FROM $this->t_name WHERE token_id = $fromId" ), 
			), 
			array( 'token_id'  => $id), 
			array( 
				'%s',
				'%d'
			), 
			array( '%d' ) 
			);
			
			return ($result!=NULL) ? true : false;
		}
		
		private function update_token($id, $h_val) {
			
			$result = $this->db->update(
				$this->t_name, 
			array( 
				'token_key'    => $h_val['token_key'], 
				'token_expiry' => $h_val['token_expiry'], 
			), 
			array( 'token_id'  => $id), 
			array( 
				'%s',
				'%d'
			), 
			array( '%d' ) 
			);
			
			return ($result!=NULL) ? true : false;
		}
		
		private function create_token($h_val) {
			
			$result = $this->db->insert(
				$t_name->t_name, 
				array( 
					'token_key'    => $h_val['token_key'], 
					'token_expiry' => $h_val['token_expiry'], 
				), 
				array( 
					'%s',
					'%d'
				)
			);
			return ($result!=NULL) ? true : false;
		}
		
		
		public function refresh_token($new_token_data) {
			
			if (!is_array($new_token_data) || 
				!array_key_exists('token_key', $new_token_data) ||  
				!array_key_exists('token_expiry', $new_token_data)){
					
				return 'input value is not correct : ! required 
				array(
					key = token_key , val = str
					key = token_expiry , val = int
				)';
			}
			
			
			$min = $this->db->get_var( "SELECT MIN(token_id) FROM $this->t_name");
			$max = $this->db->get_var( "SELECT MAX(token_id) FROM $this->t_name");
			
			$cond = ($min != NULL)? ($max != $min)? 2 : 1 : 0;
			
			switch ($cond) {
			    case 0:
			        // is no rows
			        $this->create_token($new_token_data);
			        break;
			    case 1:
			        // there only 1 row
			        $this->create_token($new_token_data);
			        break;
			    case 2:
			        // there 2 or more rows
			        $this->beckup_token($max, $min);
			        $this->update_token($min, $new_token_data);
			        break;
			}
			
			
		   //echo("<div style='position:relative; top:50%; left:50%'>  $cond  cond $min and $max </div>"); 
		}
		
		/**
		 * Static Singleton Factory Method
		 *
		 * @return Register_In_One_Click__Query_Db_Rioc
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) ) {
				$className      = __CLASS__;
				self::$instance = new $className;
			}

			return self::$instance;
		}
	}
}
