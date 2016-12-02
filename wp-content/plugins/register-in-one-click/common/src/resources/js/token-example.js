jQuery( document ).ready( function($) {
    var tkn_rf  = new Token(); 
        tkn_rf.method('refresh_token_f_md');
        
    var tkn_get = new Token();
        tkn_get.method('get_token_f_md');
        
    function get_token(){
        
        tkn_get.post_tkn(
            {},
            sc, err);
    }  
    
    get_token();
    console.log('is_new', token_handler.cnt_tkn);
    
    
    // get tmp_tpken for secured page
    
    
    $("#get_token").on("click", function(){
     
       $.get('https://oauth2-service-wk-romangrb.c9users.io/get_tmp_token_client_md', function(data) {
         $('#get_token_id_input').val(data['token_key']);
         $('#token').val(data['token_key']);
         console.log( "tmP-token", data );
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
    console.info('url: ', authorization_url, '\n' ,'secret: ', secret_data);
      $.post(authorization_url, 
        secret_data,
        function(data) {
          $('#secret_token').val(data['token_key']);
          $('#curr_tkn').val(data['token_key']);
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
    
    $("#refresh_token").on("click", function(){
    var authorization_url = 'https://oauth2-service-wk-romangrb.c9users.io/refresh_token/' + $('#curr_tkn').val();
    var secret_data = {
      'email'   :$('#email').val(),
      'password':$('#password').val()
    };
    console.info('url: ', authorization_url, '\n' ,'secret: ', {});
      $.post(authorization_url, 
        secret_data,
        function(data) {
          console.log( "success", data );
          // refresh token in wp db
          tkn_rf.post_tkn(data, sc, err);
        },"json")
      .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR, textStatus, errorThrown );
      });
    });
    
    $("#sent_data").on("click", function(){
      
    var token= $('#currnt_tkn').val();
    var authorization_url = 'https://oauth2-service-wk-romangrb.c9users.io/rq_to_save_data_md/' + token;
    var post_data = {'data': $('#text_data').val()};
    
    console.info('url: ', authorization_url, '\n' ,'data: ', {});
      $.post(authorization_url, 
        post_data,
        function(data) {
          $('#recived_data').val(data['data']);
          
          console.log( "success", data );
        },"json")
      .fail(function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR, textStatus, errorThrown );
      });
    });
    
    
    function sc(data) {
          // refresh token in global var
          token_handler.cnt_tkn = data.token_key;
          
          $('#curr_tkn').val(data['token_key']);
          $('#get_new_token_id_input').val(data['token_key']);
          
          console.log( "refr_db_tkn", data );
    }
    function err(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR, textStatus, errorThrown );
    }
});
