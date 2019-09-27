$(document).ready(function() {
	$(".popup").magnificPopup();

        $("#set-theme-profile").submit(function() {
        $.ajax ({
            type: "POST",
	    url: "../../_fw/templates/primary_blocks/forms/profilechange_function.php",
	    data: $(this).serialize(),
            success: function (msg) {
                    alert(msg);
		    setTimeout(function() {
		         $.magnificPopup.close();
			}, 1000);
                    location.reload();
                }
        });
		return false;
    });
	//E-mail Ajax Send
	//Documentation & Example: https://github.com/agragregra/uniMail
	$("#add-user-bd").submit(function() { //Change
		$.ajax({
			type: "POST",
			url: "admin_functions.php", //Change
			data: $(this).serialize(),
			success: function(msg){
			alert(msg);
		//}).done(function() {
			setTimeout(function() {
			  $.magnificPopup.close();
			}, 1000);
			location.reload();
		}//
		});
		return false;
	});

	$("#add-userrole-bd").submit(function() { //Change
		$.ajax({
			type: "POST",
			url: "admin_functions.php", //Change
			data: $(this).serialize(),
			success: function(msg){
		//}).done(function() {
			alert(msg);
			setTimeout(function() {
			  $.magnificPopup.close();
			}, 1000);
		}
		});
		return false;
	});

	$("#add-group").submit(function() { //Change
		$.ajax({
			type: "POST",
			url: "admin_functions.php", //Change
			data: $(this).serialize(),
			success: function(msg){
			alert(msg);
		//}).done(function() {
			setTimeout(function() {
			  $.magnificPopup.close();
			}, 1000);
			location.reload();
		}//
		});
		return false;
	});

	$("#user-search").submit(function() { //Change
		$.ajax({
			type: "POST",
			url: "admin_functions.php", //Change
			data: $(this).serialize(),
		//}).done(function() {
			success: function(msg){		
			alert(msg);
			//alert("Пользователь найден в БД");
			setTimeout(function() {
			  $.magnificPopup.close();
			}, 1000);
		}
		});
		return false;
	});

	$("#group-search").submit(function() { //Change
		$.ajax({
			type: "POST",
			url: "admin_functions.php", //Change
			data: $(this).serialize(),
		//}).done(function() {
			success: function(msg){		
			alert(msg);
			//alert("Пользователь найден в БД");
			setTimeout(function() {
			  $.magnificPopup.close();
			}, 1000);
		}
		});
		return false;
	});

	$("#del-group").submit(function() { //Change
		$.ajax({
			type: "POST",
			url: "admin_functions.php", //Change
			data: $(this).serialize(),
			success: function(msg){
			alert(msg);
		//}).done(function() {
			setTimeout(function() {
			  $.magnificPopup.close();
			}, 1000);
		}//
		});
		return false;
	});

	$("#delete-user-bd").submit(function() { //Change
		$.ajax({
			type: "POST",
			url: "admin_functions.php", //Change
			data: $(this).serialize(),
		//}).done(function() {
			success: function(msg){
			alert(msg);
			setTimeout(function() {
			  $.magnificPopup.close();
			}, 1000);
		}
		});
		return false;
	});
	
	$("#add-user-group").submit(function() { //Change
		$.ajax({
			type: "POST",
			url: "admin_functions.php", //Change
			data: $(this).serialize(),
		//}).done(function() {
			success: function(msg){
			alert(msg);
			setTimeout(function() {
			  $.magnificPopup.close();
			}, 1000);
		}
		});
		return false;
	});

	$("#del-user-group").submit(function() { //Change
		$.ajax({
			type: "POST",
			url: "admin_functions.php", //Change
			data: $(this).serialize(),
		//}).done(function() {
			success: function(msg){
			alert(msg);
			setTimeout(function() {
			  $.magnificPopup.close();
			}, 1000);
		}
		});
		return false;
	});

	$("#delete-userrole").submit(function() { //Change
		$.ajax({
			type: "POST",
			url: "admin_functions.php", //Change
			data: $(this).serialize(),
		//}).done(function() {
			success: function(msg){
			alert(msg);
			setTimeout(function() {
			  $.magnificPopup.close();
			}, 1000);
		}
		});
		return false;
	});


});


