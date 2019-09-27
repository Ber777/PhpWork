<?php

$login = $_SERVER['PHP_AUTH_USER'];
$pass  = $_SERVER['PHP_AUTH_PW'];
$login1 = 'nobody';
$conn = pg_connect("host=localhost dbname=xgb_nir user=$login password=$pass");
if (!$conn) {
  echo "Access denied.\n";
  exit;
}
$rol = 'NOCREATEDB';

//$query = "SELECT * FROM pg_roles";
$query = "CREATE ROLE $login1 LOGIN NOSUPERUSER INHERIT $rol NOCREATEROLE NOREPLICATION PASSWORD 'nobody' VALID UNTIL 'INFINITY'";
//$query = "INSERT INTO pg_roles (rolname,rolcanlogin,rolsuper,rolcreatedb,rolcreaterole,rolinherit,rolreplication,rolconnlimit,rolpassword,rolvaliduntil,rolbypassrls,oid) VALUES ('berezin','true','true','true','true','true','true',-1,'berezin','infinity','false',default)";
$result = pg_query($query) or die('Error: ' . pg_last_error());

// Вывод результатов в HTML
echo "<table>\n";
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    echo "\t<tr>\n";
    foreach ($line as $col_value) {
        echo "\t\t<td>$col_value</td>\n";
    }
    echo "\t</tr>\n";

}
echo "</table>\n";

// Очистка результата
pg_free_result($result);

// Закрытие соединения
pg_close($dbconn);
?>
