<?php

/**
 * Specifies the minimal interface required of all logging implementations.
 */
interface Register_In_One_Click__Initialization_Interfaces {
	
	const MENU_SLUG     			= 'rioc-init';
	
	const AUTH_SLUG 				= 'rioc-authentication';
	
	const REG_SLUG 					= 'rioc-registration';
	
	const SYNC_SERVICE				= 'https://oauth2-service-wk-romangrb.c9users.io/sync_service/';
	
	const AUTH_URL					= 'https://oauth2-service-wk-romangrb.c9users.io';
	
	const REG_URL					= 'https://oauth2-service-wk-romangrb.c9users.io/smtp-service/get_authorization.php';
	
	const AUTH_FORM 			    = 'auth_form';
	
	const AUTH_TAG					= '#';
	
	const FORM_TRIGGER  			= 'get_new_auth';
	
	const FORM_CAPTCHA  			= 'form_captcha';
	
    const LABEL_FOR_CAPTCHA 		= 'label_for_captcha';
    
    const REFRESH_TRIGGER			= 'refresh_btn';
    
    const RQ_CAPTCHA_QUERY			= 'get_captcha=new';
    
    const GET_TOKEN_ID      		= 'get_token';
    
    const GET_INIT_TOKEN_ID 		= 'init_token';
    
    const AUTH_PAGE_URL 			= 'init_token';
    
	const AUTH_PAGE_N				= 'rioc-authentication';
	
	const REG_PAGE_N				= 'rioc-registration';
	
	const INIT_PAGE_N				= 'rioc-init';
	
	const POST_TYPE_N				= 'rioc-common';
	
	const TOKEN_TABLE_ROWS			= array('token_id'=>'%d' ,'token_key'=>'%s', 'token_expire'=>'%d', 'token_life'=>'%d', 'refresh_token'=>'%s');
	
	const SYNC_TABLE_ROWS			= array('post_id'=>'%d', 'is_sync'=>'%d', 'cr_time'=>'%d');
}
