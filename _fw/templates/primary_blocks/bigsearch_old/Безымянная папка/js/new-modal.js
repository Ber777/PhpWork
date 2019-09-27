$(document).ready(function($){
			// bind event handlers to modal triggers
			$(document).on('click', '.trigger', function(e){
				e.preventDefault();
				$('#terms-of-service').modal().open();
			});

			// attach modal close handler
			$(document).on('click','.close', function(e){
				e.preventDefault();
				$.modal().close();
			});

			// below isn't important (demo-specific things)
			$(document).on('click','.add-more', function(e){
				e.stopPropagation();
				$('.modal .more').html(
					"<form action=\"\" method=\"post\">"+
					"<h2>Введите название нового каталога</h2>"+
					"<p><br>"+
					"<input placeholder=\"Введите название\" id=\"for-add\" type=\"text\" name=\"your-name'\" value=\"\" size=\"40\">"+
					"</p><p style=\"padding-bottom: 10px; padding-top: 20px;\">"+
					"<a href=\"#\" class=\"google-button \" id=\"add\">Добавить</a>"+
					"<a href=\"#\" class=\"google-button  cancel-more\">Отмена</a>"+
					"</p></form>");
				$('.modal .more').toggle();
				$('.buttons').hide();

			});
			$(document).on('click','.delete-more', function(e){
				e.stopPropagation();
				$('.modal .more').html("<p style=\"text-align: center; padding-bottom: 10px; font-size: 14px;\">Точно хотите удалить?</p>"+
					"<p style=\"text-align: center; padding-bottom: 10px;\">"+
					"<a href=\"#\" class=\"google-button \" id=\"delete\">Да</a>"+
					"<a href=\"#\" class=\"google-button  cancel-more\">Отмена</a>"+"</p>");
				$('.modal .more').toggle();
				$('.buttons').hide();
			});
			$(document).on('click','a.cancel-more', function(e){
				e.stopPropagation();
				$('.modal .more').css('display','none');
				$('.buttons').show();
			});
		});