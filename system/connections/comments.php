<?php
  
$reply_us = db::get_string("SELECT `ID`,`MESSAGE`,`USER_ID`,`TIME`,`REPLY_USER` FROM `COMMENTS` WHERE `REPLY_USER_ID` = ? ORDER BY `TIME` DESC LIMIT 1", [$list['ID']]);

$likes = '<a href="/m/comments/likes/?id='.$list['ID'].'&action='.base64_encode($action2).'&'.TOKEN_URL.'" '.$ajn.'><div class="list-menu hover">'.icons('thumbs-up', 20, 'fa-fw').' '.lg('Кто оценил').'</div></a>';

if (isset($reply_us['ID'])) {
  
  $reply_data = null;
  if ($reply_us['REPLY_USER'] > 0) {
    
    if (user('ID') == 0 && $r == 0 && config('COMMENTS_SET') == 0 || user('ID') > 0 && $r == 0 && settings('COMMENTS_FORMAT') == 1) {
      
      $reply_data = '<small>'.lg('ответил(-а)').' <a href="/id'.$reply_us['REPLY_USER'].'" '.$ajn.'>'.user::login_mini($reply_us['REPLY_USER']).'</a></small><br />';
      
    }
  
  }
  
  if ($reply_us['USER_ID'] != user('ID') && user('ID') > 0) {
    
    $reply2 = ' - <a ajax="no" onclick="reply(\''.url_request_get($action).'reply='.$reply_us['ID'].'&'.TOKEN_URL.'\', \'#reply\', \''.$list['ID'].'\')">'.lg('Ответить').'</a>';
  
  }else{
    
    $reply2 = null;
  
  }
  
  if (db::get_column("SELECT COUNT(*) FROM `ATTACHMENTS` WHERE `TYPE_POST` = ? AND `ID_POST` = ? LIMIT 1", [$type, $reply_us['ID']]) > 0) {
    
    $file = '<br /><b>'.icons('paperclip', 17, 'fa-fw').' '.lg('Файл').'</b>';
  
  }else{
    
    $file = null;
  
  }
  
  if (db::get_column("SELECT COUNT(*) FROM `BAN_USER` WHERE `USER_ID` = ? AND `BAN` = ? LIMIT 1", [$reply_us['USER_ID'], 1]) > 0){
    
    $message = '<br />'.icons('lock', 17, 'fa-fw')." ".('Автор сообщения заблокирован, информация скрыта');
  
  }else{
    
    $message = (text($reply_us['MESSAGE']) != null ? '<br />'.text($reply_us['MESSAGE']) : null).$file;
  
  }
  
  if (user('ID') == 0 && $r == 0 && config('COMMENTS_SET') == 0 || user('ID') > 0 && $r == 0 && settings('COMMENTS_FORMAT') == 1) {
    
    $reply_count = db::get_column("SELECT COUNT(*) FROM `COMMENTS` WHERE `REPLY_USER_ID` = ?", [$list['ID']]);  
    $reply_go = '<div class="comments-reply-optimize"><div class="comments-reply-avatar"><a href="/id'.$reply_us['USER_ID'].'" '.$ajn.'>'.user::avatar($reply_us['USER_ID'], 30).'</a></div><div class="comments-reply-text">'.user::login($reply_us['USER_ID'], 0, 1).'<br />'.$reply_data.$message.'
<br /><br /><span class="time">
'.ftime($reply_us['TIME']).'
</span>
'.$reply2.'<br /><small onclick="modal_comments(\'/system/AJAX/php/comments/comments_reply.php?id='.$list['ID'].'&type='.$list['OBJECT_TYPE'].'&notif='.$notification.'&o_id='.$list['OBJECT_ID'].'&action='.base64_encode($action).'&author='.$author.'&ajn='.$ajn2.'&'.TOKEN_URL.'\')" class="comments-reply-ot">'.icons('angle-down', 17, 'fa-fw').' '.lg('Показать все ответы').' <span class="count">'.$reply_count.'</span></small></div></div>';
    $reply_data_sub = null;
    $reply_data_sub_r = null;
    
  }else{
    
    $reply_count = null;  
    $reply_go = null;
    $reply_data_sub = null;
    $reply_data_sub_r = null;
    
  }

}else{
  
  $reply_go = null;
  $reply_data_sub = null;
  $reply_data_sub_r = null;
  
  if ($list['REPLY_USER'] > 0) {
    
    if (isset($r) && $r == 0 && config('COMMENTS_SET') == 0 || !isset($r)){
      
      $reply_data_sub = '<small>'.lg('ответил(-а)').' <a href="/id'.$list['REPLY_USER'].'" '.$ajn.'>'.user::login_mini($list['REPLY_USER']).'</a></small><br />';
      
    }elseif (isset($r) && $r == 1 || config('COMMENTS_SET') == 1){
      
      $reply_data_sub_r = '<a href="/id'.$list['REPLY_USER'].'" '.$ajn.'>'.user::login_mini($list['REPLY_USER']).'</a>, ';
      
    }
  
  }

}

