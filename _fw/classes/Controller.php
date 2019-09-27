<?php

class Controller {
    /* базовые атрибуты */
    var $object_response;
    var $object_request;
    var $arrayCSS_default;
    var $arrayJS_default;
    var $db_link; //соединение с БД
    var $user_info;

    /* изменяемые */
    var $name_object;
    var $object_helper;
    var $values_from_url;
    var $values_from_POST;
    var $arrayCSS = array();
    var $arrayJS = array();
    var $data;

    public function __construct()
    {
        $this->db_link = $GLOBALS['DATABASE']->getLink();
        $this->values_from_POST = $_POST;
        $this->object_response = new Response();
        $this->arrayJS_default = array(
            '/_fw/libraries/jquery-1.11.0.min.js',
            '/_fw/libraries/jquery-ui-1.11.4.custom/jquery-ui.js',
            '/_fw/libraries/jquery-ui-1.11.4.custom/jquery-ui.min.js',
            '/_fw/libraries/jquery.validate.js',
            '/_fw/libraries/jquery.cookie.min.js',
            '/_fw/libraries/jquery.form.js',
            //'/js/desertation.js',
            //'/js/test.js',
            '/js/javascript.js',
            '/js/search.js',
            '/js/forms.js',
            '/js/script_set_access.js'
        );
        $this->arrayCSS_default = array(
            '/css/navigation_menu.css',
            '/css/base_blocks.css',
            '/css/style.css',
            '/css/base_classes.css',
            '/css/base_buttons.css',
            '/css/pagination.css',
            '/_fw/libraries/jquery-ui-1.11.4.custom/jquery-ui.css',
            '/_fw/libraries/jquery-ui-1.11.4.custom/jquery-ui.min.css',
            '/_fw/libraries/jquery-ui-1.11.4.custom/jquery-ui.structure.css',
            '/_fw/libraries/jquery-ui-1.11.4.custom/jquery-ui.structure.min.css',
            '/_fw/libraries/jquery-ui-1.11.4.custom/jquery-ui.theme.css',
            '/_fw/libraries/jquery-ui-1.11.4.custom/jquery-ui.theme.min.css',
        );

        $this->user_info = new Users();
        $this->user_info->link = $this->db_link;
        if ($this->getUserSession()) {
            $this->user_info->user_in_system = $this->getUserSession();
            $this->user_info->get_current_user();
            $this->user_info->get_attr_profile();
        }

    }

    public function getUserInfo () {
        return $this->user_info;
    }

    public function getRightsObject($id, $key = '') {
        $right = $this->user_info->get_Rigth($id);
        if ($key == '') {
            return $right;
        } else {
            return $right->{$key};
        }

    }

    //стандартный формат ответа
    public function setObjectResponse($template, $arrCSS, $arrJS, $title, $data) {
        $this->object_response->setObject($this->name_object);
        $this->object_response->setTemplate($template);
        $this->object_response->setArrCSS($arrCSS);
        $this->object_response->setArrJS($arrJS);
        $this->object_response->setTitle($title);
        $this->object_response->setData($data);
        $this->object_response->setIdUser($this->user_info->id);
        $this->object_response->setAuthUser($this->getUserSession());
    }

    public function setObjectAjaxResponse($template, $data) {
        $this->object_response->setObject('ajax');
        $this->object_response->setTemplate($template);
        $this->object_response->setData($data);
        $this->object_response->delHeader();
        $this->object_response->delFooter();
        $this->object_response->setIdUser($this->user_info->id);
        $this->object_response->setAuthUser('xgb_nir');
    }
    //создание хэлпера для вывода блоков на страницу
    public function setObjectHelper($name_helper) { /*здесь можно добавить по умолчанию наличие левого бокового меню */
        if (file_exists(HELPERS . $name_helper . 'Helper.php')) {
            require_once(HELPERS . $name_helper . 'Helper.php');
            $name_class_helper = $name_helper.'Helper';
            $helper = new $name_class_helper;
            $helper->name_object = $name_helper;
            return $helper;
        }
        else {
            $this->setErrorResponse('function Controller::setObjectHelper()', 'Такого хелпера не существует');
        }
    }

