<?php
html::title('Форум');
acms_header(); 

if (config('PRIVATE_FORUM') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

if (get('get') == 'new') {
  
  $root = 'new';
  
  $h_v = null;
  $h_r = null;
  $h_a = null;
  $h_n = 'h';
  
}elseif (get('get') == 'rating') {
  
  $root = 'rating';
  
  $h_v = null;
  $h_r = 'h';
  $h_a = null;
  $h_n = null;
  
}elseif (get('get') == 'act') {
  
  $root = 'act';
  
  $h_v = null;
  $h_r = null;
  $h_a = 'h';
  $h_n = null;
  
}else{
  
  $root = 'all';
  
  $h_v = 'h';
  $h_r = null;
  $h_a = null;
  $h_n = null;
  
}
  
?> 
<div class='menu-nav-content'>
  
<a class='menu-nav <?=$h_v?>' href='/m/forum/?'>
<?=lg('Все')?>
</a>
    
<a class='menu-nav' href='/m/forum/sc/'>
<?=lg('Разделы')?>
</a>
  
<a class='menu-nav <?=$h_a?>' href='/m/forum/?get=act'>
<?=lg('Актуальные')?>
</a>
    
<a class='menu-nav <?=$h_r?>' href='/m/forum/?get=rating'>
<?=lg('ТОП')?>
</a>
    
<a class='menu-nav <?=$h_n?>' href='/m/forum/?get=new'>
<?=lg('Новые')?>
</a>
  
<?php if (user('ID') > 0) { ?>  
<a class='menu-nav' href='/m/forum/users/?id=<?=user('ID')?>'>
<?=lg('Мои')?>
</a>
<?php } ?>  
  
</div>
<?

require_once (ROOT.'/modules/search/plugins/form/forum.php'); 
require_once (ROOT.'/modules/forum/plugins/'.$root.'.php'); 

back('/', 'На главную');
acms_footer();