<?php


		$connection = pg_connect ("host=localhost port=5432 dbname=department user=postgres password=12003400");
		if (!$connection)
		{
			echo "\nCould not open connection to database server";
		}
?>
