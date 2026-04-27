<?php
  
if (config('PRIVATE_BLOGS') == 1) {  
  
  $blogs = db::get_column("SELECT COUNT(*) FROM `BLOGS` WHERE `USER_ID` = ? AND `COMMUNITY` = ?", [$account['ID'], 0]);  

  ?>
  <a class='menu_user' href='/m/blogs/users/?id=<?=$account['ID']?>'>
  <div><?=num_format($blogs, 2)?></div>
  <span><?=num_decline($blogs, ['запись', 'записи', 'записей'], 0)?></span>
  </a>
  <?
    
}