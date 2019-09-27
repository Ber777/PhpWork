<?
#unset($_SESSION['user_id']);
#session_unset(); 
#redirect#$_SESSION["AUTH"] = 0;
#unset($_SESSION['user_id']);
#header('Location: index.php');
//require_once 'settings.php';
//require_once 'dbconn.php';
//require_once 'functions.php';

session_start();

if(isset($_SESSION["AUTH"]) && ($_SESSION["AUTH"] != ''))
{
  unset($_SESSION["AUTH"]);
  //session_destroy();
  header('HTTP/1.0 401 Unauthorized');
  //header('Location: http://localhost');
  header('WWW-Authenticate: Basic Realm="Private"');
  exit;
}

else{
  header('Location: http://localhost');
  exit;
}
//echo session_id();
//echo "FMEWKFMEWNGOPEW";
//echo $_SESSION["AUTH"];
//unset($_SESSION["AUTH"]);
//session_destroy();

//echo session_id();
//echo "HELLOOO";
//echo $_SESSION["AUTH"];

//session_start();echo session_id();
/*if(isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_USER'] != ''))
{
  unset($_SESSION["AUTH"]);
  session_destroy();
  header('HTTP/1.0 401 Unauthorized');
  header('Location: http://localhost');
  header('WWW-Authenticate: Basic Realm="Private"');
  exit;
}*/

?>

