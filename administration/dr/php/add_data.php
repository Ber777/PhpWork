<?php
$tempAdr = $_SERVER['DOCUMENT_ROOT'];
include($tempAdr.'/include/dbConnect.php');

if($_POST["newName"]!='')
{
	$query_for_name = "SELECT nir.addcatalog('".$_POST["newName"]."',".$_POST["parentId"].")";

	$result_for_name = pg_query($link, $query_for_name) or die ("Error in query_par_add:$query_for_name.". pg_last_error($link));

	$id = pg_fetch_row($result_for_name);

	if ($id[0]!=-1) {
		echo "<li id=".$id[0]."><span>";// class=\"last\"><span>";
		echo "<a class=\"catalog\">".$_POST["newName"]."</a></span></li>";
	}

	else echo "falseName";
}

else echo "nullName";

?>