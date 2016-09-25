<div id="e-rn-app-shop" class="wrap">
		
	<div class="header">
		<h1><?php esc_html_e( 'Authentication', 'e-rn-common' );?></h1>
	</div>
	<div class="site-inner">
		
		<!--echo '<iframe align="center" width="800px" height="800px" src="https://oauth2-service-wk-romangrb.c9users.io/smtp-service/p.php" frameborder="yes" scrolling="yes" name="myIframe" id="myIframe"> </iframe>'?><div class="wrap">-->
		
		<?php
            
            if (!!$is_stat){?>
               <div class="container">
                   <div class="col-sm-4">
                        <a href="/" class="btn btn-defoult btn-md col-xs-12">
                          <span class="glyphicon glyphicon-home"> Home </span>
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a href="p.php" class="btn btn-info btn-md col-xs-12">
                          <span class="glyphicon glyphicon-repeat"> Reload </span>
                        </a>
                    </div>
               </div>
            <?php 
            } else {
                //if no errors ?>
                <div class="container">
                    <div class="col-xs-12">
                        <a href="/" class="btn btn-link btn-md col-xs-1 text-left">
                          <span class="glyphicon glyphicon-home"> Home </span>
                        </a>
                    </div>
                    <h2 class='text-center'>Please field this form <br> for authorization</h2>
                    <form action="<?php echo $PHP_SELF; ?>" method="POST" id="auth_form">
                    <div class="form-group row">
                        <label for="first_name" class="col-xs-2 col-sm-4 col-form-label">First name</label>
                        <div class="col-xs-8 col-sm-4">
                            <input class="form-control" type="text" value="" id="first_name" data-validation="required" aria-describedby="f_name_help" placeholder="Enter first name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="last_name" class="col-xs-2 col-sm-4 col-form-label">Last name</label>
                        <div class="col-xs-8 col-sm-4">
                            <input class="form-control" type="text" value="" id="last_name" data-validation="required" aria-describedby="l_name_help" placeholder="Enter last name">
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label for="Email" class="col-xs-2 col-sm-4 col-form-label">Your email address</label>
                        <div class="col-xs-8 col-sm-4">
                            <input class="form-control" type="text" data-validation="email" name="body"  id="email" aria-describedby="email_help" placeholder="Enter email">
                            <small id="email_help" class="form-text text-muted">We'll use your registered WordPress email, if you want you can change it.</small>
                        </div>
                    </div>    
                    <div class="form-group row">
                        <label for="captcha" class="col-xs-2 col-sm-4 col-form-label">security question</label>
                        <div class="col-xs-6 col-sm-2">
                            <input class="form-control" 
                                   name="captcha"
                                   id="captcha"
                                   data-validation="spamcheck"
                                   data-validation-captcha="<?=($_SESSION['captcha'][0]) + ($_SESSION['captcha'][1])?>"
                                   placeholder="0"
                            />
                            <small id="captcha" class="form-text text-muted"> 
                                What is the sum of <?=$_SESSION['captcha'][0]?> + <?=$_SESSION['captcha'][1]?>?
                            </small>
                        </div>
                    </div> 
                    <div class="col-sm-4">
                        <button type="submit" class="btn btn-md btn-primary col-xs-12" name="get_authorize" value="Authorize"> Authorize </button>
                    </div>
                    <div class="col-sm-4">
                        <button type="button" class="btn btn-md btn-defoult col-xs-12" id="reset"> reset </button>
                    </div>
                </form>    
                
                </div>
               
               
            <?php } ?>
		
		
		
	</div>

</div>