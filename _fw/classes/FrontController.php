<?php

class FrontController
{
    var $object_request; //объект запроса
    var $object_response; // объект ответa

    function __construct($object_request1)
    {
        if (!($object_request1 instanceof Request)) 
	{
            die('В конструктор FrontController необходимо подавать объект типа Request!');
        }
        $this->object_request = $object_request1;
    }
    public function renderAction()
    {

        if ($this->object_request->name_controller == '') {
            $this->object_request->name_controller = 'database';
        }

        $name_controller = $this->object_request->name_controller;
        $name_action = $this->object_request->name_action;
        $input_value_for_action = $this->object_request->input_value_for_action;
        $params = $this->object_request->params;

	#echo (isset($name_controller));
	#echo (isset($name_action));
	#echo (isset($input_value_for_action));

        $name_controller .= 'Controller';
        $file = CONTROLLERS . $name_controller . '.php';
        if (file_exists($file)) {
            require_once "$file";
        } else {
            require_once CONTROLLERS . 'errorController.php';
            $controller = new errorController();
            $controller->page404();
            return $controller;
        }

        if (!class_exists($name_controller)) {
            require_once CONTROLLERS . 'errorController.php';
            $controller = new errorController();
            $controller->page404();
            $controller->setMessageErrorResponse('404', "Не существует класса $name_controller");
            return $controller;
        }

        $controller = new $name_controller();
        $controller->object_request = $this->object_request;
        $controller->values_from_url = $params;

        if (isset($input_value_for_action) && ($input_value_for_action != '')) {
            if (method_exists($name_controller, $name_action)) {
                $controller->{$name_action}($input_value_for_action);
            } else {
                require_once CONTROLLERS . 'errorController.php';
                $controller = new errorController();
                $controller->page404();
                $controller->setMessageErrorResponse('404', "Не существует метода $name_action($input_value_for_action) у класса $name_controller");
                return $controller;
            }
        } elseif (isset($name_action) && ($name_action != '')) {
            if (method_exists($controller, $name_action)) {
                $controller->{$name_action}();
            } else {
                require_once CONTROLLERS . 'errorController.php';
                $controller = new errorController();
                $controller->page404();
                $controller->setMessageErrorResponse('404', "Не существует метода $name_action у класса $name_controller");
                return $controller;
            }
        } else {
            if (method_exists($controller, 'index')) {
                $controller->index();
            } else {
                require_once CONTROLLERS . 'errorController.php';
                $controller = new errorController();
                $controller->page404();
                $controller->setMessageErrorResponse('404', "Не существует метода index у класса $name_controller");
                return $controller;
            }

        }
        return $controller;
    }

    public function renderPage($response, $helper)
    {
        $this->object_response = $response;

        if (!($this->object_response instanceof Response)) {
            die('На вывод страницы, небходим объект Response');
        }

        if (!($helper instanceof Helper)) {
            $this->object_response->setErrorMessages('Error Helper', 'Во FrontController не взовращаются данные типа Helper');
        }

        $response = _fw_json_encode($this->object_response);
        $response = _fw_json_decode_assoc($response);

        if ($response['error']) {
            $response['object'] = 'error';
        }

        if (!$response['auth_user']) {
            $response['object'] = 'error';
            $response['template'] = 'auth';
            $response['footer'] = '';
        }

        if (empty($response['object'])) {
            _fw_print_html_tag('pre', $response);
            die('Response пустой, обязательные параметры: (object, template)');
        }

        if ($response['header']) {
            include HEADERS . $response['header'] . '.php';
        }

        if (file_exists(PAGES  . $response['object'] . '/' . $response['template'] . '.php')) {
            include PAGES  . $response['object'] . '/' . $response['template'] . '.php';
        }
        else {
            include PAGES . '/error/page404.php';
        }

        if ($response['footer']) {
            include FOOTERS . $response['footer'] . '.php';
        }
        Logger::write_log('Был осуществлен запрос: '. $_SERVER["REQUEST_URI"] . ' с IP-адреса: ' . $_SERVER["REMOTE_ADDR"]);
    }

}
