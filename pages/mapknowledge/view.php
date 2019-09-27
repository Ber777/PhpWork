<?

$helper->BlockNavigationChain();
$helper->BlockSearch('default');
$helper->BlockWorkPanel();
$helper->ListObjects('catalogs_in_mk');
$helper->Pagination('catalogs');
$helper->ListObjects('documents');
$helper->Pagination('documents');
$helper->BlockComment();
$helper->Pagination('messages');
$helper->HideFormSearch();
