<?php
  
if (config('PRIVATE_GUESTBOOK') == 1) {
  
  ?>
  <a class='panel-left-menu hover' href='/m/guestbook/'><?=m_icons('comment', 12, '#F2DA4A')?> <span><?=lg('Гостевая')?></span></a>
  <?
  
}