<?php

include($_SERVER['DOCUMENT_ROOT'].'/include/dbConnect.php');


$all_ids = $_POST["objId"];

$query_delete_parent = "SELECT nir.drop_catalog(".$all_ids.")";
$result_delete_parent = pg_query($link, $query_delete_parent) or die ("Error in query_delete_parent:$query_delete_parent.". pg_last_error($link));

?>