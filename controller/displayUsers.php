<?php
  //cerco il valore di orderBy e se non è tra quelli presenti nell'elenco delle colonne prestabilite, imposto ID di default
  if(!in_array($orderBy, getConfig('order_by_columns'))){ 
    $orderBy = 'id';
  }

  $params = [
    'orderBy' => $orderBy,
    'orderDir' => $orderDir,
    'recordsPage' => $recordsPage,
    'search' => $search,
    'page' => $page
  ];

  $orderByParams = $orderByNavigatorParams = $params;
  //uso unset così non viene passata la chiave una seconda volta
  unset($orderByParams['orderBy']);
  unset($orderByParams['orderDir']);
  unset($orderByNavigatorParams['page']);

  //key = value & key2 = value 2 & ecc ecc
  $orderByQueryString = http_build_query($orderByParams, '', '&amp;');
  $navOrderByQueryString = http_build_query($orderByNavigatorParams, '', '&amp;');

  $totalUsers = countUsers($params);

  //arrotondo per eccesso
  $numPages = ceil($totalUsers / $recordsPage);
  
  $users = getUsers($params);
  // $users = [];
  
  require_once 'views/usersList.php';
?>