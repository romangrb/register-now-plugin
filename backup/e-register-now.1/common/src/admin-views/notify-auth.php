<?php

function get_inform_stat_ui($e, $type){
            
    switch ($type) {
    
        case 'danger':
           
            $cont = array("header"  =>"The error has occurred ! please try to reload page or contact to <a href='mailto:support@registerinoneclick.com'> us.</a>",
                          "content" => "Message : " . $e->getMessage() . "<br>" . 
                                        "Code   : " . $e->getCode()    . "<br>" .
                                        "File   : " . $e->getFile()    . "<br>" . 
                                        "Line   : " . $e->getLine() );
                                        
            if ( session_status() != PHP_SESSION_NONE ) session_destroy();
            $is_stat = true;
            show_info_stat_ui($type, $cont);
            exit();
            
            break;
        case 'warning':
            
            $cont = array("header"  =>"The problem on your client has occurred ! to fix this please follow to follow operations beneath.",
                          "content" => $e->getMessage());
            if ( session_status() != PHP_SESSION_NONE ) session_destroy();
            $is_stat = true;
            show_info_stat_ui($type, $cont);
            exit();
            
            break;
        case 'success':  
            
            $cont = array("header"  => "The authorization message has been sent successfully.",
                          "content" => "Check your email address from support@registerinoneclick.com,
                                        if you can find, then plese wait 1 min also check the message in spam");
            $is_stat = true;
            show_info_stat_ui($type, $cont);
            exit();
            
            break;
        }
}
    
function show_info_stat_ui($type, $cont){
    
    echo "<div class='alert alert-$type'>
                  <h5 class='text-center'>
                    <strong>" . $cont['header'] . "</strong>
                  </h5>
                  <br> 
                  " . $cont['content'] .
         "</div>";
}