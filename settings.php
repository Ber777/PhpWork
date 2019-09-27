<?php

ini_set('display_errors', 1);
header('Content-Type: text/html; charset=utf-8' ); //занести в request
iconv_set_encoding("internal_encoding", "UTF-8");
//session_start(); proverki

define('SITE', __DIR__);
define('PAGES', __DIR__ . '/pages/');
define('DIR_FW', __DIR__.'/_fw/');
define('CLASSES', DIR_FW . 'classes/');
define('CONTROLLERS', DIR_FW . 'controllers/');
define('HELPERS', DIR_FW . 'helpers/');
define('MODELS', DIR_FW . 'models/');
define('TEMPLATES', DIR_FW . 'templates/');
define('HEADERS', TEMPLATES . 'headers/');
define('FOOTERS', TEMPLATES . 'footers/');
define('BLOCKS', TEMPLATES . 'blocks/');
define('PRIMARY_BLOCKS', TEMPLATES . 'primary_blocks/');
define('SECONDARY_BLOCKS', TEMPLATES . 'secondary_blocks/');
define('IMAGES', __DIR__ . 'images/');


/*------------------ АВТОМАТИЧЕСКОЕ ПОДКЛЮЧЕНИЕ КЛАССОВ -----------------------*/

spl_autoload_register('models_autoload');
spl_autoload_register('classes_autoload');


function models_autoload($model_name) {
    if (file_exists(MODELS . $model_name . '.php')) {
        require_once(MODELS . $model_name . '.php');
    }    
}

function classes_autoload($class_name) {
    if (file_exists(CLASSES . $class_name . '.php')) {
        require_once(CLASSES . $class_name . '.php');
    }
}

/*------------------ ОПРЕДЕЛЕНИЕ ГЛОБАЛЬНОЙ ПЕРЕМЕННОЙ -----------------------*/


/**
 * Created by PhpStorm.
 * User: Mezizto
 * Date: 25.05.2016
 * Time: 23:00
 */
