(function( window, $ ) {
	'use strict';
    
    var cl_menu_name = '.rioc-slider-options-menu-item';
    
    $(cl_menu_name).on('click submit', function(e){
        // get the item id
        var item_id = $(this).index(),
            cl_sel_name_menu = 'rioc-options-menu-item-selected',
            cl_cont_name = '.rioc-slider-options-tab',
            cl_sel_cont_name = 'rioc-slider-options-tab-selected';
        // toggle menu tabs
        toggleClasses(cl_menu_name, item_id,  cl_sel_name_menu);
        // toggle content
        toggleClasses(cl_cont_name, item_id,  cl_sel_cont_name);
    });
    
    function toggleClasses (el, item_id, cl_sel_name){
        $(el).each(function(i){
            (i != item_id ) ? $(this).removeClass(cl_sel_name) : $(this).addClass(cl_sel_name);
        });
    }

})( window, jQuery );