$(document).ready(function() {
	$(".add").magnificPopup();
	//E-mail Ajax Send
	//Documentation & Example: https://github.com/agragregra/uniMail
	$("#add").submit(function() { //Change
		$.ajax({
			type: "POST",
			url: "../php/add_data.php", //Change
			data: $(this).serialize()
		}).done(function() {
			alert("Роль пользователя создана в БД");
			setTimeout(function() {
			  $.magnificPopup.close();
			}, 1000);
		});
		return false;
	});

	$("#add-userrole-bd").submit(function() { //Change
		$.ajax({
			type: "POST",
			url: "admin_functions.php", //Change
			data: $(this).serialize()
		}).done(function() {
			alert("Роль пользователя создана в БД");
			setTimeout(function() {
			  $.magnificPopup.close();
			}, 1000);
		});
		return false;
	});

	$("#add-userprofile-bd").submit(function() { //Change
		$.ajax({
			type: "POST",
			url: "admin_functions.php", //Change
			data: $(this).serialize()
		}).done(function() {
			alert("Профиль пользователя создан в БД");
			setTimeout(function() {
			  $.magnificPopup.close();
			}, 1000);
		});
		return false;
	});

	$("#user-search").submit(function() { //Change
		$.ajax({
			type: "POST",
			url: "admin_functions.php", //Change
			data: $(this).serialize()
		}).done(function() {
			alert("Пользователь найден в БД");
			setTimeout(function() {
			  $.magnificPopup.close();
			}, 1000);
		});
		return false;
	});

	$("#delete-user-bd").submit(function() { //Change
		$.ajax({
			type: "POST",
			url: "admin_functions.php", //Change
			data: $(this).serialize()
		}).done(function() {
			alert("Пользователь удален");
			setTimeout(function() {
			  $.magnificPopup.close();
			}, 1000);
		});
		return false;
	});
	
	$("#add-user-group").submit(function() { //Change
		$.ajax({
			type: "POST",
			url: "admin_functions.php", //Change
			data: $(this).serialize()
		}).done(function() {
			alert("Пользователь добавлен в группу");
			setTimeout(function() {
			  $.magnificPopup.close();
			}, 1000);
		});
		return false;
	});

});


