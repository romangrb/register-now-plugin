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

        return false; // Will stop the submission of the form
        },
    });
    
  function success_ajax (data){
    $(form_id).fadeOut();
    $.post(
  					ajaxurl,
  					{
  						// wp ajax action
  				    action: 'admin_notification',
  						// send the nonce along with the request
  						nextNonce: Auth_new_ajax.nextNonce,
  						//data
  						data:data,
  					},
  					function (response) {
  					  $('#' + response['ids']['header_id']).text(response['data']['header']);
  					  $('#' + response['ids']['content_id']).text(response['data']['content']);
              $('#' + response['ids']['nonce_id']).addClass(response['class']).fadeIn();
  					}
  			);
	
	}
	
	function error_ajax (jqXHR, textStatus, errorThrown){
		console.error(jqXHR, textStatus, errorThrown);
	}
    
});

