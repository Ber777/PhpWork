<?php

class documentController extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->name_object = 'document';
        $this->arrayCSS = array(
            '/pages/document/document_style.css'
        );
        $this->arrayJS = array();
        $this->object_helper = $this->setObjectHelper($this->name_object);
        $this->object_helper->user_styles = $this->user_info->profile_settings;
    }

    public function add($id_parent = 0)
    {
        $this->addNewPathJS('/js/add_edit_objects.js');
	$list_attributes_object = array();
        if ($id_parent) {
            $id_template = $this->getURL('template');

            $object_tag = new Tag();
            $object_tag->link = $this->db_link;
            $list_tags = $object_tag->get_all_tegs();

            $object_attribute = new Attribute();
            $object_attribute->link = $this->db_link;
            $list_attributes = $object_attribute->get_all_attr();

            $object_catalog = new Catalog();
            $object_catalog->link = $this->db_link;
            $object_catalog->id = $id_parent;
            $current_object = $object_catalog->get_this_catalog(); // получение информации о текущем каталоге
            $parents_path = $object_catalog->get_parent_path(); // получения списка родительских каталогов
            $name_main_parent = _fw_translate_type_object($object_catalog->getTypeFirstParent(), 'eng');
            $name_block = 'Добавление документа';
            
            if ($id_template > 0) {
                
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
		    $list_attributes_object[$key]['value'] = '';
                    #unset($list_attributes_object[$key]['value']);
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
                    'array_attributes_object' => $list_attributes_object
                ));

                $name_block = 'Создание документа по шаблону "'.$name_template['name'] .'"';
            }
            
            $this->setValuesHelper(array(
                'title_block' => $name_block,
                'array_tags' => $list_tags,
                'array_attributes' => $list_attributes,
                'id_parent' => $id_parent,
                'type_submit' => 'submit_add',
                'id_for_ajax' => $id_parent,
                'name_type_first_parent' => $name_main_parent,
                'parents_path' => $parents_path,
                'name_current_object' => $current_object['name'],
                'id_current_object' => $id_parent,
                'url_ajax_handler' => '/document/ajaxAddDocument/',
                'link_come_back' => '/'.$name_main_parent.'/view/'.$id_parent.'/'
            ));

            $this->setObjectResponse(
                __FUNCTION__,
                $this->mergeWithDefaultCSS($this->arrayCSS),
                $this->mergeWithDefaultJS($this->arrayJS),
                'Создание документа',
                $this->data
            );
        } else {
            $this->setErrorResponse('404', 'Не указан родительский объект');
        }

    }

    public function edit($id_object)
    {
        $this->addNewPathJS('/js/add_edit_objects.js');
        if ($id_object > 0) {
            $right_update = $this->getRightsObject($id_object, 'update');
            if ($right_update) {
                $object_document = new Document();
                $object_document->link = $this->db_link;
                $object_document->id = $id_object;
                $name_document = $object_document->get_Document_Name_by_id();
                $path_file = $object_document->downloadFile();
                $parent_path = $object_document->get_parent_path();

                $name_main_parent = _fw_translate_type_object($object_document->getTypeFirstParent(), 'eng');
                $id_parent = $object_document->getParentCatalog();

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
                    'name_document' => $name_document[0],
                    'id_parent' => $id_parent["id"],
                    'path_file' => $path_file
                ));

                $this->setValuesHelper(array(
                    'array_tags_object' => $list_tags_object,
                    'array_attributes_object' => $list_attributes_object,
                    'array_tags' => $list_tags,
                    'array_attributes' => $list_attributes,
                    'name_document' => $name_document[0],
                    'name_type_first_parent' => $name_main_parent,
                    'parents_path' => $parent_path,
                    'id_parent' => $id_parent["id"],
                    'id_current_object' => $id_object,
                    'type_submit' => 'submit_edit',
                    'path_file' => $path_file['filename'],
                    'name_file' => $this->nameFile($path_file['filename']),
                    'id_for_ajax' => $id_object,
                    'url_ajax_handler' => '/document/ajaxEditDocument/',
                    'link_come_back' => '/'.$name_main_parent.'/view/'.$id_parent["id"].'/',
                ));


                $this->setObjectResponse(
                    __FUNCTION__,
                    $this->mergeWithDefaultCSS($this->arrayCSS),
                    $this->mergeWithDefaultJS($this->arrayJS),
                    'Редактирование документа',
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

    public function templates($id_parent)
    {
        if ($id_parent > 0) {
            $object_documents = new Document();
            $object_documents->link = $this->db_link;
            $list_templates = $object_documents->get_my_doc_temp();

            $object_catalog = new Catalog();
            $object_catalog->link = $this->db_link;
            $object_catalog->id = $id_parent;
            $name_main_parent = _fw_translate_type_object($object_catalog->getTypeFirstParent(), 'eng');

            $link_come_back = '/'.$name_main_parent.'/view/'.$id_parent.'/';

            $this->setData(array(
                'Список шаблонов' => $list_templates
            ));

            if (count($list_templates) == 0) {
                $this->setMessageErrorResponse('documentController::templates', 'Отсутствуют данные в переменной $list_templates');
            }

            $this->setObjectResponse(
                __FUNCTION__,
                $this->mergeWithDefaultCSS($this->arrayCSS),
                $this->mergeWithDefaultJS($this->arrayJS),
                'Выбор шаблона для документа',
                $this->data
            );

            $this->setValuesHelper(array(
                "array_templates" => $list_templates,
                "id_parent" => $id_parent,
                "link_come_back" => $link_come_back
            ));

        } else {
            $this->setErrorResponse('404', 'Непонятно, куда сохранять объект?!');
        }
    }

    public function passport($id_object)
    {
        $this->addNewPathCSS('/pages/document/document_style.css');
        if ($id_object > 0) {

            $object_attributes = new Attribute();
            $object_attributes->link = $this->db_link;
            $object_attributes->obj_id = $id_object;
            $array_attributes = $object_attributes->get_attributes_of_obj();

            $object_tags = new Tag();
            $object_tags->link = $this->db_link;
            $object_tags->obj_id = $id_object;
            $array_tags = $object_tags->get_tags_obj();

            $object_document = new Document();
            $object_document->link = $this->db_link;
            $object_document->id = $id_object;
            $path_file = $object_document->downloadFile();
            $name_document = $object_document->get_Document_Name_by_id();
            $parent_path = $object_document->get_parent_path();
            $info_parent = $object_document->getParentCatalog();
            $name_type_first_parent = _fw_translate_type_object($object_document->getTypeFirstParent(), 'eng');


            $object_rights = $this->user_info->get_Rigth($id_object);
            $rights['drop'] = $object_rights->drop;
            $rights['update'] = $object_rights->update;
            $rights['grant'] = $object_rights->grant;
            $rights['read'] = $object_rights->read;

            $this->setData(array(
                'название документа' => $name_document[0],
                'путь файла' => $path_file,
                'список атрибутов' => $array_attributes,
                'список дескрипторов' => $array_tags
            ));

            $this->setValuesHelper(array(
                'id_current' => $id_object,
                'name_document' => $name_document[0],
                'rights_object' => $rights,
                'parents_path' => $parent_path,
                'name_type_first_parent' => $name_type_first_parent,
                'id_parent' => $info_parent['id'],
                'download_link' => $path_file['filename'],
                'array_tags' => $array_tags,
                'array_attributes' => $array_attributes
            ));

            $this->setObjectResponse(
                __FUNCTION__,
                $this->mergeWithDefaultCSS($this->arrayCSS),
                $this->mergeWithDefaultJS($this->arrayJS),
                'Паспорт документа',
                $this->data
            );
        } else {
            $this->setErrorResponse('404', 'Такого документа не существует');
        }
    }

    public function ajaxAddDocument($parent_id)
    {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                if (isset($parent_id)) {
                    // получение данных с формы
                    $array_tags = _fw_json_decode_assoc($this->getPOST('tags'));
                    $array_attributes = $this->getPOST('attributes');
                    $name_template = $this->getPOST('template');

                    foreach ($array_attributes as $key => $value) {
                        $array_attributes[$key] = _fw_json_decode_assoc($value);
                    }

                    $name_document = array_shift($array_attributes);

                    $object_document = new Document();
                    $object_document->link = $this->db_link;
                    $object_document->tegs = $array_tags;
                    $object_document->attributes = $array_attributes;

                    $object_document->name = $name_document['value'];
                    $object_document->parent_id = $parent_id;
                    $result_add_document = $object_document->addDoc();
                    if ($result_add_document[0] < 0) {
                        $errors['add_document'] = 'Документ с таким именем уже существует';
                    } else {
                        $success['add_document'] = 'Документ успешно сохранен';
                        $this->uploadFile($result_add_document[0], 'doc_file');
                    }
                    if (!empty($name_template)) {
                        $object_document->name = $name_template;
                        $result_add_template = $object_document->addTemplate();
                        if ($result_add_template[0] < 0) {
                            $errors['add_template'] = 'Шаблон с таким именем уже существует.';
                        } else {
                            $success['add_template'] = 'Шаблон успешно сохранен';
                        }
                    }
                    if (isset($errors) && count($errors) > 0) {
                        $this->setValuesHelper(array(
                            'array_errors_form' => $errors,
                            'array_success_form' => $success
                        ));
                        $this->setObjectAjaxResponse('html', 'ErrorsMainForm');
                    } else {
                        $this->setObjectAjaxResponse('json', 1);
                    }
                } else {
                    $this->setErrorResponse('404', 'Не известен родительский объект');
                }
            } else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    public function ajaxEditDocument($id_document)
    {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                if (isset($id_document)) {
                    // получение данных с формы
                    $array_tags = _fw_json_decode_assoc($this->getPOST('tags'));
                    $array_attributes = $this->getPOST('attributes');
                    $name_template = $this->getPOST('template');
                    $delete_file = $this->getPOST('delete_file');

                    foreach ($array_attributes as $key => $value) {
                        $array_attributes[$key] = _fw_json_decode_assoc($value);
                    }

                    $name_document = array_shift($array_attributes);

                    $object_document = new Document();
                    $object_document->link = $this->db_link;
                    $object_document->id = $id_document;
                    $object_document->name = $name_document['value'];
                    $object_document->parent_id = $object_document->getParentCatalog()['id'];
                    $object_document->tegs = $array_tags;
                    $object_document->attributes = $array_attributes;

                    $result_edit_document = $object_document->editDoc();
                    if ($result_edit_document[0] != $id_document) {
                        $errors['add_document'] = 'Документ с таким именем уже существует';
                    }
                    else {
                        $success['add_document'] = 'Документ успешно сохранен';
                        if ($delete_file) {
                            $this->deleteFile($id_document, $delete_file);
                        }
                        $this->uploadFile($result_edit_document[0], 'doc_file');
                    }
                    if (!empty($name_template)) {
                        $object_document->name = $name_template;
                        $result_add_template = $object_document->addTemplate();
                        if ($result_add_template[0] < 0) {
                            $errors['add_template'] = 'Шаблон с таким именем уже существует.';
                        } else {
                            $success['add_template'] = 'Шаблон успешно сохранен';
                        }
                    }
                    if (isset($errors) && count($errors) > 0) {
                        $this->setValuesHelper(array(
                            'array_errors_form' => $errors,
                            'array_success_form' => $success
                        ));
                        $this->setObjectAjaxResponse('html', 'ErrorsMainForm');
                    } else {
                        $this->setObjectAjaxResponse('json', 1);
                    }
                } else {
                    $this->setErrorResponse('404', 'Не известен родительский объект');
                }
            } else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    public function ajaxDeleteDocument ($id_document) {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                if (isset($id_document)) {
                    $object_document = new Document();
                    $object_document->link = $this->db_link;
                    $object_document->id = intval($id_document);
                    $type_first_parent = $object_document->getTypeFirstParent();
                    $parent_info = $object_document->getParentCatalog();
                    $result_del_document = $object_document->deleteDocument();
                    if ($result_del_document[0] == 1) {
                        $array_result = array(
                            "reload_page" => false,
                            "result_delete" => $result_del_document[0],
                            "link_back" => '/' . _fw_translate_type_object($type_first_parent, 'eng') . '/view/' . $parent_info["id"],
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
                    $this->setErrorResponse('404', 'Не известен удаляемый документ');
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

    public function ajaxCopy($id) {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                setcookie('buffer_doc[]', $id);
                
                $this->setObjectAjaxResponse('string', 1);
            } else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }


}
