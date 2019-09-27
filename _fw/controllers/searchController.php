<?php

class searchController extends Controller {
    

    function __construct()
    {
        parent::__construct();
        $this->name_object = 'search';
        $this->arrayCSS = array(
            '/pages/search/style_search.css'
        );
        $this->object_helper = $this->setObjectHelper('search');
        $this->object_helper->user_styles = $this->user_info->profile_settings;
        
    }
    
    public function result() {
        //_fw_print_html_tag('pre', $_POST);
        if (!$this->isPOST()) {
            $this->setErrorResponse('Error access', 'Переход по странице идет методом GET, а не POST');
        }
        else {
            switch ($this->getPOST('type-search')) {
                case 'quick':
                    if($this->getPOST('flag-attribute', 0)) {
                        $this->page_search_by_attribute();
                    } else {
                        $this->page_search_by_tag();
                    }
                    break;

                case 'template':
                    $this->page_search_by_template();
                    break;
            }
        }
    }

    public function page_search_by_tag() {
        if (!$this->isPOST()) {
            $this->setErrorResponse('Error access', 'Переход по странице идет методом GET, а не POST');
        }
        else {
            $object_document = new Document();
            $object_document->link = $this->db_link;
            $object_document->id = $this->getPOST('search-radio');

            $object_catalog = new Catalog();
            $object_catalog->link = $this->db_link;
            $object_catalog->id = $this->getPOST('search-radio');

            $name_place = $object_catalog->get_this_catalog()['name'];

            $input_tag = $this->getPOST('input-tag', false);
            $array_tags = $this->getPOST('list-tags', false);
            $array_tags = strip_tags($array_tags);
            $array_tags = explode('/', $array_tags);

            array_pop($array_tags);

            if (!empty($input_tag)) {
                $array_tags[] = $input_tag;
            }

            $object_document->tegs = $array_tags;
            $list_documents = $object_document->search_doc_by_tag();

            $object_attributes = new Attribute();
            $object_attributes->link = $this->db_link;

            $object_tags = new Tag();
            $object_tags->link = $this->db_link;

            if (count($list_documents)) {
                foreach ($list_documents as $key => $value) {
                    $object_attributes->obj_id = $value['id'];
                    $object_tags->obj_id = $value['id'];
                    $object_document->id = $value['id'];
                    $list_documents[$key]['attributes'] = $object_attributes->get_attributes_of_obj(); // получение атрибутов для докумнта
                    $list_documents[$key]['tags'] = $object_tags->get_tags_obj(); // получение тегов для документа
                    $list_documents[$key]['parent_path'] = $object_document->get_parent_path(); // получения списка родительских каталогов
                    $list_documents[$key]['main_parent'] = _fw_translate_type_object($object_document->getTypeFirstParent(), 'eng');
                    $list_documents[$key]['link_download'] = $object_document->downloadFile();

                    $rights = $this->getRightsObject($value['id']);
                    $list_documents[$key]['rights']['grant'] = $rights->canGrant();
                    $list_documents[$key]['rights']['read'] = $rights->canRead();
                    $list_documents[$key]['rights']['update'] = $rights->canUpdate();
                    $list_documents[$key]['rights']['drop'] = $rights->canDrop();
                }
            }

            $this->setData(array(
                'Список тегов для поиска' => $array_tags,
                'Список документов удовл-х запросу' => $list_documents
            ));

            $this->setObjectResponse(
                'result',
                $this->mergeWithDefaultCSS($this->arrayCSS),
                $this->arrayJS_default,
                'Результат поиска',
                $this->data
            );

            $this->setValuesHelper(array(
                'place_name_search' => $name_place,
                'id_current_object' => $object_catalog->id,
                'search_result_tags' => $array_tags,
                'array_documents' => $list_documents,
                'show_form' => 'Y'
            ));

        }
    }

