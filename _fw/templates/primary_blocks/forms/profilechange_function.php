<?
     if(isset($_POST['background_navmenu']) || isset($_POST['image_mapknowledge']) || isset($_POST['font_size'])){
	// Переменные с формы
        $background_navmenu = $_POST['background_navmenu'];
        $image_mapknowledge = $_POST['image_mapknowledge']; 
        $font_size = $_POST['font_size'];

        // Параметры для подключения
        $host = 'localhost'; 
        $db_user = $_SERVER['PHP_AUTH_USER'];// Логин БД
        $db_password = $_SERVER['PHP_AUTH_PW']; // Пароль БД
        $db_name = 'xgb_nir';

	// Подключение к базе данных
        $dbconn = pg_connect("host=$host dbname=$db_name user=$db_user password=$db_password") OR DIE ("Не могу создать соединение.");
     
	$query = "SELECT user_id_object FROM nir.nir_user WHERE user_id_system='$db_user'";
	$result = pg_query($query) or die('Error: ' . pg_last_error());
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
   	foreach ($line as $col_value) {}}
	$query11 = "SELECT profile_id FROM nir.user_profile_view WHERE user_id=".$col_value;
	$result11 = pg_query($query11) or die('Error: ' . pg_last_error());
	while ($line = pg_fetch_array($result11, null, PGSQL_ASSOC)) {
   	foreach ($line as $col_value11) {}}

	$query01 = "SELECT o_id FROM nir.nir_object WHERE o_name='background_navmenu'";
	$result01 = pg_query($query01) or die('Error: ' . pg_last_error());
	while ($line = pg_fetch_array($result01, null, PGSQL_ASSOC)) {
   	foreach ($line as $col_value01) {}}
	$query02 = "SELECT o_id FROM nir.nir_object WHERE o_name='image_mapknowledge'";
	$result02 = pg_query($query02) or die('Error: ' . pg_last_error());
	while ($line = pg_fetch_array($result02, null, PGSQL_ASSOC)) {
   	foreach ($line as $col_value02) {}}
	$query03 = "SELECT o_id FROM nir.nir_object WHERE o_name='font_size'";
	$result03 = pg_query($query03) or die('Error: ' . pg_last_error());
	while ($line = pg_fetch_array($result03, null, PGSQL_ASSOC)) {
   	foreach ($line as $col_value03) {}}

	$query1 = "SELECT l_id FROM nir.nir_links WHERE (l_id1 = $col_value11 AND l_id2 = $col_value01) ";
	$query2 = "SELECT l_id FROM nir.nir_links WHERE (l_id1 = $col_value11 AND l_id2 = $col_value02) ";
	$query3 = "SELECT l_id FROM nir.nir_links WHERE (l_id1 = $col_value11 AND l_id2 = $col_value03) ";

	$result1 = pg_query($query1) or die('Error: ' . pg_last_error());
	$result2 = pg_query($query2) or die('Error: ' . pg_last_error());
	$result3 = pg_query($query3) or die('Error: ' . pg_last_error());

	while ($line1 = pg_fetch_array($result1, null, PGSQL_ASSOC)) {
   	foreach ($line1 as $col_value1) {}}
	while ($line2 = pg_fetch_array($result2, null, PGSQL_ASSOC)) {
  	foreach ($line2 as $col_value2) {}}
	while ($line3 = pg_fetch_array($result3, null, PGSQL_ASSOC)) {
   	foreach ($line3 as $col_value3) {}}

	$query4 = "UPDATE nir.nir_object_value_varchar SET ovv_value='$background_navmenu' WHERE ovv_link_id=$col_value1";
	$query5 = "UPDATE nir.nir_object_value_varchar SET ovv_value='$image_mapknowledge' WHERE ovv_link_id=$col_value2";
	$query6 = "UPDATE nir.nir_object_value_varchar SET ovv_value=$font_size WHERE ovv_link_id=$col_value3";
	
	$result4 = pg_query($query4) or die('Error: ' . pg_last_error());
	$result5 = pg_query($query5) or die('Error: ' . pg_last_error());
	$result6 = pg_query($query6) or die('Error: ' . pg_last_error());
	
	echo "Настройки профиля успешно произведены";

// Очистка результата
pg_free_result($result);
pg_free_result($result11);
pg_free_result($result01);
pg_free_result($result02);
pg_free_result($result03);
pg_free_result($result1);
pg_free_result($result2);
pg_free_result($result3);
pg_free_result($result4);
pg_free_result($result5);
pg_free_result($result6);
// Закрытие соединения
pg_close($dbconn);
}

?>



