<?php
  
$comments = db::get_string("SELECT `USER_ID`,`TIME` FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? ORDER BY `TIME` DESC LIMIT 1", [$list['ID'], 'forum_comments']);

$forum_list = "
<a href='/m/forum/show/?id=".$list['ID']."'>
<div class='list-menu hover'>
".($list['TOP'] > TM ? '<span class="info green">'.lg('ТОП').'</span>' : null)." <b>".($list['SECURE'] == 1 ? icons('thumb-tack', 15, 'fa-fw') : null)." ".($list['BAN'] == 0 ? icons('comments', 15, 'fa-fw').' '.tabs($list['NAME']) : icons('ban', 15, 'fa-fw').' '.lg('Тема заблокирована'))."</b> - <span class='time'>".ftime($list['TIME'])."</span>
<br /><br />
<span class='time'>
".lg('Автор темы').": <font color='black'><b>".user::login_mini($list['USER_ID'])."</b></font><br />
".lg('Посл. комментарий').": <font color='black'>".(intval($comments['USER_ID']) == 0 ? '<b>'.user::login_mini($list['USER_ID']).'</b></font> - '.ftime($list['TIME']) : '<b>'.user::login_mini($comments['USER_ID']).'</b></font> - '.ftime($comments['TIME']))."
</span>
</div>
</a>
";