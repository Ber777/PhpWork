 $(document).ready(function()
  {
  	$(document).on("click", "#multi-derevo li span", function(){
  		$('a.current').removeClass('current');
          var a = $('a:first',this.parentNode); // Выделяем выбранный узел
          a.toggleClass('current');// open-this');
          var li=$(this.parentNode);
          /* если это последний узел уровня, то соединительную линию к следующему
          рисовать не нужно */  
          if (!li.next().length) {
            /* берем корень разветвления <li>, в нем находим поддерево <ul>,
            выбираем прямых потомков ul > li, назначаем им класс 'last' */
            li.find('ul:first > li').addClass('last');
        }
          // анимация раскрытия узла и изменение состояния маркера
          var ul=$('ul:first',this.parentNode);// Находим поддерево
          if (ul.length) {// поддерево есть
           ul.slideToggle(300); //свернуть или развернуть
           // Меняем сосотояние маркера на закрыто/открыто
           var em=$('em:first',this.parentNode);// this = 'li span'
           em.toggleClass('open');
       }
       var currentLi = $('#multi-derevo').find('a.current').parent().parent();
       if (!currentLi.has('ul').length) {
       	$.ajax({
       		url:"php/click_table.php",
       		type: 'POST',
       		dataType: "html",
       		data:{
       			parentId : currentLi.attr('id'),
       		},
       		success: function(html){
       			currentLi.append(html);
       		},
       		complete: function(){
       			if (currentLi.has('ul').length) {
       				$("a.current").prepend('<em class="marker"></em>');
       			}
       		}
       	});
       }
   });

  	$(document).on("click", "#showdb", function(){
  		$.ajax({
  			url:"php/click_table.php",
  			type: 'POST',
  			dataType: "html",
  			data:{
  				parentId : 0,
  			},
  			success: function(html){
  				$('#multi-derevo').html(html);
  			},
  			complete: function(){}
  		});
  	});
  });