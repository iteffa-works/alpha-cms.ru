<?php
  
if (config('PRIVATE_VIDEOS') == 1) {
  
  ?>
  <a class='panel-left-menu hover' href='/m/videos/?get=new'><?=m_icons('film', 12, '#32CFE8')?> <span><?=lg('Видео')?></span></a>  
  <?
  
}