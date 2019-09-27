<?php
/*echo session_status();
if (session_status()!=PHP_SESSION_ACTIVE)
{
	session_start();
	echo "begin";
	}
else echo "continue";
echo session_id();*/
#error_reporting (0);

require_once 'settings.php';
require_once 'dbconn.php';
require_once 'functions.php';


 //$_SESSION["AUTH"]  = '';
 //$_SESSION["PASSWORD"]  = '';
//echo 12345;
/*if(!(isset($_SESSION["AUTH"])) || ($_SESSION["AUTH"]== ''))
{
  //$Users = new Users();
  //$Users->get_cur_user_name();
  if(isset($_SERVER['PHP_AUTH_USER']))
  {
    $_SESSION["AUTH"]  = $_SERVER['PHP_AUTH_USER'];
    $_SESSION["PASSWORD"]  = $_SERVER['PHP_AUTH_PW'];
  }

  else{ 
        header('WWW-Authenticate: Basic Realm="Login please"');
        exit;
  }
}*/


 //if(!(isset($_SERVER['PHP_AUTH_USER'])))
 //{
 //   $_SERVER["PHP_AUTH_USER"]  = "xgb_nir";
 //}

#print_r($_GET);

$ROUTER = new Router();
$REQUEST = $ROUTER->parseURL();

$FRONT_CONTROLLER = new FrontController($REQUEST);

$ACTIVE_CONTROLLER = $FRONT_CONTROLLER->renderAction();

$RESPONSE = $ACTIVE_CONTROLLER->object_response;

$HELPER = $ACTIVE_CONTROLLER->object_helper;

$FRONT_CONTROLLER->renderPage($ACTIVE_CONTROLLER->object_response, $ACTIVE_CONTROLLER->object_helper);


require_once 'include/array_objects.php';

/**
 * Created by PhpStorm.
 * User: Mezizto
 * Date: 25.05.2016
 * Time: 22:59
 */
