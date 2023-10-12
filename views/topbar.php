<?php
  $indexPage = 'index.php';
  $action = $_GET['action'] ?? '';
  $indexActive = !$action ? 'active' : '';
  $newActive = $action === 'insert' ? 'active' : '';
?>

<header>
  <!-- Fixed navbar -->
  <nav class='navbar navbar-expand-md navbar-dark fixed-top bg-dark'>
    <div class='container-fluid'>
      <a class='navbar-brand' href='#'><i class="fa-solid fa-user fa-lg"></i></a>
      <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarCollapse'
        aria-controls='navbarCollapse' aria-expanded='false' aria-label='Toggle navigation'>
          <span class='navbar-toggler-icon'></span>
      </button>
      <div class='collapse navbar-collapse' id='navbarCollapse'>
        <ul class='navbar-nav me-auto mb-2 mb-md-0'>
          <li class='nav-item'>
            <a class='nav-link <?=$indexActive?>' aria-current='page' href='<?=$indexPage?>'>
              <i class='fa-solid fa-users'></i>Users
            </a>
          </li>
          <li class='nav-item'>
            <a class='nav-link <?=$newActive?>' href="updateUser.php?action=insert">
              <i class='fa-solid fa-user-plus'></i>New User
            </a>
          </li>
        </ul>
        <ul class="nav navbar-nav flex-row justify-content-between">
          <?php if(isUserLoggedIn()):?>
          <li class="nav-item order-2 order-md-1"><a href="#" class="nav-link" title="settings"><i class="fa fa-cog fa-fw fa-lg"></i></a></li>
          <li class="dropdown order-1">
          <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">My profile <span class="caret"></span></button>
            <ul class="dropdown-menu dropdown-menu-end mt-3 p-3 text-center">
              <li class="fw-bold"><?=getUserLoggedInFullName()?></li>
              <li><?=getUserEmail()?></li>
              <li>
                <form class="form mt-2" role="form" method="POST" action="verify-login.php">
                  <!-- <div class="form-group mb-2">
                      <input id="emailInput" placeholder="Email" class="form-control form-control-sm" type="text" required="">
                  </div>
                  <div class="form-group mb-2">
                      <input id="passwordInput" placeholder="Password" class="form-control form-control-sm" type="text" required="">
                  </div>
                  <div class="form-group mb-2">
                      <button type="submit" class="btn btn-sm btn-primary btn-block">Login</button>
                  </div>
                  <div class="form-group text-xs-center">
                      <small><a href="#">Forgot password?</a></small>
                  </div> -->
                  <input type="hidden" name="action" value="logout">
                  <button class="btn btn-outline-primary">Logout</button>
                </form>
              </li>
            </ul>
          </li>
          <?php else: ?>
          <li class="nav-item">
            <a href="login.php" class="btn btn-outline-success">Login</a>
          </li>
          <?php endif; ?>
        </ul>
        <!-- <div class="dropdown">
          <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
            Dropdown button
          </button>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </div> -->
      </div>
    </div>
  </nav>
</header>