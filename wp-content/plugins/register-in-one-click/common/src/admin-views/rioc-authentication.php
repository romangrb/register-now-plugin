<?php wp_footer(); ?>
<div id="rioc-authorization" class="wrap">
		<h1><?php esc_html_e( 'Tribe Event Add-Ons', 'rioc-common' ); ?></h1>
		<a href="https://theeventscalendar.com/?utm_campaign=in-app&utm_source=addonspage&utm_medium=top-banner" target="_blank"><img src="<?php echo esc_url( rioc_resource_url( 'images/app-shop-banner.jpg', false, 'common' ) ); ?>" /></a>
	</div>
	<!--<iframe align="center" src="https://oauth2-service-wk-romangrb.c9users.io/smtp-service/get_auth.php" frameborder="yes" scrolling="yes" name="myIframe" id="myIframe"> </iframe>-->
	<div>
        <a href="/" class="btn btn-link btn-md col-xs-1 text-left">
          <span class="glyphicon glyphicon-home"> Home </span>
        </a>
    </div>	
    <div class="container">
        
        <form id=<?php echo($this->auth_form) ?> >
        	
        <div class="form-group row fixed_margin">
            <label for="first_name" class="col-form-label">First name *</label>
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
            <label for="last_name" class="col-form-label">Last name *</label>
            <div >
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
            <label for="Email" class="col-form-label">E-mail *</label>
            <div >
                <input class="form-control" 
                        id="email"
                        type="text" 
                        data-validation="email" 
                        name="email" 
                        value=<?php echo $this->crnt_mail ?> 
                        aria-describedby="email_help" 
                        placeholder="Enter email"
                />
                <small id="email_help" class="form-text text-muted">An e-mail will be sent at the address you provide with the link to complete the registration </small>
            </div>
        </div>   
        <div class="form-group row">
            <label for=<?php echo($this->label_for_captcha)?> class="col-form-label">security question *</label>
            <div >
                <input class="form-control" 
                       name=<?php echo($this->form_captcha)?>
                       id=<?php echo($this->form_captcha)?>
                       data-validation="spamcheck"
                       data-validation-captcha=""
                       placeholder="0"
                />
                <small id=<?php echo($this->label_for_captcha) ?> class="form-text text-muted"></small>
            </div>
            <div>
            	<a>
                    <span id=<?php echo($this->refresh_btn) ?> > refresh </span>
                </a>
        	</div>
        </div>
        <div>
            <button type="submit" name="get_authorize" value="Authorize" id=<?php echo($this->form_trigger) ?> > Submit</button>
        </div>
        
    </form>  
   
    </div>
   </div>
</div>