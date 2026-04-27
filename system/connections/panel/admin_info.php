<?php
  
/*
--------------------------------------
Предупреждение об использовании cookie
--------------------------------------
*/
  
if (config('ADMIN_INFO') == 0 && url_request_validate('/admin') == true && MANAGEMENT == 1){
  
  if (get('get') == 'admin_info'){

    ini::upgrade(ROOT.'/system/config/global/settings.ini', 'ADMIN_INFO', 1);
    redirect('/admin/desktop/');
    
  }
  
  ?>
  <div id='modal-fixed2'></div>    
  <div id='modal-fixed'>
  <div class='modal-fixed-content'><center>
  <img src='/style/images/gagarin.png' style='max-width: 200px;'><br /><br />
  <font size='+2' color='black'><?=lg('Добро пожаловать в новый')?> <?=config('ACMS_NAME')?></font><br /><br />  
  <font size='+1'><?=lg('Море новых возможностей, комфорт, безопасность')?></font><br /><br /><br />
  <a ajax='no' href='/admin/desktop/?get=admin_info' class='button'><?=lg('Начать работу')?></a>
  </center></div>  
  </div>
  <?
  
}