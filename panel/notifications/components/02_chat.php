<?php
$url_menu = '/admin/notifications/chat/';
if (url_request_validate($url_menu) == true){ $menu_active = 'menu-active'; }else{ $menu_active = null; }

$count_chat = db::get_column("SELECT COUNT(`ID`) FROM `COMMENTS` WHERE `OBJECT_TYPE` = ? AND `TIME` > ?", ['admin_chat_comments', (TM - 86400)]);
if ($count_chat > 0){ $count_chat1 = '+'; }else{ $count_chat1 = null; }
?>

<li class="has-children <?=$menu_active?>">
<span><font color='#EBFF8E'><?=icons('comments', 18)?></font></span> <a href="<?=$url_menu?>"><font color='#EBFF8E'><?=lg('Чат администрации')?></font><span style='position: absolute; right: 7px; bottom: 8px;'><span class='count' style='background-color: #EBFF8E; color: black;'><?=$count_chat1.$count_chat?></span></span></a>
</li>