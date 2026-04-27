<?php
acms_header('Поиск по сайту');

if (post('search')){ session('search', esc(post('search'))); }
define('SEARCH', tabs(session('search')));

$root = 'all';

//Пути до поиска по определенным модулям
if (get('type') == 'blogs'){
  
  $root = 'blogs';
  
}elseif (get('type') == 'photos'){
  
  $root = 'photos';
  
}elseif (get('type') == 'videos'){
  
  $root = 'videos';
  
}elseif (get('type') == 'forum'){
  
  $root = 'forum';
  
}elseif (get('type') == 'users'){
  
  $root = 'users';
  
}elseif (get('type') == 'music'){
  
  $root = 'music';
  
}elseif (get('type') == 'communities'){
  
  $root = 'communities';
  
}elseif (get('type') == 'downloads'){
  
  $root = 'downloads';
  
}elseif (get('type') == 'files'){
  
  $root = 'files';
  
}elseif (get('type') == 'games'){
  
  $root = 'games';
  
}

?>
<div class='menu-nav-content'>  
<a class='menu-nav <?=($root == 'all' ? 'h' : null)?>' href='/m/search/?'>
<?=lg('Все')?>
</a>
<?php if (config('PRIVATE_PHOTOS') == 1) : ?>
<a class='menu-nav <?=($root == 'photos' ? 'h' : null)?>' href='/m/search/?type=photos'>
<?=lg('Фото')?>
</a>
<?php endif ?>
<?php if (config('PRIVATE_VIDEOS') == 1) : ?>
<a class='menu-nav <?=($root == 'videos' ? 'h' : null)?>' href='/m/search/?type=videos'>
<?=lg('Видео')?>
</a>
<?php endif ?>
<?php if (config('PRIVATE_MUSIC') == 1) : ?>
<a class='menu-nav <?=($root == 'music' ? 'h' : null)?>' href='/m/search/?type=music'>
<?=lg('Музыка')?>
</a>
<?php endif ?>
<?php if (config('PRIVATE_FILES') == 1) : ?>
<a class='menu-nav <?=($root == 'files' ? 'h' : null)?>' href='/m/search/?type=files'>
<?=lg('Файлы')?>
</a>
<?php endif ?>
<a class='menu-nav <?=($root == 'users' ? 'h' : null)?>' href='/m/search/?type=users'>
<?=lg('Пользователи')?>
</a>
<?php if (config('PRIVATE_COMMUNITIES') == 1) : ?>
<a class='menu-nav <?=($root == 'communities' ? 'h' : null)?>' href='/m/search/?type=communities'>
<?=lg('Сообщества')?>
</a>
<?php endif ?>
<?php if (config('PRIVATE_BLOGS') == 1) : ?>
<a class='menu-nav <?=($root == 'blogs' ? 'h' : null)?>' href='/m/search/?type=blogs'>
<?=lg('Блоги')?>
</a>
<?php endif ?>
<?php if (config('PRIVATE_FORUM') == 1) : ?>
<a class='menu-nav <?=($root == 'forum' ? 'h' : null)?>' href='/m/search/?type=forum'>
<?=lg('Форум')?>
</a>
<?php endif ?>
<?php if (config('PRIVATE_GAMES') == 1) : ?>
<a class='menu-nav <?=($root == 'games' ? 'h' : null)?>' href='/m/search/?type=games'>
<?=lg('Игры')?>
</a>
<?php endif ?>
<?php if (config('PRIVATE_DOWNLOADS') == 1) : ?>
<a class='menu-nav <?=($root == 'downloads' ? 'h' : null)?>' href='/m/search/?type=downloads'>
<?=lg('Загрузки')?>
</a>
<?php endif ?>
</div>
<?

require (ROOT.'/modules/search/plugins/'.$root.'.php');  

forward('/', 'На главную');
acms_footer();