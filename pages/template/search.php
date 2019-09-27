<?php

$helper->Title('default', 'Шаблоны поиска');
$helper->BlockSearch('templates');
$helper->LinkAddSearchTemplate();
$helper->ListSearchTemplatesUser();
//$helper->Pagination('user_templates');
$helper->ListSearchTemplates();
//$helper->Pagination('all_templates');
$helper->HideFormSearch();
