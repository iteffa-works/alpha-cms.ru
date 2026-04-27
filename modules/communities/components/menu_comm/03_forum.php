<?php
$count = db::get_column("SELECT COUNT(`ID`) FROM `COMMUNITIES_FORUM_THEM` WHERE `COMMUNITY_ID` = ?", [$comm['ID']]);  
?>

<a class='menu_user' href='/m/communities/forum/?id=<?=$comm['ID']?>'>
<div><?=num_format($count, 2)?></div>
<span><?=num_decline($count, ['тема', 'темы', 'тем'], 0)?></span>