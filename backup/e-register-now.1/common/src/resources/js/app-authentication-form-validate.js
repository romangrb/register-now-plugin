jQuery( document ).ready( function($) {
    
    $.validate({
        form : '#auth_form',
        modules : 'date, security',
        onError : function($form) {
          console.warn('Validation of form  failed!');
        },
        onSuccess : function($form) {
          console.info('The form  is valid!');
         
        //   return false; // Will stop the submission of the form
        },
    });
    
});