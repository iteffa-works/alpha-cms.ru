<?php
html::title('Пользователи');
acms_header();
  
$online = db::get_column("SELECT COUNT(*) FROM `USERS` WHERE `DATE_VISIT` > ?", [(TM - config('ONLINE_TIME_USERS'))]); 
$guests = db::get_column("SELECT COUNT(*) FROM `GUESTS` WHERE `DATE_VISIT` > ?", [(TM - config('ONLINE_TIME_GUESTS'))]);                 

if (get('get') == 'online') {
  
  $root = 'online';
  
  $h_v = null;
  $h_o = 'h';
  $h_r = null;
  $h_g = null;
  
}elseif (get('get') == 'rating') {
  
  $root = 'rating';
  
  $h_v = null;
  $h_o = null;
  $h_r = 'h';
  $h_g = null;
  
}elseif (get('get') == 'guests') {
  
  $root = 'guests';
  
  $h_v = null;
  $h_o = null;
  $h_r = null;
  $h_g = 'h';
  
}else{
  
  $root = 'all';
  
  $h_v = 'h';
  $h_o = null;
  $h_r = null;
  $h_g = null;
  
}
  
?> 
<div class='menu-nav-content'>
  
<a class='menu-nav <?=$h_v?>' href='/m/users/?'>
<?=lg('Все')?>
</a>
    
<a class='menu-nav <?=$h_o?>' href='/m/users/?get=online'>
<?=lg('Онлайн')?> <span class='menu-nav-count'><?=$online?></span>
</a>
    
<a class='menu-nav <?=$h_r?>' href='/m/users/?get=rating'>
<?=lg('Рейтинг')?>
</a>
    
<a class='menu-nav <?=$h_g?>' href='/m/users/?get=guests'>
<?=lg('Гости')?> <span class='menu-nav-count'><?=$guests?></span>
</a>
  
</div>
  
<?php 
  
if (get('get') != 'guests') {
  
  require_once (ROOT.'/modules/search/plugins/form/users.php'); 
    
}
  
define('URL_FRSCB', '/m/users/?get='.$root.'&page='.tabs(get('page')));
require_once (ROOT.'/modules/users/plugins/friends_and_subscribers.php');
require_once (ROOT.'/modules/users/plugins/'.$root.'.php');  

back('/', 'На главную');
acms_footer();