<?php
  
if (config('PRIVATE_FORUM') == 1) {
  
  ?>
  <a class='panel-left-menu hover' href='/m/forum/sc/'><?=m_icons('comments', 12, '#FF87EA')?> <span><?=lg('Форум')?></span></a>  
  <?
  
}