<?php
  
if (config('PRIVATE_MUSIC') == 1) {
  
  ?>
  <a class='panel-left-menu hover' href='/m/music/?get=new'><?=m_icons('music', 12, '#FF4D51')?> <span><?=lg('Музыка')?></span></a>
  <?
  
}