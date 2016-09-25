jQuery( document ).ready( function($) {

    $.validate({
        modules : 'date, security'
    });
    
    $(document)
      .on('click',  '#reset', reset_trigger)
      .on('keypress', this,  reset_trigger);
    
    function reset_trigger(e){
        if (e.which=='13' || e.type=='click'){
            reset_form($('#auth_form')); // by id
        }
    }
    
    function reset_form($form) {
        $form.find('input:text, input:file, select, textarea').val('');
        $form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
    } 

} );