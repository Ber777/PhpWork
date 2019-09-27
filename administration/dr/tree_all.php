<?php
    $host = 'localhost'; 
    $db_user = $_SERVER['PHP_AUTH_USER'];// Логин БД
    $db_password = $_SERVER['PHP_AUTH_PW']; // Пароль БД
    $db_name = 'xgb_nir';
     
    // Подключение к базе данных
    $dbconn = pg_connect("host=$host dbname=$db_name user=$db_user password=$db_password") OR DIE ("Не могу создать соединение.");


  function show_all_tree($par_id)
  {
    $query_time = "SELECT * FROM nir.nir_parent_view WHERE parent_id=$par_id";
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
?>

<!DOCTYPE HTML>
<html>
<head>
  <title>Полное дерево</title>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="css/style_for_db.css">
  <script type="text/javascript" src="js/jquery-2.2.2.min.js"></script>
   <script type="text/javascript" src="js/summary.js"></script>
</head>
<body>

  <div id="multi-derevo">
  <h4>Дерево элемента</h4>
    <?
      $id = $_GET['id'];
      show_all_tree($id); 
    ?>
  </div>

</body>
</html>
