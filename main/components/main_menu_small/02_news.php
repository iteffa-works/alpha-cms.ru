<?php
  
if (config('PRIVATE_NEWS') == 1) {
  
  ?>
  <a class='panel-left-menu hover' href='/m/news/'><?=m_icons('feed', 12, '#758891')?> <span><?=lg('Новости')?></span></a> 
  <?
  
}