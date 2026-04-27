<?php
acms_header('Переход по внешней ссылке');  
$link = tabs(base64_decode(get('data')));

if (str($link) == 0 || url_check_validate($link) == 'none') {
  
  error('Неверная директива');
  redirect('/');
  
}

?>
<div class='circle1'></div>
<div class='circle2'></div>  
<div class='circle3'></div>  

<center class='list-tr'>
<div class='list-tr-avatar'>
<?=icons('link', 40)?>
</div>
<div class='aut-text'><?=lg('Осторожность, осторожность и еще раз осторожность...')?></div> 
<?=lg('Вы собираетесь перейти по внешней ссылке:')?> <b><?=$link?></b><br /><br /> 
<?=lg('Администрация')?> <b><?=HTTP_HOST?></b> <?=lg('НЕ несет ответственности за содержимое ссылки. Вы действительно хотите перейти по ссылке')?>?<br /><br />
<a href='<?=$link?>' class='button' ajax='no'><?=lg('Да, перейти')?></a>
<a href='/' class='button-o'><?=lg('Нет, остаться')?></a>
</center>
<?
  
acms_footer();