(function($, authAjax){
     
    $(document).ready( function() {

    	$('#get_new_auth').on('click', function(){
    		var rq_Ajax_form = new authAjax();
    		console.log(rq_Ajax_form.getRq("POST", '', success_ajax, error_ajax));
    		
    	});
    	
    	
    	function success_ajax (data){
    		
			console.info('The form  is valid!', data);
				
    	}
    	
    	function error_ajax (jqXHR, textStatus, errorThrown){
    		
    		console.info(textStatus);
    	}
	    
    });
    

})(jQuery, authAjax);
