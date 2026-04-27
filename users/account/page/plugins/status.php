<?php
  
$status = tabs(crop_text($settings['STATUS'], 0, 100));  
  
if (str($status) > 0 && user('ID') != $account['ID']) {
  
  ?>
  <div class='status'><?=$status?></div>
  <?
  
}elseif (user('ID') == $account['ID']) {
  
  $status = tabs(crop_text($settings['STATUS'], 0, 100));
  
  ?>
  <a href="/account/status/">
  <div class="status">
  <?=($status != null ? $status : lg('Добавьте статус'))?> <?=icons('pencil', 12, 'fa-fw')?>
  </div>
  </a>   
  <?
  
}