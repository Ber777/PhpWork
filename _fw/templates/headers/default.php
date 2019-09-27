
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php foreach($response['arrCSS'] as $item): ?>
       <link href="<?=$item ?>" rel="stylesheet" type="text/css">
    <?php endforeach; ?>
    <?php $helper->UserStyles() ?>
    <?php foreach ($response['arrJS'] as $item): ?>
        <script type="text/javascript" src="<?=$item ?>"></script>
    <?php endforeach; ?>
    <title><?=$response['title'] ?></title>
</head>
<body>

<?php
if (!$response['auth_user']) {
    $helper->PopUp('auth');
} else {
    $helper->PopUp();
}

$Users = Users::get_curuser();

$helper->TopMenu(
    $arrayLinks = array(
        0 => array('name' => 'Базы данных', 'link' => '/database/'),
        1 => array('name' => 'Управление знаниями', 'link' => '/mapknowledge/'),
        2 => array('name' => 'Шаблоны', 'link' => '#' ),
        3 => array('name' => 'Свойства', 'link' => '#'),
        4 => array('name' => 'Логи', 'link' => '/logs/log.txt'),
	5 => array('name' =>  'Пользователь: '.$Users->name, 'link' => '#')
        //6 => array('name' => 'Выход', 'link' => '/profile/logout/')
        //5 => array('name' =>  'ALD-User:'.$_SERVER['REMOTE_USER'], 'class' => 'modal-access-user')
        //3 => array('name' => 'Роли', 'link' => 'roles', 'class' => 'standart-button'),
    ),
    $arraySubMenu = array(
        2 => array(
            array('name' => 'Шаблоны поиска', 'link' => '/template/search/'),
            array('name' => 'Шаблоны документов', 'link' => '/template/document/'),
            array('name' => 'Шаблоны рубрик', 'link' => '/template/catalog/'),
            array('name' => 'Шаблоны карт знаний', 'link' => '/template/mapknowledge/'),
            array('name' => 'Оповещения', 'link' => '/template/alert/'),
            array('name' => 'Поиск', 'link' => '/bigsearch/'),
        ),
        3 => array(
            array('name' => 'Дескрипторы', 'link' => '/tag/'),
            array('name' => 'Атрибуты', 'link' => '/attribute/'),
            array('name' => 'Профиль', 'link' => '/profile/settings/'),
        ),
        5 => array(
            array('name' => 'Список пользователей', 'class' => 'all-users')
        )
    ));

if (isset($helper->left_block) && $response['object'] != 'error' && $response['auth_user']) {
    $helper->LeftMenu();
}?>


<div id="content" class="_inline-block">

<?php
if ($response['status_messages']) {
    $helper->TableErrors($response['status_messages']);
}?>




