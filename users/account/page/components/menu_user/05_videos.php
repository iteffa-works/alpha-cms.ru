<?php
  
if (config('PRIVATE_VIDEOS') == 1) { 
  
  $count_videos = db::get_column("SELECT COUNT(DISTINCT `VIDEOS`.`ID`) AS `count_videos` FROM `VIDEOS` LEFT JOIN `VIDEOS_DIR` ON (`VIDEOS_DIR`.`ID` = `VIDEOS`.`ID_DIR` OR `VIDEOS_DIR`.`ID_DIR` = `VIDEOS`.`ID_DIR`) WHERE `VIDEOS_DIR`.`PRIVATE` != '3' AND `VIDEOS`.`USER_ID` = ?", [$account['ID']]);
  
  ?>
  <a class='menu_user' href='/m/videos/users/?id=<?=$account['ID']?>'>
  <div><?=num_format($count_videos, 2)?></div>
  <span><?=lg('видео')?></span>
  </a>
  <?
    
}