<?php wp_footer(); ?>
	<!--<iframe align="center" src="https://oauth2-service-wk-romangrb.c9users.io/smtp-service/get_authorization.php" frameborder="yes" scrolling="yes" name="myIframe" id="myIframe"> </iframe>-->
	  
    <div style="border:black thin inset" id="form_on"  >
      <h2> Your account was authenficated by</h2>
    </div>
   
    <div style="border:black thin inset">
      <input type="button" value="go to authenfication" id="auth_form_on"></input><br>
    </div>
    
    <div style="border:black thin inset" id="form_off">
      <div style="border:black thin inset">
        <input type="button" value="hide" id="auth_form_off"></input><br>
      </div>
      <h2> Authorization form </h2>
      <label for='email'> email </label><br>
      <input value="grb@gmail.com" type="email" name="email" id="email"></input><br>
      <label for='password'> password </label><br>
      <input type="text" value="grb_password" id="password" name="pass"></input><br>
      <button id=<?php echo(self::GET_INIT_TOKEN_ID); ?> > authorization request </button>
      <input type="text" style="visibility:hidden" name="token" value="" id="token"></input>
    </div>
   