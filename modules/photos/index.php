<?php
html::title('Фотографии');
acms_header(); 

if (config('PRIVATE_PHOTOS') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

if (get('get') == 'new') {
  
  $root = 'new';
  
  $h_v = null;
  $h_n = 'h';
  $h_r = null;
  
}elseif (get('get') == 'rating') {
  
  $root = 'rating';
  
  $h_v = null;
  $h_n = null;
  $h_r = 'h';
  
}else{
  
  $root = 'all';
  
  $h_v = 'h';
  $h_n = null;
  $h_r = null;
  
}
  
?> 
<div class='menu-nav-content'>
  
<a class='menu-nav <?=$h_v?>' href='/m/photos/?'>
<?=lg('Все')?>
</a>
    
<a class='menu-nav <?=$h_r?>' href='/m/photos/?get=rating'>
<?=lg('ТОП')?>
</a>
    
<a class='menu-nav <?=$h_n?>' href='/m/photos/?get=new'>
<?=lg('Новые')?>
</a>
  
<?php if (user('ID') > 0) { ?>  
<a class='menu-nav' href='/m/photos/users/?id=<?=user('ID')?>'>
<?=lg('Мои')?>
</a>
<?php } ?>
  
</div>
<?

require_once (ROOT.'/modules/search/plugins/form/photos.php'); 
require_once (ROOT.'/modules/photos/plugins/'.$root.'.php'); 

back('/', 'На главную');
acms_footer();