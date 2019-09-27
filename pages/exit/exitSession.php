<?
#session_destroy(); 
#redirect
$_SESSION["AUTH"] = 0;
#unset($_SESSION['user_id']);
header('Location: index.php');
exit;
?>

