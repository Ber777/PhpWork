<ul class="list-maps">
    <? if (count($this->array_catalogs)): ?>
        <? foreach ($this->array_catalogs as $key => $value): ?>
            <li>
                <div class="square">
                    <? $this->AdminPanelObject('catalog_in_square', $key) ?>
                    <a href="/mapknowledge/view/<?=$value['id'] ?>">
                        <img width="150px" height="150px" src="<?=$this->user_styles['image_mapknowledge'] ?>">
                        <span><?=$value['name'] ?></span>
                    </a>
                </div>
            </li>
        <? endforeach; ?>
    <? else: ?>
        <h2>Каталоги отсутствуют</h2>
    <? endif; ?>
</ul>