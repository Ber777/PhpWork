<?php

class tagController extends Controller {

    function __construct() {
        parent::__construct();
        $this->name_object = 'tag';
        $this->arrayCSS = array(
            '/pages/tag/tag_style.css'
        );
        $this->arrayJS = array(
            '/pages/tag/tag.js'
        );
        $this->object_helper = $this->setObjectHelper($this->name_object);
        $this->object_helper->user_styles = $this->user_info->profile_settings;
    }

    public function index() {

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

        $object_tag = new Tag();
        $object_tag->link = $this->db_link;
        if (empty($place)) {
            $list_tags = $object_tag->get_all_tegs();
        } else {
            $object_tag->place_id = $place;
            $list_tags = $object_tag->GetListTagsIn();
        }


        $this->setData(array(
            'Список тегов' => $list_tags,
        ));

        $this->setValuesHelper(array(
            'array_db' => $list_databases,
            'array_tags' => $list_tags,
            'array_mk' => $list_mapknowledge,
            'place' => $place,
            'sort_by' => mb_strtoupper($sort_by, 'utf-8')
        ));

        $this->setObjectResponse(
            __FUNCTION__,
            $this->mergeWithDefaultCSS($this->arrayCSS),
            $this->mergeWithDefaultJS($this->arrayJS),
            'Список дескрипторов',
            $this->data
        );

    }

    public function searchTagsByName () {
        if ($this->isAjax()) {
            if ($this->isPOST()) {

                $name = $this->values_from_POST['name'];

                $object_tag = new Tag();
                $object_tag->search_name = $name;
                $object_tag->link = $this->db_link;
                $result = $object_tag->search_tags();
                $this->setObjectAjaxResponse('json', $result);
            }
            else {
                $this->setErrorResponse('Ошибка доступа', 'Запрос к файлу должен идти методом POST');
            }
        }
        else {
            $this->setErrorResponse('Ошибка доступа', 'Запрос должен идти с помощью AJAX');
        }
    }
    
    public function ajaxAddTag() {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                $name = $this->getPOST('name');
                
                $object_tag = new Tag();
                $object_tag->link = $this->db_link;
                $object_tag->name = $name;
                $result_add_tag = $object_tag->add_teg();

                $this->setObjectAjaxResponse('string', $result_add_tag[0]);
            }
            else {
                $this->setErrorResponse('Ошибка доступа', 'Запрос к файлу должен идти методом POST');
            }
        }
        else {
            $this->setErrorResponse('Ошибка доступа', 'Запрос должен идти с помощью AJAX');
        }
    }

    public function ajaxDeleteTag($id_tag) {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                if (isset($id_tag)) {

                    $object_tag = new Tag();
                    $object_tag->link = $this->db_link;
                    $object_tag->id = $id_tag;
                    $result_delete_tag = $object_tag->DropTag();

                    if ($result_delete_tag[0] == $id_tag) {
                        $array_result = array(
                            "reload_page" => true,
                            "result_delete" => $result_delete_tag[0],
                        );
                    }
                    else {
                        $array_result = array(
                            "result_delete" => false,
                        );
                    }
                    $this->setObjectAjaxResponse('json', $array_result);
                    
                } else {
                    $this->setErrorResponse('404', 'Не известен удаляемый дескриптор');
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

    public function ajaxEditName($id_tag) {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                if (isset($id_tag)) {
                    
                    $name = $this->getPOST('name');
                    $first_position = $this->getPOST('first_position');

                    $object_tag = new Tag();
                    $object_tag->link = $this->db_link;
                    $object_tag->id = $id_tag;
                    $object_tag->name = $name;
                    $result_edit_tag = $object_tag->EditTag();

                    if ($result_edit_tag[0] == $id_tag) {
                        if (mb_strtoupper($first_position, 'utf-8') == mb_strtoupper(mb_substr($name, 0, 1, 'utf-8'), 'utf-8')) {
                            $this->setObjectAjaxResponse('string', 1);
                        } else {
                            $this->setObjectAjaxResponse('string', 0);
                        }

                    } else {
                        $this->setObjectAjaxResponse('string', -1);
                    }

                } else {
                    $this->setErrorResponse('404', 'Не известен редактируемый дескриптор');
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



}