jQuery( document ).ready( function($) {

var SyncInterface = {
	  
	constructor: function fn0( action_name, nounce_tkn, success_cb, error_cb ){
		if (!action_name || !nounce_tkn) return 'object could not been initialized, please provide required properties - action_name, nounce_tkn';
		
		this.__ajax_prop['data']['action'] = action_name;
		this.__ajax_prop['data']['security'] = nounce_tkn;
		if ({}.toString.call(success_cb)==='[object Function]') this.__ajax_prop['success'] = success_cb;
		if ({}.toString.call(error_cb)==='[object Function]')   this.__ajax_prop['error'] = error_cb;
	  },
	  
	  get: function(){
		var ajaxObj = this.__getAjaxProp('get');
		return ajaxObj;
	  },
 
	  create: function(data){
		var ajaxObj = this.__getAjaxProp('create');
		ajaxObj.data = {'token_hash':data};
		return ajaxObj;
	  },
	  
	  update: function(data){
		var ajaxObj = this.__getAjaxProp('update');
		ajaxObj.data = {'token_hash':data};
		return ajaxObj;
	  },
	  
	  delete: function(data){
	  	var ajaxObj = this.__getAjaxProp('delete');
		ajaxObj.data = {'token_hash':data};
		return ajaxObj;
	  },

	};
	
	function SyncProp(hash){
		
		var self = this;
		
		this.__success_cb = function(data){
			console.info('defoult Sync respond \n', data);
		};
		this.__error_cb = function(jqXHR, textStatus, errorThrown){
			console.error('defoult Sync respond \n', jqXHR, textStatus, errorThrown);
		};
		
		this.__content_type = (window.XDomainRequest)? "text/plain" : "application/x-www-form-urlencoded; charset=utf-8";
		
		this.__ajax_prop = {
			'success':self.__success_cb,
			'error':self.__error_cb,
			'content_type':self.__content_type,
			'dataType': "json",
    		'data': {'action':null, 'security':null}
		},
		
		this.__getAjaxProp = function(mth){
			switch (mth) {
				case 'create':
					this.__ajax_prop.url = sync_data.ajax_url;
					this.__ajax_prop.type = 'PUSH';
					break;
				case 'update':
					this.__ajax_prop.url = sync_data.ajax_url;
					this.__ajax_prop.type = 'POST';
					break;
				case 'delete':
					this.__ajax_prop.url = sync_data.ajax_url;
					this.__ajax_prop.type = 'DELETE';
					break;
				default:
					this.__ajax_prop.url = sync_data.ajax_url;
					this.__ajax_prop.type = 'GET';
			}
			return  this.__ajax_prop;
		};
		
	}
	
	// var SyncObj = Object.create(SyncInterface);
	// SyncProp.prototype = SyncObj;
	// var Sync = new SyncProp();

	// Sync.constructor('sync_data.action', sync_data.nounce_tkn);
	
		
	function success_cb (response){
		console.info('defoult Sync respond \n', response);
		if (response&&response['data']&&response['data']['html']) {
		$( 'td.ticket_list_container' ).empty().html( response.data.html );
		
		}
			
	}
	function error_cb (jqXHR, textStatus, errorThrown){
		console.error('defoult Sync respond \n', jqXHR, textStatus, errorThrown);
	}
	
	$('#test').on('click', function(){
		// $.ajax(Sync.get({}));
		$.ajax({
				 url: sync_data.ajax_url,
				 data: {
				    action:  'sync_action_cb',
				    security:   sync_data.nounce
				 },
				 type:"POST",
				 dataType: "json",   
				 
			     success:success_cb,
			     error:error_cb
		});
		
		// console.log('rtest');
	});
	
});

	
	
	