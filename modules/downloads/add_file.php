<?php 
$id_dir = intval(get('id'));  
html::title('Добавить файл');
acms_header(); 
get_check_valid();
access('users');

if (config('PRIVATE_DOWNLOADS') == 0){
  
  error('Модуль отключен администратором');
  redirect('/');
  
}

?>
<div class='list-body'>
<div class='list-menu'><b><?=lg('Выберите тип')?>:</b></div>
<a href='/m/photos/users/?id=<?=user('ID')?>&<?=TOKEN_URL?>&add_dl=<?=$id_dir?>'><div class='list-menu hover'><?=icons('image', 15, 'fa-fw')?> <?=lg('Фото')?></div></a>
<a href='/m/videos/users/?id=<?=user('ID')?>&<?=TOKEN_URL?>&add_dl=<?=$id_dir?>'><div class='list-menu hover'><?=icons('film', 15, 'fa-fw')?> <?=lg('Видео')?></div></a> 
<a href='/m/music/users/?id=<?=user('ID')?>&<?=TOKEN_URL?>&add_dl=<?=$id_dir?>'><div class='list-menu hover'><?=icons('music', 15, 'fa-fw')?> <?=lg('Музыка')?></div></a> 
<a href='/m/files/users/?id=<?=user('ID')?>&<?=TOKEN_URL?>&add_dl=<?=$id_dir?>'><div class='list-menu hover'><?=icons('file', 15, 'fa-fw')?> <?=lg('Файл')?></div></a>   
</div>
<?
  
back('/m/downloads/?id='.$id_dir);  
acms_footer();