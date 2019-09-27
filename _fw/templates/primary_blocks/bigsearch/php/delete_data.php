<?php

include($_SERVER['DOCUMENT_ROOT'].'/dbconn.php');


$all_ids = $_POST["objId"];
//echo $all_ids;
$query_delete_parent = "SELECT nir.drop_catalog(".$all_ids.")";
$result_delete_parent = pg_query($query_delete_parent) or die ("Error in query_delete_parent:$query_delete_parent.". pg_last_error());

?>
