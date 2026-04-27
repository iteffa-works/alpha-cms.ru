<?php

/*
------------
Смена версии
------------
*/
  
if (get('version') == 'web' && cookie('PANEL_VERSION') != 'web'){
  
  setcookie('PANEL_VERSION', 'web', TM + 60*60*24*365, '/'); 
  
}elseif (get('version') == 'touch' && cookie('PANEL_VERSION') != 'touch'){
  
  setcookie('PANEL_VERSION', 'touch', TM + 60*60*24*365, '/'); 
  
}

redirect('/admin/desktop/');