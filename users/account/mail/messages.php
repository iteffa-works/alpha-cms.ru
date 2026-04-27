<?php 
  
$account = db::get_string("SELECT `ID`,`LOGIN`,`SEX`,`DATE_VISIT` FROM `USERS` WHERE `ID` = ? LIMIT 1", [intval(get('id'))]); 

define('ACCOUNT_ID', $account['ID']);
define('ACCOUNT_LOGIN', $account['LOGIN']);
define('ACCOUNT_SEX', $account['SEX']);
define('ACCOUNT_DATE_VISIT', $account['DATE_VISIT']);
  
html::title(lg('Переписка с %s', $account['LOGIN']));
acms_header();
access('users');
get_check_valid();

if (ACCOUNT_ID == user('ID')) {
  
  error('Нельзя писать самому себе');
  redirect('/account/mail/');
  
}

if (!isset($account['ID'])) {
  
  error('Пользователь не найден');
  redirect('/account/mail/');
  
}

if (post('ok_message')){
  
  $at = db::get_column("SELECT COUNT(*) FROM `ATTACHMENTS` WHERE `TYPE_POST` = ? AND `ID_POST` = ? LIMIT 1", ['ok_message', 0]);
  
  if ($at > 0){
    
    $limit = 0;
  
  }else{
    
    $limit = 1;
  
  }
  
  valid::create(array(
    
    'MAIL_MESSAGE' => ['message', 'text', [$limit, 5000], 'Сообщение', $limit]
    
  ));
  
  if (config('SYSTEM') == ACCOUNT_ID) {
    
    error('Нельзя писать сообщения системному аккаунту');
    redirect('/account/mail/');
  
  }
  
  $mail_set_my = db::get_string("SELECT * FROM `MAIL_SETTINGS` WHERE `USER_ID` = ? LIMIT 1", [user('ID')]);
  $mail_set_user = db::get_string("SELECT * FROM `MAIL_SETTINGS` WHERE `USER_ID` = ? LIMIT 1", [ACCOUNT_ID]);
  
  if ($mail_set_my['PRIVATE'] == 2){

    error('Вы не можете писать письма, так как закрыли свою почту от всех');
    redirect('/account/mail/messages/?id='.ACCOUNT_ID.'&'.TOKEN_URL);
  
  }
  
  if ($mail_set_user['PRIVATE'] == 2){

    error('Вы не можете писать письма этому пользователю, так как он закрыл свою почту от всех');
    redirect('/account/mail/messages/?id='.ACCOUNT_ID.'&'.TOKEN_URL);
  
  }
  
  if ($mail_set_user['PRIVATE'] == 1 && db::get_column("SELECT COUNT(`ID`) FROM `FRIENDS` WHERE `USER_ID` = ? AND `MY_ID` = ? AND `ACT` = '0' LIMIT 1", [user('ID'), ACCOUNT_ID]) == 0){

    error('Данному пользователю могут писать только друзья');
    redirect('/account/mail/messages/?id='.ACCOUNT_ID.'&'.TOKEN_URL);
  
  }
  
  if (ERROR_LOG == 1){

    redirect('/account/mail/messages/?id='.ACCOUNT_ID.'&'.TOKEN_URL);
    
  }
  
  if (db::get_column("SELECT COUNT(*) FROM `MAIL_MESSAGE` WHERE `TID` = ? AND `USER` = ? LIMIT 1", [intval(session('REPLY_ID_MESS'.ACCOUNT_ID)), user('ID')]) > 0){
    
    define('REPLY_ID', intval(session('REPLY_ID_MESS'.ACCOUNT_ID)));
    
  }else{
    
    define('REPLY_ID', 0);
    
  }
  
  messages::get(user('ID'), ACCOUNT_ID, MAIL_MESSAGE, REPLY_ID);
  
  if ($at > 0){
    
    db::get_set("UPDATE `ATTACHMENTS` SET `ID_POST` = ?, `ACT` = '1' WHERE `USER_ID` = ? AND `ACT` = '0' AND `TYPE_POST` = ?", [TID, user('ID'), 'ok_message']);
  
  }
  
  session('REPLY_ID_MESS'.ACCOUNT_ID, null);
  
  redirect('/account/mail/messages/?id='.intval(get('id')).'&'.TOKEN_URL);
  
}

messages::read(user('ID'), ACCOUNT_ID);
require (ROOT.'/users/account/mail/plugins/delete.php');

$count = db::get_column("SELECT COUNT(*) FROM `MAIL_MESSAGE` WHERE (`USER_ID` = ? OR `MY_ID` = ?) AND (`USER_ID` = ? OR `MY_ID` = ?) AND `USER` = ?", [user('ID'), user('ID'), ACCOUNT_ID, ACCOUNT_ID, user('ID')]);

if ($count > 30){
  
  ?>
  <br /><div class='comments-ended'><button count_show="<?=(intval(session('COUNT_MESS')) != 0 ? intval(session('COUNT_MESS')) : 30)?>" count_add="30" class="button" onclick="show_more('/system/AJAX/php/messages/messages_list.php?id=<?=ACCOUNT_ID?>&<?=TOKEN_URL?>', '#show_more_m', '#messages_list', 30, 'prepend')" name_show="<?=lg('Показать ещё')?> <?=icons('spinner fa-spin', 17)?>" name_finish="<?=lg('Показать ещё')?> <?=icons('angle-down', 17)?>" id="show_more_m" name_hide="<?=lg('Конец')?> <?=icons('times', 17)?>"><?=lg('Показать ещё')?> <?=icons('angle-down', 17)?></button></div>  
  <div id='messages_list'>
  <?
    
}
  
$id = 0;
$data = db::get_string_all("SELECT * FROM (SELECT * FROM `MAIL_MESSAGE` WHERE (`USER_ID` = ? OR `MY_ID` = ?) AND (`USER_ID` = ? OR `MY_ID` = ?) AND `USER` = ? ORDER BY `TIME` DESC LIMIT ".(intval(session('COUNT_MESS')) != 0 ? intval(session('COUNT_MESS')) : 30).") A ORDER BY `TIME`", [user('ID'), user('ID'), ACCOUNT_ID, ACCOUNT_ID, user('ID')]);
while ($list = $data->fetch()){
  
  require (ROOT.'/users/account/mail/plugins/list.php');
  echo $mess;
  $id = $list['ID'];

}

if ($count == 0){

  html::empty(lg('Начните беседу с %s', ACCOUNT_LOGIN), 'comments-o');
  
}

?> 
</div> 
<div id='ajax_loaders_interval' action='/system/AJAX/php/messages/messages.php?id=<?=ACCOUNT_ID?>&<?=TOKEN_URL?>' count_add='<?=$id?>'></div>
<button class='mail-message-scrollheight' id='OnBottom'><?=icons('angle-down', 20)?></button>
<div class='messages_prints' action='/system/AJAX/php/messages/messages_prints.php?id=<?=ACCOUNT_ID?>&<?=TOKEN_URL?>'></div>
<div class='scroll bottom'></div> 
<?

html::comment('message', '/account/mail/messages/?id='.ACCOUNT_ID.'&'.TOKEN_URL, null, 'ok_message', 'count_char', 0, 1);

?>
<div id='body-top-comments' id_post='0' pixel='0'></div>
<?

acms_footer();