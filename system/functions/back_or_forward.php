<?php
  
/*
------------
Блок "Назад"
------------
*/
  
function back($link, $name = 'Назад') {
  
  //$link - ссылка
  //$name - имя блока
  
  ?>
  <a href='<?=$link?>'><div class='list hover back_or_forward'>
  <?=icons('arrow-circle-left', 21, 'fa-fw')?> <?=lg($name)?>
  </div></a>
  <?
  
}
  
/*
-------------
Блок "Вперед"
-------------
*/

function forward($link, $name = 'Назад') {
  
  //$link - ссылка
  //$name - имя блока
  
  ?>
  <a href='<?=$link?>'><div class='list hover back_or_forward'>
  <?=icons('arrow-circle-right', 21, 'fa-fw')?> <?=lg($name)?>
  </div></a>
  <?
  
}