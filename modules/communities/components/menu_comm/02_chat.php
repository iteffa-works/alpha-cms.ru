<?php
$count = db::get_column("SELECT COUNT(`ID`) FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$comm['ID'], 'comm_chat_comments']);  
?>

<a class='menu_user' href='/m/communities/chat/?id=<?=$comm['ID']?>'>
<div><?=num_format($count, 2)?></div>
<span><?=num_decline($count, ['сообщение', 'сообщения', 'сообщений'], 0)?></span>