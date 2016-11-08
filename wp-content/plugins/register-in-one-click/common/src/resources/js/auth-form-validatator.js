jQuery( document ).ready( function($) {
    
    $("#get_token").on("click", function(){
     
      $.get(Auth_new_ajax.auth_url, function(data) {
        console.log( "success", data );
      },"json")
      .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR, textStatus, errorThrown );
      });
      
    });
});

