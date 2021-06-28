<div id="header_banner_rotator" class="row col-sm-<?= $content_width ?> cm-header-banners text-center align-self-center">
 <?php
  while ($banner_values = tep_db_fetch_array($banner_query)) {
    echo '<div class="cm-header-banners col-sm-' . MODULE_CONTENT_HEADER_BANNERS_ROTATOR_BANNER_WIDTH . '" style="margin-bottom:20px;">';
    if (tep_not_null($banner_values['advert_html_text'])) {
      echo $banner_values['advert_html_text'];
    } else {
      if (tep_not_null($banner_values['advert_url'])) {
        echo '<a href="' . tep_href_link($banner_values['advert_url'], $banner_values['advert_fragment']) . '" target="_blank" rel="noopener">' . tep_image('images/' . $banner_values['advert_image'], htmlspecialchars($banner_values['advert_title'])) . '</a>';
      } else {
        echo tep_image('images/' . $banner_values['advert_image'], htmlspecialchars($banner_values['advert_title']));              
      }
    }
    echo '</div>';
  }
 ?>
</div>

<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  banner modules 2.1
  by @raiwa 
  info@oscaddons.com
  www.oscaddons.com

  Copyright (c) 2020 Rainer Schmied
*/
