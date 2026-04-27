<?php
  
if (config('PRIVATE_PHOTOS') == 1) {
  
  ?>
  <a class='panel-left-menu hover' href='/m/photos/?get=new'><?=m_icons('image', 12, '#4CB3FC')?> <span><?=lg('Фото')?></span></a>  
  <?
  
}