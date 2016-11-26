<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'Register_In_One_Click__Notice' ) ) {
	/**
	 * Class that handles the integration with our Shop App API
	 */
	class Register_In_One_Click__Notice {
		
		protected $notify_arr = [
			'type_id'=>'notice notice-success',
			'nonce_id'=>'nonce_id',
			'header_id'=>'nonce_header_id',
			'content_id'=>'nonce_content_id'
		];
		
		protected function display_admin_notice () {
		
	        $html = "<div id='" . $this->notify_arr['nonce_id'] . "' class='hidden' >";
	        	$html .= "<a href='" . get_permalink() . "' > refresh </a>"; 
	        	$html .= "<h4  id='" . $this->notify_arr['header_id']  . "'> </h4>";
	        	$html .= "<p   id='" . $this->notify_arr['content_id'] . "'> </p>";
	        $html .= "</div>";
	 
	        echo $html;
	 
	    }
	    
	    /**
	     * Renders the administration notice. Also renders a hidden nonce used for security when processing the Ajax request.
	     */
		protected function admin_notification() {
			// check nonce
			$nonce = $_POST['nextNonce'];
			if ( ! wp_verify_nonce( $nonce, 'myajax-next-nonce' ) ) {
				die( 'forbidden request !' );
			}
			
			if ($_POST['data']['type'] == 'success'){
				update_option('is_auth', true);
			}
			
			$response = json_encode($_POST);
			// response output
			header( "Content-Type: application/json" );
			echo $response;
			// IMPORTANT: don't forget to "exit"
			die();
		}
		
		private function sighn_class_notice ($type='info') {
		
			switch ($type) {
			    case 'success':
			        return "notice notice-success";
			        break;
			    case 'error':
			        return "notice notice-error";
			        break;
			    case 'warning':
			        return "notice notice-warning";
			        break;
		        case 'info':
			        return "notice notice-info";
			        break;
			}
			
		}
	
		/**
		 * Static Singleton Factory Method
		 *
		 * @return Register_In_One_Click__Authentication
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

