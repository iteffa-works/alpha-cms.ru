<?php
  
if (config('PRIVATE_DOWNLOADS') == 1) {
  
  $count = db::get_column("SELECT COUNT(*) FROM `DOWNLOADS`");
  
  ?>
  <a class='menu-container_item' href='/m/downloads/'><?=b_icons('download', $count, 30, '#44E687', '#23937A')?><span><?=lg('Загрузки')?></span></a>
  <?
  
}