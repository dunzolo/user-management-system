<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  
  // require_once (realpath(dirname(__FILE__) . '/connection.php'));

  require_once 'connection.php';

  function verifyLogin($email, $password, $token){

    require_once 'model/user.php';
    
    $result = [
      'message' => 'USER LOGGED IN',
      'success' => true
    ];

    if($token != $_SESSION['csrf']){
      $result = [
        'message' => 'TOKEN MISMATCH',
        'success' => false
      ];

      return $result;
    }

    //verifica se la mail è valida
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if(!$email){
      $result = [
        'message' => 'WRONG EMAIL',
        'success' => false
      ];

      return $result;
    }

    //verifico lunghezza della password
    if(strlen($password) < 6){
      $result = [
        'message' => 'PASSWORD TOO SMALL',
        'success' => false
      ];

      return $result;
    }

    $resEmail = getUserByEmail($email);

    if(!$resEmail){
      $result = [
        'message' => 'USER NOT FOUND',
        'success' => false
      ];
      return $result;
    }

    if(!password_verify($password, $resEmail['password'])){
      $result = [
        'message' => 'WRONG PASSWORD',
        'success' => false
      ];
      return $result;
    }

    $result['user'] = $resEmail;
    return $result;
  }

  function getConfig($param, $default = null){
    $config = require 'config.php';
    return array_key_exists($param, $config) ? $config[$param] : $default;
  }

  function getParam($param, $default = null){
    return !empty($_REQUEST[$param]) ? $_REQUEST[$param] : $default;
  }

  function getRandName(){
    $names = [ 'ROBERTO', 'GIOVANNI', 'GIULIA', 'ALESSANDRO', 'DAVIDE', 'PIETRO', 'MARTA', 'FRANCESCO', 'ARIANNA', 'MICHELE', 'GIACOMO'];
    $lastnames = ['ROSSI', 'BIANCHI', 'PROVA', 'CRUZ', 'SMITH', 'PIANTA', 'SASSO', 'TETTO', 'VERDI', 'FOX', 'CASALE', 'CARRO'];

    $randName = mt_rand(0, count($names) - 1);
    $randLastname = mt_rand(0, count($lastnames) - 1);

    return $names[$randName].' '.$lastnames[$randLastname];
  }

  function getRandEmail($name){
    $domains = ['google.com', 'libero.it', 'gmail.com', 'yahoo.com'];

    $randDomains = mt_rand(0, count($domains) - 1);

    return strtolower(str_replace(' ', '.', $name).mt_rand(10,99).'@'.$domains[$randDomains]);
  }

  function getRandFiscalCode(){
    $i = 16;
    $res = '';

    while($i > 0){
      $res .= chr(mt_rand(65,90));
      $i--;
    }

    return $res;
  }

  function getRandAge(){
    return mt_rand(0, 100);
  }

  function insertRandUser($totale, mysqli $conn){
    while($totale > 0){
      
      $username = getRandName();
      $email = getRandEmail($username);
      $fiscalCode = getRandFiscalCode();
      $age = getRandAge();
  
      $sql = 'INSERT INTO users (username, email, fiscal_code, age) VALUES';
      $sql .= "('$username', '$email', '$fiscalCode', $age)";
      
      $res = $conn->query($sql);

      if(!$res){ echo $conn->error; }
      else{ $totale--; }


    }

  }

  function getUsers(array $params=[]){

    /**
     * @var $conn mysqli
     */

    $conn = $GLOBALS['mysqli'];

    //leggo i valori se sono presenti nell'array, altrimenti imposto di default i valori di ordinamento
    $orderBy = array_key_exists('orderBy', $params) ? $params['orderBy'] : 'id';
    $orderDir = array_key_exists('orderDir', $params) ? $params['orderDir'] : 'ASC';
    $limit = (int)array_key_exists('recordsPage', $params) ? $params['recordsPage'] : '10';
    $page = (int)array_key_exists('page', $params) ? $params['page'] : 0;
    $start = $limit * ($page - 1);
    if($start < 0) { $start = 0; }
    $search = array_key_exists('search', $params) ? $params['search'] : '';

    //se inserisco caratteri speciale, sql pensa a sistemare la query
    $search = $conn->escape_string($search);
    
    //evitiamo il passaggio di altri parametri nell'ordinamento della chiamata
    if($orderDir !== 'ASC' && $orderDir !== 'DESC') { $orderDir = 'ASC'; }
    
    $records = [];

    //concateno la query in caso di campo compilato nella search
    $sql = "SELECT * FROM users ";

    if($search){ 
      $sql .= "WHERE username LIKE '%$search%' "; 
      $sql .= "OR fiscal_code LIKE '%$search%' "; 
      $sql .= "OR email LIKE '%$search%' "; 
      $sql .= "OR age LIKE '%$search%' ";
      $sql .= "OR id LIKE '%$search%' "; 
    }
    $sql .= "ORDER BY $orderBy $orderDir LIMIT $start, $limit";

    $res = $conn->query($sql);
    
    if($res){
      while($row = $res->fetch_assoc()){
        $records[] = $row;
      }
    }else{
      die($conn->error);
    }
    
    return $records;
  }

  function countUsers(array $params=[]){

    /**
     * @var $conn mysqli
     */

    $conn = $GLOBALS['mysqli'];

    //leggo i valori se sono presenti nell'array, altrimenti imposto di default i valori di ordinamento
    $orderBy = array_key_exists('orderBy', $params) ? $params['orderBy'] : 'id';
    $orderDir = array_key_exists('orderDir', $params) ? $params['orderDir'] : 'ASC';
    $limit = (int)array_key_exists('recordsPage', $params) ? $params['recordsPage'] : '10';
    $search = array_key_exists('search', $params) ? $params['search'] : '';

    //se inserisco caratteri speciale, sql pensa a sistemare la query
    $search = $conn->escape_string($search);
    
    //evitiamo il passaggio di altri parametri nell'ordinamento della chiamata
    if($orderDir !== 'ASC' && $orderDir !== 'DESC') { $orderDir = 'ASC'; }
    
    $total = 0;

    //concateno la query in caso di campo compilato nella search
    $sql = "SELECT COUNT(*) as total FROM users ";

    if($search){ 
      $sql .= "WHERE username LIKE '%$search%' "; 
      $sql .= "OR fiscal_code LIKE '%$search%' "; 
      $sql .= "OR email LIKE '%$search%' "; 
      $sql .= "OR age LIKE '%$search%' ";
      $sql .= "OR id LIKE '%$search%' "; 
    }

    $res = $conn->query($sql);
    
    if($res){
      $row = $res->fetch_assoc();
      $total = $row['total'];
    } else{
      die($conn->error);
    }
    
    return $total;
  }

  function copyAvatar(int $userId){
    $result = [
      'success' => false,
      'message' => 'PROBLEM SAVING IMAGE',
      'file_name' => ''
    ];

    //1 - controllo se è stato caricato un file
    if(empty($_FILES)){
      $result['message'] = 'NO FILE UPLOADED';
      return $result;
    }

    $FILE = $_FILES['avatar']; //così da scrivere meno codice ogni volta che devo verificare dati all'interno dell'array Avatar

    //2 - controllo se è stato caricato via browser (trmite HTTP POST)
    if(!(is_uploaded_file($FILE['tmp_name']))){
      $result['message'] = 'NO FILE UPLOADED VIA HTTP POST';
      return $result;
    }

    //3 - recupero le informazioni del nostro file
    $finfo = finfo_open(FILEINFO_MIME); //recuperato dalla documentazione PHP
    $info = finfo_file($finfo, $FILE['tmp_name']);

    //4 - controllo se il formato è quello che interessa a noi in fase di caricamento
    if(stristr($info,'image/jpeg') === false){
      $result['message'] = 'THE UPLOADED FILE IS NOT JPEG';
      return $result;
    }
    
    //5 - controllo se la dimensione del file supera il limite da noi impostato 
    $maxSize = getConfig('max_file_upload');
    if($FILE['size'] > $maxSize){
      $result['message'] = 'THE UPLOADED FILE IS TOO BIG. MAX FILE IS '.$maxSize;
      return $result;
    }

    //6 - salvo il file caricato
    $avatarDir = getConfig('avatar_dir');
    $fileName = $userId.'_'.str_replace('.', '', microtime(true)).'.jpg'; //creo il nome del file concatendando
    //sposto il file nella cartella indicata da $avatarDir+fileName
    if(!move_uploaded_file($FILE['tmp_name'], $avatarDir.$fileName)){
      $result['message'] = 'COULD NOT MOVE UPLOADED FILE';
      return $result;
    }

    //7 - recupero l'immagine dall'URL
    $newImg = imagecreatefromjpeg($avatarDir.$fileName);
    if(!$newImg){
      $result['message'] = 'COULD NOT CREATE THUMBNAIL RESOURCE';
      return $result;
    }

    //8 - creo la thumbnail dell'immagine passando la risorsa e la dimensione per la thumb da visualizzare
    $thumbImg = imagescale($newImg, getConfig('thumb_width', 150));
    $previewThumbImg = imagescale($newImg, getConfig('preview_thumb_img', 400));
    if(!$thumbImg){
      $result['message'] = 'COULD NOT SCALE THUMBNAIL RESOURCE';
      return $result;
    }

    //9 - salvo immagine
    imagejpeg($thumbImg, $avatarDir.'thumb_'.$fileName);
    imagejpeg($previewThumbImg, $avatarDir.'preview_'.$fileName);

    $result['file_name'] = $fileName;
    $result['success'] = true;
    $result['message'] = '';
    return $result;
  }

  function removeOldAvatar(int $userId, array $userData = null){

    //se è presente $userData lo assegna altrimenti lo recupera
    $userData = $userData?:getUser($userId);

    //se non è presente utente o avatar, ritorno subito ed evito i controlli successivi
    if(!$userData || !$userData['avatar']) return;

    $avatarFolder = getConfig('avatar_dir');
    $fileName = $avatarFolder.$userData['avatar'];
    $fileThumbName = $avatarFolder.'thumb_'.$userData['avatar'];

    //elimino l'immagine corrente
    if(file_exists($fileName)){
      unlink($fileName);
    }

    //elimino la thumb corrente
    if(file_exists($fileThumbName)){
      unlink($fileThumbName);
    }
  }

  function isUserLoggedIn(){
    return $_SESSION['logged_in'] ?? false;    
  }

  function getUserLoggedInFullName(){
    return $_SESSION['user_data']['username'] ?? '';
  }

  function getUserRole(){
    return $_SESSION['user_data']['role_type'] ?? '';
  }
  
  function getUserEmail(){
    return $_SESSION['user_data']['email'] ?? '';
  }

  function isUserAdmin(){
    return getUserRole() === 'admin';
  }

  function userCanUpdate(){
    $role = getUserRole();
    return $role === 'admin' || $role === 'editor';
  }
  
  function userCanDelete(){
    return isUserAdmin();
  }

  //uso se voglio inserire dati casuali
  // insertRandUser(150, $mysqli);

  //var_dump(getUsers());

  //echo countUsers();
