<?php

class mapknowledgeController extends Controller {

    function __construct() {
        parent::__construct();
        $this->name_object = 'mapknowledge';
        $this->arrayCSS  = array(
            '/pages/mapknowledge/mapknowledge_style.css'
        );
        $this->object_helper = $this->setObjectHelper($this->name_object);
        $this->object_helper->user_styles = $this->user_info->profile_settings;
    }

    public function index() {

        $object_mapknowledge = new Mapknowledge();
        $object_mapknowledge->link = $this->db_link;
        $list_mapknowledge = $object_mapknowledge->get_all_kzRead();

        foreach ($list_mapknowledge as $key => $value) {
            $rights = $this->getRightsObject($value['id']);
            $list_mapknowledge[$key]['rights']['grant'] = $rights->canGrant();
            $list_mapknowledge[$key]['rights']['read'] = $rights->canRead();
            $list_mapknowledge[$key]['rights']['update'] = $rights->canUpdate();
            $list_mapknowledge[$key]['rights']['drop'] = $rights->canDrop();
        }

        $object_alert = new Alert();
        $object_alert->link = $this->db_link;
        $list_alerts = $object_alert->get_my_templates();
	$list_active_alerts = array();

        foreach ($list_alerts as $key => $value) {
            $object_alert->id = $value['id'];
            $flag_active = $object_alert->isActive();
            if ($flag_active) {
                $list_active_alerts[] = $list_alerts[$key];
            }
        }

        $object_user_info = $this->user_info;
        $user_info['add_mapkmowledge'] = $object_user_info->canAddKz();
        $user_info['id'] = $this->user_info->id;
	
        $this->setValuesHelper(array(
            'left_block' => 'mapknowledge',
            'array_mk' => $list_mapknowledge,
            'array_active_alerts' => $list_active_alerts,
            'user_info' => $user_info
        ));
        
        $this->setObjectResponse(
            __FUNCTION__,
            $this->mergeWithDefaultCSS($this->arrayCSS),
            $this->mergeWithDefaultJS($this->arrayJS),
            'Карты знаний',
            isset($this->setData)
        );
    }

