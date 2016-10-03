(function($){
     
    $(document).ready( function($) {
    
    	var authProp = {
    		formId: '#'+Auth_new_ajax.auth_form_id,
    		contentType:"application/x-www-form-urlencoded; charset=utf-8",
    	};
    
	    $.validate({
	        form : authProp['formId'],
	        modules : 'date, security',
	        onError : function($form) {
	        	console.warn('Validation of form  failed!');
	        },
	        onSuccess : function($form) {
	        	
				if(window.XDomainRequest) authProp['contentType'] = "text/plain";
				var form_data = $(authProp['formId']).serialize();
				console.info('The form  is valid!', form_data);
				
				$.ajax({url: Auth_new_ajax.auth_url,
				         data: form_data,
				         xhrFields: { "withCredentials":true },
				         type:"POST",
				         dataType:"json",   
				         contentType: authProp['contentType'], 
				     
				     success:function(data)
				     {
				     	
				        console.warn($form);
				     },
				     
				     error:function(jqXHR, textStatus, errorThrown)
				     {
				        var data = {
							action: 'ajax-inputtitleSubmit',
							// send the nonce along with the request
							nextNonce: Auth_new_ajax.nextNonce
						};
						// We can also pass the url value separately from ajaxurl for front end AJAX implementations
						jQuery.post(Auth_new_ajax.ajaxurl, data, function() {
							console.info('Got this from the server: ' + 5);
						});
				        
				        console.error(errorThrown);
				     }
				
				});
		        
	          return false; // Will stop the submission of the form
	        },
	    });
    });
    
    
    
     

})(jQuery);
