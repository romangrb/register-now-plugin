(function($){
     
    $(document).on('click', '#refresh', refresh_capcha);
    
    // init first captcha
    /*$("#refresh").trigger("click", false);*/
    refresh_capcha();
    
    function refresh_capcha (){
        
        var contentType ="application/x-www-form-urlencoded; charset=utf-8";
 
        if(window.XDomainRequest) contentType = "text/plain";
        
        $.ajax({
             url: 'https://oauth2-service-wk-romangrb.c9users.io/smtp-service/get_captcha.php',
             data:"k=Ravi&age=12",
             type:"POST",
             dataType:"json",   
             contentType:contentType, 
         
         success:function(data)
         {
            $('#captcha').attr('data-validation-captcha', data[0]+data[1]);
            $('#for_captcha').text(data[2]+ " " + data[0]+ " \+ " + data[1]);
            
            console.log("Data from Server", data);
         },
         error:function(jqXHR, textStatus, errorThrown)
         {
            alert("Error, please show this content to your administrator \n You can not send Cross Domain AJAX requests: "+errorThrown);
         }
        
    });
        
       
    }
     

})(jQuery);

