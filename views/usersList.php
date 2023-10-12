<?php
  $orderDirClass = $orderDir;

  require_once 'nav.php';
  
  $orderDir = $orderDir === 'ASC' ? 'DESC' : 'ASC';
?>

<table class="table table-striped table-bordered">
  <!-- <caption>USER LIST</caption> -->
  <thead>
    <tr><th colspan="8">TOTAL USERS: <?=$totalUsers?> - PAGE <?=$page?> OF <?=$numPages?></th></tr>
    <tr>
      <th class="<?=$orderBy === 'id' ? $orderDirClass : ''?>">
        <a href="<?=$pageUrl?>?<?=$orderByQueryString?>&orderDir=<?=$orderDir?>&orderBy=id">ID</a>
      </th>
      <th class="<?=$orderBy === 'username' ? $orderDirClass : ''?>">
        <a href="<?=$pageUrl?>?<?=$orderByQueryString?>&orderDir=<?=$orderDir?>&orderBy=username">NAME</a>
      </th>
      <th class="<?=$orderBy === 'role_type' ? $orderDirClass : ''?>">
        <a href="<?=$pageUrl?>?<?=$orderByQueryString?>&orderDir=<?=$orderDir?>&orderBy=role_type">ROLE</a>
      </th>
      <th>
        AVATAR
      </th>
      <th class="<?=$orderBy === 'fiscal_code' ? $orderDirClass : ''?>">
        <a href="<?=$pageUrl?>?<?=$orderByQueryString?>&orderDir=<?=$orderDir?>&orderBy=fiscal_code">FISCAL CODE</a>
      </th>
      <th class="<?=$orderBy === 'email' ? $orderDirClass : ''?>">
        <a href="<?=$pageUrl?>?<?=$orderByQueryString?>&orderDir=<?=$orderDir?>&orderBy=email">EMAIL</a>
      </th>
      <th class="<?=$orderBy === 'age' ? $orderDirClass : ''?>">
        <a href="<?=$pageUrl?>?<?=$orderByQueryString?>&orderDir=<?=$orderDir?>&orderBy=age">AGE</a>
      </th>
      <th>
        AZIONI
      </th>
    </tr>
  </thead>
  <tbody>
    <?php
      if($users){
      
        $avatarDirWeb = getConfig('avatar_dir_web');
        $avatarDir = getConfig('avatar_dir');
        $thumbWidth = getConfig('thumb_width');
        
        foreach($users as $user){
          
          $avatarThumbImg = $user['avatar'] && file_exists($avatarDir.'thumb_'.$user['avatar']) ? $avatarDirWeb.'thumb_'.$user['avatar'] : $avatarDirWeb.'placeholder.jpg';
          $avatarPreviewImg = $user['avatar'] && file_exists($avatarDir.'preview_'.$user['avatar']) ? $avatarDirWeb.'preview_'.$user['avatar'] : '';
          $avatarBigImg = $user['avatar'] && file_exists($avatarDir.$user['avatar']) ? $avatarDirWeb.$user['avatar'] : '';
          
          ?>
          <tr>
            <td><?=$user['id']?></td>
            <td><?=$user['username']?></td>
            <td><?=$user['role_type']?></td>
            <td>
              <?php if($avatarBigImg): ?>
              <a href="<?=$avatarBigImg?>" target="_blank" class="thumbnail">
                <img class="avatar thumb-user-list" src="<?=$avatarThumbImg?>" alt="thumb">
                <!-- <?php if($avatarPreviewImg): ?>
                <span>
                  <img class="avatar" src="<?=$avatarPreviewImg?>" alt="thumb">
                </span>
                <?php endif; ?> -->
              </a>
              <?php endif; ?>
            </td>
            <td><?=$user['fiscal_code']?></td>
            <td><a href="mailto:<?=$user['email']?>"><?=$user['email']?></a></td>
            <td><?=$user['age']?></td>
            <td>
              <div class="row">
                <?php if(userCanUpdate()): ?>
                <div class="col-auto">
                  <a class="btn btn-success" href="<?=$updateUrl?>?action=update&id=<?=$user['id']?>&<?=$navOrderByQueryString?>&page=<?=$page?>">
                    <i class="fa fa-pen me-1"></i> UPDATE
                  </a>
                </div>
                <?php endif; ?>
                <?php if(userCanDelete()): ?>
                <div class="col-auto">
                  <a class="btn btn-danger" href="<?=$deleteUrl?>?action=delete&id=<?=$user['id']?>&<?=$navOrderByQueryString?>&page=<?=$page?>" onclick="return confirm('Delete user?')">
                    <i class="fa fa-trash me-1"></i> DELETE
                  </a>
                </div>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php
        }
      ?>
  </tbody>
  <tfoot>
    <tr><td colspan="8">
      <?php require_once 'navigation.php'; ?>
    </td></tr>
  </tfoot>
    <?php 
      }else{
        echo '<tr><td colspan="8" class="text-center"><h2>No Records found</h2></td></tr>';
      }
    ?>
</table>