<?php

class catalogController extends Controller
{


    function __construct()
    {
        parent::__construct();
        $this->name_object = 'catalog';
        $this->arrayCSS = array();
        $this->arrayJS = array('/js/add_edit_objects.js');
        $this->object_helper = $this->setObjectHelper($this->name_object);
        $this->object_helper->user_styles = $this->user_info->profile_settings;
    }

    public function add($id_parent = 0)
    {
        if ($id_parent > 0) {

            $object_catalog = new Catalog();
            $object_catalog->link = $this->db_link;
            $object_catalog->id = $id_parent;
            $name_main_parent = _fw_translate_type_object($object_catalog->getTypeFirstParent(), 'eng');

            $object_tag = new Tag();
            $object_tag->link = $this->db_link;
            $list_tags = $object_tag->get_all_tegs();

            $object_attribute = new Attribute();
            $object_attribute->link = $this->db_link;
            $list_attributes = $object_attribute->get_all_attr();

            $this->setValuesHelper(array(
                'array_tags' => $list_tags,
                'array_attributes' => $list_attributes,
                'url_ajax_handler' => '/catalog/ajaxAddCatalog/',
                'id_for_ajax' => $id_parent,
                'type_submit' => 'submit_add_without_template',
                'link_come_back' => '/'.$name_main_parent.'/view/'.$id_parent.'/'
            ));

            $this->setObjectResponse(
                __FUNCTION__,
                $this->mergeWithDefaultCSS($this->arrayCSS),
                $this->mergeWithDefaultJS($this->arrayJS),
                'Добавление каталога',
                $this->data
            );
        } else {
            $this->setErrorResponse('404', 'Не указан родитель');
        }
    }

    public function edit($id_catalog = 0) {
        if ($id_catalog > 0) {

            $object_catalog = new Catalog();
            $object_catalog->link = $this->db_link;
            $object_catalog->id = $id_catalog;
            $name_catalog = $object_catalog->get_this_catalog();
            $parent_catalog = $object_catalog->getParentCatalog();
            $name_main_parent = _fw_translate_type_object($object_catalog->getTypeFirstParent(), 'eng');

            $object_tag = new Tag();
            $object_tag->link = $this->db_link;
            $list_tags = $object_tag->get_all_tegs();

            $object_attribute = new Attribute();
            $object_attribute->link = $this->db_link;
            $list_attributes = $object_attribute->get_all_attr();

            $object_tag->obj_id = $id_catalog;
            $list_tags_object = $object_tag->get_tags_obj();

            $object_attribute->obj_id = $id_catalog;
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

            $this->setValuesHelper(array(
                'id_current_object' => $id_catalog,
                'array_tags' => $list_tags,
                'array_attributes' => $list_attributes,
                'array_tags_object' => $list_tags_object,
                'array_attributes_object' => $list_attributes_object,
                'name_catalog' => $name_catalog['name'],
                'url_ajax_handler' => '/catalog/ajaxEditCatalog/',
                'id_for_ajax' => $id_catalog,
                'type_submit' => 'submit_edit_without_template',
                'link_come_back' => '/'.$name_main_parent.'/view/'.$parent_catalog["id"].'/'
            ));

            $this->setObjectResponse(
                __FUNCTION__,
                $this->mergeWithDefaultCSS($this->arrayCSS),
                $this->mergeWithDefaultJS($this->arrayJS),
                'Редактирование рубрики',
                $this->data
            );
        }
        else {
            $this->setErrorResponse('404', 'Не указан ID редактируемого каталога');
        }
    }


