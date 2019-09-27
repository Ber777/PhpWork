<ul class="admin_panel_object">
    <? if ($this->array_mk[$data]['rights']['grant']): ?>
    <li><a class="edit-access click-button" title="Доступ..." its="mapknowledge" idd="<?=$this->array_mk[$data]['id'] ?>"><img src="/images/administrative-docs.png"></a></li>
    <? endif; ?>
    <? if ($this->array_mk[$data]['rights']['grant']): ?>
    <li><a class="edit-something click-button" title="Изменить..." href="/mapknowledge/edit/<?=$this->array_mk[$data]['id'] ?>"><img src="/images/pencil.png"></a></li>
    <? endif; ?>
    <? if ($this->array_mk[$data]['rights']['grant']): ?>
    <li><a class="delete-something click-button" its="mapknowledge" idd="<?=$this->array_mk[$data]['id'] ?>" title="Удалить..."><img src="/images/close.png"></a></li>
    <? endif; ?>
</ul>