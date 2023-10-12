<?php
  function delete(int $id){
    /**
     * @var $conn mysqli
     */
     $conn = $GLOBALS['mysqli'];

     $sql = 'DELETE FROM users WHERE id ='.$id;

     $res = $conn->query($sql);

     return $res && $conn->affected_rows;
  }

  function getUser(int $id){
    /**
    * @var $conn mysqli
    */
    $conn = $GLOBALS['mysqli'];
    $result = [];

    $sql = 'SELECT * FROM users WHERE id ='.$id;

    $res = $conn->query($sql);

     //se la query è vera e sono tornati delle righe dalla query
    if($res && $res->num_rows){
      $result = $res->fetch_assoc();
    }

    return $result;
  }

  function getUserByEmail(string $email){
    /**
    * @var $conn mysqli
    */
    $conn = $GLOBALS['mysqli'];
    $result = [];

    //verifica se la mail è valida
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    if(!$email) return $result;

    $email = $conn->escape_string($email);
    $sql = "SELECT * FROM users WHERE email = '$email'";

    $res = $conn->query($sql);

     //se la query è vera e sono tornati delle righe dalla query
    if($res && $res->num_rows){
      $result = $res->fetch_assoc();
    }

    return $result;
  }

  function storeUser(array $data, int $id){
    /**
    * @var $conn mysqli
    */
    $result = [
      'success' => 1,
      'affectedRows' => 0,
      'error' => 0
    ];

    $conn = $GLOBALS['mysqli'];
    
    $username = $conn->escape_string($data['username']);
    $email = $conn->escape_string($data['email']);
    $fiscalCode = $conn->escape_string($data['fiscal_code']);
    $age = $conn->escape_string($data['age']);
    $avatar = $conn->escape_string($data['avatar']);

    $sql = 'UPDATE users SET ';
    $sql .= "username='$username', email='$email', fiscal_code='$fiscalCode', age=$age, avatar='$avatar'";
    
    if($data['password']){
      //se è presente la password inserisci quella altrimenti usi 'testuser'
      $data['password'] = $data['password'] ?? 'testuser';
      
      //cripto la password col metodo di defualt
      $password = password_hash($data['password'], PASSWORD_DEFAULT);
      
      $sql .= ", password='$password'";
    }
    
    if($data['role_type']){
      //verifico se il ruolo passato è presente nei ruoli definiti, altrimenti inserisco user
      $roleType = in_array($data['role_type'], getConfig('role_types', [])) ? $data['role_type'] : 'user';
      
      $sql .= ", role_type='$roleType'";
    }
    
    $sql .= ' WHERE id ='.$id;

    // var_dump($sql); die();
    
    $res = $conn->query($sql);
    
    //se la query va a buon fine, ritorna numero di righe modificate
    if($res){
      $result['affectedRows'] = $conn->affected_rows;
    }else{
      $result['success'] = false;
      $result['error'] = $conn->error;
    }

    return $result;
  }

  function saveUser(array $data){
    /**
    * @var $conn mysqli
    */
    $conn = $GLOBALS['mysqli'];
    
    $result = [
      'id' => 0,
      'success' => false,
      'message' => 'PROBLEM SAVING USER'
    ];
    
    $username = $conn->escape_string($data['username']);
    $email = $conn->escape_string($data['email']);
    $fiscalCode = $conn->escape_string($data['fiscal_code']);
    $age = (int)($data['age']);

    //se è presente la password inserisci quella altrimenti usi 'testuser'
    $data['password'] = $data['password'] ?? 'testuser';
    
    //cripto la password col metodo di defualt
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    
    //verifico se il ruolo passato è presente nei ruoli definiti, altrimenti inserisco user
    $roleType = in_array($data['role_type'], getConfig('role_types', []) ? $data['role_type'] : 'user');
    
    $sql = 'INSERT INTO users (username, email, fiscal_code, age, password, role_type) ';
    $sql .= "VALUES ('$username', '$email', '$fiscalCode', $age, '$password', '$roleType')";

    $res = $conn->query($sql);
    
    //se la query va a buon fine, ritorna numero l'id inserito in questa connessione
    if($res && $conn->affected_rows){
      $result['id'] = $conn->insert_id;
      $result['success'] = true;
    }else{
      $result['message'] = $conn->error;
    }

    return $result;
  }

  function updateUserAvatar(int $userId, $avatar = null){
    /**
    * @var $conn mysqli
    */
    $conn = $GLOBALS['mysqli'];

    if(!$avatar) return false;

    $avatar = $conn->escape_string($avatar);
    $sql = "UPDATE users SET avatar = '$avatar' WHERE id = $userId";
    
    $res = $conn->query($sql);
    
    //ritorna numero di righe modificate
    return $res && $conn->affected_rows;
  }
?>