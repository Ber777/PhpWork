<div id="left-block" class="_inline-block">
    <ul class="admin-panel-mk">
        <? if ($this->user_info['add_mapkmowledge']): ?>
        <li>
            <a href="/mapknowledge/templates/" class="click-button">
                <img width="28px" src="/images/folder-add2.png">
                <span>Добавить карту знаний</span>
            </a>
        </li>
        <? endif; ?>
    </ul>
    <ul class="list-float-none">
        <? foreach ($this->array_mk as $key => $value): ?>
        <li class="standart-button">
            <a style="width: 60%" href="/mapknowledge/view/<?=$value['id'] ?>"><?=$value['name'] ?></a>
            <? if ($value['user_id'] != $this->user_info['id']): ?>
                <h6>[Владелец: <?=$value['iuser_name'] ?>]</h6>
            <? endif; ?>
            <? $this->AdminPanelObject('mapknowledge', $key); ?>
        </li>
        <? endforeach; ?>
    </ul>
    <h3>Оповещения</h3>
    <ul class="list-float-none">
        <? if (count($this->array_active_alerts)): ?>
        <? foreach ($this->array_active_alerts as $key => $value): ?>
        <li class="standart-button ">
            <a class="search-this-template" data-id="<?=$value['id'] ?>" style="color:red"><?=$value['name'] ?></a>
        </li>
        <? endforeach; ?>
        <? endif; ?>
    </ul>
</div>
