<?php
acms_header('Онлайн игры'); 
is_active_module('PRIVATE_GAMES');

$root = (get('get') == 'new' ? 'new' : (get('get') == 'rating' ? 'rating' : 'all'));
  
?> 
<div class='menu-nav-content'>  
<a class='menu-nav <?=($root == 'all' ? 'h' : null)?>' href='/m/games/?'>
<?=lg('Все')?>
</a>    
<a class='menu-nav <?=($root == 'rating' ? 'h' : null)?>' href='/m/games/?get=rating'>
<?=lg('ТОП')?>
</a>    
<a class='menu-nav <?=($root == 'new' ? 'h' : null)?>' href='/m/games/?get=new'>
<?=lg('Новые')?>
</a>  
<?php if (user('ID') > 0) : ?>  
<a class='menu-nav' href='/m/games/users/?id=<?=user('ID')?>'>
<?=lg('Мои')?>
</a>
<?php endif ?>  
</div>
<?
  
require (ROOT.'/modules/search/plugins/form/games.php');
require (ROOT.'/modules/games/plugins/resources.php');
  
?>
<?php if (access('games', null) == true) : ?>
<div class='list'>  
<a href='/m/games/add/' class='btn'><?=icons('plus', 15, 'fa-fw')?> <?=lg('Добавить игру')?></a>
</div>
<?php if (MANAGEMENT == 1) : ?>
<?=message('Сообщение для создателя сайта', lg('Вы также можете добавлять новые игры для вашего проекта с официального магазина движка')." <a href='https://alpha-cms.ru' ajax='no'>Alpha-CMS.Ru</a>", 'games')?>
<?php endif ?>
<?php endif ?>
<?
 
require (ROOT.'/modules/games/plugins/'.$root.'.php');
require (ROOT.'/modules/games/plugins/players.php'); 

back('/', 'На главную');
acms_footer();