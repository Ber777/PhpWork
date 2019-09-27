<? 
function dbconnect()
{
    // Параметры для подключения
    $host = 'localhost'; 
    $db_user = $_SERVER['PHP_AUTH_USER'];// Логин БД
    $db_password = $_SERVER['PHP_AUTH_PW']; // Пароль БД
    $db_name = 'xgb_nir';
     
    // Подключение к базе данных
    $dbconn = pg_connect("host=$host dbname=$db_name user=$db_user password=$db_password") OR DIE ("Не могу создать соединение.");
}

function crypt_apr1_md5($plainpasswd)
{
    $salt = substr(str_shuffle("./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);
    $len = strlen($plainpasswd);
    $text = $plainpasswd . '$apr1$' . $salt;
    $bin = pack("H32", md5($plainpasswd . $salt . $plainpasswd));
    for ($i = $len; $i > 0; $i -= 16) {
        $text .= substr($bin, 0, min(16, $i));
    }
    for ($i = $len; $i > 0; $i >>= 1) {
        $text .= $i & 1 ? chr(0) : $plainpasswd[0];
    }
    $bin = pack("H32", md5($text));
    for ($i = 0; $i < 1000; $i++) {
        $new = $i & 1 ? $plainpasswd : $bin;
        if ($i % 3) {
            $new .= $salt;
        }
        if ($i % 7) {
            $new .= $plainpasswd;
        }
        $new .= $i & 1 ? $bin : $plainpasswd;
        $bin = pack("H32", md5($new));
    }
    for ($i = 0; $i < 5; $i++) {
        $k = $i + 6;
        $j = $i + 12;
        if ($j == 16) {
            $j = 5;
        }
        $tmp = $bin[$i] . $bin[$k] . $bin[$j] . $tmp;
    }
    $tmp = chr(0) . chr(0) . $bin[11] . $tmp;
    $tmp = strtr(strrev(substr(base64_encode($tmp), 2)), "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/", "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");
    $tmp .= "\n";
    return "\$" . "apr1" . "\$" . $salt . "\$" . $tmp;
}

    if (isset($_POST['rolpassword']) && isset($_POST['username']) && isset($_POST['user_id_system']))
    {
    // Переменные с формы
    $username = $_POST['username'];
    $user_id_system = $_POST['user_id_system']; 
    $rolpassword = $_POST['rolpassword'];

    // Переменные для обращения к ролям в БД
    //$rolname = $username;
    //$rolcanlogin;
    $rolsuper = 'NOSUPERUSER';
    $rolcreatedb = 'NOCREATEDB';
    $rolcreaterole = 'NOCREATEROLE';
    $rolinherit = 'NOINHERIT';
    $rolreplication = 'NOREPLICATION';
    //$rolconnlimit = -1;
    //$rolpassword;
    //$rolvaliduntil = false;
    //$oid;

    $str = 'Профиль пользователя ';
    $str.=$username;
     
    // Подключение к базе данных
    dbconnect();

    $query = "SELECT nir.add_user('$user_id_system','$username')";
    $result = pg_query($query) or die('Error: ' . pg_last_error());
    $id = pg_fetch_row($result);
    if($id[0] == -2)
    {
	echo "Логин пользователя совпадает с ролью в системе, пожалуйста, придумайте другой логин.";
	die();
    }
    
    else if($id[0] == -1)
    {
	echo "Пользователь c логином '$user_id_system' уже существует, пожалуйста, придумайте другой логин.\nИли же удалите этого пользователя и создайте корректно заново.";
	die();
    }

    else
    {

    $query_id = "SELECT user_id FROM nir.nir_user WHERE user_id_system = '$user_id_system'";
    $result_id = pg_query($query_id) or die('Error: ' . pg_last_error());
    while ($line = pg_fetch_array($result_id, null, PGSQL_ASSOC)) {
    foreach ($line as $col_value) {}}

    //Создание профиля по умолчанию.
    $query_prof = "SELECT nir.addprofilechar1('$str', $col_value)";
    $result_prof = pg_query($query_prof) or die("Профиль пользователя не был создан, попробуйте заново создать пользователя.");
   
    $query_reg = "CREATE ROLE $user_id_system LOGIN $rolsuper $rolinherit $rolcreatedb $rolcreaterole $rolreplication PASSWORD '$rolpassword' VALID UNTIL 'INFINITY'";
    $result_reg = pg_query($query_reg) or die('Error: ' . pg_last_error());//"Учетная запись для пользователя '$user_id_system' уже существует.\nУдалите этого пользователя и создайте корректно заново."
    
    $password = $rolpassword;
    $encryptpassword =  crypt_apr1_md5($password);
    
    /*$login = $_POST['user_id_system'];
    $password = $_POST['rolpassword'];
    $encpassword = crypt_apr1_md5($password);
    $path_to_passwd = '.htpasswd';
    $passstring = $login . ':' . $encpassword;
    $passfile = fopen($path_to_passwd, 'a');
    fputs($passfile, $passstring);
    fclose($passfile);*/

    file_put_contents('.htpasswd', $user_id_system.':'.$encryptpassword, FILE_APPEND) or die("Учетная запись на сервере не была создана проверьте правильность ввода информации.");  

    echo "Учетная запись для пользователя '$user_id_system' создана.";
    }

    pg_free_result($result_reg);
    pg_free_result($result);
    pg_close($dconnb);
}
   
    if(isset($_POST['user_del']))
    {
         $user_id_system = $_POST['user_del']; 
	 $str = 'Профиль пользователя ';
         
     
         // Параметры для подключения
    	 dbconnect();

         $query_username = "SELECT user_name FROM nir.nir_user WHERE user_id_system='$user_id_system'";
	 $result_username = pg_query($query_username) or die('Error: ' . pg_last_error());
	 while ($line = pg_fetch_array($result_username, null, PGSQL_ASSOC)) {
    	 foreach ($line as $col_value) {}}
	 $username = $col_value;
   	 $str.=$col_value;
	 $query_userprof="SELECT o_id FROM nir.nir_object WHERE o_name = '$str'";
	 $result_userprof = pg_query($query_userprof) or die('Error: ' . pg_last_error());
	 while ($line = pg_fetch_array($result_userprof, null, PGSQL_ASSOC)) {
    	 foreach ($line as $col_value_prof) {}}
         $id = $col_value_prof;
	 $query_profdel="SELECT nir.dropprofile($id)";
	 $result_profdel = pg_query($query_profdel) or die("Не удалось удалить профиль пользователя '$username', а соответственно и пользователя. Попробуйте еще раз.");
	     
    	 $query = "SELECT nir.drop_user('$user_id_system')";
         $result = pg_query($query) or die('Error: ' . pg_last_error());
         $id = pg_fetch_row($result);
         if($id[0] == -2)
    	 {
	     echo "Логин пользователя совпадает с ролью в системе, данный логин нельзя удалить, пожалуйста, введите другой";
    	 }
    
    	 else if($id[0] == -1)
    	 {
	     echo "Пользователь с таким логином не существует, пожалуйста, введите другой логин";
    	 }

    	 else 
    	 {
	     $query_delrole = "DROP ROLE $user_id_system";
             $result_delrole = pg_query($query_delrole) or die("Роль '$user_id_system' не удалена, поскольку ее не существует");
	     
	     /*$query_delrole = "DROP ROLE $user_id_system"; DELETE HTPASSWF ????
    $result_delrole = pg_query($query_delrole) or die("Роль '$user_id_system' не удалена, поскольку ее не существует");
	     $dir = '.htpasswd';
	     $contents = file_get_contents($dir);
		$contents = str_replace($user_id_system, '', $contents);
		file_put_contents($dir, $contents);*/
	     echo "Пользователь удален.\nНе забудьте удалить строку информации о пользователе '$user_id_system' ('$col_value') в файле .htpasswd.";
    	 }

    pg_free_result($result);
    pg_close($dconnb);
}


    if (isset($_POST['users']) && isset($_POST['roles']))
    //if(isset($_POST['submit10']))
    {
    // Переменные с формы
    $user_id_system = $_POST['users']; 
    $user_role = $_POST['roles'];

    // Переменные для обращения к ролям в БД
    //$rolname = $username;
    //$rolcanlogin;
    $rolsuper = 'NOSUPERUSER';
    $rolcreatedb = 'NOCREATEDB';
    $rolcreaterole = 'NOCREATEROLE';
    $rolinherit = 'INHERIT';
    $rolreplication = 'NOREPLICATION';
    //$rolconnlimit = -1;
    //$rolpassword;
    //$rolvaliduntil = false;
    //$oid;
     
    // Подключение к базе данных
    dbconnect();
    $query_id = "SELECT user_id FROM nir.nir_user WHERE user_id_system = '$user_id_system'";
    $result_id = pg_query($query_id) or die('Error: ' . pg_last_error());
    while ($line = pg_fetch_array($result_id, null, PGSQL_ASSOC)) {
    foreach ($line as $col_value) {}}

    $query_rol = "SELECT r_id FROM nir.nir_role WHERE r_name = '$user_role'";
    $result_rol = pg_query($query_rol) or die('Error: ' . pg_last_error());
    while ($line1 = pg_fetch_array($result_rol, null, PGSQL_ASSOC)) {
    foreach ($line1 as $col_value_rol) {}}
    /*else{
        echo "Невозможно создать роль, пожалуйста, выберите одну из этих ролей: администратор, руководитель, редактор, научный работник, читатель\n\n"; die();}*/
    $query_addrole_id = "SELECT nir.addtouserrole($col_value,$col_value_rol)";
    $result_addrole_id = pg_query($query_addrole_id) or die("Роль невозможно создать, поскольку не существует пользователя с логином '$user_id_system' или же роль была уже создана для пользователя '$user_id_system'.\nУбедитесь, что пользователь с логином '$user_id_system' существует, или что роль уже не была создана для этого пользователя");
   
    echo "Роль для пользователя '$user_id_system' создана.";
}


    if (isset($_POST['user_id_system1']))
    {
    // Переменные с формы
    $user_id_system = $_POST['user_id_system1']; 
     
    // Параметры для подключения
    $host = 'localhost'; 
    $db_user = $_SERVER['PHP_AUTH_USER'];// Логин БД
    $db_password = $_SERVER['PHP_AUTH_PW']; // Пароль БД
    $db_name = 'xgb_nir';
     
    // Подключение к базе данных
    $dbconn = pg_connect("host=$host dbname=$db_name user=$db_user password=$db_password") OR DIE ("Не могу создать соединение.");
    $query_addrole = "DROP ROLE $user_id_system";
    $result_addrole = pg_query($query_addrole) or die("Роль '$user_id_system' не удалена, поскольку ее не существует");
    $query_id = "SELECT user_id FROM nir.nir_user WHERE user_id_system = '$user_id_system'";
    $result_id = pg_query($query_id) or die('Error: ' . pg_last_error());
    while ($line = pg_fetch_array($result_id, null, PGSQL_ASSOC)) {
    foreach ($line as $col_value) {}}
    $query_addrole_id = "SELECT nir.dropuserrole($col_value)";
    $result_addrole_id = pg_query($query_addrole_id) or die("Роли с именем '$user_id_system' не существует или же пользователь был удален ранее.\nРоль для пользователя удалена '$user_id_system'");
    echo "Роль успешно удалена";
    //$query_addrole = "DROP ROLE $user_id_system";
    //$result_addrole = pg_query($query_addrole) or die("Роль '$user_id_system' не удалена");
    //if(!(pg_last_error()))
    //{
        //echo "Роль успешно удалена";
    //}

    //else
    //    echo "Роль '$user_id_system' не удалена1";
}

    if(isset($_POST['username0']))
    {
		$username = $_POST['username0'];
		dbconnect();
	        $query = "SELECT * FROM nir.nir_user WHERE user_name = '$username'";
		$result = pg_query($query) or die("Пользователь не найден в БД.");
		while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    	        foreach ($line as $col_value) {}}
		if($col_value > 0)
 		{
		echo "Пользователь найден в БД.\n";
		$query = "SELECT * FROM nir.nir_user WHERE user_name = '$username'";
		$result = pg_query($query) or die("Пользователь не найден в БД.");
		$id = pg_fetch_row($result);
		echo "Id в системе = ";echo $id[0];
		echo "\nИмя в системе: ";echo $id[1];
		echo "\nЛогин в системе: ";echo $id[2];
		//while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    	        //foreach ($line as $col_value) {echo $col_value;echo"\n";}}
		}
		else{
		echo "Пользователь не найден в БД.";}
    }

    if(isset($_POST['groupname']))
    {
		$groupname = $_POST['groupname'];
		dbconnect();
	        $query = "SELECT * FROM pg_roles WHERE rolcanlogin = 'false' and rolname = '$groupname'";
		$result = pg_query($query) or die("Группа не найдена в БД.");
		
		while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    	        foreach ($line as $col_value) {}}
		if($col_value > 0)
 		{
		echo "Группа найдена в БД.";}
		/*$query = "SELECT * FROM nir.nir_user WHERE user_name = '$username'";
		$result = pg_query($query) or die("Пользователь не найден в БД.");
		$id = pg_fetch_row($result);
		echo "Id в системе = ";echo $id[0];
		echo "\nИмя в системе: ";echo $id[1];
		echo "\nЛогин в системе: ";echo $id[2];
		//while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    	        //foreach ($line as $col_value) {echo $col_value;echo"\n";}}
		}*/
		else{
		echo "Группа не найдена в БД.";}
    }
    

    if (isset($_POST['userss']) && isset($_POST['roless']))
    {
    // Переменные с формы
    $user_id_system = $_POST['userss']; 
    $user_role = $_POST['roless'];
     
    // Подключение к базе данных
    dbconnect();
    $query10="GRANT $user_role TO $user_id_system";
    $result10 = pg_query($query10) or die('Error: ' . pg_last_error());
   
    echo "Роль для пользователя '$user_id_system' создана.";
    }

    if (isset($_POST['del-user-group']) && isset($_POST['group-role']))
    {
    // Переменные с формы
    $user_id_system = $_POST['del-user-group']; 
    $user_role = $_POST['group-role'];
     
    // Подключение к базе данных
    dbconnect();
    $query10="REVOKE $user_role FROM $user_id_system";
    $result10 = pg_query($query10) or die('Error: ' . pg_last_error());
   
    echo "Пользователя '$user_id_system' был исключен из группы '$user_role'.";
}

    if (isset($_POST['add_group']) && isset($_POST['rolgpassword']))
    {
    // Переменные с формы
    $user_role = $_POST['add_group'];
    $rolgpassword = $_POST['rolgpassword'];

    // Переменные для обращения к ролям в БД
    $rolsuper = 'NOSUPERUSER';
    $rolcreatedb = 'NOCREATEDB';
    $rolcreaterole = 'NOCREATEROLE';
    $rolinherit = 'NOINHERIT';
    $rolreplication = 'NOREPLICATION';
     
    // Подключение к базе данных
    dbconnect();
    $query="CREATE ROLE $user_role NOLOGIN $rolsuper $rolinherit $rolcreatedb $rolcreaterole $rolreplication PASSWORD '$rolgpassword' VALID UNTIL 'INFINITY'";
    $result = pg_query($query) or die("Не удалось добавить роль '$user_role'");
   
    echo "Группа '$user_role' успешно добавлена.";
    }

    if (isset($_POST['group-role-del']))
    {
    // Переменные с формы
    $user_role = $_POST['group-role-del'];
     
    // Подключение к базе данных
    dbconnect();
    $query="DROP ROLE $user_role";
    $result = pg_query($query) or die('Error: ' . pg_last_error());
   
    echo "Группа '$user_role' успешно удалена.";
    }

?>
