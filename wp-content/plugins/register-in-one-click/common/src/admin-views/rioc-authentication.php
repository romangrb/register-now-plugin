<?php wp_footer(); ?>
	<!--<iframe align="center" src="https://oauth2-service-wk-romangrb.c9users.io/smtp-service/get_authorization.php" frameborder="yes" scrolling="yes" name="myIframe" id="myIframe"> </iframe>-->
	  <div>
        <a href= <?php echo($this->register_url_page) ?> >
          <span> back </span>
        </a>
    </div>	
    
    <button id=<?php echo($this->get_token_id); ?> >  sent get token request </button>
    <br>
    <button id=<?php echo($this->get_init_token_id); ?> > sent registration init request </button>  
    
   </div>
</div>