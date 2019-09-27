 $(document).ready(function()
  {
  	$(document).on("click", "#multi-derevo li span", function(){
       var currentLi = $('#multi-derevo').find('a.current').parent().parent();
       if (!currentLi.has('ul').length) {
       	$.ajax({
       		url:"/php/click_table.php",
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
  			url:"/php/click_table.php",
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
