<div id="header_banner_rotator" class="row col-sm-<?= $content_width ?> cm-header-banners text-center align-self-center">
  <div id="banner_rotator" class="carousel slide" data-ride="carousel">
 <?php
  $slideindex=0;
  while ($banner_values = tep_db_fetch_array($banner_query)) {
      //indicators
      $indicators[] = '<li data-target="#banner_rotator" data-slide-to="' . $slideindex . '" class="active"></li>';
      $im = tep_image('images/' . $banner_values['advert_image'], htmlspecialchars($banner_values['advert_title']));
      $ac = $slideindex==0?'active':'';
      $items[] = <<<item
    <div class="carousel-item $ac">
       $im
    </div>
item;
      $slideindex++;
  }
 ?>
      
  <!-- Indicators -->
  <ul class="carousel-indicators">
      <?php echo implode(PHP_EOL, $indicators); ?>
  </ul>
  <?php echo implode(PHP_EOL, $items); ?>
  
  <!-- Left and right controls -->
  <a class="carousel-control-prev" href="#banner_rotator" data-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </a>
  <a class="carousel-control-next" href="#banner_rotator" data-slide="next">
    <span class="carousel-control-next-icon"></span>
  </a>
  </div>
</div>

<?php
/*
*/
