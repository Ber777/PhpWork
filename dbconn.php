<?php
//require_once MODELS . 'Users.php';
Users::auth_curuser();
$DATABASE = new Dbase();
$DATABASE->dbPostgresSQLConnect();
//DATABASE::db_link = $DATABASE->link;//TODO
Users::get_curuser();

//$user = new Users();
//$user->link = $DATABASE->link;

//$curuser = $user->get_current_user();

