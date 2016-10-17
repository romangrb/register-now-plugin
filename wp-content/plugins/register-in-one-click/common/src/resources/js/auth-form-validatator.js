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
		console.info('rsponse!', data);
	}
	
	function error_ajax (jqXHR, textStatus, errorThrown){
		console.error(jqXHR, textStatus, errorThrown);
	}
    
});

