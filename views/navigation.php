<?php
  $numLinks = getConfig('num_link_nav', 5);
?>

<nav>
  <ul class="pagination justify-content-center mb-0">
    <li class="page-item <?=$page==1?'disabled':''?>">
      <a class="page-link" href="<?="$pageUrl?$navOrderByQueryString&page=".($page-1)?>" tabindex="-1">
        Previous
      </a>
    </li>
    <?php 
    $extraLink = $page + $numLinks - $numPages;
    $extraLink = $extraLink > 0 ? $extraLink : 0;

    $startValue = $page - $numLinks - $extraLink;

    $startValue = $startValue < 1 ? 1 : $startValue;

    //creo un primo ciclo per visualizzare un numero prestabilito di pagine precedenti a quella attiva
    //applico una sintassi diversa che sostituisce le parentesi graffe, inserisco ':' ed alla fine uso 'endfor
    for($i = $startValue; $i < $page; $i++):?>
      <li class="page-item">
        <a class="page-link" href="<?="$pageUrl?$navOrderByQueryString&page=$i"?>">
          <?=$i?>
        </a>
      </li>
    <?php endfor ?>

    <li class="page-item active">
      <a href="#" class="page-link" disabled>
        <?=$page?>
      </a>
    </li>

    <?php 
    $extraLink = ($page - $numLinks) < 0 ? abs($page - $numLinks) : 0;

    $startValue = $page + 1;
    $startValue = $startValue < 1 ? 1 : $startValue;

    $endValue = $page + $numLinks + $extraLink;
    $endValue = min($endValue, $numPages);

    //creo un secondo ciclo per visualizzare un numero prestabilito di pagine successive a quella attiva
    for($i = $startValue; $i <= $endValue; $i++):?>
      <li class="page-item">
        <a class="page-link" href="<?="$pageUrl?$navOrderByQueryString&page=$i"?>">
          <?=$i?>
        </a>
      </li>
    <?php endfor ?>
      
    <li class="page-item <?=$page==$numPages?'disabled':''?>">
      <a class="page-link" href="<?="$pageUrl?page=".($page+1)?>">
        Next
      </a>
    </li>
  </ul>
</nav>