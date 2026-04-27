<?php
  
if (config('PRIVATE_VIDEOS') == 1) {
  
  $count = db::get_column("SELECT COUNT(DISTINCT `VIDEOS`.`ID`) AS `count_videos` FROM `VIDEOS` LEFT JOIN `VIDEOS_DIR` ON (`VIDEOS_DIR`.`ID` = `VIDEOS`.`ID_DIR` OR `VIDEOS_DIR`.`ID_DIR` = `VIDEOS`.`ID_DIR`) WHERE `VIDEOS_DIR`.`PRIVATE` != '3' AND `VIDEOS`.`USER_ID` = ?", [user('ID')]);
  
  ?>
  <a class='menu-container_item' href='/m/videos/users/?id=<?=user('ID')?>'><?=b_icons('film', $count, 30, '#32CFE8', '#2F7EBD')?><span><?=lg('Видео')?></span></a>  
  <?
  
}