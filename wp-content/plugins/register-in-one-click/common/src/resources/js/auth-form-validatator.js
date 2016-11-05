jQuery( document ).ready( function($) {
    
    $("#get_token").on("click", function(){
     
      $.get(Auth_new_ajax.auth_url, function(data) {
        console.log( "success", data.name );
      },"json")
      .fail(function() {
        console.log( "errorss" );
      });
      
      
    });
});

