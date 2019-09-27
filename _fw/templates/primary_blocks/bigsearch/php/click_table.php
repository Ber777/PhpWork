<?php

include($_SERVER['DOCUMENT_ROOT'].'/dbconn.php');

if (!$_POST["parentId"]) {
	$_POST["parentId"] = 0;
}

$query_step = "SELECT * FROM nir.nir_parent_view WHERE parent_id=".$_POST["parentId"];
$result = pg_query($query_step) or die ("Error in query_step:$query_step.". pg_last_error());

if (pg_num_rows($result)>0) {
	echo "<ul>";
	while ($row = pg_fetch_array($result)) {
		$id = $row["obj_id"];
		if ($row["obj_type"] == 13) {
			echo "<li id=\"$id\"><span>";
			echo "<a class=\"db\">".$row["obj_name"]." (База данных)</a></span>";
		}
		elseif ($row["obj_type"] == 5) {	
			echo "<li id=\"$id\"><span>";
			echo "<a class=\"document\">".$row["obj_name"]." (Документ)</a></span>";
		}
		else {
			echo "<li id=\"$id\"><span>";
			echo "<a class=\"catalog\">".$row["obj_name"]."</a></span>";
		}
	}
	echo "</ul>";
}

echo "</li>";
?>
