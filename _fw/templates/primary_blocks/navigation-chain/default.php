<div id="nav-chain">
    <ul>
        <? foreach ($this->array_parents_object as $item): ?>
        <li><a href="/<?=$this->name_type_first_parent ?>/view/<?=$item['id'] ?>/"><?=$item['name']?></a></li>
        <? endforeach; ?>
    </ul>
</div>