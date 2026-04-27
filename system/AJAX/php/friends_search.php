<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');

if (ajax() == true){
  
  $id = intval(get('id'));
  
  get_check_valid();
  
  ?>     
  <div class='search_result_fixed'>
  <b><?=lg('Результаты поиска')?></b>
  <span class="search-close" onclick="open_or_close('search_close', 'close')"><?=icons('times', 21)?></span>
  </div>
  <?
  
  $search = tabs(esc(post('search')));
  $s = 0;  
  $data = db::get_string_all("SELECT * FROM `FRIENDS` INNER JOIN `USERS` ON `FRIENDS`.`USER_ID` = `USERS`.`ID` WHERE `FRIENDS`.`MY_ID` = ? AND `FRIENDS`.`ACT` = '0' AND `USERS`.`LOGIN` LIKE ? LIMIT 10", [$id, '%'.$search.'%']);
  while ($list = $data->fetch()){
    
    $s++;    
    require (ROOT.'/modules/users/plugins/list-mini.php');
    echo $list_mini;
    
  }
  
  if ($s == 0) {
    
    ?>
    <div class='list3'> 
    <span><?=icons('times', 84)?></span>
    <div><?=lg('Ничего не найдено')?></div>
    </div>
    <?
    
  }
  
}else{
  
  echo lg('Не удалось установить соединение');
  
}