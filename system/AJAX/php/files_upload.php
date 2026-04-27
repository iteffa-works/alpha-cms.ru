<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('users');

if (ajax() == true){
  
  ?>
  <div class="modal_bottom_title2">
  <?=lg('Загрузить файлы')?>
  </div>    
  <div class="modal-bottom-container" style="height: 267px;">      
  <?=file::upload('/files/receiver/files.php?dir='.intval(get('dir')))?>  
  </div>
  <center>
  <span onclick='modal_bottom_close()' class='modal-bottom-button'><?=lg('Отменить')?></span>
  </center>
  <?
  
}else{
  
  echo lg('Не удалось установить соединение');
  
}