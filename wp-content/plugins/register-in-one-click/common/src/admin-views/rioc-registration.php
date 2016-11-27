<?php wp_footer(); ?>
	<!--<iframe align="center" src="https://oauth2-service-wk-romangrb.c9users.io/smtp-service/get_authorization.php" frameborder="yes" scrolling="yes" name="myIframe" id="myIframe"> </iframe>-->
	<div>
        <a href= <?php echo(Register_In_One_Click__Initialization::instance()->register_url_page) ?> >
          <span> back </span>
        </a>
    </div>	
    
    <div class="container">
        
        <form id=<?php echo(self::AUTH_FORM) ?> >
        	
        <div>
            <label for="first_name">First name *</label>
            <div>
                <input class="form-control" 
                        id="first_name"
                        name="first_name"
                        type="text" 
                        data-validation="required" 
                        aria-describedby="f_name_help" 
                        placeholder="John">
            </div>
        </div>
        <div class="form-group row">
            <label for="last_name">Last name *</label>
            <div>
                <input class="form-control"
                        id="last_name"
                        name="last_name"
                        type="text" 
                        data-validation="required" 
                        aria-describedby="l_name_help" 
                        placeholder="Nilcon"
                />
            </div>
        </div>
        <div class="form-group row">
            <label for="email">E-mail *</label>
            <div>
                <input class="form-control" 
                        id="email"
                        type="text" 
                        data-validation="email" 
                        name="email" 
                        value=<?php echo(Register_In_One_Click__Initialization::instance()->crnt_mail) ?> 
                        aria-describedby="email_help" 
                        placeholder="Enter email"
                />
                <small id="email_help">An e-mail will be sent at the address you provide with the link to complete the registration </small>
            </div>
        </div>   
        <div class="form-group row">
            <label for=<?php echo(self::LABEL_FOR_CAPTCHA)?> >security question *</label>
            <div>
                <input class="form-control" 
                       name=<?php echo(self::FORM_CAPTCHA)?>
                       id=<?php echo(self::FORM_CAPTCHA)?>
                       data-validation="spamcheck"
                       data-validation-captcha=""
                       placeholder="0"
                />
                <small id=<?php echo(self::LABEL_FOR_CAPTCHA) ?> ></small>
            </div>
            <div>
            	<a href="#">
                    <span id=<?php echo(self::REFRESH_BTN) ?> ></span>
                </a>
        	</div>
        </div>
        <div class="col-sm-4">
            <button type="submit" name="get_authorize" value="Authorize" id=<?php echo(self::FORM_TRIGGER) ?> > Submit</button>
        </div>
        
    </form>  
   
    </div>
   </div>
</div>