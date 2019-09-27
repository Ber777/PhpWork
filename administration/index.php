
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="ru"> <!--<![endif]-->

<head>

	<meta charset="utf-8">

	<title>Заголовок</title>
	<meta name="description" content="">

	<!--<link rel="shortcut icon" href="img/favicon/favicon.ico" type="image/x-icon">
	<link rel="apple-touch-icon" href="img/favicon/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="img/favicon/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="img/favicon/apple-touch-icon-114x114.png">-->

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<link rel="stylesheet" href="libs/bootstrap/css/bootstrap-grid.min.css">
	<link rel="stylesheet" href="libs/magnific-popup/magnific-popup.css">
	
	<link rel="stylesheet" href="css/fonts.css">
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/media.css">

	<!--<script src="libs/modernizr/modernizr.js"></script>-->

</head>

<body>
	<?require_once 'admin_functions.php';?>
	<!--<img src = "../../images/фон.jpg">-->
	<h1 align="center">Панель администратора</h1>
	
	<table cellspacing="10">
	<tr align="center"><th><font color = "#00BFFF">Управление пользователями</font color></th><th><font color = "#00BFFF">Управление группами</font color></th></tr>
	<tr><td><a href="#user-search" class="popup">Найти пользователя в системе</a></td>
	    <td><a href="#group-search" class="popup">Найти группу в системе</a></td></tr>
	<tr><td><a href="#add-user-bd" class="popup">Создать пользователя</a></td>
	    <td><a href="#add-group" class="popup">Создать группу</a></td></tr>
	<tr><td><a href="#add-userrole-bd" class="popup">Создать роль пользователю</a></td>
	    <td><a href="#del-group" class="popup">Удалить группу</a></td></tr>
	<!--<tr><td><a href="#add-userprofile-bd" class="popup">Создать профиль пользователю в БД</a></td></tr>-->
	<tr><td><a href="#add-user-group" class="popup">Добавить пользователя в группу</a></td>
	    <td><a href="#del-user-group" class="popup">Удалить пользователя из группы</a></td></tr>
	<tr><td><a href="#delete-user-bd" class="popup">Удалить пользователя</a></td></tr>
	<!--<tr><td><a href="#delete-userrole" class="popup">Удалить роль пользователя</a></td></tr>-->
	</table>

	<!-- Здесь пишем код -->
	
	<div class="hidden">
	  <form id="add-user-bd">
            <input type="text" name="username" placeholder="Имя" required/><br>
	    <input type="text" name="user_id_system" placeholder="Логин" required/><br>
	    <input type="text" name="rolpassword" placeholder="Пароль" required/><br>
	    <input class="button-add-user" type="submit" name="submit" value="Создать пользователя"/>
	    <!--<button>Создать пользователя</button>-->
	  </form>
	
	  <form id="add-userrole-bd">
	    <!--<input type="text" name="user_id_system0" placeholder="Логин" required/><br>-->
	    <!--<input type="text" name="rolpassword" placeholder="Пароль" required/><br>-->
	    <!--<input type="text" name="role_name" placeholder="Роль (например, читатель)" required/><br>-->
	    <!--<c href="#select_login" class="popup">Выберете пользователя</c>
	    <c href="#select_role" class="popup">Выберете роль</c>-->
	    <h3 align="center">Выберите пользователя:</h3>
            <?  dbconnect();
		$query = "SELECT * FROM nir.nir_user ORDER BY user_name";//тут выбираем всю информацию из таблицы в базе данных
		$result= pg_query($query) or die('Error: ' . pg_last_error());
		$myrow = pg_fetch_array($result);//всю полученную инфу помещаем в массив
		echo "<select name=users>";//выводим открывающийся тэг
		do//открываем цикл
		{
			//$username=$myrow["user_name"];//присваеваем переменной f(фамилия) - 1-ю записи из массива
			$login=$myrow["user_id_system"];
			//printf("<option>%s | %s</option>",$username,$login);
			printf("<option>%s</option>",$login);
		}
		while($myrow = pg_fetch_array($result));//здесь мы переходим на слудующую запись в базе
		echo "</select>";//выводим закрывающий тэг?>

		<h4 align="center">Выберите роль для пользователя:</h4>

		<?$query1 = "SELECT * FROM nir.nir_role";//тут выбираем всю информацию из таблицы в базе данных
		$result1= pg_query($query1) or die('Error: ' . pg_last_error());
		$myrow = pg_fetch_array($result1);//всю полученную инфу помещаем в массив
		echo "<select name=roles>";//выводим открывающийся тэг
		do//открываем цикл
		{
			$rname=$myrow["r_name"];//присваеваем переменной f(фамилия) - 1-ю записи из массива
			printf("<option>%s</option>",$rname);
		}
		while($myrow = pg_fetch_array($result1));//здесь мы переходим на слудующую запись в базе
		echo "</select>";//выводим закрывающий тэг?>
	    <input class="button-add-userrole" type="submit" name="submit10" value="Создать роль пользователю"/>
	    <!--<button>Создать роль пользователю</button>-->
	  </form>

	  <form id="add-user-group">
	    <!--<input type="text" name="user_id_system0" placeholder="Логин" required/><br>-->
	    <!--<input type="text" name="rolpassword" placeholder="Пароль" required/><br>-->
	    <!--<input type="text" name="role_name" placeholder="Роль (например, читатель)" required/><br>-->
	    <!--<c href="#select_login" class="popup">Выберете пользователя</c>
	    <c href="#select_role" class="popup">Выберете роль</c>-->
	    <h3 align="center">Выберите пользователя:</h3>
            <?  dbconnect();
		$query = "SELECT * FROM nir.nir_user ORDER BY user_id_system";//тут выбираем всю информацию из таблицы в базе данных
		$result= pg_query($query) or die('Error: ' . pg_last_error());
		$myrow = pg_fetch_array($result);//всю полученную инфу помещаем в массив
		echo "<select name=userss>";//выводим открывающийся тэг
		do//открываем цикл
		{
			//$username=$myrow["user_name"];//присваеваем переменной f(фамилия) - 1-ю записи из массива
			$login=$myrow["user_id_system"];
			//printf("<option>%s | %s</option>",$username,$login);
			printf("<option>%s</option>",$login);
		}
		while($myrow = pg_fetch_array($result));//здесь мы переходим на слудующую запись в базе
		echo "</select>";//выводим закрывающий тэг?>

		<h4 align="center">Выберите роль для добавления:</h4>

		<?$query1 = "SELECT * FROM pg_roles WHERE rolname='r_reader' or rolname='rnir'";//тут выбираем всю информацию из таблицы в базе данных
		$result1= pg_query($query1) or die('Error: ' . pg_last_error());
		$myrow = pg_fetch_array($result1);//всю полученную инфу помещаем в массив
		echo "<select name=roless>";//выводим открывающийся тэг
		do//открываем цикл
		{
			$rname=$myrow["rolname"];//присваеваем переменной f(фамилия) - 1-ю записи из массива
			printf("<option>%s</option>",$rname);
		}
		while($myrow = pg_fetch_array($result1));//здесь мы переходим на слудующую запись в базе
		echo "</select>";//выводим закрывающий тэг?>
     
	    <input class="button-add-user-group" type="submit" name="submit" value="Добавить пользователя в группу"/>
	    <!--<button>Создать роль пользователю</button>-->
	  </form>

          <form id="user-search">
	    <input type="text" name="username0" placeholder="Иванов И.И." required/><br>
	    <input class="button-all-users" type="submit" name="submitsearch" value="Найти пользователя в системе"/>
	    <!--<button>Создать роль пользователю</button>-->
	  </form>

	  <form id="group-search">
	    <input type="text" name="groupname" placeholder="Имя группы" required/><br>
	    <input class="button-all-users" type="submit" name="submitsearch" value="Найти группу в системе"/>
	  </form>

	  <form id="delete-user-bd">
            <!--<input type="text" name="user_id_system_del" placeholder="Логин" required/><br>-->
	     <h3 align="center">Выберите пользователя:</h3>
	     <?  dbconnect();
		$query = "SELECT * FROM nir.nir_user ORDER BY user_name";//тут выбираем всю информацию из таблицы в базе данных
		$result= pg_query($query) or die('Error: ' . pg_last_error());
		$myrow = pg_fetch_array($result);//всю полученную инфу помещаем в массив
		echo "<select name=user_del>";//выводим открывающийся тэг
		do//открываем цикл
		{
			//$username=$myrow["user_name"];//присваеваем переменной f(фамилия) - 1-ю записи из массива
			$login=$myrow["user_id_system"];
			printf("<option>%s</option>",$login);
		}
		while($myrow = pg_fetch_array($result));//здесь мы переходим на слудующую запись в базе
		echo "</select>";//выводим закрывающий тэг?>
	    <input class="button-delete-user" type="submit" name="submit" value="Удалить пользователя"/>
	  </form>
	
	    <!--<form id="delete-userrole">
            <input type="text" name="user_id_system1" placeholder="Логин" required/><br>
	    <input class="button-delete-userrole" type="submit" name="submit1" value="Удалить роль"/>
	    <!--<button>Создать пользователя</button>
	  </form>-->

	  <form id="del-user-group">
	    <h3 align="center">Выберите пользователя:</h3>
            <?  dbconnect();
		$query = "SELECT * FROM nir.nir_user ORDER BY user_id_system";//тут выбираем всю информацию из таблицы в базе данных
		$result= pg_query($query) or die('Error: ' . pg_last_error());
		$myrow = pg_fetch_array($result);//всю полученную инфу помещаем в массив
		echo "<select name=del-user-group>";//выводим открывающийся тэг
		do//открываем цикл
		{
			$login=$myrow["user_id_system"];
			printf("<option>%s</option>",$login);
		}
		while($myrow = pg_fetch_array($result));//здесь мы переходим на слудующую запись в базе
		echo "</select>";//выводим закрывающий тэг?>

		<h4 align="center">Выберите роль для удаления пользователя:</h4>
		<?$query1 = "SELECT * FROM pg_roles WHERE rolcanlogin = 'false'";
		$result1= pg_query($query1) or die('Error: ' . pg_last_error());
		$myrow = pg_fetch_array($result1);//всю полученную инфу помещаем в массив
		echo "<select name=group-role>";//выводим открывающийся тэг
		do//открываем цикл
		{
			$rname=$myrow["rolname"];//присваеваем переменной f(фамилия) - 1-ю записи из массива
			printf("<option>%s</option>",$rname);
		}
		while($myrow = pg_fetch_array($result1));//здесь мы переходим на слудующую запись в базе
		echo "</select>";?>
     
	    <input class="button-add-user-group" type="submit" name="submit" value="Удалить пользователя из группы"/>
	  </form>

	  <form id="add-group">
            <input type="text" name="add_group" placeholder="Название группы" required/><br>
	    <input type="text" name="rolgpassword" placeholder="Пароль для группы" required/><br>
	    <input class="button-add-user" type="submit" name="submit" value="Создать группу"/>
	  </form>

          <form id="del-group">
            <h4 align="center">Выберите группу для удаления:</h4>
		<?$query1 = "SELECT * FROM pg_roles WHERE rolcanlogin = 'false'";
		$result1= pg_query($query1) or die('Error: ' . pg_last_error());
		$myrow = pg_fetch_array($result1);//всю полученную инфу помещаем в массив
		echo "<select name=group-role-del>";//выводим открывающийся тэг
		do//открываем цикл
		{
			$rname=$myrow["rolname"];//присваеваем переменной f(фамилия) - 1-ю записи из массива
			printf("<option>%s</option>",$rname);
		}
		while($myrow = pg_fetch_array($result1));//здесь мы переходим на слудующую запись в базе
		echo "</select>";?>
	    <input class="button-delete-userrole" type="submit" name="submit" value="Удалить группу"/>
	  </form>

	</div>

	<!--<div class="loader">
		<div class="loader_inner"></div>
	</div>-->

	<!--[if lt IE 9]>
	<script src="libs/html5shiv/es5-shim.min.js"></script>
	<script src="libs/html5shiv/html5shiv.min.js"></script>
	<script src="libs/html5shiv/html5shiv-printshiv.min.js"></script>
	<script src="libs/respond/respond.min.js"></script>
	<![endif]-->

	<script src="libs/jquery/jquery-1.11.2.min.js"></script>
	<!--<script src="libs/waypoints/waypoints.min.js"></script>
	<script src="libs/animate/animate-css.js"></script>
	<script src="libs/plugins-scroll/plugins-scroll.js"></script>-->
	<script src="libs/magnific-popup/jquery.magnific-popup.min.js"></script>
	<script src="js/common.js"></script>
	
</body>
</html>