if ($list['USER_ID'] != user('ID') && user('ID') > 0) {
  
  $abuse = '<a href="/m/abuse/comments/?id='.$list['ID'].'&action='.base64_encode($action2).'" '.$ajn.'><div class="list-menu hover">'.icons('flag', 20, 'fa-fw').' '.lg('Жалоба').'</div></a>';
  
}else{
  
  $abuse = null;
  
}
  
if ($list['USER_ID'] != user('ID') && user('ID') > 0) {
  
  $reply = '<div class="list-menu hover" id="modal_center_close_set" onclick="reply(\''.url_request_get($action2).'reply='.$list['ID'].'&'.TOKEN_URL.'\', \'#reply\', \''.$list['ID'].'\')">'.icons('mail-forward', 20, 'fa-fw').' '.lg('Ответить').'</div>';
  $reply2 = '<br /><a ajax="no" onclick="reply(\''.url_request_get($action2).'reply='.$list['ID'].'&'.TOKEN_URL.'\', \'#reply\', \''.$list['ID'].'\')">'.lg('Ответить').'</a>';
  
}else{
  
  $reply = null;
  $reply2 = null;
  
}

$delete = null;
$edit = null;

if ($list['USER_ID'] == user('ID') || $author == user('ID') || access('comments', null) == true) {
  
  $delete = '<div class="list-menu hover" onclick="request(\''.url_request_get($action).'delete_comments='.$list['ID'].'&'.TOKEN_URL.'\', \'#blink'.$list['ID'].'\')">'.icons('trash', 20, 'fa-fw').' '.lg('Удалить').'</div>';

}

if ($list['USER_ID'] == user('ID') || access('comments', null) == true) {

  $edit = '<a href="/m/comments/edit/?id='.$list['ID'].'&action='.base64_encode($action2).'&type='.$type.'&'.TOKEN_URL.'" '.$ajn.'><div class="list-menu hover">'.icons('pencil', 20, 'fa-fw').' '.lg('Редактировать').'</div></a>';

}

if (intval($list['EDIT_TIME']) > 0){
  
  if ($type == 'forum' || $type == 'communities_forum_them'){
    
    $dedit = '<br /><small>'.lg('Посл. ред.').': '.ftime($list['EDIT_TIME']).'<br />'.lg('Редактировал').': <a href="/id'.$list['EDIT_USER_ID'].'" '.$ajn.'>'.user::login_mini($list['EDIT_USER_ID']).'</a></small>';
    
  }else{
    
    $dedit = null;
    
  }
  
}else{
  
  $dedit = null;
  
}

if (db::get_column("SELECT COUNT(*) FROM `BAN_USER` WHERE `USER_ID` = ? AND `BAN` = ? LIMIT 1", [$list['USER_ID'], 1]) > 0){
  
  $message = icons('lock', 17, 'fa-fw')." ".('Автор сообщения заблокирован, информация скрыта');

}else{
  
  $message = (text($list['MESSAGE']) != null ? text($list['MESSAGE']).'<br />' : null).attachments_files($list['ID'], $list['OBJECT_TYPE']);

}
  
$mess = '
<div id="blink'.$list['ID'].'">
<div class="list-menu">

<div class="modal_phone modal_center_close" id="cmenu'.$list['ID'].'2" onclick="modal_center(\'cmenu'.$list['ID'].'\', \'close\')"></div>
<div id="cmenu'.$list['ID'].'" class="modal_center modal_center_open">
<div class="modal_bottom_title2">'.lg('Действия').'<button onclick="modal_center_close()">'.icons('times', 20).'</button></div>
<div class="modal-container">'.$reply.$likes.$edit.$delete.$abuse.'</div>
</div>

<div class="comments-list-avatar"><a href="/id'.$list['USER_ID'].'" '.$ajn.'>'.user::avatar($list['USER_ID'], 45).'</a></div>
<div class="comments-list-text">
<div>'.user::login($list['USER_ID'], 0, 1).'<span onclick="modal_center(\'cmenu'.$list['ID'].'\', \'open\')" class="comments-list-menu">'.icons('ellipsis-v', 20).'</span></div>
'.$reply_data_sub.'
<span class="comments-list-text-optimize">'.$reply_data_sub_r.$message.'</span>
</div>
<span class="time comments-list-time">
'.ftime($list['TIME']).'
<span class="comments-list-like">
<div id="like'.$list['ID'].'">'.likescount($list['ID'], $list['OBJECT_TYPE'], $action).'</div>
</span>
'.$reply2.'
'.$dedit.'
</span>
'.$reply_go.'
</div>
</div>
';