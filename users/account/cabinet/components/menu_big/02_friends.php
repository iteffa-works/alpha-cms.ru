<?php  
$count = db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE `MY_ID` = ? AND `ACT` = '0'", [user('ID')]);
?>

<a class='menu-container_item' href='/account/friends/?id=<?=user('ID')?>'><?=b_icons('user', $count, 30, '#54C5E4', '#54E479')?><span><?=lg('Друзья')?></span></a>