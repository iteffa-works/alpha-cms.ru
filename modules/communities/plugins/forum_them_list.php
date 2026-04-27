<?php
$comments = db::get_string("SELECT `USER_ID`,`TIME` FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? LIMIT 1", [$list['ID'], 'communities_forum_them_comments']); 
$comments_c = db::get_column("SELECT COUNT(`ID`) FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ?", [$list['ID'], 'communities_forum_them_comments']);
?>

<a href='/m/communities/forum/?id=<?=$comm['ID']?>&id_them=<?=$list['ID']?>'>
<div class='list-menu hover'>
  
<?=($list['ACTIVE'] == 0 ? icons('lock', 17, 'fa-fw') : icons('comments', 17, 'fa-fw'))?>
<b><?=($list['BAN'] > 0 ? icons('lock', 17, 'fa-fw').' '.lg('Тема заблокирована') : crop_text(tabs($list['NAME']), 0, 80))?></b> <span class='count'><?=$comments_c?></span>
<br />
<span class='time'>
<?=lg('Последний комментарий')?>:
<?=(intval($comments['USER_ID']) == 0 ? '<font color="black"><b>'.user::login_mini($list['USER_ID']).'</b></font> - '.ftime($list['TIME']) : '<font color="black"><b>'.user::login_mini($comments['USER_ID']).'</b></font> - '.ftime($comments['TIME']))?></b>
</font>
</span>
  
</div>
</a>