<?php
echo $_SERVER['PHP_AUTH_USER'];
echo $_SERVER['PHP_AUTH_PW'];

$login = $_SERVER['PHP_AUTH_USER'];
$pass  = $_SERVER['PHP_AUTH_PW'];

echo $login;
echo $pass;
?>
