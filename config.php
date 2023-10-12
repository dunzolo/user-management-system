<?php
  $mega = 1024;
  $giga = $mega * 1024;

  $maxUpload = ini_get('upload_max_filesize');

  if(stristr($maxUpload, 'G')){
    $maxUpload = intval($maxUpload) * $giga;
  }else{
    $maxUpload = intval($maxUpload) * $mega;
  }

  return  [
    'mysql_host' => 'mysql',
    'mysql_user' => 'root',
    'mysql_pwd' => 'root',
    'mysql_db' => 'php_corso',
    'records_page' => 10,
    'records_page_options' => [5,10,20,30,50,100],
    'order_by_columns' => ['id', 'email', 'fiscal_code', 'username', 'age', 'role_type'],
    'num_link_nav' => 5,
    'max_file_upload' => $maxUpload,
    'avatar_dir' => $_SERVER['DOCUMENT_ROOT'].'/avatar/',
    'avatar_dir_web' => '/avatar/',
    'thumb_width' => 200,
    'thumb_width_user_list' => 100,
    'preview_img_width' => 500,
    'role_types' => ['user', 'editor', 'admin']
  ];

  // metodo alternativo
  // const MAX_FILE_SIZE = 3000000;
