<div id="left-block" class="_inline-block">
    <ul class="admin-panel-db">
        <? if ($this->user_info['add_database']): ?>
        <li><a href="/database/add/" class="click-button"><img width="35px" src="/images/Database-add.png"><span>Добавить базу данных</span></a></li>
        <? endif ?>
        <!--<li><a class="click-button export_data">Экспорт данных</a></li>-->
    </ul>

    <ul id="db-list" class="list-float-none">
        <? foreach ($this->array_db as $key => $item): ?>
        <li class="standart-button list-link-db ">
            <a class=" " href="/database/view/<?=$item['id'] ?>"><?=$item['name'] ?></a>
            <? $this->AdminPanelObject('database', $key) ?>
        </li>
        <? endforeach; ?>
    </ul>
</div>
