<?php
$conn = pg_connect("host=localhost dbname=xgb_nir user=postgres password=123456");
if (!$conn) {
  echo "Access denied.\n";
  exit;
}

$query = "SELECT * FROM nir.nir_user";
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
