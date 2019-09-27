<?php

class templateController extends Controller {
    var $list_templates = array(
        7 => array(
            'name' => 'document',
            'name_class' => 'Document',
            'result_search' => 'ListDocumentTemplatesResultSearch',
            'method_copy' => 'copy_template',
            'method_delete' => '',
            'ajax_edit' => '/template/ajaxEditTemplateDocument/',
            'ajax_add' => '/template/ajaxAddTemplateDocument/',
            'step_4' => 'step_4_template_doc'
        ),
        8 => array(
            'name' => 'mapknowledge',
            'name_class' => 'Mapknowledge',
            'result_search' => 'ListMapKnowledgeTemplatesResultSearch',
            'method_copy' => 'copy_template',
            'method_delete' => '',
            'ajax_edit' => '/template/ajaxEditTemplateMapknowledge/',
            'ajax_add' => '/template/ajaxAddTemplateMapknowledge/',
            'step_4' => 'step_4_template_mk',
        ),
        9 => array(
            'name' => 'search',
            'name_class' => 'SearchTemplate',
            'result_search' => 'ListSearchTemplatesResultSearch',
            'method_copy' => 'copy_template',
            'method_delete' => ''
        ),
        15 => array(
            'name' => 'catalog',
            'name_class' => 'CatalogTemplate',
            'result_search' => 'ListCatalogTemplatesResultSearch',
            'method_copy' => 'copy_template',
            'method_delete' => '',
        ),
        16 => array(
            'name' => 'alert',
            'name_class' => 'Alert',
            'method_copy' => 'copy_alert',
            'result_search' => 'ListAlertTemplatesResultSearch',
            'method_delete' => '',
        )
    );
    private $list_templates_for_edit = array(7, 8);
    private $list_templates_for_add = array(7, 8);
    private $list_templates_for_bigsearch = array(9, 16);
    var $id_type_template;
    
    function __construct()
    {
        parent::__construct();
        $this->name_object = 'template';
        $this->arrayCSS = array('/pages/template/template_style.css');
        $this->arrayJS = array('/pages/template/template.js');
        $this->object_helper = $this->setObjectHelper($this->name_object);
        $this->object_helper->user_styles = $this->user_info->profile_settings;
    }

    public function add($id_type) {
        if (in_array($id_type, $this->list_templates_for_add)) {
            $this->addNewPathJS('/js/add_edit_objects.js');

            $object_attribute = new Attribute();
            $object_attribute->link = $this->db_link;
            $list_attributes = $object_attribute->get_all_attr();

            $object_tag = new Tag();
            $object_tag->link = $this->db_link;
            $list_tags = $object_tag->get_all_tegs();

            $this->setValuesHelper(array(
                'array_tags' => $list_tags,
                'array_attributes' => $list_attributes,
                'type_submit' => 'submit_add_without_template',
                'url_ajax_handler' => $this->list_templates[$id_type]['ajax_add'],
                'step_4' => $this->list_templates[$id_type]['step_4'],
                'link_come_back' => '/template/'.$this->list_templates[$id_type]['name'].'/'
            ));

            $this->setObjectResponse(
                __FUNCTION__,
                $this->mergeWithDefaultCSS($this->arrayCSS),
                $this->mergeWithDefaultJS($this->arrayJS),
                'Добавление шаблона',
                $this->data
            );

        } else {
            $this->setErrorResponse('404', 'Указан неверный тип щаблона');
        }
    }

