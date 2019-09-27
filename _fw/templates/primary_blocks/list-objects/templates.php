<ul class="list-templates">
    <? foreach ($this->array_templates as $item): ?>
    <li><a class="standart-button" href="/<?=$this->name_object ?>/add/<?=$this->id_parent ?>/?template=<?=$item['id'] ?>"><?=$item['name'] ?></a></li>
    <? endforeach; ?>
</ul>
<p class="center-in-div">
    <a class="click-button" href="/<?=$this->name_object ?>/add/<?=$this->id_parent ?>/?template=none">Создать без шаблона</a>
    <a class="click-button" href="<?=$this->link_come_back ?>">Вернуться</a>
</p>