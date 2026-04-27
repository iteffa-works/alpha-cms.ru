<?php
$status = abs(intval(get('status')));
acms_header(lg('Ошибка %d', $status));

if ($status != 400 && $status != 401 && $status != 402 && $status != 403 && $status != 404) {
  
  redirect('/?');
  
}

if ($status == 400) {
  
  $msg = 'Обнаруженная ошибка в запросе';
  
}elseif ($status == 401) {
  
  $msg = 'Нет прав для выдачи документа';
  
}elseif ($status == 402) {
  
  $msg = 'Не реализованный код запроса';
  
}elseif ($status == 403) {
  
  $msg = 'Доступ запрещен';
  
}elseif ($status == 404) {
  
  $msg = 'Такая страница отсутствует или была удалена';
  
}

$msg = lg($msg);

?>
<div class='circle1'></div>
<div class='circle2'></div>  
<div class='circle3'></div>  

<center class='list-tr'>
<div class='list-tr-avatar'>
<?=icons('exclamation-triangle', 40)?>
</div>
<div class='aut-text' style='font-size: 35px'><?=$status?></div>  
<?=$msg?><br /><br />
<a href='/?' class='button' ajax='no'><?=lg('Вернуться на сайт')?></a>
</center>
<?
  
acms_footer();