<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  session_start();
  
  // require_once (realpath(dirname(__FILE__) . '/../functions.php'));
  require_once '../functions.php';
  $action = getParam('action', '');
  
  require '../model/user.php';

  $params = $_GET;

  unset($params['action']);
  unset($params['id']);

  $queryString = http_build_query($params);

  switch($action){
    case 'delete':
      
      if(!userCanDelete()) break;

      $id = getParam('id', 0);
      $userData = getUser($id);
      $res = delete($id);

      if($res){
        removeOldAvatar($id, $userData);
      }

      $message = $res ? 'USER '.$id.' DELETED' : 'ERRORE DELETING USER '.$id;

      $_SESSION['message'] = $message;
      $_SESSION['success'] = $res;
      
      header('Location:../index.php?'.$queryString);
      break;
    case 'save':

      if(!userCanUpdate()) break;

      $data = $_POST;
      $res = saveUser($data);

      if($res['id'] > 0){
        $resCopy = copyAvatar($res['id']);

        if($resCopy['success']){
          updateUserAvatar($res['id'], $resCopy['file_name']);
        }
        
        $message = 'USER INSERTED WITH ID '.$res['id'];
      }else{
        $message = 'ERRORE INSERTING USER '.$data['username'].' : '.$res['message'];
      }

      $_SESSION['message'] = $message;
      $_SESSION['success'] = $res;

      header('Location:../index.php?'.$queryString);
      break;
      
    case 'store':

      if(!userCanUpdate()) break;

      $data = $_POST;
      $id = getParam('id', 0);
      $resCopy = copyAvatar($id);
      
      if($resCopy['success']){
        removeOldAvatar($id);
        $data['avatar'] = $resCopy['file_name'];
      }
      $res = storeUser($data, $id);

      if($res['success']){
        $message = 'USER '.$id.' UPDATED';
      }else{
        $message = 'ERRORE UPDATING USER '.$id. ':'. $res['error'];
      }

      if(!($resCopy['success'])){
        $message .= ' '.$resCopy['message'];
      }

      $_SESSION['message'] = $message;
      $_SESSION['success'] = $res;
      
      header('Location:../index.php?'.$queryString);
      break;
  }
?>