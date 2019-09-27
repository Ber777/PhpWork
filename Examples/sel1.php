<?
$login = $_SERVER['PHP_AUTH_USER'];
$pass  = $_SERVER['PHP_AUTH_PW'];
$login1 = 'adminkv';
$conn = pg_connect("host=localhost dbname=xgb_nir user=$login password=$pass");
if (!$conn) {
  echo "Access denied.\n";
  exit;
}
$str = 'Профиль пользователя ';
$user = 'nobody';
$query_username = "SELECT user_name FROM nir.nir_user WHERE user_id_system='$user'";
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
	     $result_profdel = pg_query($query_profdel) or die("Не удалось удалить профиль пользователя '$username'");
	     
echo 555555;
   
