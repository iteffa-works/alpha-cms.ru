<?php
  
if (config('PRIVATE_GUESTBOOK') == 1) {
  
  $count = db::get_column("SELECT COUNT(*) FROM `COMMENTS` WHERE `OBJECT_TYPE` = 'guestbook_comments'");
  
  ?>
  <a class='menu-container_item' href='/m/guestbook/'><?=b_icons('comment', $count, 30, '#FFD932', '#FF4532')?><span><?=lg('Гостевая')?></span></a>  
  <?
  
}