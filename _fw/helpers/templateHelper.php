<?php

class templateHelper extends Helper {

    var $name_template; // имя шаблона
    var $path_file_template_doc; // путь файла щаблона документа

    var $id_type_template;
    var $array_templates_for_print; // список шаблонов для вывода
    var $admin_panel_object;

    var $search_name_template; // имя по которому идет поиск шаблонов

    var $step_4; // переменная для шаблона главной формы (с файлом или без файла)

    public function ListTemplates($flag_user_template = false) {
        if ($flag_user_template) {
            if (count($this->array_templates)) {
                foreach ($this->array_templates as $key => $value) {
                    foreach ($this->array_user_templates as $key2 => $value2) {
                        if ($value['id'] == $value2['id']) {
                            unset($this->array_templates[$key]);
                        }
                    }
                }
            }
            $this->array_templates_for_print = $this->array_templates;
            $this->drawBlock('list-objects', 'page_templates');
        } else {
            $this->array_templates_for_print = $this->array_user_templates;
            $this->drawBlock('list-objects', 'page_templates');
        }
    }
    
    public function FormAddCatalogTemplate () {
        $this->BlockForm('add_catalog_template');
    }
    
    public function LinkAddDocumentTemplate() {
        $this->Link('p', '/template/add/7/', 'click-button', 'Добавить шаблон документа');
    }
    
    public function LinkAddMapknowledgeTemplate() {
        $this->Link('p', '/template/add/8/', 'click-button', 'Добавить шаблон карт знаний');
    }

    public function LinkAddSearchTemplate() {
        $this->Link('p', '/bigsearch/', 'click-button', 'Добавить шаблон поиска');
    }

    public function LinkAddAlertTemplate() {
        $this->Link('p', '/bigsearch/', 'click-button', 'Добавить оповещение');
    }

    public function ListCatalogTemplatesUser() {
        $this->title_block = 'Мои шаблоны рубрик';
        $this->admin_panel_object = 'template_catalog';
        $this->ListTemplates();
    }

    public function ListCatalogTemplates() {
        $this->title_block = 'Все шаблоны рубрик';
        $this->admin_panel_object = 'template_catalog';
        $this->ListTemplates(1);
    }

    public function ListDocumentTemplatesUser() {
        $this->title_block = 'Мои шаблоны документов';
        $this->admin_panel_object = 'template_document';
        $this->ListTemplates();
    }

    public function ListDocumentTemplates() {
        $this->title_block = 'Все шаблоны документов';
        $this->admin_panel_object = 'template_document';
        $this->ListTemplates(1);
    }

    public function ListSearchTemplates() {
        $this->title_block = 'Все шаблоны поиска';
        $this->admin_panel_object = 'template_search';
        $this->ListTemplates(1);
    }

    public function ListSearchTemplatesUser() {
        $this->title_block = 'Мои шаблоны поиска';
        $this->admin_panel_object = 'template_search';
        $this->ListTemplates();
    }

    public function ListMapknowledgeTemplates() {
        $this->title_block = 'Все шаблоны карт знаний';
        $this->admin_panel_object = 'template_mapknowledge';
        $this->ListTemplates(1);
    }

    public function ListMapknowledgeTemplatesUser() {
        $this->title_block = 'Мои шаблоны карт знаний';
        $this->admin_panel_object = 'template_mapknowledge';
        $this->ListTemplates();
    }

    public function ListAlertTemplates() {
        $this->title_block = 'Все оповещения';
        $this->admin_panel_object = 'template_alert';
        $this->ListTemplates(1);
    }

    public function ListAlertTemplatesUser() {
        $this->title_block = 'Мои оповещения';
        $this->admin_panel_object = 'template_alert';
        $this->ListTemplates();
    }

    public function ListCatalogTemplatesResultSearch() {
        $this->title_block = 'Результат поиска шаблонов рубрик по запросу - '. $this->search_name_template;
        $this->admin_panel_object = 'template_catalog';
        $this->ListTemplates(1);
        $this->Link('', '', 'close-result-search-template click-button', 'Закрыть результат поиска');
    }

    public function ListDocumentTemplatesResultSearch() {
        $this->title_block = 'Результат поиска шаблонов документов по запросу - '. $this->search_name_template;
        $this->admin_panel_object = 'template_document';
        $this->ListTemplates(1);
        $this->Link('', '', 'close-result-search-template click-button', 'Закрыть результат поиска');
    }

    public function ListMapKnowledgeTemplatesResultSearch() {
        $this->title_block = 'Результат поиска шаблонов карт знаний по запросу - '. $this->search_name_template;
        $this->admin_panel_object = 'template_mapknowledge';
        $this->ListTemplates(1);
        $this->Link('', '', 'close-result-search-template click-button', 'Закрыть результат поиска');
    }

    public function ListSearchTemplatesResultSearch() {
        $this->title_block = 'Результат поиска шаблонов поиска по запросу - '. $this->search_name_template;
        $this->admin_panel_object = 'template_search';
        $this->ListTemplates(1);
        $this->Link('', '', 'close-result-search-template click-button', 'Закрыть результат поиска');
    }

    public function ListAlertTemplatesResultSearch() {
        $this->title_block = 'Результат поиска оповещений по запросу - '. $this->search_name_template;
        $this->admin_panel_object = 'template_search';
        $this->ListTemplates(1);
        $this->Link('', '', 'close-result-search-template click-button', 'Закрыть результат поиска');
    }

    public function HideFormSearch(){
        $this->BlockForm('hide_form_search_template');
    }
    
}