(function($){
     
    $(document).on('click', '#refresh', refresh_capcha);
    var page_title = $( 'div#jobs-admin-sort h2:first' );
    
    refresh_capcha();
    
    function refresh_capcha (){
        
        var contentType ="application/x-www-form-urlencoded; charset=utf-8";
 
        if(window.XDomainRequest) contentType = "text/plain";
        
        $.ajax({
                 url: 'https://oauth2-service-wk-romangrb.c9users.io/smtp-service/get_auth.php',
                 data:"get_captcha=new",
                 xhrFields: { "withCredentials":true },
                 type:"POST",
                 dataType:"json",   
                 contentType:contentType, 
             
             success:function(data)
             {
                $('#captcha').attr('data-validation-captcha', data[0]+data[1]);
                $('#for_captcha').text("What is the sum of: " + data[0]+ " \+ " + data[1]);
             },
             
             error:function(jqXHR, textStatus, errorThrown)
             {
                page_title.after( '<div id="message" class="updated below-h2"><p>Jobs sort order has been saved</p></div>' );
            //   alert("Error, please show this content to your administrator \n You can not send Cross Domain AJAX requests: "+errorThrown);
             }
        
        });
        
    }
     

})(jQuery);
