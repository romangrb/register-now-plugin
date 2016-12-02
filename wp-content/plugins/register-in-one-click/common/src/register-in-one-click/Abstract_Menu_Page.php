<?php

abstract class Register_In_One_Click__Abstract_Menu_Page implements Register_In_One_Click__Initialization_Interfaces {

	/**
	 * Adds the pages to the admin menu
	 */
	 
	abstract public function add_menu_page();
	
	/**
	 * function that loaded content (html) for add_meny_page
	 */
	abstract public function do_menu_page();

	/**
	 * function that emplode js in content (html)
	 */
	abstract public function enqueue_script();
	
	/**
	 * function that emplode css style in content (html)
	 */
	abstract public function enqueue_style();
	
	
	 //echo "<div style='position:relative;top:50%;left:50%'>" . "TEST" . "</div>";
}

