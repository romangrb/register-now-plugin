
	'use strict';

var FieldsOperator = {
  constructor: function fn0(hash){
    this._flds_hash = hash;
  },
  get: function fn1(){
    return this._flds_hash;
  }

};
// arr_names_ch[arr], arr_names_txt[arr]
function typeFields(arr_names_ch, arr_names_txt){
    
  this._isArr = function(obj){  
    return ( Object.prototype.toString.call( obj ) === '[object Array]' )? true : false;
  },
  
  this._arr_ch = (this._isArr(arr_names_ch))? arr_names_ch : [];
  this._arr_txt= (this._isArr(arr_names_txt))? arr_names_txt : [];
  this._sep_hash = {'txt':{}, 'chk_box':{}};
  
  this._setTxtFlds = function(){
    this._separateFlds(this._arr_txt, 'txt');
    this._setFldsVl(this._sep_hash['txt']);
  },
  
  this._setChFlds = function(){
    this._separateFlds(this._arr_ch, 'chk_box');
    this._setChVl(this._sep_hash['chk_box']);
  },
  
  this._separateFlds = function(n_arr, type){
    var type_f = type || 'txt';
    for (var i=0, ln = n_arr.length; i<ln; i++){
      if (this._flds_hash[n_arr[i]] != undefined){
      	var obj = {};
      	obj[n_arr[i]]=this._flds_hash[n_arr[i]];
        this._sep_hash[type_f] = obj;
      }
    }
  }, 
  
  this._setFldsVl = function(obj){
    for (var key in obj){
      if ($('#'+key)) $('#'+key).val(obj[key]); 
    }
  },
  
  this._setChVl = function(obj){
    for (var key in obj){
      if ($('#'+key)){
        (obj[key])? $('#'+key).prop('checked', true) : $('#'+key).prop('checked', false);
      }
    }
     
  },
  
  this.setValues = function(){
    this._setTxtFlds();
    this._setChFlds();
  };
  
}

// var initObj = Object.create(FieldsOperator);
// typeFields.prototype = initObj;
// var FldsOpr = new typeFields();
// FldsOpr.constructor(2332);

