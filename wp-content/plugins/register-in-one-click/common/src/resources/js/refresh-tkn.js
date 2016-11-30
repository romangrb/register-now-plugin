	var tokenProp = {
    		auth_url : token_handler.ajax_url,
    		refresh_a_name : 'refresh_token_f_md',
    		get_a_name : 'get_token_f_md',
    	};
    	
	function Token(){
		var self = this;
		
		this.action_name = self.get_a_name;
		
		this.method = function( a_type ){
			self.action_name = (!!a_type)? a_type : self.refresh_a_name;
		}
		
		this.test = function(){
			return this.action_name;
		}
		this.post_tkn = function( token_data, success_cb, error_cb ){
		    
		     jQuery.ajax(
				{
				 url: self.auth_url,
				 data: {
				    action:     self.action_name,
				    token_hash: token_data,
				    security:   token_handler.nounce_tkn
				 },
				 type:"POST",
				 dataType: "json",   
				
			     success:success_cb,
			     error:error_cb
				});
		};
	
	}
	
	Token.prototype = tokenProp;
