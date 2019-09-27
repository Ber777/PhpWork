<ul class="admin_panel_object">
    <? if (isset($this->array_documents[$data]['link_download']['filename'])): ?>
        <li><a target="_blank" href="<?=$this->array_documents[$data]['link_download']['filename'] ?>" class="download-doc click-button" title="Скачать..."><img src="/images/upcoming-work.png"></a></li>
    <? endif; ?>
    <li><a class="copy-to click-button" title="Копировать..." idd="<?= $this->array_documents[$data]['id'] ?>" object="document"><img src="/images/copy-item-1.png"></a></li>
    <? if ($this->array_documents[$data]['rights']['grant']): ?>
        <li><a class="edit-access click-button" title="Доступ..." its="document" idd="<?= $this->array_documents[$data]['id'] ?>"><img src="/images/administrative-docs.png"></a></li>
    <? endif; ?>
    <? if ($this->array_documents[$data]['rights']['update']): ?>
        <li><a class="edit-something click-button" title="Изменить..." href="/document/edit/<?= $this->array_documents[$data]['id'] ?>"><img src="/images/pencil.png"></a></li>
    <? endif; ?>
    <? if ($this->array_documents[$data]['rights']['drop']): ?>
        <li><a class="delete-something click-button" its="document" idd="<?= $this->array_documents[$data]['id'] ?>" title="Удалить..."><img src="/images/close.png"></a></li>
    <? endif; ?>
</ul>
