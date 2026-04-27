<?php
  
if (get('get') != 'new' && get('get') != 'rating') {
  
  $dir = db::get_string("SELECT * FROM `DOWNLOADS_DIR` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]); 
  $id = intval($dir['ID']);
  $id_dir = intval($dir['ID_DIR']);
  
  if ($id > 0){
    
    html::title(lg('Загрузки - %s', tabs($dir['NAME'])));
    
  }else{
    
    html::title('Загрузки');
    
  }
  
}else{
  
  html::title('Загрузки');
  
}

acms_header(); 

if (config('PRIVATE_DOWNLOADS') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

if (get('get') == 'new') {
  
  $root = 'new';
  
  $h_v = null;
  $h_r = null;
  $h_g = 'h';
  
}elseif (get('get') == 'rating') {
  
  $root = 'rating';
  
  $h_v = null;
  $h_r = 'h';
  $h_g = null;
  
}else{
  
  $root = 'all';
  
  $h_v = 'h';
  $h_r = null;
  $h_g = null;
  
}
  
?> 
<div class='menu-nav-content'>
  
<a class='menu-nav <?=$h_v?>' href='/m/downloads/?'>
<?=lg('Все')?>
</a>
    
<a class='menu-nav <?=$h_r?>' href='/m/downloads/?get=rating'>
<?=lg('ТОП')?>
</a>
    
<a class='menu-nav <?=$h_g?>' href='/m/downloads/?get=new'>
<?=lg('Новые')?>
</a>
  
</div>
<?

require_once (ROOT.'/modules/search/plugins/form/downloads.php'); 
require_once (ROOT.'/modules/downloads/plugins/'.$root.'.php'); 

acms_footer();