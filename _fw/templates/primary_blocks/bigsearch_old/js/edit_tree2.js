  $(document).ready(function()
  {
    $(this).find('ul:first').addClass('ul-first');
    //$(this).find('ul').find('li:last').addClass('last');
    function getParameterByName(name, url) 
    {
      if (!url) url = window.location.href;
      name = name.replace(/[\[\]]/g, "\\$&");
      var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)", "i"),
      results = regex.exec(url);
      if (!results) return null;
      if (!results[2]) return '';
      return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

	   $(document).on("click", "a#add1" , function(){
      var i=0;
      var treeAfterAdd = getParameterByName('id');
      var treeEdit = $("ul.ul-first");
      if (typeof ($('#multi-derevo').find('a.current').parent().parent().attr('id')) != 'undefined')
      {
        treeEdit = $('#multi-derevo').find('a.current').parent().parent()
        treeAfterAdd = $('#multi-derevo').find('a.current').parent().parent().attr('id') 
        i=1;
      }
      $.ajax({
        url: "/php/add_data.php",
        type : 'POST',
        dataType: "html",
        data:{
         parentId : treeAfterAdd,
         newName : $('#for-add').val()
       },
       success: function(html){
         if (html=="five")
         {
          alert('В документ нельзя добавлять каталоги')
        }
        else if (html=="falseName") {
          alert('Такой каталог уже существует. Введите другое имя')
        }
        else if (html=='nullName')
        {
          alert('Вы ничего не ввели')
        }
        else {
          if (!treeEdit.has('ul').length)
          {
            if(i==0) {
              treeEdit.append(html);
            }
            else{ 
              $("a.current").prepend('<em class="marker open"></em>');
              treeEdit.append("<ul style=\"display:block;\">"+html+"</ul>");
              if (!treeEdit.next().length) {
                treeEdit.find('ul:first > li').addClass('last');
              }
            }
          }

          else
          {
            treeEdit.find('ul:first').append(html)
          }
        }

      }
    })
    });

	 $(document).on("click", "a#add" , function(){
      var i=0;
      var treeAfterAdd = getParameterByName('id');
      var treeEdit = $("ul.ul-first");
      if (typeof ($('#multi-derevo').find('a.current').parent().parent().attr('id')) != 'undefined')
      {
        treeEdit = $('#multi-derevo').find('a.current').parent().parent()
        treeAfterAdd = $('#multi-derevo').find('a.current').parent().parent().attr('id') 
        i=1;
      }
      $.ajax({
        url: "/php/add_data.php",
        type : 'POST',
        dataType: "html",
        data:{
         parentId : treeAfterAdd,
         newName : $('#for-add').val()
       },
       success: function(html){
         if (html=="five")
         {
          alert('В документ нельзя добавлять каталоги')
        }
        else if (html=="falseName") {
          alert('Такой каталог уже существует. Введите другое имя')
        }
        else if (html=='nullName')
        {
          alert('Вы ничего не ввели')
        }
        else {
          if (!treeEdit.has('ul').length)
          {
            if(i==0) {
              treeEdit.append(html);
            }
            else{ 
              $("a.current").prepend('<em class="marker open"></em>');
              treeEdit.append("<ul style=\"display:block;\">"+html+"</ul>");
              if (!treeEdit.next().length) {
                treeEdit.find('ul:first > li').addClass('last');
              }
            }
          }

          else
          {
            treeEdit.find('ul:first').append(html)
          }
        }

      }
    })
    });
    
    /*$(document).on("click", "a#add" , function(){
      var i=0;
      var treeAfterAdd = getParameterByName('id');
      var treeEdit = $("ul.ul-first");
      if (typeof ($('#multi-derevo').find('a.current').parent().parent().attr('id')) != 'undefined')
      {
        treeEdit = $('#multi-derevo').find('a.current').parent().parent()
        treeAfterAdd = $('#multi-derevo').find('a.current').parent().parent().attr('id') 
        i=1;
      }
      $.ajax({
        url: "/php/add_data.php",
        type : 'POST',
        dataType: "html",
        data:{
         parentId : treeAfterAdd,
         newName : $('#for-add').val(),
       },success : function(html){
	   if (html=="five")
           {
          	alert('В документ нельзя добавлять каталоги')
           }
           	else if (html=="falseName") 
	   	{
          		alert('Такой каталог уже существует. Введите другое имя')
          	 }
           	else if (html=='nullName')
           	{
         	 	alert('Вы ничего не ввели')
           	}
          else 
	  {
          	if (!treeEdit.has('ul').length)
          	{
            		if(i==0) 
			{
              		treeEdit.append(html);
            		}
            		else
			{ 
              			$("a.current").prepend('<em class="marker open"></em>');
              			treeEdit.append("<ul style=\"display:block;\">"+html+"</ul>");
              			if (!treeEdit.next().length) 
				{
               			treeEdit.find('ul:first > li').addClass('last');
              			}
          		}
         	 }

          	 else
         	 {
            		treeEdit.find('ul:first').append(html)
          	 }
          }
	}
	});
	});*/
       //success: function(html){
         /*if (html=="five")
         {
          alert('В документ нельзя добавлять каталоги')
        }
        else if (html=="falseName") {
          alert('Такой каталог уже существует. Введите другое имя')
        }
        else if (html=='nullName')
        {
          alert('Вы ничего не ввели')
        }
        else {
          if (!treeEdit.has('ul').length)
          {
            if(i==0) {
              treeEdit.append(html);
            }
            else{ 
              $("a.current").prepend('<em class="marker open"></em>');
              treeEdit.append("<ul style=\"display:block;\">"+html+"</ul>");
              if (!treeEdit.next().length) {
                treeEdit.find('ul:first > li').addClass('last');
              }
            }
          }

          else
          {
            treeEdit.find('ul:first').append(html)
          }
        }

     // }
    })
    });*/

    $(document).on("click", "a#delete", function(){
      if (typeof ($('#multi-derevo').find('a.current').parent().parent().attr('id')) != 'undefined')
      {
       var treeAfterDelete = $('#multi-derevo').find('a.current').parent().parent()
       $.ajax({
        url:"php/delete_data.php",
        type: "POST",
        dataType: "html",
        data:{
         objId : treeAfterDelete.attr('id')
       },
       success:function(html){


         if(treeAfterDelete.parent().find('li').size() == 1)
         {
          treeAfterDelete.parent().parent().find('em:first').remove();
          treeAfterDelete.parent().remove();
        }
        else $("#"+treeAfterDelete.attr('id')).remove();
      }
    });
     }
     else {
       alert("Вы не выбрали каталог или документ");
     }
   });

    $(document).on("click", "a#apply", function(){
      if (typeof ($('#multi-derevo').find('a.current').parent().parent().attr('id')) != 'undefined')
      {
        var a = $('#multi-derevo').find('a.current').parent().parent().attr('id');
        $(this).attr("target","_blank")
        $(this).attr("href","http://astral.bmstu.ru/views/cattemplate/tree_edit.php?view=catalogoftemplate&id="+a)
      }
      else {
        alert("Вы не выбрали каталог или документ");
      }
    })
  });
