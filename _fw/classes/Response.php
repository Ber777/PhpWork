<?php

class Response {
    var $id_user;
    var $auth_user;
    var $object;
    var $template;
    var $title;
    var $header = 'default';
    var $arrCSS = array();
    var $arrJS = array();
    var $data = array();
    var $error = 0;
    var $status_messages = array();
    var $footer = 'default';


    function __construct()
    {
    }

    public function setAuthUser ($auth_user) {
        $this->auth_user = $auth_user;
    }

    public function getError()
    {
        return $this->error;
    }

    public function setObject($object)
    {
        $this->object = $object;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setArrCSS($arrCSS)
    {
        $this->arrCSS = $arrCSS;
    }

    public function setArrJS($arrJS)
    {
        $this->arrJS = $arrJS;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function setError()
    {
        $this->error = 1;
    }

    public function setIdUser($id) {
        $this->id_user = $id;
    }

    public function setHeader($name) {
        $this->header = $name;
    }

    public function delHeader() {
        $this->header = 0;
    }

    public function setFooter($name) {
        $this->footer = $name;
    }

    public function delFooter() {
        $this->footer = 0;
    }

    public function setErrorMessages($key, $message) {
        $this->status_messages[$key] = $message;
    }





}

/**
 * Created by PhpStorm.
 * User: Mezizto
 * Date: 07.05.2016
 * Time: 22:24
 */