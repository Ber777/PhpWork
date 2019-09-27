<ul class="list-templates">
    <? foreach ($this->array_templates as $item): ?>
        <li><a class="standart-button" href="/<?=$this->name_object ?>/add/<?=$item['id'] ?>"><?=$item['name'] ?></a></li>
    <? endforeach; ?>
</ul>
<p class="center-in-div">
    <a class="click-button" href="/mapknowledge/add/">Создать без шаблона</a>
    <a class="click-button btn-comeback">Вернуться</a>
</p>