<?php  
require ($_SERVER['DOCUMENT_ROOT'].'/system/connections/core.php');
access('users');

if (ajax() == true){
  
  ?>
  <div class="modal_bottom_title2">
  <?=lg('Новая заставка')?>
  </div>
    
  <div class="modal-bottom-container" style="height: 267px;">      
  <?=file::upload('/files/receiver/screensaver.php')?>
    
  <?php
    
  //Если есть установленная заставка
  if (is_file(ROOT.'/files/upload/screensaver/850x200/'.settings('SCREENSAVER'))) {
    
    ?>
    <div class='screensaver_set'>
    <a href="/id<?=user('ID')?>?get=delete_screensaver&<?=TOKEN_URL?>" class="screensaver_del"><?=icons('trash', 15, 'fa-fw')?></a>
    <img class='img' src='/files/upload/screensaver/850x200/<?=settings('SCREENSAVER')?>'>
    </div>
    <?
  
  }
  
  hooks::challenge('screensaver_user', 'screensaver_user');
  hooks::run('screensaver_user');
    
  ?>
  
  </div>  
    
  <center>
  <span onclick='modal_bottom_close()' class='modal-bottom-button'><?=lg('Отменить')?></span>
  </center>
  <?
  
}else{
  
  echo lg('Не удалось установить соединение');
  
}