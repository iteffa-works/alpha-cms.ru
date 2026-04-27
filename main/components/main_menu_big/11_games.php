<?php
  
if (config('PRIVATE_GAMES') == 1) {
  
  $count = db::get_column("SELECT COUNT(*) FROM `GAMES`");
  
  ?>
  <a class='menu-container_item' href='/m/games/'><?=b_icons('gamepad', $count, 30, '#EBBF9A', '#98653B')?><span><?=lg('Онлайн игры')?></span></a>
  <?
  
}