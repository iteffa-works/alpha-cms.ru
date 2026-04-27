<?php
  
if (config('PRIVATE_BLOGS') == 1) {
  
  $count = db::get_column("SELECT COUNT(*) FROM `BLOGS` WHERE `PRIVATE` = '0'");
  
  ?>
  <a class='menu-container_item' href='/m/blogs/?get=new'><?=b_icons('book', $count, 30, '#8F8EE6', '#283689')?><span><?=lg('Блоги')?></span></a>  
  <?
  
}