jQuery(document).ready(function($) {
	var data = {
		action: 'my_action',
		whatever: 1234
	};
    /*javascript переменная ajaxurl определена глобально на всех страницах админ-панели. 
    Используйте её в js файлах, как ссылку на файл обработчик запроса, обычно это файл: /wp-admin/admin-ajax.php*/
	jQuery.post( ajaxurl, data, function(response) {
		console.log('Получено с сервера: ' + response);
	});
});