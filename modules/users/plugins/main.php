<?php
  
$online = db::get_column("SELECT COUNT(*) FROM `USERS` WHERE `DATE_VISIT` > ?", [(TM - config('ONLINE_TIME_USERS'))]); 
$guests = db::get_column("SELECT COUNT(*) FROM `GUESTS` WHERE `DATE_VISIT` > ?", [(TM - config('ONLINE_TIME_GUESTS'))]); 
  
?>    
<div class='online'>
<a href='/m/users/?get=online'><?=icons('user', 16)?> <?=lg('Онлайн')?>: <span><?=$online?></span></a>
<a href='/m/users/?get=guests'><?=icons('user-secret')?> <?=lg('Гостей')?>: <span><?=$guests?></span></a>
</div>