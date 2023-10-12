<?php
  session_start();

  require_once 'functions.php';

  if(isUserLoggedIn()){
    header('Location: index.php');
    exit;
  }
  
  $bytes = random_bytes(32);
  $token = bin2hex($bytes);
  $_SESSION['csrf'] = $token;

  require_once 'views/top.php';
?>
  
  <div class="container mt-5">
    <div id="login-form">
      <h1 class="text-center">LOGIN</h1>
      <?php if(!empty($_SESSION['message'])):?>
      <div id="message" class="alert alert-info">
        <?=$_SESSION['message']?>
      </div>
      <?php $_SESSION['message'] = ''; endif; ?>
      <form action="verify-login.php" method="POST">
        <input type="hidden" name="_csrf" value="<?=$token?>">
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
        </div>
        <!-- <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" id="exampleCheck1">
          <label class="form-check-label" for="exampleCheck1">Check me out</label>
        </div> -->
        <div class="mb-3 text-center">
          <button type="submit" class="btn btn-primary btn-lg">Submit</button>
        </div>
      </form>
    </div>
  </div>

<?php
  require_once 'views/footer.php';
?>

<script>
  $(
    function(){
      $('form').on('submit', function(evt){
        evt.preventDefault();
        const data = $(this).serialize();
        $.ajax({
          method:'POST',
          data: data,
          url: 'verify-login-ajax.php',
          success: function(response){
            const data = JSON.parse(response);

            if(data){
              alert(data.message);
              if(data.success){ location.href = 'index.php'; }
            }
          },
          failure: function(){
            alert('PROBLEM CONTACTING SERVER')
          }
        })
      }
    )}
  )
</script>