    public function edit($id_type) {
        if (in_array($id_type, $this->list_templates_for_edit)) {
            $id_template = $this->getURL('id');
            $this->addNewPathJS('/js/add_edit_objects.js');
            if (isset($id_template)) {

                $object_document = new Document();
                $object_document->link = $this->db_link;
                $object_document->id = $id_template;
                $path_file = $object_document->downloadFile();

                $object_attribute = new Attribute();
                $object_attribute->link = $this->db_link;
                $object_attribute->obj_id = $id_template;
                $list_attributes = $object_attribute->get_all_attr();
                $list_attributes_object = $object_attribute->get_attributes_of_obj();

                $object_tag = new Tag();
                $object_tag->link = $this->db_link;
                $object_tag->obj_id = $id_template;
                $list_tags = $object_tag->get_all_tegs();
                $list_tags_object = $object_tag->get_tags_obj();

                $object_catalog = new Catalog();
                $object_catalog->link = $this->db_link;
                $object_catalog->id = $id_template;
                $name_template = $object_catalog->get_this_catalog();

                //проверка на совпадение дескрипторов
                foreach ($list_tags_object as $key => $value) {
                    foreach ($list_tags as $key2 => $value2) {
                        if ($value["id"] == $value2["id"]) unset($list_tags[$key2]);
                    }
                }

                //проверка на совпадение атрибутов
                foreach ($list_attributes_object as $key => $value) {
                    if ($value['type'] == 3) {
                        $list_attributes_object[$key]['datepicker'] = 'its-datepicker';
                        $list_attributes_object[$key]['itype'] = $value['type'];
                        $list_attributes_object[$key]['type'] = 'text';
                        $list_attributes_object[$key]['type_rus'] = 'дата';
                    } else {
                        $list_attributes_object[$key]['itype'] = $value['type'];
                        $list_attributes_object[$key]['type'] = _fw_translate_type_attribute($value['type'], 'string');
                        $list_attributes_object[$key]['type_rus'] = _fw_translate_type_attribute($value['type'], 'rus');
                    }
                    foreach ($list_attributes as $key2 => $value2) {
                        if ($value['id'] == $value2['id']) unset($list_attributes[$key2]);
                    }
                }

                $this->setData(array(
                    'path_file' => $path_file
                ));

                $this->setValuesHelper(array(
                    'array_tags_object' => $list_tags_object,
                    'array_attributes_object' => $list_attributes_object,
                    'array_tags' => $list_tags,
                    'array_attributes' => $list_attributes,
                    'name_template' => $name_template['name'],
                    'name_object' => 'template_'.$this->list_templates[$id_type]['name'],
                    'id_current_object' => $id_template,
                    'type_submit' => 'submit_edit_without_template',
                    'path_file_template_doc' => $path_file['filename'],
                    'id_for_ajax' => $id_template,
                    'url_ajax_handler' => $this->list_templates[$id_type]['ajax_edit'],
                    'step_4' => $this->list_templates[$id_type]['step_4'],
                    'link_come_back' => '/template/'.$this->list_templates[$id_type]['name'].'/'
                ));

                $this->setObjectResponse(
                    __FUNCTION__,
                    $this->mergeWithDefaultCSS($this->arrayCSS),
                    $this->mergeWithDefaultJS($this->arrayJS),
                    'Редактирование шаблона документов',
                    $this->data
                );
            }
            else {
                $this->setErrorResponse('404', 'Не указан ID редактируемого шаблона документа');
            }
        } else {
            $this->setErrorResponse('404', 'Неверный тип шаблона');
        }
    }

    public function document() {

        $id_type_template = 7;

        $object_document = new Document();
        $object_document->link = $this->db_link;
        $list_user_templates = $object_document->get_my_doc_temp();
        $list_templates = $object_document->get_all_doc_temp();

        $sort_list_templates = $this->sortByName($list_templates);
        $sort_list_user_templates = $this->sortByName($list_user_templates);

        // пагинация списка шаблонов документов
        //$templates_paginator = new Paginator($list_templates, 12, 'templ_page');
        //$list_templates = paginate($templates_paginator, $this);

        // пагинация пользовательского списка шаблонов документов
        //$user_templates_paginator = new Paginator($list_user_templates, 12, 'user_templ_page');
        //$list_user_templates = paginate($user_templates_paginator, $this);

        $this->setData(array(
            'Список пользовательских шаблонов' => $list_user_templates,
            'Список всех шаблонов документов' => $list_templates
        ));

        $this->setValuesHelper(array(
            'id_type_template' => $id_type_template,
            'array_templates' => $sort_list_templates,
            'array_user_templates' => $sort_list_user_templates,
            // список пагинаторов, передаваемых в Helper, для работы с ними в шаблоне
            'paginator' => array(
                //'all_templates' => $templates_paginator,
                //'user_templates' => $user_templates_paginator,
            ),
        ));

        $this->setObjectResponse(
            __FUNCTION__,
            $this->mergeWithDefaultCSS($this->arrayCSS),
            $this->mergeWithDefaultJS($this->arrayJS),
            'Шаблоны документов',
            $this->data
        );
    }

