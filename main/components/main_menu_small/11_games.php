<?php
  
if (config('PRIVATE_GAMES') == 1) {
  
  ?>
  <a class='panel-left-menu hover' href='/m/games/'><?=m_icons('gamepad', 12, '#EBBF9A')?> <span><?=lg('Онлайн игры')?></span></a>  
  <?
  
}