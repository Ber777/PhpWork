<ul class="admin_panel_object">
    <li><a class="copy-this-template click-button" title="Скопировать шаблон к себе" id_type="<?=$this->id_type_template ?>" id_template="<?=$this->array_templates_for_print[$data]['id'] ?>"><img src="/images/plus.png"></a></li>
    <li><a class="search-this-template click-button" title="Поиск" data-id="<?=$this->array_templates_for_print[$data]['id'] ?>"><img src="/images/search.png"></a></li>
    <li><a class="edit-something click-button" idd="" href="/bigsearch/?id=<?=$this->array_templates_for_print[$data]['id'] ?>&type=alert"><img src="/images/pencil.png"></a></li>
    <li><a class="delete-something click-button" its="template_alert" idd="<?=$this->array_templates_for_print[$data]['id'] ?>" title="Удалить шаблон"><img src="/images/close.png"></a></li>
</ul>