    public function alert() {
        $id_type_template = 16;

        $object_catalog_template = new Alert();
        $object_catalog_template->link = $this->db_link;
        $list_all_templates = $object_catalog_template->get_all_templates();

        $sort_list_all_templates = $this->sortByName($list_all_templates);

        // пагинация списка шаблонов оповещений
        //$templates_paginator = new Paginator($list_all_templates, 12, 'templ_page');
        //$list_all_templates = paginate($templates_paginator, $this);
        
        $object_template = new Template();
        $object_template->link = $this->db_link;
        $object_template->id_type = $id_type_template;
        $list_user_templates = $object_template->get_user_templates();

        $sort_list_user_templates = $this->sortByName($list_user_templates);

        // пагинация пользовательского списка шаблонов оповещений
        //$user_templates_paginator = new Paginator($list_user_templates, 12, 'user_templ_page');
        //$list_user_templates = paginate($user_templates_paginator, $this);
        
        $this->setData(array(
            'Все оповещения' => $sort_list_all_templates,
            'Все пользовательские оповещения' => $sort_list_user_templates
        ));

        $this->setValuesHelper(array(
            'id_type_template' => $id_type_template,
            'array_templates' => $list_all_templates,
            'array_user_templates' => $list_user_templates,
            // список пагинаторов, передаваемых в Helper, для работы с ними в шаблоне
            'paginator' => array(
                //'all_templates' => $templates_paginator,
                //'user_templates' => $user_templates_paginator,
            )
        ));

        $this->setObjectResponse(
            __FUNCTION__,
            $this->mergeWithDefaultCSS($this->arrayCSS),
            $this->mergeWithDefaultJS($this->arrayJS),
            'Оповещения',
            $this->data
        );
    }
    
    public function mapknowledge() {
        $id_type_template = 8;

        $object_template = new Template();
        $object_template->link = $this->db_link;
        $object_template->id_type = $id_type_template;
        $list_user_templates = $object_template->get_user_templates();

        $sort_list_user_templates = $this->sortByName($list_user_templates);

        // пагинация пользовательского списка шаблонов карт знаний
        //$user_templates_paginator = new Paginator($list_user_templates, 12, 'user_templ_page');
        //$list_user_templates = paginate($user_templates_paginator, $this);
        
        $object_mapknowledge = new Mapknowledge();
        $object_mapknowledge->link = $this->db_link;
        $list_templates = $object_mapknowledge->get_all_kz_temp();

        $sort_list_templates = $this->sortByName($list_templates);

        // пагинация списка шаблонов карт знаний
        //$templates_paginator = new Paginator($list_templates, 12, 'templ_page');
        //$list_templates = paginate($templates_paginator, $this);
        
        $this->setValuesHelper(array(
            'id_type_template' => $id_type_template,
            'array_templates' => $sort_list_templates,
            'array_user_templates' => $sort_list_user_templates,
            // список пагинаторов, передаваемых в Helper, для работы с ними в шаблоне
            'paginator' => array(
                //'all_templates' => $templates_paginator,
                //'user_templates' => $user_templates_paginator,
            ),
        ));

        $this->setObjectResponse(
            __FUNCTION__,
            $this->mergeWithDefaultCSS($this->arrayCSS),
            $this->mergeWithDefaultJS($this->arrayJS),
            'Шаблоны карт знаний',
            $this->data
        );

    }
    
    public function catalog() {
        $id_type_template = 15;
        
        $object_catalog_template = new CatalogTemplate();
        $object_catalog_template->link = $this->db_link;
        $list_all_templates = $object_catalog_template->get_all_templates();

        $sort_list_all_templates = $this->sortByName($list_all_templates);

        // пагинация списка шаблонов рубрик
        //$templates_paginator = new Paginator($list_all_templates, 12, 'templ_page');
        //$list_all_templates = paginate($templates_paginator, $this);
        
        $object_template = new Template();
        $object_template->link = $this->db_link;
        $object_template->id_type = $id_type_template;
        $list_user_templates = $object_template->get_user_templates();

        $sort_list_user_templates = $this->sortByName($list_user_templates);

        // пагинация пользовательского списка шаблонов рубрик
        //$user_templates_paginator = new Paginator($list_user_templates, 12, 'user_templ_page');
        //$list_user_templates = paginate($user_templates_paginator, $this);
        
        $this->setData(array(
            'Все шаблоны рубрик' => $sort_list_all_templates,
            'Все пользовательские шаблоны рубрик' => $sort_list_user_templates
        ));

        $this->setValuesHelper(array(
            'id_type_template' => $id_type_template,
            'array_templates' => $list_all_templates,
            'array_user_templates' => $list_user_templates,
            // список пагинаторов, передаваемых в Helper, для работы с ними в шаблоне
            'paginator' => array(
                //'all_templates' => $templates_paginator,
                //'user_templates' => $user_templates_paginator,
            ),
        ));

        $this->setObjectResponse(
            __FUNCTION__,
            $this->mergeWithDefaultCSS($this->arrayCSS),
            $this->mergeWithDefaultJS($this->arrayJS),
            'Шаблоны рубрик',
            $this->data
        );
    }
    
