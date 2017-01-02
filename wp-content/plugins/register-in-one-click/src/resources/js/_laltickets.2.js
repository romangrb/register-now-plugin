// numeration of ids is important
var time_ids = {id:'#ticket_start_hour,#ticket_start_minute,#ticket_start_meridian,#ticket_end_hour,#ticket_end_minute,#ticket_end_meridian',
                t_o:{}
               };
	'use strict';

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
    this._is_fixed = bool;
  },
  set_same_day: function(bool){
    this._is_fixed = bool;
  },
  
  fixingTime: function(){
			if (this.get_fixed() || !this.get_same_day()) return;
			
			$(this._getIdArr()).each(function(i, key){
				var sel_val = $("option:selected", key).text(),
            val = (/^(am|pm)/gi.test(sel_val)) ? sel_val : parseInt(sel_val, 10);
            this._t_o[this._name_t[i]] = val;
			});
  }
  
  
  /*
  var fixed_val = getValidSelection(time_ids['t_o']);
			var ft = toSelectFormat(fixed_val);		
			is_fixed = true;
			setTimeOption(id_arr, ft, name_t);
			is_fixed = false; */
};
function TimeProtObj(arr_names_txt){
  this._name_t = {0:'h',1:'m',2:'me',3:'vs_h',4:'vs_m',5:'vs_me'};
  this._ids_arr = null;
  this._ids = null;
  this._t_o = {};
  this._same_day = false;
  this._is_fixed = false;
  
  this._getIdArr = function(){
    return this._ids_arr;
  },
  this._getIds = function(){
    return this._ids;
  },
  this._compareTime(t, vs_t){
    return t>vs_t;
  },
  // ------------------------   begin  ------------  end ------
	this._getValidSelection(o){
	// compare meridian & hour & minutes
  // if minutes is equal or lowest that set this equal time to start time
	// if o.me = am and vs_me = pm
			if (/^(am)/gi.test(o.me)&&o.me==o.vs_me) return o;
			       
			if (o.me==o.vs_me){
				if (compareTime(o.h, o.vs_h)){
		          o.h = o.vs_h;
		          return getValidSelection(o);
		        }else{
		          if (compareTime(o.m, o.vs_m))o.m = o.vs_m;
		        }
		    } else {
	        o.vs_me = o.me;
	        return getValidSelection(o);
	    	}
		  return o;
		        
		}
 
}

var TimeOpr = Object.create(TimeValidation);
TimeProtObj.prototype = TimeValidation;
var T = new TimeProtObj();
T.constructor(time_ids);
//T.set_fix(true);
//console.log(T._get_ids());



$(time_ids.id).on(
			'change', function(){
			if (is_fixed || !is_same_day) return;
			fixingTime(time_ids);
		});
		
	
		function setTimeOption(id, opt_v, name_t){
			for (var i = 0, ln=3; i<ln; i++){
				$('option', id[i]).each(function(){
					($(this).val()==opt_v[name_t[i]]) ? $(this).attr('selected','selected') : $(this).removeAttr('selected');
				});
			}
		}
		
		function toSelectFormat(time_o){
			for (var key in time_o){
				if (!(/^(am|pm)/gi.test(time_o[key]))) {
					time_o[key] = ((time_o[key]+'').length>1) ? time_o[key]+'' : '0'+time_o[key];
				} 
			}
			return time_o;
		}
		
		
		
		
var str = {h:12,m:1,me:'pm',vs_h:12,vs_m:1,vs_me:'am'};
console.log(getValidSelection(str));
*/