    //метод для формирования параметров хелпера
    public function setValuesHelper($array_values) {
        if (is_array($array_values)) {
            foreach ($array_values as $property => $item) {
                if (property_exists($this->object_helper, $property)) {
                    $this->object_helper->setValue($property, $item);
                }
                else {
                    $name_class = get_class($this->object_helper);
                    $this->setMessageErrorResponse("Ошибка присваивания $property", "У объекта класса $name_class отсутствует свойство $property");
                }
            }
        }
        else {
            $this->setMessageErrorResponse('Controller::setValuesHelper($array_values)', 'Методу необходим массив формата (название параметра => значение)');
        }

    }

    // метод загрузки файла
    public function uploadFile ($id_document, $key) {
        $object_document = new Document();
        $object_document->link = $this->db_link;
        if (!empty($_FILES[$key]['tmp_name'])) {
            if (is_uploaded_file($_FILES[$key]['tmp_name'])) {
                $path_folder_user = SITE . '/upload/files/' . $this->user_info->login;
                if (!file_exists($path_folder_user)) {
                    mkdir($path_folder_user);
                }
                if (move_uploaded_file($_FILES[$key]['tmp_name'], $path_folder_user.'/'.$id_document.'_'.$_FILES[$key]["name"])) {
                    $path_file = '/upload/files/'. $this->user_info->login . '/' . $id_document . '_' . $_FILES[$key]["name"];
                    $object_document->id = $id_document;
                    $object_document->file_name = $path_file;
                    $result_add_file = $object_document->loadFile();
                }
                else {
                    return $errors['add_file'] = 'Ошибка при загрузке файла';
                }
            }
            else {
                return $errors['add_file'] = 'Файл не обработан сервером';
            }
        }
    }

    // метод удаления файла
    public function deleteFile ($id_document, $path) {
        $object_document = new Document();
        $object_document->link = $this->db_link;
        $object_document->id = $id_document;
        $result_drop_file = $object_document->dropFile();
        if ($result_drop_file[0] == 0) {
            return $errors['drop_file'] = 'Ошибка при удалении. У документа не был сохранен физический адрес файла на сервере';
        }
        else {
            $path_delete = SITE . $path;
            if (file_exists($path_delete)) {
                unlink($path_delete);
            }
            else {
                return $errors['drop_file'] = "Ошибка при удалени. Отсутствует файл по пути $path_delete";
            }
        }
    }

    // метод получения из целового пути файла только его имя и расширение
    public function nameFile ($path_file) {
        $path_file = explode('/', $path_file);
        return $path_file[count($path_file)-1];
    }

    // ответ с ошибкой и передача ключа и описания ошибки
    public function setErrorResponse($key, $message, $template = 404) {
        $this->object_response->setError();
        $this->object_response->setErrorMessages($key, $message);
        $this->object_response->setArrCSS($this->arrayCSS_default);
        $this->object_response->setIdUser($this->user_info->id);
        $this->object_response->setTemplate('page'.$template);
    }

    // запись ключа и описание ошибки с правильным отображением страницы
    public function setMessageErrorResponse($key, $message) {
        $this->object_response->setErrorMessages($key, $message);
    }

