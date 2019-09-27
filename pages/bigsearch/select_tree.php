<?php

function show_all_tree($par_id)
{
    global $link;
    $query_time = "SELECT * FROM nir.nir_parent_view WHERE parent_id=" . $par_id;
    $result = pg_query($link, $query_time) or die ("Error in query:$query_time." . pg_last_error($link));

    if (pg_num_rows($result) > 0) {
        echo "<ul class=\"modal-ul\">";
        while ($row = pg_fetch_array($result)) {
            $id = $row["obj_id"];
            echo "<li id=\"$id\" class=\"modal-li\"><span class=\"modal-span\">";
            echo "<a class=\"catalog modal-a\">" . $row["obj_name"] . "</a></span>";
            show_all_tree($id);
        }

        echo "</ul>";
    }
    echo "</li>";
}

?>


<div class="modal" id="terms-of-service" style="display: none">
    <a href="#" class="close">&times;</a>
    <h1>Каталог шаблонов</h1>
    <div class="buttons">
        <!--        <a href="#more" class="add-more google-button">Добавить</a>-->
        <!--        <a href="#more" class="delete-more google-button">Удалить</a>-->
        <a id="select-id" class="apply-more google-button">Выбрать</a>
    </div>


    <div class="more" style="display: none"></div>


    <div id="multi-derevo">
        <h4 class="modal-four">Все шаблоны</h4>
        <?php
        //$id = $_POST['id'];
        $tempAdr = $_SERVER['DOCUMENT_ROOT'];
        include($tempAdr . '/include/dbConnect.php');

        $id = 0;
        $query_step_db = "SELECT DISTINCT ON (parent_id) * FROM nir.nir_parent_view WHERE parent_id_type=13;"; //AND parent_id=" . $id;
        $query_step_kz = "SELECT DISTINCT ON (parent_id) * FROM nir.nir_parent_view WHERE parent_id_type=1;"; //AND parent_id=" . $id;
        $result_db = pg_query($link, $query_step_db) or die ("Error in query_step:$query_step_db." . pg_last_error($link));
        $result_kz = pg_query($link, $query_step_kz) or die ("Error in query_step:$query_step_kz." . pg_last_error
            ($link));
        ?>
        <ul class="all modal-ul">
            <li id="0" class="last modal-li">
                <span class="modal-span">
                    <a class="all modal-a">Все
                    </a>
                </span>
                <ul class="modal-ul">

                    <li id="db" class="last modal-li">
                        <span class="modal-span">
                            <a class="modal-a">Базы Данных
                            </a>
                        </span>
                        <? if (pg_num_rows($result_db) > 0) : ?>
                            <ul class="modal-ul">
                                <? while ($row = pg_fetch_array($result_db)) :
                                $id = $row["parent_id"]; ?>
                                <li id="<?= $id ?>" class="modal-li">
                                     <span class="modal-span">
                                      <a class="db modal-a"><?= $row["parent_name"] ?></a></span>
                                    <? show_all_tree($id) ?>
                                    <? endwhile; ?>
                            </ul>
                        <? endif; ?>
                    </li>
                    </li>
                    <li id="kz" class="last modal-li">
                        <span class="modal-span">
                            <a class="modal-a">Каталоги Знаний

                            </a>
                        </span>
                        <? if (pg_num_rows($result_kz) > 0) : ?>
                            <ul class="modal-ul">
                                <? while ($row = pg_fetch_array($result_kz)) :
                                $id = $row["parent_id"]; ?>
                                <li id="<?= $id ?>" class="modal-li">
                                     <span class="modal-span">
                                      <a class="kz modal-a"><?= $row["parent_name"] ?></a></span>
                                    <? show_all_tree($id) ?>
                                    <? endwhile; ?>
                            </ul>
                        <? endif; ?>
                        </li>
                    </li>
                </ul>

            </li>
        </ul>

    </div>
</div>