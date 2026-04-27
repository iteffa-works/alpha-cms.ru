<?php
$count = db::get_column("SELECT COUNT(`ID`) FROM `BLOGS` WHERE `COMMUNITY` = ?", [$comm['ID']]);  
?>

<a class='menu_user' href='/m/communities/blogs/?id=<?=$comm['ID']?>'>
<div><?=num_format($count, 2)?></div>
<span><?=num_decline($count, ['запись', 'записи', 'записей'], 0)?></span>