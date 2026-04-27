<?php
  
/*
--------------------------------------
Предупреждение об использовании cookie
--------------------------------------
*/
  
if (config('COOKIE') == 1 && empty(cookie('COOKIE'))){
  
  if (get('get') == 'cookie'){

    setcookie('COOKIE', 1, TM + 60 * 60 * 24 * 365, '/');
    redirect('/');
    
  }
  
  ?>
  <div id='modal-fixed2'></div>    
  <div id='modal-fixed'>
  <div class='modal-fixed-content'><center>
  <?=icons('info-circle', 50, 'fa-fw')?><br /><br />
  <font size='+1'><?=lg('Этот сайт использует файлы cookies. Вы даете разрешение на использование?')?></font><br /><br />
  <a ajax='no' href='/?get=cookie' class='button'><?=lg('Да, разрешаю')?></a>
  </center></div>  
  </div>
  <?
  
}