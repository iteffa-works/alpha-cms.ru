<?php

$count = db::get_column("SELECT COUNT(*) FROM `USERS`");

?>
<a class='menu-container_item' href='/m/users/'><?=b_icons('user', $count, 30, '#37A6FF', '#1372BD')?><span><?=lg('Пользователи')?></span></a>  
<?