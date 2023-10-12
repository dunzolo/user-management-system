<?php
  
  // $config = require_once (realpath(dirname(__FILE__) . '/config.php'));
  $config = require 'config.php';

  $mysqli = new mysqli(
    $config['mysql_host'],
    $config['mysql_user'],
    $config['mysql_pwd'],
    $config['mysql_db'],
  );

  unset($config);

  //verifico la connessione e nel caso di errore, comunico quest'ultimo ed interrompo
  if($mysqli->connect_error){
    die($mysqli->connect_error);
  }