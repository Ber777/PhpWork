<?php

class errorHelper extends Helper {
    var $name_error;
    var $code_error;

    function __construct(){}

    public function Draw404() {
        $this->drawBlock('error', '404');
    }

}

/**
 * Created by PhpStorm.
 * User: Kodix
 * Date: 08.06.2016
 * Time: 16:56
 */