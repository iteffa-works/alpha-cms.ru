<?php  
acms_header('Лента', 'users');

if (get('get') == 'blogs') {
  
  $root = 'blogs';
  
  $h_vs = null;
  $h_b = 'h';
  $h_f = null;
  $h_p = null;
  $h_v = null;
  $h_m = null;
  $h_fl = null;
  
}elseif (get('get') == 'forum') {
  
  $root = 'forum';
  
  $h_vs = null;
  $h_b = null;
  $h_f = 'h';
  $h_p = null;
  $h_v = null;
  $h_m = null;
  $h_fl = null;
  
}elseif (get('get') == 'photos') {
  
  $root = 'photos';
  
  $h_vs = null;
  $h_b = null;
  $h_f = null;
  $h_p = 'h';
  $h_v = null;
  $h_m = null;
  $h_fl = null;
  
}elseif (get('get') == 'videos') {
  
  $root = 'videos';
  
  $h_vs = null;
  $h_b = null;
  $h_f = null;
  $h_p = null;
  $h_v = 'h';
  $h_m = null;
  $h_fl = null;
  
}elseif (get('get') == 'music') {
  
  $root = 'music';
  
  $h_vs = null;
  $h_b = null;
  $h_f = null;
  $h_p = null;
  $h_v = null;
  $h_m = 'h';
  $h_fl = null;
  
}elseif (get('get') == 'files') {
  
  $root = 'files';
  
  $h_vs = null;
  $h_b = null;
  $h_f = null;
  $h_p = null;
  $h_v = null;
  $h_m = null;
  $h_fl = 'h';
  
}else{
  
  $root = 'all';
  
  $h_vs = 'h';
  $h_b = null;
  $h_f = null;
  $h_p = null;
  $h_v = null;
  $h_m = null;
  $h_fl = null;
  
}
  
?> 
<div class='menu-nav-content'>
  
<a class='menu-nav <?=$h_vs?>' href='/account/tape/?'>
<?=lg('Все')?>
</a>
 
<?php if (config('PRIVATE_BLOGS') == 1) : ?>
<a class='menu-nav <?=$h_b?>' href='/account/tape/?get=blogs'>
<?=lg('Блоги')?>
</a>
<?php endif ?>
 
<?php if (config('PRIVATE_FORUM') == 1) : ?>
<a class='menu-nav <?=$h_f?>' href='/account/tape/?get=forum'>
<?=lg('Форум')?>
</a>
<?php endif ?>

<?php if (config('PRIVATE_PHOTOS') == 1) : ?>
<a class='menu-nav <?=$h_p?>' href='/account/tape/?get=photos'>
<?=lg('Фото')?>
</a>
<?php endif ?>

<?php if (config('PRIVATE_VIDEOS') == 1) : ?>
<a class='menu-nav <?=$h_v?>' href='/account/tape/?get=videos'>
<?=lg('Видео')?>
</a>
<?php endif ?>

<?php if (config('PRIVATE_MUSIC') == 1) : ?>
<a class='menu-nav <?=$h_m?>' href='/account/tape/?get=music'>
<?=lg('Музыка')?>
</a> 
<?php endif ?>

<?php if (config('PRIVATE_FILES') == 1) : ?>
<a class='menu-nav <?=$h_fl?>' href='/account/tape/?get=files'>
<?=lg('Файлы')?>
</a>
<?php endif ?>
  
</div>
  
<?php
require_once (ROOT.'/users/account/tape/plugins/read.php');
require_once (ROOT.'/users/account/tape/plugins/delete.php'); 
define('URL_TAPE', '/account/tape/?get='.$root.'&page='.tabs(get('page')));
$url = '/account/tape/?page='.tabs(get('page'));
?>

<div class='list'>
<a href='/account/tape/?get=delete_all&<?=TOKEN_URL?>' class='btn'><?=icons('trash', 15, 'fa-fw')?> <?=lg('Очистить ленту')?></a>
</div>
  
<div id='tpdel'>  
<?
  
if ($root == 'all') {
  
  $column = db::get_column("SELECT COUNT(`ID`) FROM `TAPE` WHERE `USER_ID` = ?", [user('ID')]);
  
}else{
  
  $column = db::get_column("SELECT COUNT(`ID`) FROM `TAPE` WHERE `USER_ID` = ? AND `TYPE` = ?", [user('ID'), $root]);
  
}

hooks::challenge('tape', 'tape');  
hooks::run('tape');

$spage = SPAGE($column, PAGE_SETTINGS);
$page = PAGE($spage);
$limit = PAGE_SETTINGS * $page - PAGE_SETTINGS;

if ($column == 0){
  
  html::empty('Нет событий');
  
}

if ($root == 'all') {
  
  $data = db::get_string_all("SELECT * FROM `TAPE` WHERE `USER_ID` = ? ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [user('ID')]);
  
}else{
  
  $data = db::get_string_all("SELECT * FROM `TAPE` WHERE `USER_ID` = ? AND `TYPE` = ? ORDER BY `TIME` DESC LIMIT ".$limit.", ".PAGE_SETTINGS, [user('ID'), $root]);
  
}

while ($list = $data->fetch()){
  
  if (is_file(ROOT.'/users/account/tape/components/'.$list['TYPE'].'.php')){
    
    require (ROOT.'/users/account/tape/components/'.$list['TYPE'].'.php');
    
  }else{
    
    ?><font color='red'><?=icons('exclamation-triangle', 15, 'fa-fw')?> <b><?=lg('Ошибка')?> <?=$list['TYPE']?></b>: <?=lg('компонент не обнаружен')?></font><?
    
  }
  
}
  
get_page(URL_TAPE.'&', $spage, $page, 'list');

?></div><?

acms_footer();