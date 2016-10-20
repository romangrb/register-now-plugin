jQuery( document ).ready( function($) {
    
    var form_id =  '' + Auth_new_ajax.auth_form_tag + Auth_new_ajax.auth_form;

    $.validate({
        form : form_id,
        modules : 'date, security',
        onError : function($form) {
          console.warn('Validation of form  failed!');
        },
        onSuccess : function($form) {
          console.info('The form  is valid!');
          
         var rq_Ajax_form = new authAjax('', true);
      	 rq_Ajax_form.getRq("POST", "json", false, success_ajax, error_ajax); 
      	 //console.log(rq_Ajax_form.serializeFormData());

        return false; // Will stop the submission of the form
        },
    });
    
  function success_ajax (data){
    $.post(
  					ajaxurl,
  					{
  						// wp ajax action
  				    action: 'ajax-inputtitleSubmit',
  						// send the nonce along with the request
  						nextNonce: Auth_new_ajax.nextNonce,
  						//data
  						data:data,
  						
  						
  					},
  					function (response) {
  						console.warn(response);
  					}
  			);
	
	}
	
	function error_ajax (jqXHR, textStatus, errorThrown){
		console.error(jqXHR, textStatus, errorThrown);
	}
    
});

