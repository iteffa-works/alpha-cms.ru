<?php
  
if (config('PRIVATE_PHOTOS') == 1) {
  
  $count_photos = db::get_column("SELECT COUNT(DISTINCT `PHOTOS`.`ID`) AS `count_photos` FROM `PHOTOS` LEFT JOIN `PHOTOS_DIR` ON (`PHOTOS_DIR`.`ID` = `PHOTOS`.`ID_DIR` OR `PHOTOS_DIR`.`ID_DIR` = `PHOTOS`.`ID_DIR`) WHERE `PHOTOS_DIR`.`PRIVATE` != '3' AND `PHOTOS`.`USER_ID` = ?", [$account['ID']]);
  
  ?>
  <a class='menu_user' href='/m/photos/users/?id=<?=$account['ID']?>'>
  <div><?=num_format($count_photos, 2)?></div>
  <span><?=lg('фото')?></span>
  </a>
  <?
    
}