<?php

abstract class Register_In_One_Click__Abstract_Initialization implements Register_In_One_Click__Initialization_Interfaces {

	// echo "<div style='position:relative;top:50%;left:50%'>" . "TEST" . "</div>";

    /**
     * Registers and enqueues admin-specific minified JavaScript.
     */
    // abstract protected function register_admin_scripts();
	
	/**
	 * Adds the page to the admin menu
	 */
	 
	abstract public function add_menu_page();

	/**
	 * Adds a link to the the WP admin bar
	 */
	// abstract public function add_toolbar_item();
	
	/**
	 * function that loaded content (html) for add_meny_page
	 */
	abstract public function do_menu_page();

	 //echo "<div style='position:relative;top:50%;left:50%'>" . "TEST" . "</div>";
}

