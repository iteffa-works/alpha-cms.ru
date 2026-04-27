<?php
acms_header('Сообщества'); 
is_active_module('PRIVATE_COMMUNITIES');

$get = tabs(get('get'));
$root = 'all';

if ($get == 'rating') {
  
  $root = 'rating';
  
}elseif ($get == 'new') {
  
  $root = 'new';
  
}
  
?> 
<div class='menu-nav-content'>  
<a class='menu-nav <?=($root == 'all' ? 'h' : null)?>' href='/m/communities/?'>
<?=lg('Все')?>
</a>    
<a class='menu-nav' href='/m/communities/categories/'>
<?=lg('Категории')?>
</a>    
<a class='menu-nav <?=($root == 'rating' ? 'h' : null)?>' href='/m/communities/?get=rating'>
<?=lg('ТОП')?>
</a>    
<a class='menu-nav <?=($root == 'new' ? 'h' : null)?>' href='/m/communities/?get=new'>
<?=lg('Новые')?>
</a>  
<?php if (user('ID') > 0) : ?>  
<a class='menu-nav' href='/m/communities/users/?id=<?=user('ID')?>'>
<?=lg('Мои')?>
</a>
<?php endif ?>
</div>
<?
  
require_once (ROOT.'/modules/search/plugins/form/communities.php');  
require_once (ROOT.'/modules/communities/plugins/'.$root.'.php'); 

back('/', 'На главную');
acms_footer();