    public function page_search_by_attribute() {
        if (!$this->isPOST()) {
            $this->setErrorResponse('Error access', 'Переход по странице идет методом GET, а не POST');
        }
        else {
            $object_document = new Document();
            $object_document->link = $this->db_link;
            $object_document->parent_id = $this->getPOST('search-radio');
            $id_attribute = $this->getPOST('list-attrs');
            $object_document->id = $id_attribute;
            $list_documents = $object_document->search_doc_by_attr();

            $object_catalog = new Catalog();
            $object_catalog->link = $this->db_link;
            $object_catalog->id = $this->getPOST('search-radio');

            $name_place = $object_catalog->get_this_catalog()['name'];

            $object_attributes = new Attribute();
            $object_attributes->link = $this->db_link;

            $object_tags = new Tag();
            $object_tags->link = $this->db_link;

            if (count($list_documents)) {
                foreach ($list_documents as $key => $value) {
                    $object_attributes->obj_id = $value['id'];
                    $object_tags->obj_id = $value['id'];
                    $object_document->id = $value['id'];
                    $list_documents[$key]['attributes'] = $object_attributes->get_attributes_of_obj(); // получение атрибутов для докумнта
                    $list_documents[$key]['tags'] = $object_tags->get_tags_obj(); // получение тегов для документа
                    $list_documents[$key]['parent_path'] = $object_document->get_parent_path(); // получения списка родительских каталогов
                    $list_documents[$key]['main_parent'] = _fw_translate_type_object($object_document->getTypeFirstParent(), 'eng');

                    $rights = $this->getRightsObject($value['id']);
                    $list_documents[$key]['rights']['grant'] = $rights->canGrant();
                    $list_documents[$key]['rights']['read'] = $rights->canRead();
                    $list_documents[$key]['rights']['update'] = $rights->canUpdate();
                    $list_documents[$key]['rights']['drop'] = $rights->canDrop();
                }
            }

            $this->setData(array(
                'ID атрибутов для поиска' => $id_attribute,
                'Список документов удовл-х запросу' => $list_documents
            ));

            $this->setObjectResponse(
                'result',
                $this->mergeWithDefaultCSS($this->arrayCSS),
                $this->arrayJS_default,
                'Результат поиска',
                $this->data
            );

            $this->setValuesHelper(array(
                'place_name_search' => $name_place,
                'id_current_object' => $object_catalog->id,
                'array_documents' => $list_documents,
                'show_form' => 'Y'
            ));
        }
    }

    public function page_search_by_template () {

        $id_template = $this->getPOST('id-template');
        $object_alert = new Alert();
        $object_alert->link = $this->db_link;
        $list_documents = $object_alert->search_by_template($id_template);

        $object_document = new Document();
        $object_document->link = $this->db_link;

        $object_attributes = new Attribute();
        $object_attributes->link = $this->db_link;

        $object_tags = new Tag();
        $object_tags->link = $this->db_link;

        if (count($list_documents)) {
            foreach ($list_documents as $key => $value) {
                $object_attributes->obj_id = $value['id'];
                $object_tags->obj_id = $value['id'];
                $object_document->id = $value['id'];
                $list_documents[$key]['attributes'] = $object_attributes->get_attributes_of_obj(); // получение атрибутов для докумнта
                $list_documents[$key]['tags'] = $object_tags->get_tags_obj(); // получение тегов для документа
                $list_documents[$key]['parent_path'] = $object_document->get_parent_path(); // получения списка родительских каталогов
                $list_documents[$key]['main_parent'] = _fw_translate_type_object($object_document->getTypeFirstParent(), 'eng');

                $rights = $this->getRightsObject($value['id']);
                $list_documents[$key]['rights']['grant'] = $rights->canGrant();
                $list_documents[$key]['rights']['read'] = $rights->canRead();
                $list_documents[$key]['rights']['update'] = $rights->canUpdate();
                $list_documents[$key]['rights']['drop'] = $rights->canDrop();
            }
        }


        $this->setObjectResponse(
            'result',
            $this->mergeWithDefaultCSS($this->arrayCSS),
            $this->arrayJS_default,
            'Результат поиска',
            $this->data
        );

        $this->setValuesHelper(array(
            'array_documents' => $list_documents,
        ));
    }

}
