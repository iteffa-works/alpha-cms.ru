<?php
  
if (config('PRIVATE_GIFTS') == 1) {  
  
  $gifts = db::get_column("SELECT COUNT(*) FROM `GIFTS_USER` WHERE `MY_ID` = ?", [$account['ID']]);  

  ?>
  <a class='menu_user' href='/account/gifts/?id=<?=$account['ID']?>'>
  <div><?=num_format($gifts, 2)?></div>
  <span><?=num_decline($gifts, ['подарок', 'подарка', 'подарков'], 0)?></span>
  </a>
  <?
    
}