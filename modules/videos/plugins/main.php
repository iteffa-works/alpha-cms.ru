<?php
  
if (config('PRIVATE_VIDEOS') == 1 && config('MAIN_VIDEOS') == 1) {
  
  ?>
  <div class="menu-info">
  <?=lg('Популярные видео')?>
  </div>
  <div class='list-body'>
  <?
    
  $v_n = 0;
  $video_main = null;
  $data = db::get_string_all("SELECT `VIDEOS`.`ID`,`VIDEOS`.`DURATION`,`VIDEOS`.`NAME` FROM `VIDEOS` LEFT JOIN `VIDEOS_DIR` ON (`VIDEOS_DIR`.`ID` = `VIDEOS`.`ID_DIR` OR `VIDEOS_DIR`.`ID_DIR` = `VIDEOS`.`ID_DIR`) WHERE `PRIVATE` = '0' GROUP BY `VIDEOS`.`ID` ORDER BY `VIDEOS`.`RATING` DESC LIMIT 7");
  while ($list = $data->fetch()) {
    
    if (str($list['DURATION']) > 0) {
      
      $duration = "<span>".$list['DURATION']."</span>";
    
    }else{
      
      $duration = null;
    
    }
    
    $video_main .= "<a href='/m/videos/show/?id=".$list['ID']."'><div class='videos-img'>".$duration."<img src='/video/".$list['ID']."/?type=screen'><div>".crop_text(tabs($list['NAME']),0,15)."</div></div></a> ";
    $v_n = 1;
  
  }
  
  if ($v_n == 1) { 
    
    ?>
    <div class='list-menu'>
    <div class='files-main-list'>
    <?=$video_main?>
    <a href='/m/videos/?get=rating' class='files-main-list-a'><?=icons('arrow-right', 40)?></a>
    <div style='padding: 4px'></div>  
    </div>
    </div>
      
    <a href='/m/videos/?get=rating'>
    <div class='list-menu' style='color: #5CB3F9'>
    <b><?=lg('Все видео')?></b>
    <span style='float: right'><?=icons('chevron-right', 14)?></span>
    </div>
    </a>
    <?
      
  }
    
  if ($v_n == 0) {  
    
    if (user('ID') > 0) {
      
      $pn = "<a href='/m/videos/users/?id=".user('ID')."'>".lg('Желаете добавить')."?</a>";
    
    }else{
      
      $pn = null;
    
    }
    
    ?>
    <div class='list-menu'>
    <?=lg('Пока нет видео')?>. <?=$pn?>
    </div>
    <?
    
  }
    
  ?></div><?
  
}