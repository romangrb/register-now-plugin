	var TimeValidation = {
	  
	  constructor: function fn0(hash){
	    this._ids = hash;
	    this._ids_arr = hash.split(",");
	  },
 
	  get_fixed: function(){
	    	return this._is_fixed;
	  },
	  get_same_day: function(){
	    	return this._same_day;
	  },
	  set_fix: function(bool){
	  		if (bool===false)this._clean_data();
	    	this._is_fixed = bool;
	  },
	  set_same_day: function(bool){
			this._same_day = bool;
	  },
	  
	  fixingTime: function(){
	  		if (this.get_fixed() || !this.get_same_day()) return;
			var self = this;   
			$(this._getIdArr()).each(function(i, key){
				var sel_val = $("option:selected", key).text(),
		    		val = (/^(am|pm)/gi.test(sel_val)) ? sel_val : parseInt(sel_val, 10);
		    		self._t_o[self._name_t[i]] = val;
				});
			self._completefixingTime();
	  }
	  
	};
	function TimeProtObj(hash){
		var self = this;
		this._name_t = {0:'h',1:'m',2:'me',3:'vs_h',4:'vs_m',5:'vs_me'};
		this._ids = hash;
		this._ids_arr = null;
		this._t_o = {};
		this._ft = null;
		this._same_day = false;
		this._is_fixed = false;
		this._fixed_val= null;
		this._clean_data = function(){
			this._ft = null;
			this._fixed_val= null;
			this._t_o = {};
		},
		this._getIdArr = function(){
			return this._ids_arr;
		},
		this._getIds = function(){
			return this._ids;
		},
		
		this._compareTime = function(t, vs_t){
			return t>vs_t;
		},
		this._getValidSelection = function(o){
			// compare meridian & hour & minutes > if minutes is equal or lowest that set this equal time to start time > if o.me = am and vs_me = pm
			if (/^(am)/gi.test(o.me)&&o.me!=o.vs_me) return o;
			if (o.me==o.vs_me){
				if (this._compareTime(o.h, o.vs_h)){
		          o.h = o.vs_h;
		          return this._getValidSelection(o);
		        } else { if(this._compareTime(o.m, o.vs_m)) o.m = o.vs_m; }
		    } else {
	        o.me = o.vs_me;
	        return this._getValidSelection(o);
	    	}
		  return o;      
		},
	  
		this._toSelectFormat = function(t_o){
			for (var key in this._t_o){
			  if (!(/^(am|pm)/gi.test(t_o[key]))) t_o[key] = ((t_o[key]+'').length>1) ? t_o[key]+'' : '0'+t_o[key];
			}
			return t_o;
		},
		
		this._setTimeOption = function(){
			for (var i = 0, ln=3; i<ln; i++){
			  $('option', self._ids_arr[i]).each(function(){
			    ($(this).val()==self._ft[self._name_t[i]]) ? $(this).attr('selected','selected') : $(this).removeAttr('selected');
			  });
			}
		},
		
		this._completefixingTime = function(){
			this._ft = this._toSelectFormat(this._getValidSelection(this._t_o));
			this.set_fix(true);
			if (!!this.get_fixed()) this._setTimeOption();
			// console.log(this._ft);
			this.set_fix(false);
		};
	 
	}