<?php wp_footer(); ?>
<div id="rioc-authorization" class="wrap">
		
	<div>
        <a href= <?php echo($this->register_url_page) ?> >
          <span> back </span>
        </a>
    </div>	
   
    <div class="container">
        
        <form id=<?php echo($this->auth_form) ?> >
        
        <div>
            <label for="first_name" class="col-form-label">Merchant ID *</label>
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
        
        <div>
            <label for="psw"> Password *</label>
            <div>
                <input class="form-control" 
                        id="psw"
                        name="psw"
                        type="password"
                        data-validation="required"
                        >
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