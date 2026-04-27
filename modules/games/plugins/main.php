<?php
  
if (config('PRIVATE_GAMES') == 1 && config('MAIN_GAMES') == 1) {
  
  ?><div class="menu-info"><?=lg('Популярные игры')?></div><?
  
  if (db::get_column("SELECT COUNT(*) FROM `GAMES`") > 0) {
    
    ?><div class='list-body'><?
      
    $data = db::get_string_all("SELECT * FROM `GAMES` ORDER BY RAND() LIMIT 3");
    while ($list = $data->fetch()) {
      
      require (ROOT.'/modules/games/plugins/list.php');
      echo $games_list;
    
    }
    
    ?>
    <a href='/m/games/'>
    <div class='list-menu' style='color: #5CB3F9'>
    <b><?=lg('Все игры')?></b>
    <span style='float: right'><?=icons('chevron-right', 14)?></span>
    </div>
    </a>
    </div>
    <?
    
  }else{
    
    ?>
    <div class='list'>
    <?=lg('Пока нет игр')?>
    </div>
    <?
    
  }
  
}