    // определение Ajax-запрос или нет
    public function isAjax() {
        if ($this->object_request->type_request && strtolower($this->object_request->type_request) == 'xmlhttprequest' ) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    // определение метода POST
    public function isPOST() {
        if ($this->object_request->method && strtolower($this->object_request->method) == "post") {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    public function getPOST($key, $print_error = true) {
        if (isset($this->values_from_POST[$key])) {
            return $this->values_from_POST[$key];
        }
        else {
            if ($print_error) {
                $this->setMessageErrorResponse('Ошибка данных POST', "В POST-данных отсутствует значение под ключом $key");
            }
        }
    }

    public function getAllPOST() {
        return $_POST;
    }

    public function getURL($key, $print_error = true) {
        if (isset($this->values_from_url[$key])) {
            return $this->values_from_url[$key];
        }
        else {
            if ($print_error) {
                $this->setMessageErrorResponse('Ошибка данных GET', "В GET-данных отсутствует значение под ключом $key");
            }
        }
    }

    public function getCookies($key, $print_error = false) {
        if (isset($_COOKIE[$key])) {
            return $_COOKIE[$key];
        } else {
            if ($print_error) {
                $this->setMessageErrorResponse('Ошибка данных COOKIE', "В cookies отсутствует значение под ключом $key");
            }
        }
    }

    public function getFile($key) {
        if (isset($_FILES[$key])) {
            return $_FILES[$key];
        }
        else {
            $this->setMessageErrorResponse('Ошибка файла', "По ключу $key отсутствует файл");
        }
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function appendData($key, $data) {
        $this->data[$key] = $data;
    }

    //удаление путей CSS стилей
    public function deleteCssFromDeafault($css_path) {
        if (is_array($css_path)) {
            foreach ($css_path as $key => $value) {
                $key2 = array_search($value, $this->arrayCSS_default);
                if ($key2 != FALSE) {
                    unset($this->arrayCSS_default[$key2]);
                }
            }
        }
        elseif (is_string($css_path)) {
            $key = array_search($css_path, $this->arrayCSS_default);
            if ($key != FALSE) {
                unset($this->arrayCSS_default[$key]);
            }
        }
        else {
            $this->setMessageErrorResponse('function Controller::deleteCSS()', 'Неверный формат входных переменных! (либо строка, либо массив строк)');
        }
    }

    public function getUserSession () {
        return $_SESSION["AUTH"];
    }

    public function setUserSession($system_name) {
        $_SESSION["AUTH"] = $system_name;
    }

    //удаление путей JS-скриптов
    public function deleteJsFromDefault($js_path) {
        if (is_array($js_path)) {
            foreach ($js_path as $key => $value) {
                $key2 = array_search($value, $this->arrayJS_default);
                if ($key2 != FALSE) {
                    unset($this->arrayJS_default[$key2]);
                }
            }
        }
        elseif (is_string($js_path)) {
            $key = array_search($js_path, $this->arrayJS_default);
            if ($key != FALSE) {
                unset($this->arrayJS_default[$key]);
            }
        }
        else {
            $this->setMessageErrorResponse('function Controller::deleteJS()', 'Неверный формат входных переменных! (либо строка, либо массив строк)');
        }
    }

    // добавление путей CSS
    public function addNewPathCSS($path) {
        if (is_array($path)) {
            foreach ($path as $value) {
                $this->arrayCSS[] = $value;
            }
        }
        else {
            $this->arrayCSS[] = $path;
        }

    }

    // добавление путей JS
    public function addNewPathJS($path) {
        if (is_array($path)) {
            foreach ($path as $value) {
                $this->arrayJS[] = $value;
            }
        }
        else {
            $this->arrayJS[] = $path;
        }
    }

    //соединение с базовыми стилями
    public function mergeWithDefaultCSS ($array_CSS) {
        return array_merge($this->arrayCSS_default, $array_CSS);
    }

    //соединение с базовыми скриптами
    public function mergeWithDefaultJS ($array_JS) {
        return array_merge($this->arrayJS_default, $array_JS);
    }

    //функция назначения прав
    public function ajaxAccess() {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                $id_object = $this->getPOST('id');
                if (!empty($id_object)) {

                    $object = new Catalog();
                    $object->link = $this->db_link;
                    $object->id = $id_object;
                    $name_current_object = $object->get_this_catalog()["name"];
                    $id_first_parent = $object->getIdTop();
                    $owner_object['id'] = $object->getOwner();

                    $array_roles_object = PageRole::getRoles($id_first_parent);

                    $main_role_user = PageRole::getUserRoleMain();

                    $all_users = pageRole::getAllUsers();
                    $owner_object['name'] = $all_users[$owner_object['id']];
                    foreach ($all_users as $id_user => $name_user) {
                        $bit_role_user = PageRole::getUserRoles($id_object, $id_user);
                        if ($bit_role_user != 0) {
                            $list_users_with_roles[$id_user] = $bit_role_user;
                        }
                    }

                    ksort($array_roles_object);

                    foreach ($all_users as $id => $name) {
                        if (!isset($list_users_with_roles[$id])) {
                            $list_users_without_roles[$id] = $name;
                        }
                    }

                    $object_user = new Users();
                    $object_user->link = $this->db_link;
                    $object_user->id = $this->user_info->id;
                    $is_owner = $object_user->isOwner($id_object);

                    $this->setValuesHelper(array(
                        'id_current_object' => $id_object,
                        'id_main_parent_object' => $object->getIdTop(),
                        'array_roles_object' => $array_roles_object,
                        'main_role_user' => $main_role_user,
                        'name_current_object' => $name_current_object,
                        'owner_object' => $owner_object,
                        'user_info' => array(
                            'id' => $this->user_info->id,
                            'is_owner' => $is_owner,
                        ),
                        'list_users' => $all_users,
                        'list_users_without_roles' => $list_users_without_roles,
                        'list_users_with_roles' => $list_users_with_roles
                    ));

                    $this->setObjectAjaxResponse('html', 'FormAccess');
                } else {
                    $this->setErrorResponse('404', 'Не указан ID базы данных');
                }
            } else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    public function ajaxSetAccess() {
        if ($this->isAjax()) {
            if ($this->isPOST()) {
                $json_data = $this->getPOST('jsonData');
                $array_del_users_json = $this->getPOST('delUser');
                if (($json_data == '') && ($array_del_users_json == '')) {
                    $this->setErrorResponse('404', 'Ответ не доступен - отсутствуют входные параметры');
                } else {
                    $data = _fw_json_decode_assoc($json_data);
                    $array_del_users = _fw_json_decode_assoc($array_del_users_json);

                    $big_mass = array(); // сконтруированный массив входящих данных с формы
                    $main_roles_user = array();
                    $message = '';
                    $id_obj = array_pop($data);

                    $main_roles_user = PageRole::getUserRoleMain();

                    foreach ($data as $key => $value) {
                        $big_mass[$value['user']] = array(
                            "obj" => $id_obj['value'],
                            "user" => $value['user'],
                            "bit_map" => $value['access']
                        );
                    }

                    foreach ($big_mass as $user => $mass) {
                        if ((int)$main_roles_user[$user] < $mass['bit_map']) {
                            $big_mass[$user]['bit_map'] = (int)$main_roles_user[$user];
                            $nameByid = PageRole::getUserNameById($big_mass[$user]['user'] );
                            $message .=  'Пользователь '. key($nameByid) . ' обладает меньшими правами<br>';
                        }
                    }
                    if (!empty($array_del_users)) $qwe1 = PageRole::delete($id_obj['value'], $array_del_users);
                    if (!empty($big_mass)) $qwe = PageRole::setUserRoles($big_mass);

                    $this->setObjectAjaxResponse('string', $message);
                }
            } else {
                $this->setErrorResponse('404', 'Ответ не доступен - доступ методом POST');
            }
        } else {
            $this->setErrorResponse('404', 'Страница не доступна');
        }
    }

    public function sortByName($sort_array) {
        $result_array = array(); // конечный массив с сортировкой
        if (count($sort_array)) {
            foreach ($sort_array as $key => $value) {
                $array_strlower[$key] = mb_strtolower($value['name'], 'UTF-8');
            }
            asort($array_strlower, SORT_STRING);
            foreach ($array_strlower as $key => $value) {
                foreach ($sort_array as $key_2 => $template) {
                    if ($key == $key_2) {
                        $result_array[] = $sort_array[$key_2];
                    }
                }
            }
        }
        return $result_array;

    }


}
?>
