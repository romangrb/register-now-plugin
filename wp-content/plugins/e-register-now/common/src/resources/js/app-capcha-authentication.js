(function($){
     
    $(document).on('click', '#refresh', refresh_capcha);
    
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
                /*$('#captcha').attr('data-validation-captcha', data[0]+data[1]);
                $('#for_captcha').text(data[2]+ " " + data[0]+ " \+ " + data[1]);*/
                
                console.log("Data from Server", data);
             },
             
             error:function(jqXHR, textStatus, errorThrown)
             {
                alert("Error, please show this content to your administrator \n You can not send Cross Domain AJAX requests: "+errorThrown);
             }
        
        });
        
    }
    
    
    
    $(document).on('click', '#test', function(){
        
        var contentType ="application/x-www-form-urlencoded; charset=utf-8";
 
        if(window.XDomainRequest) contentType = "text/plain";
        
        $.ajax({
                 url: 'https://oauth2-service-wk-romangrb.c9users.io/smtp-service/get_auth.php',
                 data:"get_authorize=Authorize",
                 type:"POST",
                 xhrFields: { "withCredentials":true },
                 dataType:"json",   
                 contentType:contentType, 
             
             success:function(data)
             {
                console.log("Datasss from Server", data);
             },
             error:function(jqXHR, textStatus, errorThrown)
             {
                alert("Errorrr, please show this content to your administrator \n You can not send Cross Domain AJAX requests: "+errorThrown);
             }
        
        });
        
       
    });
     

})(jQuery);

