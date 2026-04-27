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
  $data = db::get_string_all("SELECT * FROM `USERS` WHERE `LOGIN` LIKE ? ORDER BY `DATE_VISIT` DESC", ['%'.$search.'%']);
  while ($list = $data->fetch()){
    
    $s++;
    ?>
    <div class='list-menu'>
    <div class='user-info-mini'>
    <div class='user-avatar-mini'>
    <a href='/id<?=$list['ID']?>'><?=user::avatar($list['ID'], 45, 1)?></a>
    </div>
    <div class='user-login-mini'>
    <?=user::login($list['ID'])?>
    </div>
    </div>
    <br />
    <a href='/account/mail/messages/?id=<?=$list['ID']?>&<?=TOKEN_URL?>' class='btn'><?=icons('envelope', 15, 'fa-fw')?> <?=lg('Написать')?></a>
    </div>
    <?
    
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