<?php
$subscribers = db::get_column("SELECT COUNT(*) FROM `SUBSCRIBERS` WHERE `USER_ID` = ?", [$account['ID']]);  
?>

<a class='menu_user' href='/account/subscribers/?id=<?=$account['ID']?>'>
<div><?=num_format($subscribers, 2)?></div>
<span><?=num_decline($subscribers, ['подписчик', 'подписчика', 'подписчиков'], 0)?></span>
</a>