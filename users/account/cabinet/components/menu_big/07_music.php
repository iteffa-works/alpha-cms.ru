<?php
  
if (config('PRIVATE_MUSIC') == 1) {
  
  $count = db::get_column("SELECT COUNT(DISTINCT `MUSIC`.`ID`) AS `count_music` FROM `MUSIC` LEFT JOIN `MUSIC_DIR` ON (`MUSIC_DIR`.`ID` = `MUSIC`.`ID_DIR` OR `MUSIC_DIR`.`ID_DIR` = `MUSIC`.`ID_DIR`) WHERE `MUSIC_DIR`.`PRIVATE` != '3' AND `MUSIC`.`USER_ID` = ?", [user('ID')]);
  
  ?>
  <a class='menu-container_item' href='/m/music/users/?id=<?=user('ID')?>'><?=b_icons('music', $count, 30, '#FF4D51', '#C62B79')?><span><?=lg('Музыка')?></span></a>
  <?
  
}