jQuery( document ).ready( function($) {
    var tkn_rf  = new Token(); 
        tkn_rf.method('refresh_token_f_md');
        
    var tkn_get = new Token();
        tkn_get.method('get_token_tmp_f_md');
    
    console.log('is_new', token_handler.cnt_tkn, 'r', token_handler.refresh_token); 
    if (token_handler.cnt_tkn)  $("#form_off").attr('hidden', true), $("#form_on").attr('hidden', false),  $("#auth_form_on").attr('hidden', false);
    
    $("#auth_form_on").on("click", function(){
      $("#form_on").attr('hidden', true);
      $("#form_off").attr('hidden', false);
      $("#auth_form_on").attr('hidden', true);
      
    });
    
    $("#auth_form_off").on("click", function(){
      $("#form_on").attr('hidden', false);
      $("#form_off").attr('hidden', true);
      $("#auth_form_on").attr('hidden', false);
    });
        
    $("#get_token").on("click", function(){
     
       $.get('https://oauth2-service-wk-romangrb.c9users.io/get_tmp_token_client_md', function(data) {
         $('#token').val(data['token_key']);
         console.log('is_new', token_handler.cnt_tkn);
      },"json")
      .fail(function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR, textStatus, errorThrown );
      });
      
    });
    
    // get Auth with email, psw, tmp_tpken
    
    $("#init_token").on("click", function(){
    var authorization_url = 'https://oauth2-service-wk-romangrb.c9users.io/init_authorization_on_client_md/' + $('#token').val();
    var secret_data = {
      'email'   :$('#email').val(),
      'password':$('#password').val()
    };
    if (token_handler.refresh_token)
    console.info('url: ', authorization_url, '\n' ,'secret: ', secret_data);
      $.post(authorization_url, 
        secret_data,
        function(data) {
          // refresh token in global var
          token_handler.cnt_tkn = data.token_key;
          // refresh token in wp db
          tkn_rf.post_tkn(data, sc, err);
        console.log( "success", data );
        },"json")
      .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR, textStatus, errorThrown );
      });
    });
   
    function sc(data) {
          // refresh token in global var
          token_handler.cnt_tkn = data.token_key;
          console.log( "іс - refr_db_tkn", data );
    }
    function err(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR, textStatus, errorThrown );
    }
    
});

