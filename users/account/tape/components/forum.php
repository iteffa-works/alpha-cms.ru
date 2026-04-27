<?php
  
$them = db::get_string("SELECT * FROM `FORUM_THEM` WHERE `ID` = ? LIMIT 1", [$list['OBJECT_ID']]);
$comments = db::get_string("SELECT `USER_ID`,`TIME` FROM `COMMENTS` WHERE `OBJECT_ID` = ? AND `OBJECT_TYPE` = ? ORDER BY `TIME` DESC LIMIT 1", [$them['ID'], 'forum_comments']);

if (isset($them['ID'])){
  
  $forum_list = '
  <div class="list">
  <span style="float: right; color: #67808A;" onclick="request(\'/account/tape/?delete_one='.$list['ID'].'&page='.$page.'&'.TOKEN_URL.'\', \'#tpdel\')">'.icons('times', 18, 'fa-fw').'</span>
  <a href="/m/forum/show/?id='.$them['ID'].'">
  <b>'.($them['BAN'] == 0 ? icons('comments', 15, 'fa-fw').' '.tabs($them['NAME']) : icons('ban', 15, 'fa-fw').' '.lg('Тема заблокирована')).'</b> - <span class="time">'.ftime($them['TIME']).'</span>
  </a>
  <br /><br />
  '.lg('У %s новая тема', user::login($them['USER_ID'], 0, 1)).'
  <br /><br />
  <span class="time">
  '.lg('Автор темы').': <font color="black"><b>'.user::login_mini($them['USER_ID']).'</b></font><br />
  '.lg('Посл. комментарий').': <font color="black">'.(intval($comments['USER_ID']) == 0 ? '<b>'.user::login_mini($them['USER_ID']).'</b></font> -
  '.ftime($them['TIME']) : '<b>'.user::login_mini($comments['USER_ID']).'</b></font> - '.ftime($comments['TIME']))."
  </span>
  </div>
  ";
  
}else{
  
  $forum_list = '<div class="list">'.lg('Объект уже удален').'<span style="float: right; color: #67808A;" onclick="request(\'/account/tape/?delete_one='.$list['ID'].'&page='.$page.'&'.TOKEN_URL.'\', \'#tpdel\')">'.icons('times', 18, 'fa-fw').'</span></div>';
  
}

echo $forum_list;