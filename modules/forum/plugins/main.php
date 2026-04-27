<?php
  
if (config('PRIVATE_FORUM') == 1 && config('MAIN_FORUM') == 1) {
  
  ?><div class="menu-info"><?=lg('Актуальные темы')?></div><?
  
  if (db::get_column("SELECT COUNT(*) FROM `FORUM_THEM` LIMIT 1") > 0) {
    
    ?><div class='list-body'><?
      
    $data = db::get_string_all("SELECT * FROM `FORUM_THEM` WHERE `TOP` > ? ORDER BY `ACT_TIME` DESC LIMIT 3", [TM]);
    while ($list = $data->fetch()) {
      
      require (ROOT.'/modules/forum/plugins/list.php');
      echo $forum_list;
    
    }
      
    $data = db::get_string_all("SELECT * FROM `FORUM_THEM` WHERE (`TOP` < ? OR `TOP` IS NULL) ORDER BY `ACT_TIME` DESC LIMIT 3", [TM]);
    while ($list = $data->fetch()) {
      
      require (ROOT.'/modules/forum/plugins/list.php');
      echo $forum_list;
    
    }
    
    ?>
    <a href='/m/forum/?get=act'>
    <div class='list-menu' style='color: #5CB3F9'>
    <b><?=lg('Все темы')?></b>
    <span style='float: right'><?=icons('chevron-right', 14)?></span>
    </div>
    </a>
    </div>
    <?
    
  }else{
    
    ?>
    <div class='list'>
    <?=lg('Пока нет тем')?>.
    </div>
    <?
    
  }
  
}