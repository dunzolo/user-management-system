<?php
  session_start();
  require_once 'functions.php';
  
  if(!isUserLoggedIn()){
    header('Location: login.php');
    exit;
  }

  if(!userCanUpdate()){
    header('Location: index.php');
    exit;
  }

  require_once 'headerInclude.php';
?>
<main class='flex-shrink-0'>
  <div class='container'>
    <h1 class="text-center p-3">USER MANAGEMENT SYSTEM</h1>
    <?php
      $id = getParam('id', 0);
      $action = getParam('action', '');
      $orderDir = getParam('orderDir', 'ASC');
      $orderBy = getParam('orderBy', 'id');
      $search = getParam('search', '');
      $page = getParam('page', 1);

      $paramsArray = compact('orderBy', 'orderDir', 'page', 'search');
      $defaultParams = http_build_query($paramsArray,'','&amp;');

      //se è presente id lo recupero e sto eseguendo un UPDATE, altrimenti passo array vuoto per simulare la CREATE di un nuovo record
      if($id){
        $user = getUser($id);
      } else{
        $user = [
          'username' => '',
          'email' => '',
          'fiscal_code' => '',
          'age' => '',
          'id' => '',
          'avatar' => '',
          'password' => '',
          'role_type' => 'user'
        ];
      }

      require_once './views/formUpdate.php';
    ?>
  </div>
</main>
<?php
  require_once './views/footer.php';
?>