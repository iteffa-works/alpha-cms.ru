<?php
  
$delete_my = '
<div class="list-menu hover" onclick="request(\'/account/mail/messages/?id='.ACCOUNT_ID.'&delete_my='.$list['ID'].'&'.TOKEN_URL.'\', \'#blink'.$list['ID'].'\')">'.icons('trash', 20, 'fa-fw').' '.lg('Удалить у меня').'</div>
';

if ($list['TIME'] > TM - 600) {
  
  $delete_user = '
<div class="list-menu hover" onclick="request(\'/account/mail/messages/?id='.ACCOUNT_ID.'&delete_user='.$list['TID'].'&'.TOKEN_URL.'\', \'#blink'.$list['ID'].'\')">'.icons('trash', 20, 'fa-fw').' '.lg('Удалить у всех').'</div>
';
  
}else{
  
  $delete_user = null;
  
}

$edit = '<a href="/account/mail/edit/?id='.$list['TID'].'&'.TOKEN_URL.'"><div class="list-menu hover">'.icons('pencil', 20, 'fa-fw').' '.lg('Редактировать').'</div></a>';

$reply = '<div class="list-menu hover" id="modal_center_close_set" onclick="reply(\'/account/mail/messages/?id='.ACCOUNT_ID.'&reply_mess='.$list['TID'].'&'.TOKEN_URL.'\', \'#reply_mess\', \''.$list['ID'].'\')">'.icons('mail-forward', 20, 'fa-fw').' '.lg('Ответить').'</div>';

if ($list['REPLY'] > 0) {
  
  $rm = db::get_string("SELECT `MESSAGE`,`MY_ID`,`TID` FROM `MAIL_MESSAGE` WHERE `TID` = ? LIMIT 1", [$list['REPLY']]);
  $us = db::get_string("SELECT `ID`,`LOGIN` FROM `USERS` WHERE `ID` = ? LIMIT 1", [$rm['MY_ID']]);
  
  $reply_data = '
  <div class="messages_reply">
  <span><a href="/id'.$us['ID'].'">'.$us['LOGIN'].'</a></span><br />'.(tabs(crop_text($rm['MESSAGE'], 0, 200)) != null ? tabs(crop_text($rm['MESSAGE'], 0, 200)).'<br />' : '...<br />').'
  </div>
  ';
  
}else{
  
  $reply_data = null;
  
}

if ($list['EDIT_TIME'] > 0) {
  
  $etime = lg('ред.').' '.ftime($list['EDIT_TIME']);
  
}else{
  
  $etime = ftime($list['TIME']);
  
}
  
/*
-----------------
Сообщения от меня
-----------------
*/
  
if ($list['MY_ID'] == user('ID')){
  
  if ($list['READ'] == 0){
    
    $read = "<span class='mail-message-eye'>".icons('eye-slash', 13, 'fa-fw')."</span>";
  
  }else{
    
    $read = null;

  }
  
  $mess = ' 
  <div id="blink'.$list['ID'].'">
  <div class="modal_phone modal_center_close" id="cmenu'.$list['ID'].'2" onclick="modal_center(\'cmenu'.$list['ID'].'\', \'close\')"></div>
  <div id="cmenu'.$list['ID'].'" class="modal_center modal_center_open">
  <div class="modal_bottom_title2">'.lg('Действия').'<button onclick="modal_center_close()">'.icons('times', 20).'</button></div>
  <div class="modal-container">'.$reply.$edit.$delete_my.$delete_user.'</div>
  </div>
  <div class="mail-message-my">
  '.$read.'
  <div class="mail-message-my-form">'.$reply_data.(text($list['MESSAGE']) != null ? text($list['MESSAGE']) : '...').attachments_files($list['TID'], 'ok_message').'
  <div class="mess_time" onclick="modal_center(\'cmenu'.$list['ID'].'\', \'open\')">
  '.$etime.' '.icons('ellipsis-v', 17, 'fa-fw').'
  </div>
  </div>  
  </div>
  </div>
  ';
  
}

/*
------------------------
Сообщения от собеседника
------------------------
*/

if ($list['USER_ID'] == user('ID')){
  
  if (db::get_column("SELECT COUNT(*) FROM `BAN_USER` WHERE `USER_ID` = ? AND `BAN` = ? LIMIT 1", [ACCOUNT_ID, 1]) > 0){
    
    $message = icons('lock', 17, 'fa-fw')." ".('Автор сообщения заблокирован, информация скрыта');
  
  }else{
    
    $message = $reply_data.(text($list['MESSAGE']) != null ? text($list['MESSAGE']) : '...').attachments_files($list['TID'], 'ok_message');
  
  }
  
  $mess = ' 
  <div id="blink'.$list['ID'].'">
  <div class="modal_phone modal_center_close" id="cmenu'.$list['ID'].'2" onclick="modal_center(\'cmenu'.$list['ID'].'\', \'close\')"></div>
  <div id="cmenu'.$list['ID'].'" class="modal_center modal_center_open">
  <div class="modal_bottom_title2">'.lg('Действия').'<button onclick="modal_center_close()">'.icons('times', 20).'</button></div>
  <div class="modal-container">'.$reply.$delete_my.'</div>
  </div>
  <div class="mail-message-user">
  <div class="mail-message-user-form">'.$message.'
  <div class="mess_time" onclick="modal_center(\'cmenu'.$list['ID'].'\', \'open\')">
  '.$etime.' '.icons('ellipsis-v', 17, 'fa-fw').'
  </div></div></div></div>
  ';
  
}