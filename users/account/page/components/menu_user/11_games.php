<?php
  
if (config('PRIVATE_GAMES') == 1) {  
  
  $games = db::get_column("SELECT COUNT(*) FROM `GAMES_USERS` WHERE `USER_ID` = ?", [$account['ID']]);  

  ?>
  <a class='menu_user' href='/m/games/users/?id=<?=$account['ID']?>'>
  <div><?=num_format($games, 2)?></div>
  <span><?=num_decline($games, ['игра', 'игры', 'игр'], 0)?></span>
  </a>
  <?
    
}