    public function search() {
        $id_type_template = 9;
        
        $object_search_template = new SearchTemplate();
        $object_search_template->link = $this->db_link;
        $list_all_templates = $object_search_template->get_all_templates();

        $sort_list_all_templates = $this->sortByName($list_all_templates);

        // пагинация списка шаблонов поиска
        //$templates_paginator = new Paginator($list_all_templates, 12, 'templ_page');
        //$list_all_templates = paginate($templates_paginator, $this);

        $object_template = new Template();
        $object_template->link = $this->db_link;
        $object_template->id_type = $id_type_template;
        $list_user_templates = $object_template->get_user_templates();

        $sort_list_user_templates = $this->sortByName($list_user_templates);
    
        // пагинация пользовательского списка шаблонов поиска
        //$user_templates_paginator = new Paginator($list_user_templates, 12, 'user_templ_page');
        //$list_user_templates = paginate($user_templates_paginator, $this);
        
        $this->setData(array(
            'Все шаблоны поиска' => $sort_list_all_templates,
            'Все пользовательские шаблоны поиска' => $sort_list_user_templates
        ));

        $this->setValuesHelper(array(
            'id_type_template' => $id_type_template,
            'array_templates' => $list_all_templates,
            'array_user_templates' => $list_user_templates,
            // список пагинаторов, передаваемых в Helper, для работы с ними в шаблоне
            'paginator' => array(
                //'all_templates' => $templates_paginator,
                //'user_templates' => $user_templates_paginator,
            ),
        ));

        $this->setObjectResponse(
            __FUNCTION__,
            $this->mergeWithDefaultCSS($this->arrayCSS),
            $this->mergeWithDefaultJS($this->arrayJS),
            'Шаблоны поиска',
            $this->data
        );
    }
    
