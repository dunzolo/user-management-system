<?php
  $indexPage = 'index.php';
  $action = $_GET['action'] ?? '';
  $indexActive = !$action ? 'active' : '';
  $newActive = $action === 'insert' ? 'active' : '';
?>

  <!-- Fixed navbar -->
  <nav class='navbar navbar-expand-md navbar-dark bg-dark'>
    <div class='container-fluid justify-content-center d-block'>
      <form id="searchForm" class='d-flex justify-content-around' role='search' action="<?=$indexPage?>" method="GET">
        <input type="hidden" name="page" id="page" value="<?=$page?>">
        
        <div class="form-group d-flex flex-column">
          <label for="orderBy" class="text-white text-center">ORDER BY</label>
          <select class="form-select" name="orderBy" id="orderBy" onchange="document.forms.searchForm.submit()">
            <option value="">Select</option>
            <?php foreach($orderByColumns as $val):?>
                <option <?=$orderBy == $val ?'selected':''?> value="<?=$val?>"><?=$val?></option>
            <?php endforeach; ?>
          </select>
        </div>
        
        <div class="form-group d-flex flex-column">
          <label for="orderDir" class="text-white text-center">ORDER</label>
          <select class="form-select" name="orderDir" id="orderDir" onchange="document.forms.searchForm.submit()">
            <option <?=$orderDir == 'ASC' ?'selected':''?> value="ASC">ASC</option>
            <option <?=$orderDir == 'DESC' ?'selected':''?> value="DESC">DESC</option>
          </select>
        </div>
        
        <div class="form-group d-flex flex-column">
          <label for="recordsPage" class="text-white text-center">RECORDS PAGE</label>
          <select class="form-select" name="recordsPage" id="recordsPage" 
            onchange="document.forms.searchForm.page.value=1; document.forms.searchForm.submit()">
            <option value="">Select</option>
            <?php foreach($recordsPageOptions as $val):?>
                <option <?=$recordsPage == $val ?'selected':''?> value="<?=$val?>"><?=$val?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div id="search-bar-nav" class="form-group d-flex flex-column">
          <label for="search" class="text-white text-center">SEARCH BAR</label>
          <input id="search" class='form-control' type='search' placeholder='Search' aria-label='Search' name="search" value="<?=$search?>">
        </div>
        
        <div class="form-group d-flex flex-column">
          <label for="button-option" class="text-white text-center">OPTION</label>
          <div>
            <button class='btn btn-outline-success me-2' onclick="document.forms.searchForm.page.value=1" type='submit'>Search</button>
            <button class='btn btn-outline-warning' onclick="location.href='<?=$indexPage?>'" type='button'>Reset</button>
          </div>
        </div>
      </form>
    </div>
  </nav>