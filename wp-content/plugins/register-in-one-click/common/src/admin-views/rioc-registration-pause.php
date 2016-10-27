<?php wp_footer(); ?>
<div id="rioc-registration" class="wrap">
	
    Pause until 
    <?php 
        echo time() - get_option($this->opt_name) - self::DFLT_CEIL_TIME_SS
    ?> 
</div>