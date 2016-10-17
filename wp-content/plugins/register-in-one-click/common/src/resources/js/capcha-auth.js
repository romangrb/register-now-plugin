(function($, authAjax){
     
    var form_captcha = '#' + Auth_new_ajax.form_captcha,
        label_for_captcha = '#' + Auth_new_ajax.label_for_captcha,
        refresh_btn = '#' + Auth_new_ajax.refresh_btn,
        rq_captha_tag =  Auth_new_ajax.rq_captha_tag,
        rq_Ajax_form = new authAjax('', true);
    
    $(document).on('click, submit', refresh_btn, get_refresh_capcha);
    
    get_refresh_capcha(); 

	function get_refresh_capcha(){
	    rq_Ajax_form.getRq("POST", "json", rq_captha_tag, success_ajax, error_ajax);
	}
	
	function success_ajax (data){
        $(form_captcha).attr('data-validation-captcha', data[0]+data[1]);
        $(label_for_captcha).text("What is the sum of: " + data[0]+ " \+ " + data[1]);
	}
	
	function error_ajax (jqXHR, textStatus, errorThrown){
	    alert("Error, please show this content to your administrator \n You can not send Cross Domain AJAX requests: "+errorThrown);
	}

})(jQuery, authAjax);

