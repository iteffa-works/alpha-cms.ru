<?php
  
if (config('PRIVATE_BLOGS') == 1 && config('MAIN_BLOGS') == 1) {
  
  ?><div class="menu-info"><?=lg('Люди пишут')?></div><?
  
  if (db::get_column("SELECT COUNT(*) FROM `BLOGS` WHERE `PRIVATE` = '0' LIMIT 1") > 0) {
    
    ?><div class='list-body'><?
      
    $data = db::get_string_all("SELECT * FROM `BLOGS` WHERE `PRIVATE` = '0' AND `SHARE` = '0' ORDER BY `TIME` DESC LIMIT 3");
    while ($list = $data->fetch()) {
      
      require (ROOT.'/modules/blogs/plugins/list_mini.php');
      echo $blogs_list_mini;
    
    }
    
    ?>
    <a href='/m/blogs/?get=new'>
    <div class='list-menu' style='color: #5CB3F9'>
    <b><?=lg('Все записи')?></b>
    <span style='float: right'><?=icons('chevron-right', 14)?></span>
    </div>
    </a>
    </div>
    <?
    
  }else{
    
    if (user('ID') > 0) {
      
      $bn = "<a href='/m/blogs/add/'>".lg('Желаете создать')."?</a>";
      
    }else{
      
      $bn = null;
      
    }
    
    ?>
    <div class='list'>
    <?=lg('Пока нет записей')?>. <?=$bn?>
    </div>
    <?
    
  }
  
}