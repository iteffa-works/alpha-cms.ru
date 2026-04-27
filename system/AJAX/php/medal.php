<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('users');

if (ajax() == true){
  
  $url = tabs(get('url'));
  
  ?>
  <div class="modal_bottom_title2">
  <?=lg('Загрузить новые медали')?>
  </div>
    
  <div class="modal-bottom-container">      
  <?=file::upload('/files/receiver/medal.php?url='.$url)?>
  </div>
    
  <div class="modal_bottom_foot">
  <button onclick='modal_bottom_close()' class='modal-bottom-button2'><?=lg('Отменить')?></button>
  </div>
  <?
  
}else{
  
  echo lg('Не удалось установить соединение');
  
}