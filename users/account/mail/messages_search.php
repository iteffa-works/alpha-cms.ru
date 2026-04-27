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

if (post('search')){
  
  session('search', esc(post('search')));
  
}

define('SEARCH', tabs(session('search')));

?>
<div class='search-main-optimize'>
<form method='post' class='ajax-form2' action='/account/mail/messages_search/?id=<?=ACCOUNT_ID?>&get=go&<?=TOKEN_URL?>'>
<input type='text' name='search' class='search-main' placeholder='<?=lg('Введите содержание письма')?>' value='<?=SEARCH?>'> 
<button class="search-main-button ajax-button-search" name="ok"><?=icons('search', 20)?></button>
</form>
</div>
<?

if (get('get') == 'go') {
  
  $id = 0;
  $data = db::get_string_all("SELECT * FROM (SELECT * FROM `MAIL_MESSAGE` WHERE (`USER_ID` = ? OR `MY_ID` = ?) AND (`USER_ID` = ? OR `MY_ID` = ?) AND `MESSAGE` LIKE ? AND `USER` = ? ORDER BY `TIME` DESC LIMIT 200) A ORDER BY `TIME`", [user('ID'), user('ID'), ACCOUNT_ID, ACCOUNT_ID, '%'.SEARCH.'%', user('ID')]);
  while ($list = $data->fetch()){
    
    require (ROOT.'/users/account/mail/plugins/list.php');
    echo $mess;
    $id = 1;
  
  }
  
  if ($id == 0){
    
    html::empty('Ничего не найдено', 'times');
  
  }
  
}else{
  
  html::empty(lg('Начните искать сообщения в переписке с %s', ACCOUNT_LOGIN), 'search');
  
}
  
?> 
</div>
<button class='mail-message-scrollheight' id='OnBottom'><?=icons('angle-down', 20)?></button>
<div class='scroll bottom'></div>
<div id='body-top-comments' id_post='0' pixel='0'></div>
<?

back('/account/mail/messages/?id='.ACCOUNT_ID.'&'.TOKEN_URL, lg('Назад к переписке с %s', ACCOUNT_LOGIN));
acms_footer();