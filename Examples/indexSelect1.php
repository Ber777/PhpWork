<?
$login = $_SERVER['PHP_AUTH_USER'];
$pass  = $_SERVER['PHP_AUTH_PW'];
$login1 = 'berezin';
$conn = pg_connect("host=localhost dbname=xgb_nir user=$login password=$pass");
if (!$conn) {
  echo "Access denied.\n";
  exit;
}

/*$par_id = 1253;
$query = "SELECT obj_id FROM nir.nir_parent_view WHERE parent_id=$par_id";*/
$query_id = "SELECT user_id FROM nir.nir_user WHERE user_id_system = '$login1'";
$result = pg_query($query_id) or die('Error: ' . pg_last_error());
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) { // Эта строчка и ниже чтобы получить значений твое (костыль тоже так юзаю)
   foreach ($line as $col_value) {}}echo $col_value;

/*$query11 = "SELECT profile_id FROM nir.user_profile_view WHERE user_id=".$col_value;
$result11 = pg_query($query11) or die('Error: ' . pg_last_error());
while ($line = pg_fetch_array($result11, null, PGSQL_ASSOC)) {
   foreach ($line as $col_value11) {}}

$query1 = "SELECT l_id FROM nir.nir_links WHERE (l_id1 = $col_value11 AND l_id2 = 63485) ";
$query2 = "SELECT l_id FROM nir.nir_links WHERE (l_id1 = $col_value11 AND l_id2 = 63486) ";
$query3 = "SELECT l_id FROM nir.nir_links WHERE (l_id1 = $col_value11 AND l_id2 = 63487) ";

$result1 = pg_query($query1) or die('Error: ' . pg_last_error());
$result2 = pg_query($query2) or die('Error: ' . pg_last_error());
$result3 = pg_query($query3) or die('Error: ' . pg_last_error());

while ($line1 = pg_fetch_array($result1, null, PGSQL_ASSOC)) {
   foreach ($line1 as $col_value1) {}}
while ($line2 = pg_fetch_array($result2, null, PGSQL_ASSOC)) {
   foreach ($line2 as $col_value2) {}}
while ($line3 = pg_fetch_array($result3, null, PGSQL_ASSOC)) {
   foreach ($line3 as $col_value3) {}}

$query4 = "UPDATE nir.nir_object_value_varchar SET ovv_value= nir.nir_user WHERE user_id_system='$login'";*/


// Очистка результата
pg_free_result($result);
//pg_free_result($result11);
//pg_free_result($result1);
//pg_free_result($result2);
//pg_free_result($result3);
// Закрытие соединения
pg_close($dbconn);
?>
