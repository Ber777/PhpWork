<ul class="admin_panel_object">
    <? if ($this->array_db[$data]['rights']['grant']): ?>
        <li><a class="edit-access click-button" its="database" title="Доступ..." idd="<?=$this->array_db[$data]['id'] ?>"><img src="/images/administrative-docs.png"></a></li>
    <? endif; ?>
    <? if ($this->array_db[$data]['rights']['update']): ?>
        <li><a class="edit-something click-button" title="Изменить..." href="/database/edit/<?=$this->array_db[$data]['id'] ?>"><img src="/images/pencil.png"></a></li>
    <? endif; ?>
    <? if ($this->array_db[$data]['rights']['drop']) : ?>
        <li><a class="delete-something click-button" its="database" idd="<?=$this->array_db[$data]['id'] ?>" title="Удалить..."><img src="/images/close.png"></a></li>
    <? endif; ?>
</ul>