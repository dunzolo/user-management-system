<?php
  session_start();
  require_once 'functions.php';

  /*
  $headers = getallheaders();
  $header = strtoupper($headers['X-Requested-With']);
  */

  $header = strtoupper($_SERVER['HTTP_X_REQUESTED_WITH'] ?? ''); //chiamata effettuata con ajax

  if(!empty($_POST) && $header === 'XMLHTTPREQUEST'){

    $token = $_POST['_csrf'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $result = verifyLogin($email, $password, $token);

    if($result['success']){
      session_regenerate_id();
      $_SESSION['logged_in'] = true;
      unset($result['user']['password']);
      $_SESSION['user_data'] = $result['user'];
    }  

    echo json_encode($result);
  }