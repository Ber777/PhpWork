<?php

class Helper  {
    var $paginator = array(); // массив пагинаторов (для возможности иметь несколько пагинаторов на одной странице)

    var $left_block; // левый блок
    var $right_block; // правый блок (не используется)
    var $link_come_back; // ссылка назад

    var $user_info; // вся информация о пользователе
    var $name_object; // название объекта
    var $id_current_object; // ид текущего объекта (исп-ся в нав.цепочке)
    var $name_current_object; // имя текущего объекта (исп-ся в нав.цепочке)
    var $rights_object; // права на объект
    var $title_block; // заголовок блока

    var $array_mk = array(); // массив карт знаний
    var $array_db = array(); // массив баз данных
    var $array_catalogs = array(); // массив каталогов
    var $array_documents = array(); // массив документов
    var $array_templates = array(); // массив шаблонов
    var $array_user_templates = array(); // массив пользовательских шаблонов
    var $array_tags = array(); // массив тегов
    var $array_attributes = array(); // массив атрибутов
    var $array_tags_object = array(); // массив тегов объекта
    var $array_attributes_object = array(); // массив атрибутов объекта

    var $array_errors_form; // маасив для хранения ошибок форм
    var $array_success_form; // массив для успешных сообщений формы
    var $type_submit; // флаг для определния кнопок в главной форме (кнопки для добавления или для редактирования)

    var $id_main_parent_object; // самый первый в иерархии родителей (для блока поиска)
    var $name_type_first_parent; // название типа самого верхнего родителя (для формирования ссылки)
    var $id_parent; // id родителя
    var $parents_path = array(); // для хлебных крошек
    var $array_parents_object = array(); // массив родителей

    var $id_for_ajax; // ид чего-нибудь для передачи через js в ajax
    var $url_ajax_handler; // url аякс обработчика

    var $user_styles; // для блока пользователей

    /* переменные для назначения ролей */
    var $array_roles_object = array(); // массив ролей объекта
    var $main_role_user = array(); // массив ролей польз-ей доступа в систему
    var $list_users = array(); // список всех пользователей
    var $list_users_with_roles = array(); // список пользователей с ролями 
    var $list_users_without_roles = array(); // список пользователей без ролей
    var $is_owner = ''; // определение владельца объекта
    var $owner_object = array(); // информация о владельце объекта
    /*---------------------------------*/

    var $form_search = array(
        'search-in-all' => 'Во всем хранилище',
        'search-in-first-parent' => 'Поиск в первом родителе',
        'search-in-current' => 'В текущей рубрике'
    ); // вспомогательная переменная для формирования названий местоположений поиска (быстрый поиск)


    function  __construct($name_object = '') {
        $this->name_object = $name_object;
    }

    // главная форма добавления объектов
    public function MainFormAddEdit($step1, $step2, $step3, $step4) {
        $this->drawBlock('form-addedit', $step1);
        $this->drawBlock('form-addedit', $step2);
        $this->drawBlock('form-addedit', $step3);
        $this->drawBlock('form-addedit', $step4);
    }

    public function PopUp ($template = 'default') {
        $this->drawBlock('popup', $template);
    }

    public function PopUpContent ($template) {
        if (!empty($template)) {
            $this->drawBlock('popup-content', $template);
        } else {
            echo 'Не указан шаблон блока popup-content';
        }
    }

    public function SubmitMainForm($template) {
        $this->drawBlock('work-panel', $template);
    }

    // блок ошибок при добавлении сохранении
    public function ErrorsMainForm() {
        $this->drawBlock('error', 'main_form');
    }

    //блок поиска
    public function BlockSearch($template = 'default') {
        $this->drawBlock('search', $template);
    }

    //блок рабочей панели (добавить док, каталог)
    public function BlockWorkPanel($template = 'default') {
        $this->drawBlock('work-panel', $template);
    }

    //блок навигационной цепочки
    public function BlockNavigationChain($template = 'default') {
        $this->drawBlock('navigation-chain', $template);
    }

    //блок 404 страницы
    public function Block404() {
        $this->drawBlock('error', '404');
    }

    //блок 403 страницы
    public function Block403() {
        $this->drawBlock('error', '403');
    }

    public function Block401() {
	$this->drawBlock('error', '401');
    }

    //блок ошибок
    public function TableErrors ($array_errors) {
        $this->drawBlock('error', 'table', $array_errors);
    }

    //блок формы
    public function BlockForm($template = 'default') {
        $this->drawBlock('forms', $template);
    }

    // блок со стилями
    public function UserStyles($template = 'default') {
        $this->drawBlock('css', $template);
    }

    //блок вывода списков
    public function ListObjects($template) {
        if (isset($template)) {
            $this->drawBlock('list-objects', $template);
        }
        else {
            return 'Вы не указали тип выводимого списка';
        }
    }

    // админ панель объектов
    public function AdminPanelObject($template = 'default', $id = NULL) {
        if (isset($id)) {
            $this->drawBlock('admin-panel-object', $template, $id);
        }
        else {
            $this->drawBlock('admin-panel-object', $template);
        }
    }

    // левое меню
    public function LeftMenu() {
        $this->drawBlock('left-block', $this->left_block);
    }
    
    public function FormAccess() {
        $this->PopUpContent('form_set_access');
    }

    public function FormBuffer() {
        $this->PopUpContent('buffer');
    }


    /* прорисовка вторичных блоков */

    // список вторичных объектов
    public function ListSecondaryObjects ($template, $id) {
        if (isset($template)) {
            $this->drawBlock('list-objects', $template, $id);
        }
        else {
            return 'Вы не указали тип выводимого списка';
        }
    }

    // заголовки
    public function Title($template = 'default', $name_title) {
        $this->drawBlock('titles', $template, $name_title);
    }

    //ссылки <a>
    public function Link($template, $href = '#', $class = '', $text = '') {
        if (empty($template)) {
            $template = 'default';
        }
        $data = array(
            'href' => $href,
            'class' => $class,
            'text' => $text
        );
        $this->drawBlock('links', $template, $data);
    }

    // верхнее меню
    public function TopMenu($array_items, $array_subItems, $template = 'default'){
        $resultArray = array(
            'ITEM_MENU' => $array_items,
            'SUB_ITEMS' => $array_subItems
        );
        $this->drawBlock('top-menu', $template, $resultArray);
    }

    // пагинация
    public function Pagination($paginator_name) {
        $data = array(
            'paginator' => $this->paginator[$paginator_name],
        );
        $this->drawBlock('pagination', 'default', $data);
    }

    /*------------------*/

    //основная функция прорисовки блоков
    public function drawBlock($name_block, $template = 'default', $data = NULL) {
	global $curuser;
        if (!isset($data)) {
            $path_block = PRIMARY_BLOCKS ."$name_block/$template.php";
        }
        else {
            $path_block = SECONDARY_BLOCKS ."$name_block/$template.php";
        }
        if (file_exists($path_block)) {
            include "$path_block";
        }
        else {
            echo "Такого блока ($path_block)  не существует. Зависимость от вх.переменных: ".isset($data);
        }
    }

    //функция назначения параметро
    public function setValue($property, $value) {
        if (property_exists($this, $property)) {
            $this->{$property} = $value;
        }
    }
}
