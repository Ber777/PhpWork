

<!DOCTYPE HTML>
<html>
<head>
  <title>Полное дерево</title>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="css/test_style.css">
  <link rel="stylesheet" type="text/css" href="css/the-modal.css" media="all">
<!--   <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
 -->
   <script type="text/javascript" src="js/jquery-2.2.2.min.js"></script> 
  <script type="text/javascript" src="js/edit_tree.js"></script>
  <script type="text/javascript" src="js/summary.js"></script>
  <script type="text/javascript" src="js/new-modal.js"></script>
  <script type="text/javascript" src="js/tree_load.js"></script>
  <script type="text/javascript" src="js/jquery.the-modal.js"></script>

</head>
<body>

  <div class="modal" id="terms-of-service" style="display: none">
    <a href="#" class="close">&times;</a>
    <h1>Внутренние каталоги</h1>
<div class="buttons">
  <a href="#more" class="add-more google-button">Добавить</a>
  <a href="#more" class="delete-more google-button">Удалить</a>
</div>


  <div class="more" style="display: none"></div>
    
    <div id="multi-derevo">
    <h4>Дерево каталогов</h4>
    <?php
    $id = 2192; //$_GET['id'];
    $tempAdr = $_SERVER['DOCUMENT_ROOT'];
    include($tempAdr.'/include/dbConnect.php');

    if (!$id) {
      $id = 0;
    }

    $query_step = "SELECT * FROM nir.nir_parent_view WHERE parent_id=".$id;
    $result = pg_query($link, $query_step) or die ("Error in query_step:$query_step.". pg_last_error($link));

    if (pg_num_rows($result)>0) : ?>
      <ul> 
      <?while ($row = pg_fetch_array($result)) :
        $id = $row["obj_id"];
        if ($row["obj_type"] == 13) : ?>
          <li id="<?=$id ?>" class="last"><span>
          <a class="db"><?=$row["obj_name"] ?> (База данных)</a></span>";
       <? elseif ($row["obj_type"] == 5) : ?> 
          <li id="<?=$id ?>" class="last"><span>
          <a class="document"><?=$row["obj_name"] ?> (Документ)</a></span>
        <? else: ?>
          <li id="<?=$id ?>" class="last"><span>
          <a class="catalog"><?=$row["obj_name"] ?></a></span>
       <? endif; endwhile; ?>
      </ul> 
   <? endif; ?>
    </li>
    </div>  
  </div>
  <a class="trigger google-button">Применить</a>

</body>
</html>
