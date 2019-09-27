<div class="modal" id="terms-of-service" style="display: none">
  <a href="#" class="close">&times;</a>
  <h1>Внутренние каталоги</h1>
  <div class="buttons">
    <a href="#more" class="add-more google-button">Добавить</a>
    <a href="#more" class="delete-more google-button">Удалить</a>
  </div>


  <div class="more" style="display: none"></div>
  <div id="multi-derevo">
    <h4 class="modal-four">Дерево каталогов</h4>
    <?
    $id = $_POST['id'];
    $tempAdr = $_SERVER['DOCUMENT_ROOT'];
    $host = 'localhost'; 
    $db_user = $_SERVER['PHP_AUTH_USER'];// Логин БД
    $db_password = $_SERVER['PHP_AUTH_PW']; // Пароль БД
    $db_name = 'xgb_nir';
     
    // Подключение к базе данных
    $dbconn = pg_connect("host=$host dbname=$db_name user=$db_user password=$db_password") OR DIE ("Не могу создать соединение.");

    if (!$id) {
      $id = 0;
    }

    $query_step = "SELECT * FROM nir.nir_parent_view WHERE parent_id=$id";
    $result = pg_query($query_step) or die ("Error in query_step:$query_step.". pg_last_error());

    if (pg_num_rows($result)>0) : ?>
    <ul class="modal-ul"> 
      <?while ($row = pg_fetch_array($result)) :
      $id = $row["obj_id"];
      if ($row["obj_type"] == 13) : ?>
      <li id="<?=$id ?>" class="last modal-li"><span class="modal-span">
        <a class="db modal-a"><?=$row["obj_name"] ?> (База данных)</a></span>";
      <? elseif ($row["obj_type"] == 5) : ?> 
      <li id="<?=$id ?>" class="last modal-li"><span class="modal-span">
        <a class="document modal-a"><?=$row["obj_name"] ?> (Документ)</a></span>
      <? else: ?>
      <li id="<?=$id ?>" class="last modal-li"><span class="modal-span">
        <a class="catalog modal-a"><?=$row["obj_name"] ?></a></span>
      <? endif; endwhile; ?>
    </ul> 
  <? endif; ?>
</li>
</div>  
</div>
