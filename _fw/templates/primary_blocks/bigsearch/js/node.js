  $(document).ready(function()
  {
    $(document).on("click", "#showdb", function(){
      $.ajax({
        url:"php/all_with_null_database.php",
        type: 'POST',
        dataType: "html",
        success: function(html){
          $('#multi-derevo').html(html);
        },
        complete: function(){
          $('#multi-derevo li:has("ul")').find('a:first').prepend('<em class="marker"></em>');
        }
      });
    });
  });