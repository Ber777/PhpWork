<?php

class bigsearchController extends Controller {
    var $asd;
    
    function __construct() {
        parent::__construct();
        $this->name_object = 'bigsearch';
        $this->arrayCSS = array('/pages/bigsearch/big-search-style.css');
        $this->arrayJS = array(
            '/pages/bigsearch/handlebars-v4.0.10.js',
            '/pages/bigsearch/big-search-requests.js',
            '/pages/bigsearch/big-search-interface.js',
            '/pages/bigsearch/big-search-templates.js',
            '/pages/bigsearch/big-search-data.js',
            '/pages/bigsearch/jquery.autocomplete.js',
            '/pages/bigsearch/jquery.ui.position.js'           
        );
        $this->object_helper = $this->setObjectHelper($this->name_object);
        $this->object_helper->user_styles = $this->user_info->profile_settings;
        
    }
    
    public function index() {
        
        if (!$this->isAjax()) {

            $id_template = $this->getURL('id', false); // получаем id шаблона, если он запрошен
            $type = $this->getURL('type', false); // получаем тип
            
            if (isset($id_template) && isset($type)) {
                
                if ($type == 'search') {
                    //шаблон поиска
                    $template = new SearchTemplate();
                } else {
                    // шаблон оповещений
                    $template = new Alert();
                }

                $template->link = $this->db_link;
                $template->id = $id_template;
                $result_info = $template->get_by_id();
                    
                $this->setValuesHelper(array(
                    'id_current_object' => $result_info["id"],
                    'name_current_object' => $result_info["name"],
                    'sql_text' => $result_info["sql_text"]
                ));
                
            }


            $this->setObjectResponse(
                __FUNCTION__,
                $this->mergeWithDefaultCSS($this->arrayCSS),
                $this->mergeWithDefaultJS($this->arrayJS),
                'Расширенный поиск',
                $this->data
            );
        } else {
            $this->setErrorResponse('1', '123');
        }
    }

    public function ajaxTemplateAlert($id_template = 0) {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                $name_template = $this->getPOST('name');
                $sql_txt = $this->getPOST('sql');

                $object_alert = new Alert();
                $object_alert->link = $this->db_link;
                $object_alert->name = $name_template;
                $object_alert->sql_txt = $sql_txt;

                if ($id_template) {
                    $object_alert->id = $id_template;
                    $result = $object_alert->edit_template();
                } else {
                    $result = $object_alert->add_template();
                }

                $this->setObjectAjaxResponse("string", $result[0]) ;
            } else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    public function ajaxTemplateSearch($id_template = 0) {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                $name_template = $this->getPOST('name');
                $sql_txt = $this->getPOST('sql');

                $object_search_template = new SearchTemplate();
                $object_search_template->link = $this->db_link;
                $object_search_template->name = $name_template;
                $object_search_template->sql_txt = $sql_txt;

                if ($id_template) {
                    $object_search_template->id = $id_template;
                    $result = $object_search_template->edit_template();
                } else {
                    $result = $object_search_template->add_template();
                }

                $this->setObjectAjaxResponse("string", $result[0]) ;
            } else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }
    
    public function ajaxAutocompleteProcess() {
        echo "HELLLLOOOOO";
	exit();
        $object_big_search = new BigSearch();
        $object_big_search->link = $this->db_link;
        
        //Получаем из ajax id дескриптора 
        $object_big_search->descriptor_id = $this->getURL("id", false);
        $temp = iconv('UTF-8', 'windows-1251', $this->getURL("query", false));
        //$object_big_search->key = iconv('UTF-8', 'windows-1251', $this->getURL("query", false));
        
        //Получаем из ajax ключевое слово
        $object_big_search->key = $this->getURL("query", false) ;
        
        //Выполняем запрос и возвращаем его через ajax
        $this->setObjectAjaxResponse("string", $object_big_search->getAutocompleteRequest()) ;
        
    }
    
    
    public function ajaxShowResults() {
        
        $object_big_search = new BigSearch();
        $object_big_search->link = $this->db_link;
        $object_big_search->data = json_decode($this->getPOST("request", false));
        $queryData = $object_big_search->getBigSearchData();
        
        if(  empty($queryData)  ) {
    //      echo '<h3 class="center-in-div">Поиск не дал результата</h3>';
          $displayListSearch = 'none';
          $messageErrorSearch = "По вашему запросу результатов не найдено!";
        } else {
           $displayListSearch = 'block';
    //      $resultSearch = $data;
           $messageErrorSearch = "";
        }
        
        $queryData_array = array();
        
        //получаем все теги для документа
        foreach($queryData as $documentItem){
            
            $object_document_tag = new Tag() ;
            $object_document_tag->link = $this->db_link;
            $object_document_tag->obj_id = $documentItem['id'];
            
            $object_document_attr = new Attribute();
            $object_document_attr->link = $this->db_link;
            $object_document_attr->obj_id = $documentItem['id'];
            
            //$catalog = new Catalog();
            //$catalog->id = 
            
            
            array_push($queryData_array, array(
                'id' => $documentItem['id'],
                'name' => $documentItem['name'],
                'catalog'=>$documentItem['catalog'],
                'tags_list' => $object_document_tag->get_tags_obj(),
                'attr_list' => $object_document_attr->get_attributes_of_obj()
                ));
                
            
        }
        
                
       
        $this->setValuesHelper(array(
            'displayListSearch' => $displayListSearch,
            'messageErrorSearch' => $messageErrorSearch,
            'data' => $queryData_array
        ));
        
        
        
        
        //$this->setObjectAjaxResponse("string", $object_big_search->getSql($object_big_search->data)) ;
        $this->setObjectAjaxResponse('html', 'showBigSearchResults');
      
    }

    
}