    public function edit($id_object) {
        $this->addNewPathJS('/js/add_edit_objects.js');
        if ($id_object > 0) {
            $right_update = $this->getRightsObject($id_object, 'update');
            if ($right_update) {
                $object_mapknowledge = new Catalog();
                $object_mapknowledge->link = $this->db_link;
                $object_mapknowledge->id = $id_object;
                $name_mapknowledge = $object_mapknowledge->get_this_catalog();

                $object_tag = new Tag();
                $object_tag->link = $this->db_link;
                $list_tags = $object_tag->get_all_tegs();

                $object_attribute = new Attribute();
                $object_attribute->link = $this->db_link;
                $list_attributes = $object_attribute->get_all_attr();

                $object_tag->obj_id = $id_object;
                $list_tags_object = $object_tag->get_tags_obj();

                $object_attribute->obj_id = $id_object;
                $list_attributes_object = $object_attribute->get_attributes_of_obj();

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
                    'array_tags_object' => $list_tags_object,
                    'array_attributes_object' => $list_attributes_object,
                    'array_tags' => $list_tags,
                    'array_attributes' => $list_attributes,
                ));

                $this->setValuesHelper(array(
                    'id_current_object' => $id_object,
                    'array_tags_object' => $list_tags_object,
                    'array_attributes_object' => $list_attributes_object,
                    'array_tags' => $list_tags,
                    'array_attributes' => $list_attributes,
                    'name_current_object' => $name_mapknowledge['name'],
                    'type_submit' => 'submit_edit',
                    'id_for_ajax' => $id_object,
                    'url_ajax_handler' => '/mapknowledge/ajaxEditMapknowledge/',
                    'link_come_back' => '/mapknowledge/'
                ));


                $this->setObjectResponse(
                    __FUNCTION__,
                    $this->mergeWithDefaultCSS($this->arrayCSS),
                    $this->mergeWithDefaultJS($this->arrayJS),
                    'Редактирование карты знаний',
                    $this->data
                );
            } else {
                $this->setErrorResponse('403', 'Ошибка доступа', 403);
            }
        }
        else {
            $this->setErrorResponse('404', 'Ошибка отображения');
        }



    }

    public function templates() {
        
        $object_template = new Template();
        $object_template->link = $this->db_link;
        $object_template->id_type = 8;
        $list_templates_mapknowledge = $object_template->get_user_templates();

        $this->setValuesHelper(array(
            'array_templates' => $list_templates_mapknowledge
        ));
        
        $this->setObjectResponse(
            __FUNCTION__,
            $this->mergeWithDefaultCSS($this->arrayCSS),
            $this->mergeWithDefaultJS($this->arrayJS),
            'Выбор шаблона для создания карты знаний',
            $this->data
        );
    }

    public function add($id_template = 0) {

        $this->addNewPathJS('/js/add_edit_objects.js');

        $object_tag = new Tag();
        $object_tag->link = $this->db_link;
        $list_tags = $object_tag->get_all_tegs();

        $object_attribute = new Attribute();
        $object_attribute->link = $this->db_link;
        $list_attributes = $object_attribute->get_all_attr();

        $name_block = 'Добавление карты знаний';

        if ($id_template > 0) {

            $object_catalog = new Catalog();
            $object_catalog->link = $this->db_link;
            $object_catalog->id = $id_template;
            $name_template = $object_catalog->get_this_catalog();
            
            $object_tag->obj_id = $id_template;
            $list_tags_object = $object_tag->get_tags_obj();

            $object_attribute->obj_id = $id_template;
            $list_attributes_object = $object_attribute->get_attributes_of_obj();

            //проверка на совпадение дескрипторов
            foreach ($list_tags_object as $key => $value) {
                foreach ($list_tags as $key2 => $value2) {
                    if ($value["id"] == $value2["id"]) unset($list_tags[$key2]);
                }
            }

            //проверка на совпадение атрибутов
            foreach ($list_attributes_object as $key => $value) {
                unset($list_attributes_object[$key]['value']);
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

            $this->setValuesHelper(array(
                'array_tags_object' => $list_tags_object,
                'array_attributes_object' => $list_attributes_object,
            ));

            $name_block = 'Создание карты знаний по шаблону "'.$name_template['name'] .'"';
        }

        $this->setValuesHelper(array(
            'title_block' => $name_block,
            'array_tags' => $list_tags,
            'array_attributes' => $list_attributes,
            'type_submit' => 'submit_add_mk',
            'url_ajax_handler' => '/mapknowledge/ajaxAddMapknowledge/',
            'link_come_back' => '/mapknowledge/'
        ));

        $this->setObjectResponse(
            __FUNCTION__,
            $this->mergeWithDefaultCSS($this->arrayCSS),
            $this->mergeWithDefaultJS($this->arrayJS),
            'Создание карты знаний',
            $this->data
        );

    }

    public function view($id_object = 0) {
        if (intval($id_object)) {
            if ($id_object != 0) {
                $object_mapknowledge = new Mapknowledge();
                $object_mapknowledge->link = $this->db_link;
                $list_mapknowledge = $object_mapknowledge->get_all_kzRead();

                foreach ($list_mapknowledge as $key => $value) {
                    $rights = $this->getRightsObject($value['id']);
                    $list_mapknowledge[$key]['rights']['grant'] = $rights->canGrant();
                    $list_mapknowledge[$key]['rights']['read'] = $rights->canRead();
                    $list_mapknowledge[$key]['rights']['update'] = $rights->canUpdate();
                    $list_mapknowledge[$key]['rights']['drop'] = $rights->canDrop();
                }

                $object_alert = new Alert();
                $object_alert->link = $this->db_link;
                $list_alerts = $object_alert->get_my_templates();

                /*foreach ($list_alerts as $key => $value) {
                    $object_alert->id = $value['id'];
                    $flag_active = $object_alert->isActive();
                    if ($flag_active) {
                        $list_active_alerts[] = $list_alerts[$key];
                    }
                }*/

                $object_user_info = $this->user_info;
                $user_info['add_mapkmowledge'] = $object_user_info->canAddKz();
		$user_info['id'] = $this->user_info->id;

                $object_catalog = new Catalog();
                $object_catalog->id = $id_object;
                $object_catalog->link = $this->db_link;
                $object_catalog->parent_id = $id_object;
                $type_id_first_parent = $object_catalog->getTypeFirstParent();
                $list_catalogs = $object_catalog->get_child_Catalogs(); // получени вложенных каталогов

                // пагинация списка каталогов
                $catalogs_paginator = new Paginator($list_catalogs, 16, 'cat_page');
                $list_catalogs = paginate($catalogs_paginator, $this); 

                foreach ($list_catalogs as $key => $value) {
                    $rights = $this->getRightsObject($value['id']);
                    $list_catalogs[$key]['rights']['grant'] = $rights->canGrant();
                    $list_catalogs[$key]['rights']['read'] = $rights->canRead();
                    $list_catalogs[$key]['rights']['update'] = $rights->canUpdate();
                    $list_catalogs[$key]['rights']['drop'] = $rights->canDrop();
                }

                $first_parent = $object_catalog->getIdTop();

                $object_catalog->id = $id_object;
                $current_object = $object_catalog->get_this_catalog(); // получение информации о текущем каталоге
                $parents_path = $object_catalog->get_parent_path(); // получения списка родительских каталогов

                $object_documents = new Document();
                $object_documents->link = $this->db_link;
                $object_documents->parent_id = $id_object;
                $list_documents = $object_documents->get_Documents_of_cat(); // получение документов в текущем каталоге

                // пагинация списка документов в каталоге
                $documents_paginator = new Paginator($list_documents, 15, 'doc_page');
                $list_documents = paginate($documents_paginator, $this); 

                $object_attributes = new Attribute();
                $object_attributes->link = $this->db_link;

                $object_tags = new Tag();
                $object_tags->link = $this->db_link;

                foreach ($list_documents as $key => $value) {
                    $object_attributes->obj_id = $value['id'];
                    $object_tags->obj_id = $value['id'];
                    $object_documents->id = $value['id'];
                    $list_documents[$key]['attributes'] = $object_attributes->get_attributes_of_obj(); // получение атрибутов для докумнта
                    $list_documents[$key]['tags'] = $object_tags->get_tags_obj(); // получение тегов для документа
                    $list_documents[$key]['link_download'] = $object_documents->downloadFile();

                    $rights = $this->getRightsObject($value['id']);
                    $list_documents[$key]['rights']['grant'] = $rights->canGrant();
                    $list_documents[$key]['rights']['read'] = $rights->canRead();
                    $list_documents[$key]['rights']['update'] = $rights->canUpdate();
                    $list_documents[$key]['rights']['drop'] = $rights->canDrop();
                }

                $object_user_info = $this->user_info;
                $user_info['name_user'] = $object_user_info->name;
                $user_info['add_database'] = $object_user_info->canAddDb();
                $user_info['add_document'] = $object_user_info->canAddDoc();
                $user_info['add_catalog'] = $object_user_info->canAddCat();

                $object_message = new Message();
                $object_message->id_kz = $first_parent;
                $list_messages = $object_message->getMessage();

                // пагинация списка комментариев
                $messages_paginator = new Paginator($list_messages, 15, 'mes_page');
                $list_messages = paginate($messages_paginator, $this); 

                if ($this->getCookies('list_copy')) {
                    $print_button_insert = 1;
                }

                $this->setData(array(
                    'Список карт знаний' => $list_mapknowledge,
                    'Список вложенных каталогов' => $list_catalogs,
                    'Список вложенных докуметов' => $list_documents
                ));

                $this->setValuesHelper(array(
                    'left_block' => 'mapknowledge',
                    'id_current_object' => $id_object,
                    'name_type_first_parent' => _fw_translate_type_object($type_id_first_parent, 'eng'),
                    'name_current_object' => $current_object['name'],
                    'array_mk' => $list_mapknowledge,
                    'array_catalogs' => $list_catalogs,
                    'array_documents' => $list_documents,
                    'array_messages' => $list_messages,
                    'parents_path' => $parents_path,
                    'id_main_parent_object' => $first_parent,
                    'user_info' => $user_info,
                    'button_insert' => isset($print_button_insert),
                    // список пагинаторов, передаваемых в Helper, для работы с ними в шаблоне
                    'paginator' => array(
                        'catalogs' => $catalogs_paginator,
                        'documents' => $documents_paginator,
                        'messages' => $messages_paginator,
                    ),
                ));

                $this->setObjectResponse(
                    __FUNCTION__,
                    $this->mergeWithDefaultCSS($this->arrayCSS),
                    $this->mergeWithDefaultJS($this->arrayJS),
                    'КЗ '.$id_object,
                    $this->data
                );

            } else {
                $this->setErrorResponse('404', 'Не указан ID просматриваемого объекта');
            }
        } else {
            $this->setErrorResponse('404', 'ID просматриваемого объекта некорректен');
        }
    }
    
    public function ajaxAddMapknowledge() {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                // получение данных с формы
                $array_tags = _fw_json_decode_assoc($this->getPOST('tags'));
                $array_attributes = $this->getPOST('attributes');
                $name_template = $this->getPOST('template');
                
                foreach ($array_attributes as $key => $value) {
                    $array_attributes[$key] = _fw_json_decode_assoc($value);
                }

                $name_mapknowledge = array_shift($array_attributes);

                $object_mapknowledge = new Mapknowledge();
                $object_mapknowledge->link = $this->db_link;
                $object_mapknowledge->name = $name_mapknowledge['value'];
                $object_mapknowledge->user_id = $this->user_info->id;
                $object_mapknowledge->tags = $array_tags;
                $object_mapknowledge->attributes = $array_attributes;
                $result_add_mapknowledge = $object_mapknowledge->addKZ();

                if ($result_add_mapknowledge[0] < 0) {
                    $errors['add_mk'] = 'Карта знаний с таким именем уже существует';
                }

                if (!empty($name_template)) {
                    $object_mapknowledge->name = $name_template;
                    $result_add_template = $object_mapknowledge->addTemplate();
                    if ($result_add_template[0] < 0) {
                        $errors['add_template'] = 'Шаблон с таким именем уже существует.';
                    } else {
                        $success['add_template'] = 'Шаблон успешно сохранен';
                    }
                }
                if (isset($errors) && count($errors) > 0) {
                    $this->setValuesHelper(array(
                        'array_errors_form' => $errors,
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

    public function ajaxEditMapknowledge ($id_mapknowledge) {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                if (isset($id_mapknowledge)) {
                    // получение данных с формы
                    $array_tags = _fw_json_decode_assoc($this->getPOST('tags'));
                    $array_attributes = $this->getPOST('attributes');
                    $name_template = $this->getPOST('template');

                    foreach ($array_attributes as $key => $value) {
                        $array_attributes[$key] = _fw_json_decode_assoc($value);
                    }

                    $name_mapknowledge = array_shift($array_attributes);

                    $object_mapknowledge = new Mapknowledge();
                    $object_mapknowledge->link = $this->db_link;
                    $object_mapknowledge->name = $name_mapknowledge['value'];
                    $object_mapknowledge->id = $id_mapknowledge;
                    $object_mapknowledge->tags = $array_tags;
                    $object_mapknowledge->attributes = $array_attributes;
                    $result_edit_mapknowledge = $object_mapknowledge->editKZ();

                    if ($result_edit_mapknowledge[0] != $id_mapknowledge) {
                        $errors['edit_mk'] = 'Карта знаний с таким именем существует';
                    }

                    if (!empty($name_template)) {
                        $object_mapknowledge->name = $name_template;
                        $result_add_template = $object_mapknowledge->addTemplate();
                        if ($result_add_template[0] < 0) {
                            $errors['add_template'] = 'Шаблон с таким именем уже существует.';
                        } else {
                            $success['add_template'] = 'Шаблон успешно сохранен';
                        }
                    }

                    if (isset($errors) && count($errors) > 0) {
                        $this->setValuesHelper(array(
                            'array_errors_form' => $errors,
                        ));
                        $this->setObjectAjaxResponse('html', 'ErrorsMainForm');
                    } else {
                        $this->setObjectAjaxResponse('json', 1);
                    }
                } else {
                    $this->setErrorResponse('404', 'Не указан редактируемая карта знаний');
                }
            } else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    public function ajaxDeleteMapknowledge ($id_mapknowledge) {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                if (isset($id_mapknowledge)) {
                    $object_mapknowledge = new Mapknowledge();
                    $object_mapknowledge->link = $this->db_link;
                    $object_mapknowledge->id = intval($id_mapknowledge);
                    $result_del_mapknowledge = $object_mapknowledge->delete();
                    if ($result_del_mapknowledge[0] == $id_mapknowledge) {
                        $array_result = array(
                            "reload_page" => false,
                            "result_delete" => $result_del_mapknowledge[0],
                            "link_back" => '/mapknowledge/',
                        );
                    }
                    else {
                        $array_result = array(
                            "result_delete" => false,
                        );
                    }
                    $this->setObjectAjaxResponse('json', $array_result);
                }
                else {
                    $this->setErrorResponse('404', 'Не известна удаляемая карта знаний');
                }
            }
            else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        }
        else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    public function ajaxAddMessage() {
        if ($this->isAjax()) {
            if ($this->isPOST()) {

                $object_message = new Message();
                $object_message->id_kz = $this->getPOST('idkz');
                $object_message->name_user = $this->getPOST('userName');
                $object_message->text = $this->getPOST('txt');
                $object_message->date = 't';
                $result_add_message = $object_message->setMessage();

                $this->setObjectAjaxResponse('string', $result_add_message);
                
            }
            else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        }
        else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }
    
    public function ajaxDeleteMessage($id_message) {
        if ($this->isAjax()) {
            if ($this->isPOST()) {

                $object_message = new Message();
                $object_message->id = $id_message;
                $result_del_message = $object_message->delMessage();

                if ($result_del_message > 0) {
                    $array_result = array(
                        "reload_page" => true,
                        "result_delete" => true,
                    );
                }
                else {
                    $array_result = array(
                        "result_delete" => false,
                    );
                }
                $this->setObjectAjaxResponse('json', $array_result);

            }
            else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        }
        else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    public function ajaxEditMessage($id_message) {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                $text_message = $this->getPOST('text');

                $object_message = new Message();
                $object_message->text = $text_message;
                $object_message->id = $id_message;
                $result_edit_message = $object_message->editMessage();

                $this->setObjectAjaxResponse('string', $result_edit_message);
            }
            else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        }
        else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    public function ajaxListBuffer () {
        if ($this->isAjax()) {
            if ($this->isPOST()) {

                $array_catalogs = array();
                $array_documents = array();

                $id_parent = $this->getPOST('id_parent');
                $object = $this->getPOST('object'); // пока эта переменная сохраняется просто так
                $cookies_json = $this->getCookies('list_copy');
                $array_id = _fw_json_decode_assoc($cookies_json);

                $object_document = new Document();
                $object_document->link = $this->db_link;

                $object_catalog = new Catalog();
                $object_catalog->link = $this->db_link;

                if (count($array_id['document'])) {
                    foreach ($array_id['document'] as $key => $id) {
                        $object_document->id = $id;
                        $name_document = $object_document->get_Document_Name_by_id();
                        if ($name_document[0]) {
                            $type_first_parent = $object_document->getTypeFirstParent();
                            $array_documents[] = array(
                                'id' => $id,
                                'name' => $name_document[0],
                                'name_type_first_parent' => _fw_translate_type_object($type_first_parent, 'eng')
                            );
                        }
                    }
                }

                if (count($array_id['catalog'])) {
                    foreach ($array_id['catalog'] as $key => $id) {
                        $object_catalog->id = $id;
                        $name_catalog = $object_catalog->get_this_catalog();
                        if ($name_catalog['name']) {
                            $type_first_parent = $object_catalog->getTypeFirstParent();
                            $array_catalogs[] = array(
                                'id' => $id,
                                'name' => $name_catalog['name'],
                                'name_type_first_parent' => _fw_translate_type_object($type_first_parent, 'eng')
                            );
                        }
                    }
                }

                $this->setValuesHelper(array(
                    'array_documents' => $array_documents,
                    'array_catalogs' => $array_catalogs,
                    'id_parent' => $id_parent
                ));

                if ($id_parent > 0) {
                    $this->setObjectAjaxResponse('html', 'FormBuffer');
                } else {
                    $this->setErrorResponse('404', 'Не указан родительский объект для копирования');
                }
            }
            else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        }
        else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    public function ajaxInsertFromBuffer($id_parent) {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                if (isset($id_parent)) {
                    $array_errors = array();
                    $counter_catalogs = 0;
                    $counter_documents = 0;

                    $array_id_json = $this->getPOST('array-for-insert');
                    $array_id = _fw_json_decode_assoc($array_id_json);

                    $object_document = new Document();
                    $object_document->link = $this->db_link;

                    $object_catalog = new Catalog();
                    $object_catalog->link = $this->db_link;
                    $object_catalog->parent_id = $id_parent;

                    if (count($array_id)) {
                        foreach ($array_id as $key => $object) {
                            if ($object['name'][0] == 'c') {
                                $counter_catalogs++;
                                $object_catalog->id = $object['value'];
                                if ($object['value'] != $id_parent) {
                                    $array_doc_in_catalog = $object_catalog->copyCatalog();
                                    if (count($array_doc_in_catalog)) {
                                        foreach ($array_doc_in_catalog as $file) {
                                            $counter_documents++;
                                            if ($file['path']) {
                                                $object_document->id = $file['id'];
                                                $result_download_file = $object_document->downloadFile();
                                                $name_document = $object_document->get_Document_Name_by_id();
                                                $path_file = $result_download_file[1];
                                                $name_file = $this->nameFile($path_file);
                                                $explode_name_file = explode('_', $name_file);
                                                array_shift($explode_name_file);
                                                $new_name_file = $file['id_new'];
                                                if (count($explode_name_file)) {
                                                    foreach ($explode_name_file as $part) {
                                                        $new_name_file = $new_name_file . '_' . $part;
                                                    }
                                                }
                                                $path_folder_user = SITE . '/upload/files/' . $this->user_info->login;
                                                if (!file_exists($path_folder_user)) {
                                                    mkdir($path_folder_user);
                                                } else {
                                                    if (!file_exists(SITE . '/' . $path_file)) {
                                                        $array_errors[] = 'У документа '. $name_document[0] . ' отсутствует физический файл:' . $name_file;
                                                    } else {
                                                        copy(SITE.'/'.$path_file, SITE.'/upload/files/'. $this->user_info->login . '/'. $new_name_file);
                                                        $object_document->id = $file['id_new'];
                                                        $object_document->file_name = '/upload/files/'. $this->user_info->login . '/'. $new_name_file;
                                                        $object_document->loadFile();
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    $catalog = $object_catalog->get_this_catalog();
                                    $array_errors[] = 'Конечная папка '.$catalog['name'].', в которую вы хотите поместить файлы, является дочерней для папки, которую вы копируете';
                                }
                            } elseif ($object['name'][0] == 'd') {
                                $counter_documents++;
                                $object_document->id = $object['value'];
                                $object_document->parent_id = $id_parent;
                                $name_document = $object_document->get_Document_Name_by_id();
                                $object_document->name = 'копия_'.$name_document[0];
                                $result_copy_document = $object_document->copyDoc();
                                if ($result_copy_document[0]) {
                                    $result_download_file = $object_document->downloadFile();
                                    $path_file = $result_download_file[1];
                                    if ($path_file) {
                                        $name_file = $this->nameFile($path_file);
                                        $explode_name_file = explode('_', $name_file);
                                        array_shift($explode_name_file);
                                        $new_name_file = $result_copy_document[0];
                                        if (count($explode_name_file)) {
                                            foreach ($explode_name_file as $part) {
                                                $new_name_file = $new_name_file . '_' . $part;
                                            }
                                        }
                                        $path_folder_user = SITE . '/upload/files/' . $this->user_info->login;
                                        if (!file_exists($path_folder_user)) {
                                            mkdir($path_folder_user);
                                        } else {
                                            if (!file_exists(SITE.'/'.$path_file)) {
                                                $array_errors[] = 'У документа '. $name_document[0] . ' отсутствует физический файл:' . $name_file;
                                            } else {
                                                copy(SITE.'/'.$path_file, SITE.'/upload/files/'. $this->user_info->login . '/'. $new_name_file);
                                                $object_document->id = $result_copy_document[0];
                                                $object_document->file_name = '/upload/files/'. $this->user_info->login . '/'. $new_name_file;
                                                $object_document->loadFile();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if (count($array_errors)) {
                            $array_errors['code'] = 'errors';
                            $this->setObjectAjaxResponse('json', $array_errors);

                        } else {
                            $array_answer['code'] = 'success';
                            $array_answer['count_catalogs'] = $counter_catalogs;
                            $array_answer['count_documents'] = $counter_documents;
                            $this->setObjectAjaxResponse('json', $array_answer);
                        }
                    } else {
                        $this->setObjectAjaxResponse('string', -1);
                    }
                } else {
                    $this->setErrorResponse('404', 'Не указан родитель для переноса');
                }
            }
            else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        }
        else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }


}
