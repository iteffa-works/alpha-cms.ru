<?php
  
if (config('PRIVATE_NEWS') == 1) {
  
  $count = db::get_column("SELECT COUNT(*) FROM `NEWS`");
  
  ?>
  <a class='menu-container_item' href='/m/news/'><?=b_icons('feed', $count, 30, '#99ACB5', '#3C4B52')?><span><?=lg('Новости')?></span></a>
  <?
  
}