    public function ajaxCopy() {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                
                $id_type = $this->getPOST('id_type');
                $id_template = $this->getPOST('id');
                
                $object_template = new Template();
                $object_template->link = $this->db_link;
                $object_template->id = $id_template;

                $result_copy = $object_template->{$this->list_templates[$id_type]['method_copy']}();

                $this->setObjectAjaxResponse('string', $result_copy[0]);
                
            } else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    // удаление всех типов шаболонов через один метод
    public function ajaxDelete($id_template) {
        if ($this->isAjax()) {
            if ($this->isPOST()) {

            } else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    public function ajaxAddTemplateCatalog() {
        if ($this->isAjax()) {
            if ($this->isPOST()) {

                $name = $this->getPOST('name');

                $object_catalog_template = new CatalogTemplate();
                $object_catalog_template->link = $this->db_link;
                $object_catalog_template->name_catalog = $name;
                $result_add_catalog_template = $object_catalog_template->add_catalog_temp();

                $this->setObjectAjaxResponse('string', $result_add_catalog_template[0]);

            } else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    public function ajaxSearchTemplateByName() {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                
                $id_type = $this->getPOST('id_type');
                $name = $this->getPOST('name');
                
                $object_template = new Template();
                $object_template->link = $this->db_link;
                $object_template->id_type = $id_type;
                $object_template->name = $name;
                $result_search_templates = $object_template->search_template();
                
                $this->setValuesHelper(array(
                    'id_type_template' => $id_type,
                    'search_name_template' => $name,
                    'array_templates' => $result_search_templates
                ));
                
                $this->setObjectAjaxResponse('html', $this->list_templates[$id_type]['result_search']);
                
            } else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    public function ajaxDeleteTemplateCatalog($id_template)
    {
        if ($this->isAjax()) {
            if ($this->isPOST()) {

                $object_catalog_template = new CatalogTemplate();
                $object_catalog_template->temp_id = $id_template;
                $object_catalog_template->link = $this->db_link;
                $result_drop_template = $object_catalog_template->drop_template();

                if ($result_drop_template[0] == $id_template) {
                    $array_result = array(
                        "reload_page" => true,
                        "result_delete" => $result_drop_template[0],
                    );
                }
                else {
                    $array_result = array(
                        "result_delete" => false,
                    );
                }
                $this->setObjectAjaxResponse('json', $array_result);

            } else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    public function ajaxAddTemplateDocument () {
	$errors = array();
	$success = array();
        if ($this->isAjax()) {
            if ($this->isPOST()) {
		
                $array_tags = _fw_json_decode_assoc($this->getPOST('tags'));
                $array_attributes = $this->getPOST('attributes');

                foreach ($array_attributes as $key => $value) {
                    $array_attributes[$key] = _fw_json_decode_assoc($value);
                }

                $name_template = array_shift($array_attributes);

                $object_document = new Document();
                $object_document->link = $this->db_link;
                $object_document->tegs = $array_tags;
                $object_document->attributes = $array_attributes;
                $object_document->name = $name_template['value'];
                $result_add_template = $object_document->addTemplate();
		
                if ($result_add_template[0] < 0) {
                    $errors['add_template'] = 'Шаблон с таким именем уже существует';
                } else {
                    $success['add_template'] = 'Шаблон успешно сохранен';
                    $this->uploadFile($result_add_template[0], 'doc_file');
                }
		
                if (count($errors) > 0) {
                    $this->setValuesHelper(array(
                        'array_errors_form' => $errors,
                        'array_success_form' => $success
                    ));
                    $this->setObjectAjaxResponse('html', 'ErrorsMainForm');
                } else {
                    $this->setObjectAjaxResponse('json', 1);
                }

            } else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    public function ajaxEditTemplateDocument($id_template) {
	$errors = array();
	$success = array();
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                if (isset($id_template)) {

                    // получение данных с формы
                    $array_tags = _fw_json_decode_assoc($this->getPOST('tags'));
                    $array_attributes = $this->getPOST('attributes');
                    $delete_file = $this->getPOST('delete_file');

                    foreach ($array_attributes as $key => $value) {
                        $array_attributes[$key] = _fw_json_decode_assoc($value);
                    }

                    $name_template = array_shift($array_attributes);

                    $object_document = new Document();
                    $object_document->link = $this->db_link;
                    $object_document->id = $id_template;
                    $object_document->name = $name_template['value'];
                    $object_document->tegs = $array_tags;
                    $object_document->attributes = $array_attributes;

                    $result_edit_template = $object_document->editTemplate();
                    if ($result_edit_template[0] != $id_template) {
                        $errors['edit_template'] = 'Шаблон с таким именем уже существует';
                    }
                    else {
                        $success['edit_template'] = 'Шаблон успешно сохранен';
                        if ($delete_file) {
                            $this->deleteFile($id_template, $delete_file);
                        }
                        $this->uploadFile($result_edit_template[0], 'doc_file');
                    }

                    if (count($errors) > 0) {
                        $this->setValuesHelper(array(
                            'array_errors_form' => $errors,
                            'array_success_form' => $success
                        ));
                        $this->setObjectAjaxResponse('html', 'ErrorsMainForm');
                    } else {
                        $this->setObjectAjaxResponse('json', 1);
                    }

                } else {
                    $this->setErrorResponse('404', 'Не указан ID редактируемого шаблона');
                }
            } else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    public function ajaxDeleteTemplateDocument($id_template) {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                if (isset($id_template)) {

                    $object_document = new Document();
                    $object_document->link = $this->db_link;
                    $object_document->id = $id_template;
                    $result_del_template = $object_document->delTemplate();

                    if ($result_del_template[0] == 1) {
                        $array_result = array(
                            "link_back" => '/template/document/',
                            "result_delete" => $result_del_template[0],
                        );
                    }
                    else {
                        $array_result = array(
                            "result_delete" => false,
                        );
                    }
                    $this->setObjectAjaxResponse('json', $array_result);
                } else {
                    $this->setErrorResponse('404', 'Не указан ID удаляемого шаблона');
                }
            } else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    public function ajaxAddTemplateMapknowledge() {
	$errors = array();
	$success = array();
        if ($this->isAjax()) {
            if ($this->isPOST()) {

                $array_tags = _fw_json_decode_assoc($this->getPOST('tags'));
                $array_attributes = $this->getPOST('attributes');

                foreach ($array_attributes as $key => $value) {
                    $array_attributes[$key] = _fw_json_decode_assoc($value);
                }

                $name_template = array_shift($array_attributes);
                $object_mapknowledge = new Mapknowledge();
                $object_mapknowledge->link = $this->db_link;
                $object_mapknowledge->name = $name_template['value'];
                $object_mapknowledge->tags = $array_tags;
                $object_mapknowledge->attributes = $array_attributes;
                $result_add_template = $object_mapknowledge->addTemplate();

                if ($result_add_template[0] < 0) {
                    $errors['add_template'] = 'Шаблон с таким именем уже существует';
                } else {
                    $success['add_template'] = 'Шаблон успешно сохранен';
                }

                if (count($errors) > 0) {
                    $this->setValuesHelper(array(
                        'array_errors_form' => $errors,
                        'array_success_form' => $success
                    ));
                    $this->setObjectAjaxResponse('html', 'ErrorsMainForm');
                } else {
                    $this->setObjectAjaxResponse('json', 1);
                }

            } else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    public function ajaxEditTemplateMapknowledge($id_template){
	$errors = array();
	$success = array();
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                if (isset($id_template)) {

                    $array_tags = _fw_json_decode_assoc($this->getPOST('tags'));
                    $array_attributes = $this->getPOST('attributes');

                    foreach ($array_attributes as $key => $value) {
                        $array_attributes[$key] = _fw_json_decode_assoc($value);
                    }

                    $name_template = array_shift($array_attributes);
                    $object_mapknowledge = new Mapknowledge();
                    $object_mapknowledge->link = $this->db_link;
                    $object_mapknowledge->id = $id_template;
                    $object_mapknowledge->name = $name_template['value'];
                    $object_mapknowledge->tags = $array_tags;
                    $object_mapknowledge->attributes = $array_attributes;
                    $result_edit_template = $object_mapknowledge->editTemplate();

                    if ($result_edit_template[0] < 0) {
                        $errors['edit_template'] = 'Шаблон с таким именем уже существует';
                    } else {
                        $success['edit_template'] = 'Шаблон успешно сохранен';
                    }

                    if (count($errors) > 0) {
                        $this->setValuesHelper(array(
                            'array_errors_form' => $errors,
                            'array_success_form' => $success
                        ));
                        $this->setObjectAjaxResponse('html', 'ErrorsMainForm');
                    } else {
                        $this->setObjectAjaxResponse('json', 1);
                    }

                } else {
                    $this->setErrorResponse('404', 'Не указан ID редактируемого шаблона');
                }
            } else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    public function ajaxDeleteTemplateMapknowledge($id_template) {
        if ($this->isAjax()) {
            if ($this->isPOST()) {

                $object_mapknowledge = new Mapknowledge();
                $object_mapknowledge->link = $this->db_link;
                $object_mapknowledge->id = $id_template;
                $result_del_template = $object_mapknowledge->delTemplate();

                if ($result_del_template[0] == 1) {
                    $array_result = array(
                        "link_back" => '/template/mapknowledge/',
                        "result_delete" => $result_del_template[0],
                    );
                }
                else {
                    $array_result = array(
                        "result_delete" => false,
                    );
                }
                $this->setObjectAjaxResponse('json', $array_result);

            } else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }
    
    public function ajaxDeleteTemplateSearch($id_template)
    {
        if ($this->isAjax()) {
            if ($this->isPOST()) {

                $object_catalog_template = new SearchTemplate();
                $object_catalog_template->id = $id_template;
                $object_catalog_template->link = $this->db_link;
                $result_drop_template = $object_catalog_template->drop_template();

                if ($result_drop_template[0] == $id_template) {
                    $array_result = array(
                        "reload_page" => true,
                        "result_delete" => $result_drop_template[0],
                    );
                }
                else {
                    $array_result = array(
                        "result_delete" => false,
                    );
                }
                $this->setObjectAjaxResponse('json', $array_result);

            } else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    public function ajaxDeleteTemplateAlert($id_template) {
        if ($this->isAjax()) {
            if ($this->isPOST()) {

                $object_catalog_template = new Alert();
                $object_catalog_template->id = $id_template;
                $object_catalog_template->link = $this->db_link;
                $result_drop_template = $object_catalog_template->drop_template();

                if ($result_drop_template[0] == $id_template) {
                    $array_result = array(
                        "reload_page" => true,
                        "result_delete" => $result_drop_template[0],
                    );
                }
                else {
                    $array_result = array(
                        "result_delete" => false,
                    );
                }
                $this->setObjectAjaxResponse('json', $array_result);

            } else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

}
