<?php
  
if (config('PRIVATE_PHOTOS') == 1 && config('MAIN_PHOTOS') == 1) {
  
  ?>
  <div class="menu-info">
  <?=lg('Популярные фото')?>
  </div>
  <div class='list-body'>
  <?
    
  $p_n = 0;
  $img_main = null;
  $data = db::get_string_all("SELECT `PHOTOS`.`ID`,`PHOTOS`.`SHIF` FROM `PHOTOS` LEFT JOIN `PHOTOS_DIR` ON (`PHOTOS_DIR`.`ID` = `PHOTOS`.`ID_DIR` OR `PHOTOS_DIR`.`ID_DIR` = `PHOTOS`.`ID_DIR`) WHERE `PRIVATE` = '0' AND `PHOTOS`.`ADULT` = '0' GROUP BY `PHOTOS`.`ID` ORDER BY `PHOTOS`.`RATING` DESC LIMIT 7");
  while ($list = $data->fetch()) {
    
    $img_main .= "<a href='/m/photos/show/?id=".$list['ID']."'><img src='/files/upload/photos/240x240/".$list['SHIF'].".jpg' style='max-width: 85px' class='img'></a>";
    $p_n = 1;
  
  }
  
  if ($p_n == 1) { 
    
    ?>
    <div class='list-menu'>
    <div class='files-main-list'>
    <?=$img_main?>
    <a href='/m/photos/?get=rating' class='files-main-list-a' style='margin-top: 3px'><?=icons('arrow-right', 40)?></a>
    </div>
    </div>
      
    <a href='/m/photos/?get=rating'>
    <div class='list-menu' style='color: #5CB3F9'>
    <b><?=lg('Все фото')?></b>
    <span style='float: right'><?=icons('chevron-right', 14)?></span>
    </div>
    </a>
    <?
      
  }
    
  if ($p_n == 0) {  
    
    if (user('ID') > 0) {
      
      $pn = "<a href='/m/photos/users/?id=".user('ID')."'>".lg('Желаете добавить')."?</a>";
    
    }else{
      
      $pn = null;
    
    }
    
    ?>
    <div class='list-menu'>
    <?=lg('Пока нет фото')?>. <?=$pn?>
    </div>
    <?
    
  }
    
  ?></div><?
  
}