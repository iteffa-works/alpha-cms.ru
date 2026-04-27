<?php
  
if (config('PRIVATE_GAMES') == 1) {
  
  $count = db::get_column("SELECT COUNT(*) FROM `GAMES_USERS` WHERE `USER_ID` = ?", [user('ID')]);
  
  ?>
  <a class='menu-container_item' href='/m/games/users/?id=<?=user('ID')?>'><?=b_icons('gamepad', $count, 30, '#EBBF9A', '#98653B')?><span><?=lg('Онлайн игры')?></span></a>  
  <?
  
}