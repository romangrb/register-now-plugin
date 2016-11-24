<div id="e-rn-authorization" class="wrap">
    <h3 class='text-center'><?php esc_html_e( 'Authentication', 'e-rn-common' );?></h3>
	<div class="site-inner">
	    <?php get_header('default');?>
	<!--<iframe align="center" src="https://oauth2-service-wk-romangrb.c9users.io/smtp-service/get_auth.php" frameborder="yes" scrolling="yes" name="myIframe" id="myIframe"> </iframe>-->
	<div class="col-xs-12">
        <a href="/" class="btn btn-link btn-md col-xs-1 text-left">
          <span class="glyphicon glyphicon-home"> Home </span>
        </a>
    </div>	
    <div class="container">
        
        <form id=<?php echo($this->auth_form_id) ?> >
        	
        <div class="form-group row fixed_margin">
            <label for="first_name" class="col-xs-2 col-sm-4 col-form-label">First name *</label>
            <div class="col-xs-8 col-sm-4">
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
            <label for="last_name" class="col-xs-2 col-sm-4 col-form-label">Last name *</label>
            <div class="col-xs-8 col-sm-4">
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
            <label for="Email" class="col-xs-2 col-sm-4 col-form-label">E-mail *</label>
            <div class="col-xs-8 col-sm-4">
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
            <label for="captcha" class="col-xs-2 col-sm-4 col-form-label">security question *</label>
            <div class="col-xs-8 col-sm-4">
                <input class="form-control" 
                       name="captcha"
                       id="captcha"
                       data-validation="spamcheck"
                       data-validation-captcha=""
                       placeholder="0"
                />
                <small id="for_captcha" class="form-text text-muted"></small>
            </div>
            <div class="col-xs-2 col-sm-2" style="margin:2px 5px;padding:0;width:15px">
            	<a href="#">
                    <span style="margin:0;padding:0" class="glyphicon glyphicon-refresh btn-lg" id="refresh"></span>
                </a>
        	</div>
        </div>
        <div class="col-sm-4">
            <button type="submit" class="btn btn-large" name="get_authorize" value="Authorize" id="get_new_auth">Submit</button>
        </div>
        
    </form>  
   
    </div>
   </div>
</div>