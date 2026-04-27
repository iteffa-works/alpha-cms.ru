<?php
  
if (config('PRIVATE_DOWNLOADS') == 1) {
  
  ?>
  <a class='panel-left-menu hover' href='/m/downloads/'><?=m_icons('download', 12, '#41C18B')?> <span><?=lg('Загрузки')?></span></a> 
  <?
  
}