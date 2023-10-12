<?php
  session_start();
  require_once './functions.php';

  if(!isUserLoggedIn()){
    header('Location: login.php');
    exit;
  }
  require_once 'headerInclude.php';
?>

<!-- Begin page content -->
<main class='flex-shrink-0'>
  <div class='container'>
    <h1 class="text-center p-1">USER MANAGEMENT SYSTEM</h1>
    <?php
      
      if(!empty($_SESSION['message'])){
        $message = $_SESSION['message'];
        $alertType = $_SESSION['success'] ? 'success' : 'danger';
        require './views/message.php';
        unset($_SESSION['message'], $_SESSION['success']);
      }

      require_once './controller/displayUsers.php';
      
    ?>
  </div>
</main>

<?php
 require_once './views/footer.php';
?>