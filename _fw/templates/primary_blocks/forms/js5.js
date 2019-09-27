/*$(document).ready(function() {        
$('#set-theme-profile').submit(function(e) {
	e.preventDefault();
       // var dataForm = $(this).serialize();
        $.ajax ({
            type: 'POST',
	    data:'background_navmenu' + background_navmenu + 'image_mapknowledge' + image_mapknowledge + 'font_size' + font_size,//$(this).serialize(),
            url: 'profilechange_function.php',
            success: function (msg) {
             //   if (data == 1) {
                    alert(msg);
                    //location.reload();
                }
         //   }
        });
    });
});*/
