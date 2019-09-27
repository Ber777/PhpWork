<div>
    <ul id="list-child-catalog" class="list-float-none">
        <? if (count($this->array_catalogs)): ?>
            <? foreach ($this->array_catalogs as $key => $value): ?>
                <li class="list-link-catalog standart-button">
                    <a class="link-catalog" href="/database/view/<?=$value['id'] ?>/"><?=$value['name'] ?></a>
                    <? $this->AdminPanelObject('catalog', $key) ?>
                </li>
            <? endforeach; ?>
        <? else: ?>
            <h2 class="_padding-for-h1">Рубрики отсутствуют</h2>
        <? endif; ?>
    </ul>
</div>