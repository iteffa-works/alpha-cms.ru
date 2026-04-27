<?php  
$count = db::get_column("SELECT COUNT(*) FROM `SUBSCRIBERS` WHERE `USER_ID` = ?", [user('ID')]);
?>

<a class='menu-container_item' href='/account/subscribers/?id=<?=user('ID')?>'><?=b_icons('feed', $count, 30, '#F5FE53', '#CD8F2D')?><span><?=lg('Подписчики')?></span></a>