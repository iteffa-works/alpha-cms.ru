<?php
  
if (config('PRIVATE_BLOGS') == 1) {
  
  ?>
  <a class='panel-left-menu hover' href='/m/blogs/?get=new'><?=m_icons('book', 12, '#AE86D7')?> <span><?=lg('Блоги')?></span></a>
  <?
  
}