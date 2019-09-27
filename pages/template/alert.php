<?php

$helper->Title('default', 'Оповещения');
$helper->BlockSearch('templates');
$helper->LinkAddAlertTemplate();
$helper->ListAlertTemplatesUser();
//$helper->Pagination('user_templates');
$helper->ListAlertTemplates();
//$helper->Pagination('all_templates');
$helper->HideFormSearch();
