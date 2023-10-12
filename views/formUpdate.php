<form enctype="multipart/form-data" id="updateForm" action="controller/updateRecord.php?<?=$defaultParams?>" method="POST">

  <!-- invio id nascosto per il salvataggio/recupero del record corrispondente -->
  <input type="hidden" name="id" value="<?=$user['id']?>">
  
  <!-- se è presente id vuol dire che sto aggiornando un recordo altrimenti sto seguendo un inseriment di un nuovo record -->
  <input type="hidden" name="action" value="<?=$user['id'] ? 'store' : 'save'?>">
  <div class="row mb-3">
    <label for="username" class="col-sm-2 col-form-label">Username</label>
    <div class="col-sm-10">
      <input required type="text" class="form-control form-control-lg" id="username" placeholder="Username" name="username" value="<?=$user['username']?>">
    </div>
  </div>

  <div class="row mb-3">
    <label for="email" class="col-sm-2 col-form-label">Email</label>
    <div class="col-sm-10">
      <input required type="email" class="form-control form-control-lg" id="email" placeholder="Email" name="email" value="<?=$user['email']?>">
    </div>
  </div>
  
  <div class="row mb-3">
    <label for="password" class="col-sm-2 col-form-label">Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control form-control-lg" id="password" placeholder="Password" name="password" value="">
    </div>
  </div>

  <div class="row mb-3">
    <label for="role_type" class="col-sm-2 col-form-label">Role Type</label>
    <div class="col-sm-10">
      <select name="role_type" id="role_type" class="form-select">
        <?php foreach(getConfig('role_types', []) as $role):
          $sel = $user['role_type'] === $role ? 'selected' : '';
          echo "\n<option $sel value='$role'>$role</option>"; 
        endforeach; ?>
      </select>
    </div>
  </div>

  <div class="row mb-3">
    <label for="fiscal_code" class="col-sm-2 col-form-label">Fiscal Code</label>
    <div class="col-sm-10">
      <input required type="text" class="form-control form-control-lg" id="fiscal_code" placeholder="Fiscal Code" name="fiscal_code" value="<?=$user['fiscal_code']?>">
    </div>
  </div>

  <div class="row mb-3">
    <label for="age" class="col-sm-2 col-form-label">Age</label>
    <div class="col-sm-10">
      <input required type="number" min="0" max="120" class="form-control form-control-lg" id="age" name="age" value="<?=$user['age']?>">
    </div>
  </div>

  <div class="row mb-3">
    <?php
      $avatarDirWeb = getConfig('avatar_dir_web');
      $avatarDir = getConfig('avatar_dir');
      $thumbWidth = getConfig('thumb_width');
      $avatarImg = file_exists($avatarDir.'thumb_'.$user['avatar']) ? $avatarDirWeb.'thumb_'.$user['avatar'] : $avatarDirWeb.'placeholder.jpg';
    ?>      
    <label for="thumb" class="col-sm-2 col-form-label">Thumb</label>
    <div class="col-sm-10">
      <img class="avatar" src="<?=$avatarImg?>" width="<?=$thumbWidth?>" alt="thumb">
    </div>
  </div>

  <div class="row mb-3">
    <label for="avatar" class="col-sm-2 col-form-label">Avatar</label>
    <div class="col-sm-10">
      <!-- indica dimensione max in byte -->
      <input type="hidden" name="MAX_FILE_SIZE" value="<?=getConfig('max_file_upload')?>">

      <input type="file" class="form-control form-control-lg" id="avatar" name="avatar" accept="image/jpeg" onchange="previewFile()">
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-sm-2"></div>
    <?php if(userCanUpdate()):?>
    <div class="col-auto">
      <button class="btn btn-success" type="submit">
        <i class="fa fa-pen me-1"></i>
        <?=$user['id'] ? 'UPDATE' : 'INSERT'?>
      </button>
    </div>
    <?php endif; ?>
    <?php if($user['id'] && userCanDelete()): ?>
      <div class="col-auto">
        <a class="btn btn-danger" href="<?=$deleteUrl?>?id=<?=$user['id']?>&action=delete" onclick="return confirm('Delete user?')">
          <i class="fa fa-trash me-1"></i> DELETE
        </a>
      </div>
    <?php endif; ?>
  </div>
</form>