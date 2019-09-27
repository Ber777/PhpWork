<?php


class Router {

    var $url; //входная URL-строка

    function __construct() {
	$url1=array();
        $array_url = $_GET;
        foreach ($array_url as $key => $value) {
            if ($key == 'url') {
                $url1 = isset($_GET['url']) ? $_GET['url'] : NULL;
                $url1 = strip_tags($url1);
                $url1 = rtrim($url1, '/');
                $url1 = filter_var($url1, FILTER_SANITIZE_URL);
                $url1 = explode('/', $url1);
            }
            else {
                $url1['params'][$key] = $value;
            }
        }


        $this->url = $url1;
    }

    public function ParseUrl () {
        $object_request = new Request($this->url);
	return $object_request;
    }
}
