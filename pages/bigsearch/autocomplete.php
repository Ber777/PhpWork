<?php 
	header("Content-type: text/html; charset=windows-1251"); 
    $tempAdr = $_SERVER['DOCUMENT_ROOT'];
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);

    #include($tempAdr.'/include/dbConnect.php');	
	#include($tempAdr.'/include/dbConnect.php');
        pg_connect("host=localhost dbname=xgb_nir user=xgb_nir password=xgb_nir");

	$keyword = $_GET['query'];
	$keyword= iconv('UTF-8', 'windows-1251', $keyword);

    $dbconn = DbConnect();
	//$dbconn = pg_connect("host=localhost port=5432 dbname=xgb_nir user=xgb_nir password=admin");
	//подключиться к базе "xgb_nir" на хосте "localhost", порт "5432"
	if (!$dbconn) {
  	echo "Произошла ошибка соединения с базой.\n";  
	}



	if($keyword!=''){
		if ($_GET['id']=='1') {
			
		//ищем тег
		$querry = 'SELECT tag_name FROM nir."tags_view" WHERE UPPER(tag_name) LIKE \'%'.mb_strtoupper($keyword).'%\' GROUP BY tag_name';
		}

		
		if($_GET['id']=='2'){
			$querry = 'SELECT o_name FROM nir."all_atrs_view" WHERE UPPER(o_name) LIKE \'%'.mb_strtoupper($keyword).'%\' ';
		}
		
	}
	else{
		$querry = 'SELECT tag_name FROM nir."tags_view" WHERE tag_name=""' ;
	}

	$search= pg_query($dbconn, $querry);

	if(!$search){
		echo "Произошла ошибка запроса";
		exit;
	}

	$i=0;
	$output = '{ "query":"'. iconv('UTF-8', 'windows-1251',$_GET['query']).'","suggestions":[';  
	while ($row = pg_fetch_row($search)) {
	$i++;
		if($i == 1) {

			$output .= '"'.$row[0].'"';
			} else {
				$output .= ',"'.$row[0].'"';
			}
	}
	$output .= ']}';   
	echo $output;
	//echo '{"query": "Unit", "suggestions": ["United Arab Emirates", "United Kingdom", "United States"]}' ;

	

 ?>
