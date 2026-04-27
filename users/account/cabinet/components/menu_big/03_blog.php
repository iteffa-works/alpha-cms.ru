<?php
  
if (config('PRIVATE_BLOGS') == 1) {
  
  $count = db::get_column("SELECT COUNT(*) FROM `BLOGS` WHERE `PRIVATE` = '0' AND `COMMUNITY` = '0' AND `USER_ID` = ?", [user('ID')]);
  
  ?>
  <a class='menu-container_item' href='/m/blogs/users/?id=<?=user('ID')?>'><?=b_icons('book', $count, 30, '#8F8EE6', '#283689')?><span><?=lg('Блог')?></span></a>  
  <?
  
}