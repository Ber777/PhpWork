<?php

$helper->BlockNavigationChain();
$helper->BlockSearch();
$helper->BlockWorkPanel();
$helper->ListObjects('catalogs_in_db');
$helper->Pagination('catalogs');
$helper->ListObjects('documents');
$helper->Pagination('documents');
