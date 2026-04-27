<?php
html::title('Блоги');
acms_header(); 

if (config('PRIVATE_BLOGS') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

if (get('get') == 'new') {
  
  $root = 'new';
  
  $h_v = null;
  $h_o = null;
  $h_r = null;
  $h_g = 'h';
  
}elseif (get('get') == 'rating') {
  
  $root = 'rating';
  
  $h_v = null;
  $h_o = null;
  $h_r = 'h';
  $h_g = null;
  
}else{
  
  $root = 'all';
  
  $h_v = 'h';
  $h_o = null;
  $h_r = null;
  $h_g = null;
  
}
  
?> 
<div class='menu-nav-content'>
  
<a class='menu-nav <?=$h_v?>' href='/m/blogs/?'>
<?=lg('Все')?>
</a>
    
<a class='menu-nav' href='/m/blogs/categories/'>
<?=lg('Категории')?>
</a>
    
<a class='menu-nav <?=$h_r?>' href='/m/blogs/?get=rating'>
<?=lg('ТОП')?>
</a>
    
<a class='menu-nav <?=$h_g?>' href='/m/blogs/?get=new'>
<?=lg('Новые')?>
</a>
  
<?php if (user('ID') > 0) { ?>  
<a class='menu-nav' href='/m/blogs/users/?id=<?=user('ID')?>'>
<?=lg('Мои')?>
</a>
<?php } ?>
  
</div>
<?
  
require_once (ROOT.'/modules/search/plugins/form/blogs.php');
require_once (ROOT.'/modules/blogs/plugins/'.$root.'.php'); 

back('/', 'На главную');
acms_footer();