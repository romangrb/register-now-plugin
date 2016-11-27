jQuery( document ).ready( function($) {
    
    var form_id =  '' + Auth_new_ajax.auth_form_tag + Auth_new_ajax.auth_form,
        msg_hash = {head : '',
            	      content_id : '',
            	      notice_cl :  ''};
    $.validate({
        form : form_id,
        modules : 'date, security',
        onError : function($form) {
          console.warn('Validation of form  failed!');
        },
        onSuccess : function($form) {
          console.info('The form  is valid!');
          
         var rq_Ajax_form = new authAjax('', true);
      	 rq_Ajax_form.getRq("POST", "json", false, success_ajax_reg, error_ajax_reg); 

        return false; // Will stop the submission of the form
        },
    });
    
  function success_ajax_reg (data){
    $(form_id).fadeOut();
    $.post(
			ajaxurl,
			{
				// wp ajax action
		    action: 'admin_notification',
				// send the nonce along with the request
				// nextNonce: Auth_new_ajax.nextNonce,
				//data
				data:data,
			})
			.done(function (response) {
			  var cont = response['data']['content']['Message'],
			      head = response['data']['header'];
			  head = (head)? head.replace(/\/(\|#|$)/, '/$1') : head;
			  cont = (cont)? cont.replace(/\/(\|#|$)/, '/$1') : cont;
			  
		      msg_hash['head'] = head; msg_hash['content_id'] = cont; msg_hash['notice_cl'] = response['class'];
		      wr_notice(msg_hash);
			})
			.fail(error_ajax_reg);
	}
	
	function error_ajax_reg (jqXHR, textStatus, errorThrown){
	    msg_hash['head'] = textStatus+'status :  ' + jqXHR['status'];
	    msg_hash['content_id'] = "Please reload this page and contact us if the problem persists at <a href='mailto:support@registerinoneclick.com'> support@registerinoneclick.com </a>.";
	    msg_hash['notice_cl'] = 'notice notice-error';
	    wr_notice(msg_hash);  
		// console.error(jqXHR, textStatus, errorThrown);
	}
	
	function wr_notice(msg_arr){
	  $('#' + Auth_new_ajax['formNoth']['header_id']).append(msg_arr['head']);
	  $('#' + Auth_new_ajax['formNoth']['content_id']).append(msg_arr['cont']);
	  $('#' + Auth_new_ajax['formNoth']['nonce_id']).addClass(msg_arr['notice_cl']).fadeIn();
	  
	}
    
});

