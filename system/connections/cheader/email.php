<?php
  
/*
----------------------------------------------------
Сообщение о том что необходимо завершить регистрацию
----------------------------------------------------
*/
  
if (user('ID') > 0 && user('REG_OK') == 1 && url_request_validate('/account/settings/email/') == false){
  
  ?>
  <div class='message'> 
  <div class='mess-circle1'></div> 
  <div class='mess-circle2'></div>                             
  <span><?=lg('Почти готово')?></span>
  </div>
  <div class='message2'><div>
  <?=icons('at', 90)?><br /><br />
  <span>
    
  <?php
  if (str(user('REG_EMAIL')) > 0){
    
    ?><?=lg('Завершите регистрацию подтвердив указанный e-mail адрес')?> "<?=user('REG_EMAIL')?>"<?
      
  }else{
    
    ?><?=lg('Завершите регистрацию подтвердив e-mail адрес')?><?
    
  }
  ?>
      
  <br /><br />
  </span>
  <a href='/account/settings/email/' class='button'><?=icons('plus', 17, 'fa-fw')?> <?=lg('Завершить')?></a>  
  </div></div>
  <?
    
  acms_footer();
  
}