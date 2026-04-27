<?php
  
if (config('PRIVATE_COMMUNITIES') == 1) {
  
  $count = db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_PAR` WHERE `USER_ID` = ? AND `ACT` = '1'", [user('ID')]);
  
  ?>
  <a class='menu-container_item' href='/m/communities/users/?id=<?=user('ID')?>'><?=b_icons('users', $count, 30, '#52DEB3', '#2FA456')?><span><?=lg('Сообщества')?></span></a>
  <?
  
}