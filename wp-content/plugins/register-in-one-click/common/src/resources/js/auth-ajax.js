    	var authProp = {
    		content_type: (window.XDomainRequest)? "text/plain" : "application/x-www-form-urlencoded; charset=utf-8",
    		form_name: Auth_new_ajax.auth_form,
    		auth_form_tag : Auth_new_ajax.auth_form_tag,
    		auth_url : Auth_new_ajax.auth_url,
    		
    	};
    	
    	function authAjax(tag, is_cred){
    		var self = this;
    		
    		this.xhrFields = { "withCredentials": (is_cred==!true)? false : true},
    		this.selectorTag = tag || this.auth_form_tag,
    		this.getForm = function(){
    			return '' + self.selectorTag + self.form_name;
    		},
			this.serializeFormData = function(data){
				
				return (!data)? jQuery(self.getForm()).serialize() : data;
				
			};
			
			this.getRq = function(rqType, dType, data, success_cb, error_cb ){
			
				jQuery.ajax(
					{
					 url: self.auth_url,
					 data: self.serializeFormData(data),
					 xhrFields: self.xhrFields,
					 type:rqType || "GET",
					 dataType: dType||"json",   
					 contentType: self.content_type,
    				
				     success:success_cb,
				     error:error_cb
					});
			};
		
    	}
    	
    	authAjax.prototype = authProp;