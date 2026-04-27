<?php
  
if (config('PRIVATE_COMMUNITIES') == 1) {
  
  ?>
  <a class='panel-left-menu hover' href='/m/communities/?get=rating'><?=m_icons('users', 12, '#52DEB3')?> <span><?=lg('Сообщества')?></span></a>  
  <?
  
}