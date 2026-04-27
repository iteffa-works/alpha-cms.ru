<?php
  
if (config('PRIVATE_COMMUNITIES') == 1) {  
  
  $communities = db::get_column("SELECT COUNT(*) FROM `COMMUNITIES_PAR` WHERE `USER_ID` = ? AND `ACT` = '1'", [$account['ID']]);  

  ?>
  <a class='menu_user' href='/m/communities/users/?id=<?=$account['ID']?>'>
  <div><?=num_format($communities, 2)?></div>
  <span><?=num_decline($communities, ['сообщество', 'сообщества', 'сообществ'], 0)?></span>
  </a>
  <?
    
}