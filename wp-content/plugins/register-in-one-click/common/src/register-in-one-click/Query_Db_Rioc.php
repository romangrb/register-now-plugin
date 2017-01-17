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
	class Register_In_One_Click__Query_Db_Rioc implements Register_In_One_Click__Initialization_Interfaces {
		
		private static $instance = null;
		
		private $db;
		
		private $t_name;
		
		private $t_sunc_post;
		
		/**
		 * Class constructor
		 */
		private function __construct() {
			global $wpdb;
			$this->db = $wpdb;
			
			$this->t_sunc_post = "{$wpdb->prefix}rioc_post_sunc";
			
			$this->t_name = "{$wpdb->prefix}rioc_d";
		}
		
		private function beckup_token($id, $fromId) {
			// is more than 2 rows of id's 
			// update new token
			$result = $this->db->update( 
				$this->t_name, 
			array( 
				'token_id'     => $this->db->get_var( "SELECT token_id	   FROM	$this->t_name WHERE id = $fromId" ), 
				'token_key'    => $this->db->get_var( "SELECT token_key	   FROM	$this->t_name WHERE id = $fromId" ), 
				'token_expire' => $this->db->get_var( "SELECT token_expire FROM $this->t_name WHERE id = $fromId" ),
				'token_life'   => $this->db->get_var( "SELECT token_life   FROM $this->t_name WHERE id = $fromId" ), 
				'refresh_token'=> $this->db->get_var( "SELECT refresh_token FROM $this->t_name WHERE id = $fromId" ), 
			), 
			array( 'id'  => $id), 
			array( 
				'%d',
				'%s',
				'%d',
				'%d',
				'%s'
			), 
			array( '%d' ) 
			);
			
			return ($result!=NULL) ? true : false;
		}
		
		// returns index values array with format 
		private function get_format_rows($array){
			return array_values(array_intersect_key(self::TOKEN_TABLE_ROWS, $array));
		}
		
		// returns index values array with format 
		private function get_format_sunc_rows($array){
			return array_values(array_intersect_key(self::SUNC_TABLE_ROWS, $array));
		}
		
		private function update_token($id, $h_val) {

			$result = $this->db->update(
				$this->t_name, 
				$h_val, 
				array( 'id'  => $id), 
				$this->get_format_rows($h_val),
				array( '%d' ) 
			);
			
			return ($result!=NULL) ? true : false;
		}
		
		public function create_token($h_val) {
			
			$result = $this->db->insert(
				$this->t_name, 
				$h_val, 
				$this->get_format_rows($h_val)
			);
			return ($result!=NULL) ? true : false;
		}
		
		public function add_to_sunc_query($h_val) {
			
			if (!is_array($h_val)) return '';
			
			$result = $this->db->insert(
				$this->t_sunc_post, 
				$h_val, 
				$this->get_format_sunc_rows($h_val)
			);
			
			return ($result!=NULL) ? true : false;
		}
		
		public function update_sunc_query($h_val) {
			
			if (!is_array($h_val)) return '';
		
			$result = $this->db->update(
				$this->t_sunc_post, 
				$h_val,
				array( 'post_id'  => $h_val['post_id']),
				$this->get_format_sunc_rows($h_val),
				array( '%d' )
			);
		
			return ($result!=NULL) ? true : false;
		}
		
		public function get_sunc_data() {
		
	        $results = $this->db->get_results( 
                $this->db->prepare(
                	"SELECT * FROM {$this->t_sunc_post} WHERE is_sunc < %d LIMIT 5"
                	, 1),
                ARRAY_A
            );
			return $results;
		}
		
		public function get_complete_data_to_sunc($id) {
		
	        $results = $this->db->get_results( 
                $this->db->prepare(
                	"SELECT * FROM {$this->t_sunc_post} WHERE is_sunc < %d LIMIT 5"
                	, 1),
                ARRAY_A
            );
			return $results;
		}
		
		public function refresh_token($new_token_data) {
			
			if (!is_array($new_token_data)) return '';			
			
			$min = $this->db->get_var( "SELECT MIN(id) FROM $this->t_name");
			$max = $this->db->get_var( "SELECT MAX(id) FROM $this->t_name");
			
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
			
		}
		
		
		private function check_token_validation($id) {
			
			$token = $this->db->get_row("CALL get_token_cash_d($id)", ARRAY_A);
			
			return $token;
		}
		
		
		public function get_last_token_cash() {
			
			$id  = $this->db->get_var(
				"
						SELECT MIN(id) FROM $this->t_name
				"											
			);
			
			$is_valid = $this->check_token_validation($id);
			
			return $is_valid;
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