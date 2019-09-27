<?php

include($_SERVER['DOCUMENT_ROOT'].'/dbconn.php');

function show_all_tree($par_id)
{
	global $link;
	$query_time = "SELECT * FROM nir.nir_parent_view WHERE parent_id=".$par_id;
	$result = pg_query($query_time) or die ("Error in query:$query_time.". pg_last_error());

	if (pg_num_rows($result)>0) {
		echo "<ul>";
		while ($row = pg_fetch_array($result)) {
			$id = $row["obj_id"];
			if ($row["obj_type"] == 13) {
				echo "<li id=\"$id\"><span>";
				echo "<a>".$row["obj_name"]." (База данных)</a></span>";
				show_all_tree($id);
			}
			elseif ($row["obj_type"] == 5) {	
				echo "<li id=\"$id\"><span>";
				echo "<a>".$row["obj_name"]." (Документ)</a></span>";
				show_all_tree($id);
			}
			else {
				echo "<li id=\"$id\"><span>";
				echo "<a>".$row["obj_name"]."</a></span>";
				show_all_tree($id); 
			}
		}

		echo "</ul>";
	}



	echo "</li>";
}

$id = 0;
$level = 0;
echo "<h4><a>Все Каталоги</a></h4>";
show_all_tree($id);

?>
