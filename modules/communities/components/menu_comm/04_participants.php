<?php
$count = db::get_column("SELECT COUNT(`ID`) FROM `COMMUNITIES_PAR` WHERE `COMMUNITY_ID` = ? AND `ACT` = ?", [$comm['ID'], 1]);  
?>

<a class='menu_user' href='/m/communities/participants/?id=<?=$comm['ID']?>'>
<div><?=num_format($count, 2)?></div>
<span><?=num_decline($count, ['участник', 'участника', 'участников'], 0)?></span>
</a>