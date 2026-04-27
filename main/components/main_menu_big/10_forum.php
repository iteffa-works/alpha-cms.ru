<?php
  
if (config('PRIVATE_FORUM') == 1) {
  
  $count = db::get_column("SELECT COUNT(*) FROM `FORUM_THEM`");
  
  ?>
  <a class='menu-container_item' href='/m/forum/sc/'><?=b_icons('comments', $count, 30, '#FF87EA', '#92509A')?><span><?=lg('Форум')?></span></a>
  <?
  
}