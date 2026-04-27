<?php

if (config('PRIVATE_MUSIC') == 1) {
  
  $count_music = db::get_column("SELECT COUNT(DISTINCT `MUSIC`.`ID`) AS `count_music` FROM `MUSIC` LEFT JOIN `MUSIC_DIR` ON (`MUSIC_DIR`.`ID` = `MUSIC`.`ID_DIR` OR `MUSIC_DIR`.`ID_DIR` = `MUSIC`.`ID_DIR`) WHERE `MUSIC_DIR`.`PRIVATE` != '3' AND `MUSIC`.`USER_ID` = ?", [$account['ID']]);
  
  ?>
  <a class='menu_user' href='/m/music/users/?id=<?=$account['ID']?>'>
  <div><?=num_format($count_music, 2)?></div>
  <span><?=lg('аудио')?></span>
  </a>
  <?
    
}