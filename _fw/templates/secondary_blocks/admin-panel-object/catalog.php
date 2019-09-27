<ul class="admin_panel_object">
    <li><a class="copy-to click-button" title="Копировать..." idd="<?= $this->array_catalogs[$data]['id'] ?>" object="catalog"><img src="/images/copy-item-1.png"></a></li>
    <? if ($this->array_catalogs[$data]['rights']['grant']): ?>
        <li><a class="edit-access click-button" title="Доступ..." its="catalog" idd="<?= $this->array_catalogs[$data]['id'] ?>"><img src="/images/administrative-docs.png"></a></li>
    <? endif ?>
    <? if ($this->array_catalogs[$data]['rights']['update']): ?>
        <li><a class="edit-something click-button" title="Изменить..." href="/catalog/edit/<?= $this->array_catalogs[$data]['id'] ?>"><img src="/images/pencil.png"></a></li>
    <? endif; ?>
    <? if ($this->array_catalogs[$data]['rights']['drop']): ?>
        <li><a class="delete-something click-button" its="catalog" idd="<?= $this->array_catalogs[$data]['id'] ?>" title="Удалить..."><img src="/images/close.png"></a></li>
    <? endif ?>
</ul>