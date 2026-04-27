<?php
  
if (config('PRIVATE_VIDEOS') == 1) {
  
  $videos_count = db::get_column("SELECT COUNT(DISTINCT `VIDEOS`.`ID`) AS `count_videos` FROM `VIDEOS` LEFT JOIN `VIDEOS_DIR` ON (`VIDEOS_DIR`.`ID` = `VIDEOS`.`ID_DIR` OR `VIDEOS_DIR`.`ID_DIR` = `VIDEOS`.`ID_DIR`) WHERE `PRIVATE` = '0'");
  
  ?>
  <a class='menu-container_item' href='/m/videos/?get=new'><?=b_icons('film', $videos_count, 30, '#32CFE8', '#2F7EBD')?><span><?=lg('Видео')?></span></a>  
  <?
  
}