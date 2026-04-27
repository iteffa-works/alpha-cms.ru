<?php
$friends = db::get_column("SELECT COUNT(*) FROM `FRIENDS` WHERE `MY_ID` = ? AND `ACT` = '0'", [$account['ID']]);  
?>

<a class='menu_user' href='/account/friends/?id=<?=$account['ID']?>'>
<div><?=num_format($friends, 2)?></div>
<span><?=num_decline($friends, ['друг', 'друга', 'друзей'], 0)?></span>
</a>