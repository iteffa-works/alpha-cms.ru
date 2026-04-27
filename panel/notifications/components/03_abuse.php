<?php
$url_menu = '/admin/notifications/abuse/';
if (url_request_validate($url_menu) == true){ $menu_active = 'menu-active'; }else{ $menu_active = null; }

$count_abuse = db::get_column("SELECT COUNT(`ID`) FROM `ABUSE` WHERE `READ` = '0'");
if ($count_abuse > 0){ $count_abuse1 = '+'; }else{ $count_abuse1 = null; }
?>

<li class="has-children <?=$menu_active?>">
<span><font color='#FF617D'><?=icons('gavel', 18)?></font></span> <a href="<?=$url_menu?>"><font color='#FF617D'><?=lg('Жалобы')?></font><span style='position: absolute; right: 7px; bottom: 8px;'><span class='count' style='background-color: #FF617D; color: black;'><?=$count_abuse1.$count_abuse?></span></span></a>
</li>