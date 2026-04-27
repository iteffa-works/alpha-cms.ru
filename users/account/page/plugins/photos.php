<?php
  
if (config('PRIVATE_PHOTOS') == 1) {
  
  $photos_count = db::get_column("SELECT COUNT(DISTINCT `PHOTOS`.`ID`) AS `count_photos` FROM `PHOTOS` LEFT JOIN `PHOTOS_DIR` ON (`PHOTOS_DIR`.`ID` = `PHOTOS`.`ID_DIR` OR `PHOTOS_DIR`.`ID_DIR` = `PHOTOS`.`ID_DIR`) WHERE `PRIVATE` = '0' AND `PHOTOS`.`USER_ID` = ?", [$account['ID']]);
    
  $p_n = 0;
  $img_main = null;
  $data = db::get_string_all("SELECT `PHOTOS`.`ID`,`PHOTOS`.`SHIF` FROM `PHOTOS` LEFT JOIN `PHOTOS_DIR` ON (`PHOTOS_DIR`.`ID` = `PHOTOS`.`ID_DIR` OR `PHOTOS_DIR`.`ID_DIR` = `PHOTOS`.`ID_DIR`) WHERE `PRIVATE` = '0' AND `PHOTOS`.`USER_ID` = ? GROUP BY `PHOTOS`.`ID` ORDER BY `PHOTOS`.`TIME` DESC LIMIT 7", [$account['ID']]);
  while ($list = $data->fetch()) {
    
    $img_main .= "<a href='/m/photos/show/?id=".$list['ID']."'><img src='/files/upload/photos/240x240/".$list['SHIF'].".jpg' style='max-width: 85px' class='img'></a>";
    $p_n = 1;
  
  }
  
  if ($p_n == 1) { 
    
    ?>
    <a href='/m/photos/users/?id=<?=$account['ID']?>'> 
    <div class='profile_list'>
    <b><?=lg('Фотографии %s', $account['LOGIN'])?></b>
    <span class='count'><?=$photos_count?></span>  
    <span style='float: right; position: relative; top: 5px;'><?=icons('chevron-right', 18)?></span>
    </div>
    </a>
    <div class='profile_list'>
    <div class='files-main-list'>
    <?=$img_main?>
    <a href='/m/photos/users/?id=<?=$account['ID']?>' class='files-main-list-a' style='margin-top: 3px'><?=icons('arrow-right', 40)?></a>
    </div>
    </div>
    <?
      
  }
  
}