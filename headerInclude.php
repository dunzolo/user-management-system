<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL); 
  
  require_once './functions.php';
  require_once './model/user.php';
  require_once './views/top.php';

  $pageUrl = $_SERVER['PHP_SELF'];
  $updateUrl = 'updateUser.php';
  $deleteUrl = 'controller/updateRecord.php';
  
  $orderDir = getParam('orderDir', 'ASC');
  $orderBy = getParam('orderBy', 'id');
  $orderByColumns = getConfig('order_by_columns', ['id', 'username', 'email', 'fiscal_code', 'age', 'role_type']);
  $recordsPage = getParam('recordsPage', getConfig('records_page'));
  $recordsPageOptions = getConfig('records_page_options', [5,10,20,30,50]);
  $search = getParam('search', '');
  $page = getParam('page', 1);

  require_once './views/topbar.php';