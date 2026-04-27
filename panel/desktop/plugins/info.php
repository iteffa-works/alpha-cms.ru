<?php

$morning = lg('Доброе утро');
$day = lg('Добрый день');
$evening = lg('Добрый вечер');
$night = lg('Доброй ночи');

$watch = date("H");

$hello = $morning;
$img = 'y';
$phone = '40, 70, 90, 0.3';

if ($watch >= 04){
  
  $hello = $morning;
  $img = 'y';
  $phone = '40, 70, 90, 0.3';

}

if ($watch >= 12){
  
  $hello = $day;
  $img = 'd';
  $phone = '40, 70, 90, 0.3';

}

if ($watch >= 16){
  
  $hello = $evening;
  $img = 'v';
  $phone = '40, 70, 90, 0.3';

}

if ($watch >= 22 or $watch < 03){
  
  $hello = $night;
  $img = 'n';
  $phone = '70, 100, 130, 0.6';

}

$days = array(1 => 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье');
$days = date($days[date('N')]);

//Текущий месяц
$d = date("d F Y", TM);
$d = str_replace("January", lg("января"), $d); 
$d = str_replace("February", lg("февраля"), $d); 
$d = str_replace("March", lg("марта"), $d);
$d = str_replace("April", lg("апреля"), $d); 
$d = str_replace("May", lg("мая"), $d); 
$d = str_replace("June", lg("июня"), $d); 
$d = str_replace("July", lg("июля"), $d); 
$d = str_replace("August", lg("августа"), $d); 
$d = str_replace("September", lg("сентября"), $d); 
$d = str_replace("October", lg("октября"), $d); 
$d = str_replace("November", lg("ноября"), $d); 
$d = str_replace("December", lg("декабря"), $d);

?>
  
<div class="desktop-info" style="background-image: url(/panel/style/web/images/<?=$img?>.png)">
  
<div style="background-color: rgba(<?=$phone?>)"><span>  
<?=$hello?>, <?=user('LOGIN')?>!
<p>
<?=lg('Местное время')?> - <?=ftime(TM)?>
<small><?=lg($days)?>, <?=$d?></small>
</p> 
</span></div>
  
</div>