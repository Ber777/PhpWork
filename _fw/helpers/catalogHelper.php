<?php

class catalogHelper extends Helper {

    var $name_catalog; // имя рубрики
    var $id_current; // ид текущей рубрики
    var $list_templates; // список шаблонов рубрик
    

    public function FormAddCatalogFromTemplate() {
        $this->drawBlock('popup-content', 'add_catalog_from_template');
    }

}