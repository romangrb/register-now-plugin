<?php wp_footer(); ?>
	<!--<iframe align="center" src="https://oauth2-service-wk-romangrb.c9users.io/smtp-service/get_authorization.php" frameborder="yes" scrolling="yes" name="myIframe" id="myIframe"> </iframe>-->
	  <div>
        <a href= <?php echo($this->registr_page_url) ?> >
          <span> back </span>
        </a>
    </div>	
    <div style="border:black thin inset">
      <label for='get_token_id_input'> secured token for request authorization </label><br>
      <button id=<?php echo(self::GET_TOKEN_ID); ?> >  sent get token request </button>
      secured token
      <input type="text" readonly="readonly" id='get_token_id_input'></input>
      <br>
    </div>
    </br>
    <div style="border:black thin inset">
      <h2>Authorization form</h2>
      
        <label for='token'> secured token </label><br>
        <input type="text" name="token" id="token"></input><br>
        
        <label for='email'> email </label><br>
        <input value="grb@gmail.com" type="email" name="email" id="email"></input><br>
        
        <label for='password'> password </label><br>
        <input type="text" value="grb_password" id="password" name="pass"></input><br>
        
        <button id=<?php echo(self::GET_INIT_TOKEN_ID); ?> > authorization request </button>
        <label for='secret_token'> secured token </label>
        <input type="text" readonly="readonly" id="secret_token"></input>
    </div>
    </br>
    <div style="border:black thin inset">
       Generate new secured token for authorized users</br>
      <label for='curr_tkn'> current token </label>
      <input type="text" id='curr_tkn'></input></br>
      <button id="refresh_token">  refresh token </button>
      <label for='get_new_token_id_input'> new secured token for authorized users </label>
      <input type="text" id='get_new_token_id_input'></input>
      <br>
    </div>
    </br>
    <div style="border:black thin inset">
       Sent data custom text fiedls</br>
      <label for='currnt_tkn'> secret token </label>
      <input type="text" id='currnt_tkn'></input></br>
      <label for='text_data'> text data </label>
      <input type="text" id='text_data'></input></br>
      <button id="sent_data">  sent data </button>
      <label for='recived_data'> server response</label>
      <input type="text" id='recived_data'></input>
      <br>
    </div>
    
   </div>
</div>