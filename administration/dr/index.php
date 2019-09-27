<!DOCTYPE HTML>
<html>
<head>
  <title>Дерево с подгрузкой</title>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="css/style_for_db.css">
  <script type="text/javascript" src="js/jquery-2.2.2.min.js"></script>
<!--  <script type="text/javascript" src="js/tree_load.js"></script>-->
<script type="text/javascript" src="js/modal.js"></script>
<script type="text/javascript" src="js/new-modal.js"></script>
<script type="text/javascript" src="js/edit_tree2.js"></script>
   <script type="text/javascript" src="js/summary.js"></script>
	<link rel="stylesheet" href="../libs/bootstrap/css/bootstrap-grid.min.css">
	<link rel="stylesheet" href="../libs/magnific-popup/magnific-popup.css">

	<link rel="stylesheet" href="../css/fonts.css">
	<link rel="stylesheet" href="../css/main.css">
	<link rel="stylesheet" href="../css/media.css">
<script src="../libs/jquery/jquery-1.11.2.min.js"></script>
	<script src="../libs/magnific-popup/jquery.magnific-popup.min.js"></script>
</head>
<body>
  <a href="#add" class="open_modal google-button">Добавить</a>
  <a href="#delete" class="open_modal google-button">Удалить</a>
<!--  <a id="apply" class="google-button">Применить</a> -->

  <div id="multi-derevo">
    <h4>Дерево элемента</h4>
    <?php
//    $id = $_GET['id'];
//    include($_SERVER['DOCUMENT_ROOT'].'dbconn.php');
$host = 'localhost';
$db_user = $_SERVER['PHP_AUTH_USER'];
$db_password = $_SERVER['PHP_AUTH_PW'];
$db_name = 'xgb_nir';
$dbconn = pg_connect("host=$host dbname=$db_name user=$db_user password=$db_password") OR DIE ("Не могу создать соединение.");


  function show_all_tree($par_id)
  {
//    global $link;
//    $query_time = "SELECT * FROM nir.nir_parent_view WHERE parent_id=".$par_id;
    $query_time = "SELECT * FROM nir.nir_parent_view WHERE parent_id=$par_id";
    $result = pg_query($query_time) or die ("Error in query:$query_time.". pg_last_error());
//    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
//	foreach ($line as $col_value) {}} echo $col_value;

    if (pg_num_rows($result)>0) {
      echo "<ul>";
      while ($row = pg_fetch_array($result)) {
        $id = $row["obj_id"];
//	$type = $row["obj_type"];
/*        if ($row["obj_type"] == 13) {
          echo "<li id=\"$id\"><span>";
          echo "<a>".$row["obj_name"]." (База данных)</a></span>";
          show_all_tree($id);
        }*/
        if ($row["obj_type"] == 5) {  
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

  function show_all_tree2($par_id)
  {
//    global $link;
//    $query_time = "SELECT * FROM nir.nir_parent_view WHERE parent_id=".$par_id;
    $query_time = "SELECT * FROM nir.nir_parent_view WHERE parent_id=$par_id";
    $result = pg_query($query_time) or die ("Error in query:$query_time.". pg_last_error());
//    while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
//	foreach ($line as $col_value) {}} echo $col_value;

    if (pg_num_rows($result)>0) {
      echo "<ul>";
      while ($row = pg_fetch_array($result)) {
        $id = $row["obj_id"];
//	$type = $row["obj_type"];
/*        if ($row["obj_type"] == 13) {
          echo "<li id=\"$id\"><span>";
          echo "<a>".$row["obj_name"]." (База данных)</a></span>";
          show_all_tree($id);
        }*/
        if ($row["obj_type"] == 5) {  
          echo "<li id=\"$id\"><span>";
          echo "<a>".$row["obj_name"]." (Документ)</a></span>";
          show_all_tree2($id);
        }
        else {
          echo "<li id=\"$id\"><span>";
          echo "<a>".$row["obj_name"]."</a></span>";
          show_all_tree2($id); 
        }
      }
      echo "</ul>";
    }
    echo "</li>";
  } 

function show_all_tree_1($id)
{
    $query = "SELECT * FROM nir.nir_parent_view WHERE parent_id_type=$id";
    $result = pg_query($query) or die ("Error in query:$query.". pg_last_error());
    if (pg_num_rows($result)>0) {
      echo "<ul>";
	$v = array();
      while ($row = pg_fetch_array($result)) {
//        $id = $row["obj_id"];
	$pi = $row["parent_id"];
        if (($row["parent_id_type"] == 13) and (!in_array($row["parent_id"], $v))) {
	  $v[]=$row["parent_id"];
          echo "<li id=\"$pi\"><span>";
          echo "<a>".$row["parent_name"]." (База данных)</a></span>";
          show_all_tree($pi);
        }
	elseif (($row["parent_id_type"] == 1) and (!in_array($row["parent_id"], $v))) {
	  $v[]=$row["parent_id"];
          echo "<li id=\"$pi\"><span>";
          echo "<a>".$row["parent_name"]." (Карта знаний)</a></span>";
          show_all_tree2($pi);
        }
/*        elseif (($row["parent_id_type"] == 5) and (!in_array($row["parent_id"], $v))) {  
	  $v[]=$row["parent_id"];
          echo "<li id=\"$pi\"><span>";
          echo "<a>".$row["parent_name"]." (Документ)</a></span>";
          show_all_tree($pi);
        }
        else { if (!in_array($row["parent_id"], $v)) {
	  $v[]=$row["parent_id"];
          echo "<li id=\"$pi\"><span>";
          echo "<a>".$row["parent_name"]."</a></span>";
          show_all_tree($pi); 
        } }*/
      }
      echo "</ul>";
    }
    echo "</li>";
} 
?>

<?
 //     $id = $_GET['id'];
	$id = 13;	
 show_all_tree_1($id);
	$id = 1;	
 show_all_tree_1($id);

    ?>
  </div>


  <div id="add" class="modal_div">
    <span class="modal_close">X</span>
    <form action="" method="post">
      <h2>Добавление каталога</h2>
      <p>
        <br>
        <input placeholder="Введите название" id="for-add" type="text" name="your-name" value="" size="26">
      </p>
      <p style="text-align: center; padding-bottom: 10px;">
        <a href="#add1" class="google-button modal_close_button" id="add">Добавить</a>
      </p>
    </form>
  </div>
  <div id="delete" class="modal_div"> <!-- скрытый див с уникaльным id = modal1 -->
    <span class="modal_close">X</span>
    <p style="text-align: center; padding-bottom: 10px;">Точно хотите удалить?</p>
    <p style="text-align: center; padding-bottom: 10px;">
      <a href="#" class="google-button modal_close_button" id="delete">Да</a>
      <a href="#" class="google-button modal_close_button" id="cancel">Отмена</a>
    </p>
  </div>
  <div id="overlay"></div>
<input type="button" onclick="history.back();" value="Назад"/>
</body>
</html>
