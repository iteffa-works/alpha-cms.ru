<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('users');

if (ajax() == true){
  
  get_check_valid();
  
  ?>     
  <div class='search_result_fixed'>
  <b><?=lg('Результаты поиска')?></b>
  <span class="search-close" onclick="open_or_close('search_close', 'close')"><?=icons('times', 21)?></span>
  </div>
  <?
  
  $search = tabs(esc(post('search')));
  $s = 0;  
  $data = db::get_string_all("SELECT * FROM `MAIL` WHERE `MY_ID` = ? AND `LOGIN` LIKE ? ORDER BY `TIME` DESC LIMIT 10", [user('ID'), '%'.$search.'%']);
  while ($list = $data->fetch()){
    
    $s++;
    
    require (ROOT.'/users/account/mail/plugins/list_kont.php');
    
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