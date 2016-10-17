(function($, authAjax){
    var trig_btn =  '' + Auth_new_ajax.form_trigger_tag + Auth_new_ajax.form_trigger;
     
    $(document).ready(function() {
        
    	$(trig_btn).on('click', function(){
        
            var rq_Ajax_form = new authAjax('', true);
        	rq_Ajax_form.getRq("POST", "json", "get_captcha=new", success_ajax, error_ajax);  
     
    });
    	
	function success_ajax (data){
		console.info('rsponse!', data);
	}
	
	function error_ajax (jqXHR, textStatus, errorThrown){
		console.error(jqXHR, textStatus, errorThrown);
	}
	    
    });
    
})(jQuery, authAjax);
