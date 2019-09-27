<?php

class errorController extends Controller {

    function __construct() {
        parent::__construct();
        $this->name_object = 'error';
        $this->arrayCSS  = array(
            '/pages/error/style_error.css'
        );
        $this->arrayJS  = array();
        $this->setErrorResponse('ErrorController', 'Ошибка отображения страницы');
        $this->object_helper = $this->setObjectHelper($this->name_object);
        $this->object_helper->user_styles = $this->user_info->profile_settings;
    }

    public function index() {
        $this->setObjectResponse(
            $this->name_object,
            'page404',
            $this->mergeWithDefaultCSS($this->arrayCSS),
            $this->mergeWithDefaultJS($this->arrayJS),
            'Ошибка 404!',
            $this->data
        );
    }

    public function page404() {
        $this->setData(array(
            'TYPE_ERROR' => '404',
            'PAGE_ERROR' => 'page404'
        ));

        $this->setObjectResponse(
            __FUNCTION__,
            $this->mergeWithDefaultCSS($this->arrayCSS),
            $this->mergeWithDefaultJS($this->arrayJS),
            'Ошибка 404!',
            $this->data
        );
    }
}
/**
 * Created by PhpStorm.
 * User: Kodix
 * Date: 19.05.2016
 * Time: 12:42
 */
