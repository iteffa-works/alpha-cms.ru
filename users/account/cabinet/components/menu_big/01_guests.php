<?php  
$count = db::get_column("SELECT COUNT(*) FROM `USERS_GUESTS` WHERE `MY_ID` = ?", [user('ID')]);
?>

<a class='menu-container_item' href='/account/guests/'><?=b_icons('eye', $count, 30, '#FF4E5A', '#FF73C5')?><span><?=lg('Гости')?></span></a>