    public function ajaxAddCatalog($parent_id)
    {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                if (isset($parent_id)) {
                    // получение данных с формы
                    $array_tags = _fw_json_decode_assoc($this->getPOST('tags'));
                    $array_attributes = $this->getPOST('attributes');

                    foreach ($array_attributes as $key => $value) {
                        $array_attributes[$key] = _fw_json_decode_assoc($value);
                    }

                    $name_catalog = array_shift($array_attributes);
                    
                    $object_catalog = new Catalog();
                    $object_catalog->link = $this->db_link;
                    $object_catalog->tegs = $array_tags;
                    $object_catalog->attributes = $array_attributes;
                    $object_catalog->parent_id = $parent_id;
                    $object_catalog->name = $name_catalog['value'];
                    $result_add_catalog = $object_catalog->addCatalog();

                    if ($result_add_catalog[0] < 0) {
                        $errors['add_catalog'] = 'Рубрика с таким именем существует';
                    }

                    if (isset($errors) && count($errors) > 0) {
                        $this->setValuesHelper(array(
                            'array_errors_form' => $errors
                        ));
                        $this->setObjectAjaxResponse('html', 'ErrorsMainForm');
                    } else {
                        $this->setObjectAjaxResponse('json', 1);
                    }
                } else {
                    $this->setErrorResponse('404', 'Не известен родительская рубрика');
                }
            } else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    public function ajaxEditCatalog($id_catalog) {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                if (isset($id_catalog)) {
                    // получение данных с формы
                    $array_tags = _fw_json_decode_assoc($this->getPOST('tags'));
                    $array_attributes = $this->getPOST('attributes');

                    foreach ($array_attributes as $key => $value) {
                        $array_attributes[$key] = _fw_json_decode_assoc($value);
                    }

                    $name_catalog = array_shift($array_attributes);

                    $object_catalog = new Catalog();
                    $object_catalog->link = $this->db_link;
                    $object_catalog->tegs = $array_tags;
                    $object_catalog->attributes = $array_attributes;
                    $object_catalog->id = $id_catalog;
                    $object_catalog->parent_id = $object_catalog->getParentCatalog()['id'];
                    $object_catalog->name = $name_catalog['value'];
                    $result_edit_catalog = $object_catalog->editCatalog();

                    if ($result_edit_catalog[0] != $id_catalog) {
                        $errors['edit_catalog'] = 'Рубрика с таким именем существует';
                    }

                    if (isset($errors) && count($errors) > 0) {
                        $this->setValuesHelper(array(
                            'array_errors_form' => $errors
                        ));
                        $this->setObjectAjaxResponse('html', 'ErrorsMainForm');
                    } else {
                        $this->setObjectAjaxResponse('json', 1);
                    }
                } else {
                    $this->setErrorResponse('404', 'Не известна редактируемая рубрика');
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

    public function ajaxDeleteCatalog ($id_catalog) {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                if (isset($id_catalog)) {
                    $object_catalog = new Catalog();
                    $object_catalog->link = $this->db_link;
                    $object_catalog->id = intval($id_catalog);
                    $type_first_parent = $object_catalog->getTypeFirstParent();
                    $parent_info = $object_catalog->getParentCatalog();
                    $result_del_catalog = $object_catalog->delCatalog();
                    if ($result_del_catalog[0] == $id_catalog) {
                        $array_result = array(
                            "reload_page" => false,
                            "result_delete" => $result_del_catalog[0],
                            "link_back" => '/' . _fw_translate_type_object($type_first_parent, 'eng'). '/view/' . $parent_info["id"],
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

    public function ajaxTemplates () {
        if ($this->isAjax()) {

            $id_parent = $this->getPOST('id_parent');

            $object_catalog_template = new CatalogTemplate();
            $object_catalog_template->link = $this->db_link;
            $list_catalog_templates = $object_catalog_template->get_all_templates();
            
            /*if (count($list_catalog_templates)) {
                foreach ($list_catalog_templates as $key => $value) {
                    $array_names_templates[$key] = mb_strtolower($value['name'], 'UTF-8');
                }
                asort($array_names_templates, SORT_STRING);
                foreach ($array_names_templates as $key => $value) {
                    foreach ($list_catalog_templates as $key_2 => $template) {
                        if ($key == $key_2) {
                            $array_sort_catalog_templates[] = $list_catalog_templates[$key_2];
                        }
                    }
                }
            }*/

            $array_sort_catalog_templates = $this->sortByName($list_catalog_templates);

            $this->setValuesHelper(array(
                'list_templates' => $array_sort_catalog_templates,
                'id_current' => $id_parent
            ));

            if (count($list_catalog_templates)) {
                $this->setObjectAjaxResponse('html', 'FormAddCatalogFromTemplate');
            }
            else {
                $this->setMessageErrorResponse('Ошибка данных', 'Отсутствуют шаблоны каталога');
            }
        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    public function ajaxAddCatalogFromTemplate ($id_parent) {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                if (isset($id_parent)) {
                    $id_template = $this->getPOST('id_template');
                    $name_catalog = $this->getPOST('name_catalog');

                    $object_catalog_template = new CatalogTemplate();
                    $object_catalog_template->link = $this->db_link;
                    $object_catalog_template->name_catalog = $name_catalog;
                    $object_catalog_template->parent_id = $id_parent;
                    $object_catalog_template->temp_id = $id_template;
                    $result_add_catalog_from_template = $object_catalog_template->add_catalog_by_temp();

                    if ($result_add_catalog_from_template[0] > 0) {
                        $this->setObjectAjaxResponse('string', 1);
                    } else if ($result_add_catalog_from_template[0] < 0) {
                        $errors['add_catalog_from_template'] = 'Каталог с таким именем уже существует';
                        $this->setValuesHelper(array(
                            'array_errors_form' => $errors,
                        ));
                        $this->setObjectAjaxResponse('html', 'ErrorsMainForm');
                    } else {
                        $errors['add_catalog_from_template'] = 'Произошла ошибка при добавлении';
                        $this->setValuesHelper(array(
                            'array_errors_form' => $errors,
                        ));
                        $this->setObjectAjaxResponse('html', 'ErrorsMainForm');
                    }
                }
                else {
                    $this->setErrorResponse('404', 'Не известна родительский объект');
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

    public function ajaxCloneCatalogToTemplate () {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                $id_catalog = $this->getPOST('id');
                $name_template = $this->getPOST('name');

                $object_catalog_template = new CatalogTemplate();
                $object_catalog_template->link = $this->db_link; 
                $object_catalog_template->name_template = $name_template;
                $object_catalog_template->catalog_id = $id_catalog;
                $result_clone_catalog_to_template = $object_catalog_template->clone_cat_to_temp();

                $this->setObjectAjaxResponse('string', $result_clone_catalog_to_template[0]);

            } else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    public function ajaxCopy($id) {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
//                var_dump($_POST);
                $this->setObjectAjaxResponse('string', 1);
            } else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

}
