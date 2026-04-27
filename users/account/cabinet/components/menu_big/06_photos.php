<?php
  
if (config('PRIVATE_PHOTOS') == 1) {
  
  $photos_count = db::get_column("SELECT COUNT(DISTINCT `PHOTOS`.`ID`) AS `count_photos` FROM `PHOTOS` LEFT JOIN `PHOTOS_DIR` ON (`PHOTOS_DIR`.`ID` = `PHOTOS`.`ID_DIR` OR `PHOTOS_DIR`.`ID_DIR` = `PHOTOS`.`ID_DIR`) WHERE `PRIVATE` = '0' AND `PHOTOS`.`USER_ID` = ?", [user('ID')]);
  
  ?>
  <a class='menu-container_item' href='/m/photos/users/?id=<?=user('ID')?>'><?=b_icons('image', $photos_count, 30, '#4CB3FC', '#125482')?><span><?=lg('Фото')?></span></a>  
  <?
  
}