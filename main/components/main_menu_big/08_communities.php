<?php
  
if (config('PRIVATE_COMMUNITIES') == 1) {
  
  $count = db::get_column("SELECT COUNT(*) FROM `COMMUNITIES`");
  
  ?>
  <a class='menu-container_item' href='/m/communities/?get=rating'><?=b_icons('users', $count, 30, '#52DEB3', '#2FA456')?><span><?=lg('Сообщества')?></span></a>
  <?
  
}