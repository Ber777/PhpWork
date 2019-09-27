<?php

class attributeController extends Controller {

    function __construct()
    {
        parent::__construct();
        $this->name_object = 'attribute';
        $this->arrayCSS = array('/pages/attribute/attribute_style.css');
        $this->arrayJS = array('/pages/attribute/attribute.js');
        $this->object_helper = $this->setObjectHelper($this->name_object);
        $this->object_helper->user_styles = $this->user_info->profile_settings;
    }

    public function index()
    {
        $place = $this->getURL('place', 0);
        if (empty($place)) {
            $place = 0;
        }

        $sort_by = $this->getURL('filter', 0);
        if (iconv_strlen($sort_by) > 1) {
            $sort_by = mb_substr($sort_by, 0, 1, 'utf-8');
        }

        $object_database = new Database();
        $object_database->link = $this->db_link;
        $list_databases = $object_database->getReadListDB();

        $object_mapknowledge = new Mapknowledge();
        $object_mapknowledge->link = $this->db_link;
        $object_mapknowledge->user_id = $this->user_info->id;
        $list_mapknowledge = $object_mapknowledge->get_all_kz();

        $object_attribute = new Attribute();
        $object_attribute->link = $this->db_link;
        if (empty($place)) {
            $list_attributes = $object_attribute->get_all_attr();
        } else {
            $object_attribute->place_id = $place;
            $list_attributes = $object_attribute->get_list_attributes_in();
        }

        $this->setData(array(
            'Список атрибутов' => $list_attributes
        ));

        $this->setValuesHelper(array(
            'array_db' => $list_databases,
            'array_attributes' => $list_attributes,
            'array_mk' => $list_mapknowledge,
            'place' => $place,
            'sort_by' => mb_strtoupper($sort_by, 'utf-8')
        ));

        $this->setObjectResponse(
            __FUNCTION__,
            $this->mergeWithDefaultCSS($this->arrayCSS),
            $this->mergeWithDefaultJS($this->arrayJS),
            'Список атрибутов',
            $this->data
        );
    }

    public function ajaxAddAttribute () {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                $name = $this->getPOST('name');
                $integer_type = $this->getPOST('type');

                $object_attribute = new Attribute();
                $object_attribute->link = $this->db_link;
                $object_attribute->name = $name;
                $object_attribute->type_id = $integer_type;
                $result_add_attribute = $object_attribute->add_attr();

                $this->setObjectAjaxResponse('string', $result_add_attribute[0]);
            }
            else {
                $this->setErrorResponse('Ошибка доступа', 'Запрос к файлу должен идти методом POST');
            }
        }
        else {
            $this->setErrorResponse('Ошибка доступа', 'Запрос должен идти с помощью AJAX');
        }
    }

    public function ajaxDeleteAttribute ($id_attribute) {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                if (isset($id_attribute)) {

                    $object_attribute = new Attribute();
                    $object_attribute->link = $this->db_link;
                    $object_attribute->id = $id_attribute;
                    $result_delete_attribute = $object_attribute->drop_attr();

                    if ($result_delete_attribute[0] == $id_attribute) {
                        $array_result = array(
                            "reload_page" => true,
                            "result_delete" => $result_delete_attribute[0],
                        );
                    }
                    else {
                        $array_result = array(
                            "result_delete" => false,
                        );
                    }
                    $this->setObjectAjaxResponse('json', $array_result);

                } else {
                    $this->setErrorResponse('404', 'Не известен удаляемый атрибут');
                }
            }
            else {
                $this->setErrorResponse('Ошибка доступа', 'Запрос к файлу должен идти методом POST');
            }
        }
        else {
            $this->setErrorResponse('Ошибка доступа', 'Запрос должен идти с помощью AJAX');
        }
    }

    public function ajaxBlockEditAttribute() {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                $id_attribute = $this->getPOST('id');
                if ($id_attribute > 0) {
                    $object_attribute = new Attribute();
                    $object_attribute->link = $this->db_link;
                    $array_attributes = $object_attribute->get_all_attr();

                    foreach ($array_attributes as $key => $value) {
                        if ($id_attribute == $value['id']) {
                            $attribute_info = $array_attributes[$key];
                        }
                    }

                    if (!empty($array_attributes)) {
                        $this->setValuesHelper(array(
                            'attribute_info' => $attribute_info
                        ));
                        $this->setObjectAjaxResponse('html', 'FormEditAttribute');
                    } else {
                        $this->setErrorResponse('404', 'Ошибка доступа к выбранному атрибуту (его нет)');
                    }

                } else {
                    $this->setErrorResponse('404', 'Не корректный запрос на форму редактирования атрибута');
                }
            }
            else {
                $this->setErrorResponse('Ошибка доступа', 'Запрос к файлу должен идти методом POST');
            }
        }
        else {
            $this->setErrorResponse('Ошибка доступа', 'Запрос должен идти с помощью AJAX');
        }
    }

    public function ajaxEditAttribute($id_attribute) {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                if (isset($id_attribute)) {

                    $integer_type = $this->getPOST('type');
                    $name = $this->getPOST('name');

                    $object_attribute = new Attribute();
                    $object_attribute->link = $this->db_link;
                    $object_attribute->id = $id_attribute;
                    $object_attribute->type_id = $integer_type;
                    $object_attribute->name = $name;

                    $result_edit_attribute = $object_attribute->edit_name_attr();

                    if ($result_edit_attribute[0] == $id_attribute || $result_edit_attribute[0] == -1) {
                        $this->setObjectAjaxResponse('string', $result_edit_attribute[0]);
                    } else {
                        $this->setErrorResponse('Ошибка SQL', 'Произошла ошибка при редактировании');
                    }

                } else {
                    $this->setErrorResponse('404', 'Не известен редактируемый атрибут');
                }
            } else {
                $this->setErrorResponse('Ошибка доступа', 'Запрос к файлу должен идти методом POST');
            }
        } else {
            $this->setErrorResponse('Ошибка доступа', 'Запрос должен идти с помощью AJAX');
        }
    }

}
