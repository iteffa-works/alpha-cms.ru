<?php
  
if (config('PRIVATE_COMMUNITIES') == 1 && config('MAIN_COMMUNITIES') == 1) {
  
  ?><div class="menu-info"><?=lg('Популярные сообщества')?></div><?
  
  if (db::get_column("SELECT COUNT(*) FROM `COMMUNITIES`") > 0) {
    
    ?><div class='list-body'><?
      
    $data = db::get_string_all("SELECT * FROM `COMMUNITIES` ORDER BY RAND() LIMIT 3");
    while ($list = $data->fetch()) {
      
      require (ROOT.'/modules/communities/plugins/list.php');
      echo $comm_list;
    
    }
    
    ?>
    <a href='/m/communities/'>
    <div class='list-menu' style='color: #5CB3F9'>
    <b><?=lg('Все сообщества')?></b>
    <span style='float: right'><?=icons('chevron-right', 14)?></span>
    </div>
    </a>
    </div>
    <?
    
  }else{
    
    ?>
    <div class='list'>
    <?=lg('Пока нет сообществ')?>
    </div>
    <?
    
  }
  
}