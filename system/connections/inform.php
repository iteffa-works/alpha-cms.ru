<?php
  
/*
----------------------------
Функция оповещения об успехе
----------------------------
*/
  
function success($text) {
  
  session('success', $text);
  
}

if (session('success')){

  ?>    
  <div class="info_error_or_success success_green" id="success_id"><div class='striples_info'>
  <?=icons('check', 16, 'fa-fw')?>
  <?=lg(session('success'))?><span class="info_close" onclick="open_or_close('success_id', 'close')"><?=icons('times', 22)?></span>
  </div></div>
  <?
  
  session('success', null);

}

/*
----------------------------
Функция оповещения об ошибке
----------------------------
*/
  
function error($text) {
  
  session('error', $text);
  
}

if (session('error')){

  ?>    
  <div class="info_error_or_success error_red" id="error_id"><div class='striples_info'>
  <?=icons('exclamation-triangle', 16, 'fa-fw')?>
  <?=lg(session('error'))?><span class="info_close" onclick="open_or_close('error_id', 'close')"><?=icons('times', 22)?></span>
  </div></div>
  <?
  
  session('error', null);

}

/*
-------------------------------------
Функция системных сообщений в модулях
-------------------------------------
*/
  
function message($title, $text, $type) {
  
  //$title - заголовок сообщения
  //$text - содержание сообщения
  //$type - уникальный тип сообщения
  
  ?>
  <?php if (abs(intval(cookie($type))) != 1) : ?>
  <div class='list list-mess' id='<?=$type?>'>
  <b><?=icons('comment', 15, 'fa-fw')?> <?=$title?></b> <span class="list-mess-info-close" onclick="info_message('<?=$type?>')"><?=icons('times', 20)?></span><br /><br />
  <?=$text?>
  </div>
  <?php endif ?>
  <?
  
}