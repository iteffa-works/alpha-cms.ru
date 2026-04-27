<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('users');

if (ajax() == true){
  
  $id = intval(get('id'));
  $url = tabs(get('url'));
  
  ?>
  <div class="modal_bottom_title2">
  <?=lg('Загрузить скриншоты')?>
  </div>
    
  <div class="modal-bottom-container">      
  <?=file::upload('/files/receiver/files_screen.php?id='.$id.'&url='.$url)?>
  </div>
    
  <center>
  <div class="modal_bottom_foot">
  <span onclick='modal_bottom_close()' class='modal-bottom-button'><?=lg('Отменить')?></span>
  </div>
  </center>
  <?
  
}else{
  
  echo lg('Не удалось установить соединение');
  
}