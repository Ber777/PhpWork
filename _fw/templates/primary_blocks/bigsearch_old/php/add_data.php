<?php

$host = 'localhost';
$db_user = $_SERVER['PHP_AUTH_USER'];
$db_password = $_SERVER['PHP_AUTH_PW'];
$db_name = 'xgb_nir';
$dbconn = pg_connect("host=$host dbname=$db_name user=$db_user password=$db_password") OR DIE ("Не могу создать соединение.");

if($_POST["newName"]!='')
{
	$query_for_name = "SELECT nir.addcatalog('".$_POST["newName"]."',".$_POST["parentId"].")";

	$result_for_name = pg_query($query_for_name) or die ("Error in query_par_add:$query_for_name.". pg_last_error());

	$id = pg_fetch_row($result_for_name);

	if ($id[0]!=-1) {
		echo "<li id=".$id[0]."><span>";
		echo "<a class=\"catalog\">".$_POST["newName"]."</a></span></li>";
	}
	else echo "falseName";
}
else echo "nullName";

?>
