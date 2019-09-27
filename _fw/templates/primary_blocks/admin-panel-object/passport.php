<ul id="admin_panel_object_passport" class="_padding-for-h1">
    <li><a class="copy-to click-button" title="Копировать..." idd="<?=$this->id_current ?>" object="document" page_passport="1"><img src="/images/copy-item-1.png">Копировать в буфер</a></li>
    <? if ($this->rights_object['grant']): ?>
        <li><a class="edit-access click-button" title="Доступ..." its="document" idd="<?=$this->id_current ?>"><img src="/images/administrative-docs.png">Настроить доступ</a></li>
    <? endif; ?>
    <? if ($this->rights_object['update']): ?>
        <li><a class="edit-something click-button" title="Изменить..." href="/document/edit/<?=$this->id_current ?>/"><img src="/images/pencil.png">Изменить</a></li>
    <? endif; ?>
    <? if ($this->rights_object['drop']): ?>
        <li><a class="delete-something click-button" its="document" idd="<?=$this->id_current ?>" title="Удалить..."><img src="/images/close.png">Удалить</a></li>
    <? endif; ?>
    <li><a class="come-back click-button" href="/<?=$this->name_type_first_parent ?>/view/<?=$this->id_parent ?>/" ><img src="/images/sign-in_20x20.png">Вернуться в рубрику</a></li>
</ul>