	var tokenProp = {
    		auth_url : token_handler.ajax_url,
    		action : 'refresh_token_f_md',
    	};
    	
	function Token(){
		var self = this;
		
		this.refresh_db = function( token_data, success_cb, error_cb ){
		    
		     jQuery.ajax(
				{
				 url: self.auth_url,
				 data: {
				    action:     self.action,
				    token_hash: token_data
				 },
				 type:"POST",
				 dataType: "json",   
				
			     success:success_cb,
			     error:error_cb
				});
		};
	
	}
	
	Token.prototype = tokenProp;
