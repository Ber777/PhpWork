<?php


class Request {
    var $name_controller = '';
    var $name_action = '';
    var $input_value_for_action = ''; // входная переменная для action
    var $params = ''; // оставшиеся параметры
    var $method; // метод запроса (POST или GET)
    var $type_request;  // определение типа запроса (ajax)
    var $content_type = ''; // для шапки страницы

    public function __construct($arrayURL)
    {
	if(isset($arrayURL[0]))
        $this->name_controller = $arrayURL[0];
	if(isset($arrayURL[1]))
        $this->name_action = $arrayURL[1];
	if(isset($arrayURL[2]))
        $this->input_value_for_action = $arrayURL[2];
if(isset($arrayURL['params']))
        $this->params = $arrayURL['params'];
	
        $this->method = $_SERVER['REQUEST_METHOD'];

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']))
        $this->type_request = $_SERVER['HTTP_X_REQUESTED_WITH'];
    }
}
