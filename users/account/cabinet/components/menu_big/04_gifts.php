<?php  
$count = db::get_column("SELECT COUNT(*) FROM `GIFTS_USER` WHERE `MY_ID` = ?", [user('ID')]);
?>

<a class='menu-container_item' href='/account/gifts/?id=<?=user('ID')?>'><?=b_icons('gift', $count, 30, '#36C1D0', '#363AD0')?><span><?=lg('Подарки')?></span></a>