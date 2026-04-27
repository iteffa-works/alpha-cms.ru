<?php
  
if (config('PRIVATE_FORUM') == 1) {  
  
  $forum = db::get_column("SELECT COUNT(*) FROM `FORUM_THEM` WHERE `USER_ID` = ?", [$account['ID']]);  

  ?>
  <a class='menu_user' href='/m/forum/users/?id=<?=$account['ID']?>'>
  <div><?=num_format($forum, 2)?></div>
  <span><?=num_decline($forum, ['тема', 'темы', 'тем'], 0)?></span>
  </a>
  <?
    
}